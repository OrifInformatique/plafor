<?php

namespace App\Database\Seeds;


use CodeIgniter\Database\Seeder;

// The helper hold all Constants -> Plafor\orif\plafor\Helpers\UnitTest_helper.php
helper("UnitTest_helper");

class GradeTestSeed extends Seeder {
    private function insertTrainerApprentice() {
        $trainer_apprentice_links = [
            [
                "id"            => LINK_DEV_ID,
                "fk_trainer"    => TRAINER_DEV_ID,
                "fk_apprentice" => APPRENTICE_DEV_ID
            ],
        ];
        foreach ($trainer_apprentice_links as $trainer_apprentice_link)
        $this->db->table("trainer_apprentice")->insert($trainer_apprentice_link);
    }

    private function insertUserCourses() {
        $user_course = [
            [
                "id" => USER_COURSE_DEV_ID,
                "fk_user" => APPRENTICE_DEV_ID,
                "fk_course_plan" => COURSE_PLAN_DEV_ID,
                "fk_status" => "1",
                "date_begin" => "2020-07-09",
                "date_end" => "0000-00-00"],
        ];
        foreach ($user_course as $user_coursee){
            $this->db->table('user_course')->insert($user_coursee);
        }
    }

    private function insertUsers() {
        $users = [
            [
                "id"            => ADMIN_ID,
                "fk_user_type"  => ADMIN_USER_TYPE,
                "username"      => ADMIN_NAME,
                "password"      => ADMIN_HASHED_PW,
                "archive"       => ADMIN_ARCHIVE,
                "date_creation" => ADMIN_CREATION_DATE,
            ],
            [
                "id"            => TRAINER_DEV_ID,
                "fk_user_type"  => TRAINER_USER_TYPE,
                "username"      => TRAINER_DEV_NAME,
                "password"      => TRAINER_DEV_HASHED_PW,
                "archive"       => TRAINER_DEV_ARCHIVE,
                "date_creation" => TRAINER_DEV_CREATION_DATE,
            ],
            [
                "id"            => APPRENTICE_DEV_ID,
                "fk_user_type"  => APPRENTICE_USER_TYPE,
                "username"      => APPRENTICE_DEV_NAME,
                "password"      => APPRENTICE_DEV_HASHED_PW,
                "archive"       => APPRENTICE_DEV_ARCHIVE,
                "date_creation" => APPRENTICE_DEV_CREATION_DATE,
            ],
        ];
        foreach ($users as $user){
            $this->db->table('user')->insert($user);
        }
    }

    private function insertCBEGrade() {
        $CBEGrade = [
            [
                'fk_teaching_module' => null,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => 8, // english dev
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null,
                'grade' => 6,
            ],
            [
                'fk_teaching_module' => null,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => 8, // english dev
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null,
                'grade' => 6,
            ],
            [
                'fk_teaching_module' => null,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => 8, // english dev
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null,
                'grade' => 5.5,
            ],
            [
                'fk_teaching_module' => null,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => 8, // english dev
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null,
                'grade' => 6,
            ],
            [
                'fk_teaching_module' => null,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => 8, // english dev
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null,
                'grade' => 5,
            ],
            [
                'fk_teaching_module' => null,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => 8, // english dev
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null,
                'grade' => 4,
            ],
            [
                'fk_teaching_module' => null,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => 8, // english dev
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null,
                'grade' => 4.5,
            ],
            [
                'fk_teaching_module' => null,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => 8, // english dev
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null,
                'grade' => 5,
            ],
            [
                'fk_teaching_module' => null,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => 7, // math dev
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null,
                'grade' => 6,
            ],
            [
                'fk_teaching_module' => null,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => 7,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null,
                'grade' => 6,
            ],
            [
                'fk_teaching_module' => null,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => 7,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null,
                'grade' => 6,
            ],
            [
                'fk_teaching_module' => null,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => 7,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null,
                'grade' => 4.5,
            ],
        ];
        foreach ($CBEGrade as $grade) {
            $this->db->table('grade')->insert($grade);
        }
    }

    private function insertSchoolModuleGrade() {
        $SchoolModuleGrade = [
            [
                'grade' => 6.0,
                'fk_teaching_module' => 5,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 5.5,
                'fk_teaching_module' => 6,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 5.5,
                'fk_teaching_module' => 7,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 6.0,
                'fk_teaching_module' => 17,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 6.0,
                'fk_teaching_module' => 18,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 6.0,
                'fk_teaching_module' => 19,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 4.5,
                'fk_teaching_module' => 22,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 5.5,
                'fk_teaching_module' => 33,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 4.0,
                'fk_teaching_module' => 34,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 4.0,
                'fk_teaching_module' => 35,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 5.5,
                'fk_teaching_module' => 37,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 4.5,
                'fk_teaching_module' => 43,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 5.0,
                'fk_teaching_module' => 49,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 5.0,
                'fk_teaching_module' => 50,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 5.5,
                'fk_teaching_module' => 51,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 4.5,
                'fk_teaching_module' => 52,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 5.0,
                'fk_teaching_module' => 53,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 5.0,
                'fk_teaching_module' => 54,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 4.5,
                'fk_teaching_module' => 55,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 6.0,
                'fk_teaching_module' => 57,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 5.5,
                'fk_teaching_module' => 58,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 5.5,
                'fk_teaching_module' => 59,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 6.0,
                'fk_teaching_module' => 60,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
            [
                'grade' => 4.0,
                'fk_teaching_module' => 62,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => true,
                'archive' => null
            ],
        ];
        foreach ($SchoolModuleGrade as $grade) {
            $this->db->table('grade')->insert($grade);
        }
    }

    private function insertExternalModuleGrade() {
        $externalModuleGrade = [
            [
                'grade' => 6.0,
                'fk_teaching_module' => 1,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => 0,
                'archive' => null
            ],
            [
                'grade' => 5.5,
                'fk_teaching_module' => 4,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => 0,
                'archive' => null
            ],
            [
                'grade' => 6.0,
                'fk_teaching_module' => 25,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => 0,
                'archive' => null
            ],
            [
                'grade' => 6.0,
                'fk_teaching_module' => 38,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => 0,
                'archive' => null
            ],
            [
                'grade' => 5.5,
                'fk_teaching_module' => 44,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => 0,
                'archive' => null
            ],
            [
                'grade' => 5.0,
                'fk_teaching_module' => 45,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => 0,
                'archive' => null
            ],
            [
                'grade' => 4.5,
                'fk_teaching_module' => 56,
                'fk_user_course' => USER_COURSE_DEV_ID,
                'fk_teaching_subject' => null,
                'date' => '2024-08-22',
                'is_school' => 0,
                'archive' => null
            ],
        ];
        foreach ($externalModuleGrade as $grade) {
            $this->db->table('grade')->insert($grade);
        }
    }

    public function run() {
        $this->insertUserCourses();
        $this->insertUsers();
        $this->insertTrainerApprentice();
        $this->insertCBEGrade();
        $this->insertSchoolModuleGrade();
        $this->insertExternalModuleGrade();
    }

}
