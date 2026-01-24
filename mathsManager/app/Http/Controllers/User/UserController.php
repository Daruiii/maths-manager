<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Quizze;

class UserController extends Controller
{
    protected \App\Services\FileUploadService $fileUploadService;

    public function __construct(\App\Services\FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
    // Affiche la liste paginée des utilisateurs
    
    public function index(Request $request)
    {
        $search = $request->get('search');
        if ($search) {
            $users = User::where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('role', 'like', '%' . $search . '%')
                ->paginate(15);
        } else {
            $users = User::paginate(10)->withQueryString();
        }

        return view('user.index', compact('users'));
    }

    // Affiche les détails d'un utilisateur
    public function show($id)
    {
        $user = User::with(['quizzes', 'ds'])->findOrFail($id);
        $quizzes = $user->quizzes();
        $ds = $user->ds;

        return view('user.show', compact('user', 'quizzes', 'ds'));
    }

    // Affiche la liste paginée des students
    public function showStudents(Request $request)
    {
        $search = $request->get('search');
    
        $students = User::where('role', 'student')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10)->withQueryString();
    
        // Return the view
        return view('user.showStudents', compact('students'));
    }

    // Affiche la liste des quizzes d'un étudiant
    public function showQuizzes($student_id)
    {
        // Get all quizzes of the student
        $quizzes = Quizze::where('student_id', $student_id)->latest()->paginate(10)->withQueryString();
        $student = User::findOrFail($student_id);

        // Return the view
        return view('user.userQuizzes.show', compact('quizzes', 'student'));
    }

    // Affiche les détails d'un quiz spécifique
    public function showQuizDetails($quiz_id)
    {
        // Get the quiz with eager loading
        $quiz = Quizze::with('details.chosenAnswer')->find($quiz_id);

        // Get the details of the quiz
        $quizDetails = $quiz->details;

        // Return the view
        return view('user.userQuizzes.details', compact('quiz', 'quizDetails'));
    }

    // Affiche le formulaire de création d'un nouvel utilisateur
    public function create()
    {
        return view('user.create');
    }

    // Enregistre un nouvel utilisateur dans la base de données
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,student,teacher',
            'password' => 'required|min:6',
        ]);

        $user = User::create($validatedData);
        return redirect()->route('users.index');
    }

    // Affiche le formulaire pour modifier un utilisateur existant
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = ['admin', 'student', 'teacher'];
        return view('user.edit', compact('user', 'roles'));
    }

    // Met à jour un utilisateur dans la base de données
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,student,teacher',
            'password' => 'sometimes|min:6',
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
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
    public function destroy($id)
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

    public function verify($id)
    {
        $user = User::findOrFail($id);
        $user->verified = true;
        $user->save();

        return redirect()->route('students.show');
    }

    public function unverify($id)
    {
        $user = User::findOrFail($id);
        $user->verified = false;
        $user->save();

        return redirect()->route('students.show');
    }

    public function resetLastDSGeneratedAt($id)
    {
        $user = User::findOrFail($id);
        $user->last_ds_generated_at = null;
        $user->save();

        return redirect()->route('students.show');
    }
}
