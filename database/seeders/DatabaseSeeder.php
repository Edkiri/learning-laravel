<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Multitask;
use App\Models\MultitaskUser;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([RoleSeeder::class]);

        $users = User::factory(10)->create();

        Task::factory(20)->state(new Sequence(
            fn () => ['user_id' => $users->random()],
        ))->create();

        $multitasks = Multitask::factory(20)->create();

        foreach ($multitasks as $multitask) {
            $randomUserCount = mt_rand(1, 3);
            $randomUsers = $users->random($randomUserCount);
            $isOwner = true;

            foreach ($randomUsers as $user) {
                MultitaskUser::create([
                    'multitask_id' => $multitask->id,
                    'user_id' => $user->id,
                    'owner' => $isOwner,
                ]);
                $isOwner = false;
            }
        }
    }
}
