<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'name',
            'role' => 'user',
            'age' => 1,
            'address' => 'address',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        Event::create([
            'user_id' => '1',
            'nama_konser' => 'Event 1 Description',
            'jumlah_tiket' => 1,
            'kategori' => 'kategori',
            'status' => 'pending',
        ]);
    }
}
