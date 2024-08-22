<?php

namespace App\Database\Seeds;


use CodeIgniter\Database\Seeder;

class gradeModelTestSeed extends Seeder
{
    public function run() {
        $grade = [
            'fk_user_course' => 1,
            'fk_teaching_subject' => 1,
            'fk_teaching_module' => 1,
            'date' => '2024-08-22',
            'grade' => 4,
            'is_school' => true,
            'archive' => null,
        ];
        $this->db->table('grade')->insert($grade);
    }

}
