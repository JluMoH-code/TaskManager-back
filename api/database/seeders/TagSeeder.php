<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = Tag::factory()->count(15)->create();
        $tasks = Task::all();

        foreach ($tasks as $task) {
            $count = rand(0, 5);
            $randTags = $tags->shuffle()->take($count);
            $task->tags()->attach($randTags);
        }
    }
}
