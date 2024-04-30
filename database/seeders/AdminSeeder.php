<?php

namespace Database\Seeders;

use App\Enum\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'username' => 'Admin',
            'email' => 'fauzannurhidayat8@gmail.com',
            'password' => Hash::make('rahasia'),
            'place_of_birth' => 'Kebumen',
            'date_of_birth' => '2001-02-05',
            'phone_number' => '081335457601',
            'role' => UserRoleEnum::ADMIN,
            'profile_photo_filename' => 'blank-profile.jpg',
            'bio' => 'Keep Move',
        ]);
    }
}
