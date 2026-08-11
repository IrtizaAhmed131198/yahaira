<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'setter', 'closer', 'matchmaker', 'coach', 'billing'];

        foreach ($roles as $role) {
            $user = User::create([
                'name' => ucfirst($role).' User',
                'email' => $role.'@example.com',
                'password' => Hash::make('12345678'),
            ]);

            $user->assignRole($role);
        }
    }
}
