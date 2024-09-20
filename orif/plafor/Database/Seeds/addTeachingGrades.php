<?php
/**
 * Seed file for the table "grade"
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Seeds;

use CodeIgniter\Database\Seeder;

class addTeachingGrades extends Seeder {

    public function run(){
        $grades = [
            // apprentice 1
            [   
                "fk_user_course"        => 1,
                "fk_teaching_subject"   => 1,
                "fk_teaching_module"    => null,
                "date"                  => "2024-09-19",
                "grade"                 => 5.5,
                "is_school"             => true,
            ],
            [
                "fk_user_course"        => 1,
                "fk_teaching_subject"   => null,
                "fk_teaching_module"    => 1,
                "date"                  => "2024-09-19",
                "grade"                 => 4,
                "is_school"             => true,
            ],
            // apprentice 2
            [
                "fk_user_course"        => 2,
                "fk_teaching_subject"   => 1,
                "fk_teaching_module"    => null,
                "date"                  => "2024-09-19",
                "grade"                 => 5,
                "is_school"             => true,
            ],
            [
                "fk_user_course"        => 2,
                "fk_teaching_subject"   => null,
                "fk_teaching_module"    => 1,
                "date"                  => "2024-09-19",
                "grade"                 => 6,
                "is_school"             => true,
            ],
        ];

        foreach ($grades as $grade) {
            $this->db->table("grade")->insert($grade);
        }
    }
}