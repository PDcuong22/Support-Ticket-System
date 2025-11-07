<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Label;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labels = ['Question', 'Bug', 'Enhancement'];

        foreach ($labels as $label) {
            Label::create(['name' => $label]);
        }
    }
}
