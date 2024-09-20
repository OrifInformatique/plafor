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
            [
                // "fk_user_course"        => 1,
                // "fk_teaching_subject"   => 1,
                // "fk_teaching_module"    => null,
                // "date"                  => "2024-09-19",
                // "grade"                 => 5,
                // "is_school"             => true,
            ],
        ];

        foreach ($grades as $grade) {
            $this->db->table("grade")->insert($grade);
        }
    }
}