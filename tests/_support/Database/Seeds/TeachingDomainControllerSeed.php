<?php

namespace App\Database\Seeds;


use CodeIgniter\Database\Seeder;

class TeachingDomainControllerSeed extends Seeder {

    

    public function run() {
        $grades = [
            [
                'fk_user_course' => 1,
                'fk_teaching_subject' => 1,
                'fk_teaching_module' => null,
                'date' => '2024-08-22',
                'grade' => 4,
                'is_school' => true,
                'archive' => null
            ],
            [
                'fk_user_course' => 1,
                'fk_teaching_subject' => null,
                'fk_teaching_module' => 1,
                'date' => '2024-08-23',
                'grade' => 4.5,
                'is_school' => true,
                'archive' => null
            ],
            [
                'fk_user_course' => 1,
                'fk_teaching_subject' => 1,
                'fk_teaching_module' => null,
                'date' => '2024-08-22',
                'grade' => 4.5,
                'is_school' => true,
                'archive' => null
            ],
            [
                'fk_user_course' => 1,
                'fk_teaching_subject' => null,
                'fk_teaching_module' => 2,
                'date' => '2024-08-23',
                'grade' => 5,
                'is_school' => true,
                'archive' => null
            ],
            [
                'fk_user_course' => 1,
                'fk_teaching_subject' => null,
                'fk_teaching_module' => 3,
                'date' => '2024-08-23',
                'grade' => 3,
                'is_school' => false,
                'archive' => null
            ],
            [
                'fk_user_course' => 2,
                'fk_teaching_subject' => null,
                'fk_teaching_module' => 3,
                'date' => '2024-08-23',
                'grade' => 2,
                'is_school' => false,
                'archive' => null
            ],
        ];

        // $this->insertUserCourses();
        // $this->insertUsers();
        foreach ($grades as $grade) $this->db->table('grade')->insert($grade);
    }

}
