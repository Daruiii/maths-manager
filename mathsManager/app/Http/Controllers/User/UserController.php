<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\Quizze;

use Illuminate\Support\Facades\Auth;

use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    protected \App\Services\FileUploadService $fileUploadService;

    public function __construct(\App\Services\FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
    // Affiche la liste paginée des utilisateurs
    
    public function index(Request $request): Response
    {
        $search = $request->get('search');
        
        $query = User::query();

        // Teachers can only see their students in the general list (if they have access)
        if (Auth::user()->isTeacher()) {
             $query->where('teacher_id', Auth::id());
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%');
            });
        }
        
        $users = $query->paginate(10)->withQueryString();

        return Inertia::render('User/Index', [
            'users' => $users,
            'filters' => $request->only(['search'])
        ]);
    }

    // Affiche les détails d'un utilisateur
    public function show($id): Response
    {
        $user = User::with(['quizzes', 'ds'])->findOrFail($id);

        // Security Check: Teachers can only view their own students
        if (Auth::user()->isTeacher() && $user->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this student.');
        }

        $quizzes = $user->quizzes();
        $ds = $user->ds;

        return Inertia::render('User/Show', [
            'user' => $user,
            'quizzes' => $quizzes,
            'ds' => $ds
        ]);
    }

    // Affiche la liste paginée des students
    public function showStudents(Request $request): Response
    {
        $search = $request->get('search');
    
        $query = User::where('role', 'student');

        // Filter for teachers: only show their own students
        if (Auth::user()->isTeacher()) {
            $query->where('teacher_id', Auth::id());
        }

        $students = $query->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10)->withQueryString();
    
        return Inertia::render('User/ShowStudents', [
            'students' => $students,
            'filters' => $request->only(['search'])
        ]);
    }

    // Affiche la liste des quizzes d'un étudiant
    public function showQuizzes($student_id): Response
    {
        // Get all quizzes of the student
        $quizzes = Quizze::where('student_id', $student_id)->latest()->paginate(10)->withQueryString();
        $student = User::findOrFail($student_id);

        return Inertia::render('User/UserQuizzes/Show', [
            'quizzes' => $quizzes,
            'student' => $student
        ]);
    }

    // Affiche les détails d'un quiz spécifique
    public function showQuizDetails($quiz_id): Response
    {
        // Get the quiz with eager loading
        $quiz = Quizze::with('details.chosenAnswer')->find($quiz_id);

        // Get the details of the quiz
        $quizDetails = $quiz->details;

        return Inertia::render('User/UserQuizzes/Details', [
            'quiz' => $quiz,
            'quizDetails' => $quizDetails
        ]);
    }

    // Affiche le formulaire de création d'un nouvel utilisateur
    public function create(): Response
    {
        $teachers = User::where('role', 'teacher')->get();
        return Inertia::render('User/Create', [
            'teachers' => $teachers
        ]);
    }

    // Enregistre un nouvel utilisateur dans la base de données
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,student,teacher',
            'password' => 'required|min:6',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $user = User::create($validatedData);
        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    // Affiche le formulaire pour modifier un utilisateur existant
    public function edit($id): Response
    {
        $user = User::findOrFail($id);
        $roles = ['admin', 'student', 'teacher'];
        $teachers = User::where('role', 'teacher')->get();
        
        return Inertia::render('User/Edit', [
            'user' => $user,
            'roles' => $roles,
            'teachers' => $teachers
        ]);
    }

    // Met à jour un utilisateur dans la base de données
    public function update(Request $request, $id): RedirectResponse
    {
        $validatedData = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,student,teacher',
            'password' => 'sometimes|min:6',
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $user = User::findOrFail($id);

        if ($request->hasFile('avatar')) {
            // Supprime l'ancien avatar si ce n'est pas l'avatar par défaut
            if ($user->avatar && $user->avatar != 'default.jpg') {
                $this->fileUploadService->delete('images/' . $user->avatar, true);
            }

            // Upload le nouvel avatar avec FileUploadService
            $avatarPath = $this->fileUploadService->upload(
                file: $request->file('avatar'),
                context: 'images',
                identifier: '',
                type: 'image',
                isPublic: true,
                customName: str_replace(['@', '.'], '-', $user->email)
            );
            $user->avatar = basename($avatarPath);
        }

        $user->update($validatedData);
        return redirect()->route('users.index');
    }


    // Supprime un utilisateur de la base de données par un admin
    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);

        foreach ($user->quizzes as $quiz) {
            $quiz->details()->delete();
        }

        $user->quizzes()->delete();

        if ($user->avatar && $user->avatar != 'default.jpg') {
            $this->fileUploadService->delete('images/' . $user->avatar, true);
        }

        $user->delete();

        return redirect()->route('users.index');
    }

    public function verify($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->verified = true;
        $user->save();

        return redirect()->route('students.show');
    }

    public function unverify($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->verified = false;
        $user->save();

        return redirect()->route('students.show');
    }

    public function resetLastDSGeneratedAt($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->last_ds_generated_at = null;
        $user->save();

        return redirect()->route('students.show');
    }
}
