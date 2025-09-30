<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('whitelist_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('message')->nullable(); // Message de l'étudiant
            $table->text('admin_response')->nullable(); // Réponse de l'admin
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null'); // Qui a traité la demande
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            // Un étudiant ne peut faire qu'une demande par exercice
            $table->unique(['user_id', 'exercise_id']);
            
            // Index pour les requêtes fréquentes
            $table->index('status');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whitelist_requests');
    }
};
