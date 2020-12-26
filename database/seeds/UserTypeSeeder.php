<?php

use Illuminate\Database\Seeder;
use App\User;
use App\UserType;
use App\UserTypeRelation;
class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'test@email.com')->first();
        $userType = UserType::create([
            'name' => 'Super admin',
            'permissions' => '["user_view","user_add","user_delete","user_role_view","user_role_add","user_role_delete","course_performa_form_view","course_performa_form_add","course_performa_form_delete","batch_view","batch_add","batch_delete","program_view","program_add","program_delete","course_view","course_add","course_delete","class_view","class_add","class_delete","class_courses_view","class_courses_add","class_courses_delete","course_teacher_view","course_teacher_add","course_teacher_delete","tai_course_view","tai_course_add","tai_course_delete","assigned_teachers_view","session_view","session_add","session_delete","checklist_template"]'
        ]);
        UserType::create([
            'name' => 'Teacher',
            'permissions' => '[]'
        ]);
        UserType::create([
            'name' => 'Teaching area in-charge',
            'permissions' => '[]'
        ]);
        $userTypeRelation = UserTypeRelation::create([
            'user_id' => $user->id,
            'user_type_id' => $userType->id
        ]);
    }
}
