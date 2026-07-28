<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        Subject::insert([
            [
                'name' => 'Artificial Intelligence',
                'code' => '0613-401',
                'teacher' => 'Jahanur Biswas',
                'credit' => 3,
                'type' => 'theory',
            ],
            [
                'name' => 'Artificial Intelligence Lab',
                'code' => '0613-402',
                'teacher' => 'Jahanur Biswas',
                'credit' => 1,
                'type' => 'lab',
            ],
            [
                'name' => 'Computer Graphics and Multimedia',
                'code' => '0613-403',
                'teacher' => 'Sayma Sultana',
                'credit' => 3,
                'type' => 'theory',
            ],
            [
                'name' => 'Computer Graphics and Multimedia Lab',
                'code' => '0613-404',
                'teacher' => 'Sayma Sultana',
                'credit' => 1,
                'type' => 'lab',
            ],
            [
                'name' => 'Software Testing and Quality Assurance',
                'code' => '0613-405',
                'teacher' => 'Md. Maruf Ahmed',
                'credit' => 1.5,
                'type' => 'theory',
            ],
            [
                'name' => 'Software Testing and Quality Assurance Lab',
                'code' => '0613-406',
                'teacher' => 'Md. Maruf Ahmed',
                'credit' => 0.5,
                'type' => 'lab',
            ],
            [
                'name' => 'Mobile Application and Development Lab',
                'code' => '0613-408',
                'teacher' => 'Md. Fazle Rabbi Rizon',
                'credit' => 1,
                'type' => 'lab',
            ],
            [
                'name' => 'Mobile Application and Development',
                'code' => '0613-409',
                'teacher' => 'Md. Fazle Rabbi Rizon',
                'credit' => 3,
                'type' => 'theory',
            ],
            [
                'name' => 'Software Integration and Maintenance',
                'code' => '0613-412',
                'teacher' => 'Safwan Ishrak',
                'credit' => 3,
                'type' => 'theory',
            ],
        ]);
    }
}
