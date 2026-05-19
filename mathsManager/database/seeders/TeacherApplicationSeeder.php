<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class TeacherApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 5 pending applications for the admin dashboard
        User::factory()->count(5)->teacherPending()->create();
        
        // 2 active teachers to check they don't appear in the pending list
        User::factory()->count(2)->teacherActive()->create();
        
        // 1 rejected application to verify filtering
        User::factory()->count(1)->teacherRejected()->create();
    }
}
