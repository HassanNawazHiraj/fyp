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
            'name' => 'Admin'
        ]);
        $userTypeRelation = UserTypeRelation::create([
            'user_id' => $user->id,
            'user_type_id' => $userType->id
        ]);
    }
}
