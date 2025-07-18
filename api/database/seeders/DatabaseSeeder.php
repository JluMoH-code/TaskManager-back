<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create(['email' => 'test@test.com']);
        User::factory()->count(5)->create();
        $this->call(TaskSeeder::class);
        $this->call(TagSeeder::class);
    }
}
