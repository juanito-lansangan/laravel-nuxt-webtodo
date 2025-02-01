<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('Secret123!')
        ]);

        $tags = Tag::factory(10)->create();
        $tags = Tag::all();
        $randomTags = $tags->random(rand(2,5));

        $tasks = Task::factory(100)
            ->hasAttached($randomTags)
            ->randomPriority()
            ->for($user)
            ->create();

        $randomTags = $tags->random(3);
        $completedTasks = Task::factory(20)
            ->hasAttached($randomTags)
            ->for($user)
            ->randomPriority()
            ->completed()
            ->create();

        $randomTags = $tags->random(4);
        $archivedTasks = Task::factory(30)
            ->hasAttached($randomTags)
            ->for($user)
            ->randomPriority()
            ->archived()
            ->create();

        

        // $user->tasks()->saveMany([$tasks, $completedTasks, $archivedTasks]);
    }
}
