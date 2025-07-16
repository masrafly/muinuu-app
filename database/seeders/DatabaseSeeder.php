<?php

namespace Database\Seeders;

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
        // User::factory(1)->create();

        // dd(Hash::make('admin'));

        // User::factory()->create([
        //     'name' => 'admin',
        //     'email' => 'admin@example.com',
        // ]);

        User::create([
            'name' => 'Umbi',
            'email' => 'don@example.com',
            'password' => 'kumar112', // <-- Plaintext lain
            'role' => 'staff',
            'is_active' => true,
        ]);

        // User::create([
        //     'name' => 'Adnan',
        //     'email' => 'admin@pol.com',
        //     'password' => 'adnanlord123', // <-- Anda mengetik plaintext di sini
        //     'role' => 'admin',
        //     'is_active' => true,
        // ]);

        
        // $this->call(DatabaseSeeder::class);
    }
}
