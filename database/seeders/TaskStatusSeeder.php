<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['новый', 'в работе', 'на тестировании', 'завершен'];

        foreach ($statuses as $name) {
            TaskStatus::query()->firstOrCreate(['name' => $name]);
        }
    }
}

