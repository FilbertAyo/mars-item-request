<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Mars Communications',
            'email' => 'marscommunication.team@gmail.com',
            'phone' => '0755237692',
            'status' => 'active',
            'password' => Hash::make('Mars@2025'), // always hash passwords
        ]);

        $user->syncPermissions(Permission::all());
    }
}
