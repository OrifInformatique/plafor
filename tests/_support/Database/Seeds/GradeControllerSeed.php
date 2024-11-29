<?php

namespace App\Database\Seeds;


use CodeIgniter\Database\Seeder;

// The helper hold all Constants -> Plafor\orif\plafor\Helpers\UnitTest_helper.php
helper("UnitTest_helper");

class GradeControllerSeed extends Seeder {
    public function insertTrainerApprentice() {
        $trainer_apprentice_links = [
            [
                "id"            => LINK_DEV_ID,
                "fk_trainer"    => TRAINER_DEV_ID,
                "fk_apprentice" => APPRENTICE_DEV_ID
            ],
            [
                "id"            => LINK_SYS_ID,
                "fk_trainer"    => TRAINER_SYS_ID,
                "fk_apprentice" => APPRENTICE_SYS_ID
            ],
            [
                "id"            => LINK_OPE_ID,
                "fk_trainer"    => TRAINER_OPE_ID,
                "fk_apprentice" => APPRENTICE_OPE_ID
            ],
        ];
        foreach ($trainer_apprentice_links as $trainer_apprentice_link)
        $this->db->table("trainer_apprentice")->insert($trainer_apprentice_link);
    }

    public function insertUserCourses() {
        $user_course = [
            [
                "id" => 101,
                "fk_user" => APPRENTICE_DEV_ID,
                "fk_course_plan" => COURSE_PLAN_DEV_ID,
                "fk_status" => "1",
                "date_begin" => "2020-07-09",
                "date_end" => "0000-00-00"],
            [
                "id" => 102,
                "fk_user" => APPRENTICE_SYS_ID,
                "fk_course_plan" => COURSE_PLAN_SYS_ID,
                "fk_status" => "1",
                "date_begin" => "2020-07-09",
                "date_end" => "0000-00-00"],
            [
                "id" => 103,
                "fk_user" => APPRENTICE_OPE_ID,
                "fk_course_plan" => COURSE_PLAN_OPE_ID,
                "fk_status" => "1",
                "date_begin" => "2020-07-09",
                "date_end" => "0000-00-00"]
        ];
        foreach ($user_course as $user_coursee){
            $this->db->table('user_course')->insert($user_coursee);
        }
    }

    public function insertUsers() {
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
                "id"            => TRAINER_SYS_ID,
                "fk_user_type"  => TRAINER_USER_TYPE,
                "username"      => TRAINER_SYS_NAME,
                "password"      => TRAINER_SYS_HASHED_PW,
                "archive"       => TRAINER_SYS_ARCHIVE,
                "date_creation" => TRAINER_SYS_CREATION_DATE,
            ],
            [
                "id"            => TRAINER_OPE_ID,
                "fk_user_type"  => TRAINER_USER_TYPE,
                "username"      => TRAINER_OPE_NAME,
                "password"      => TRAINER_OPE_HASHED_PW,
                "archive"       => TRAINER_OPE_ARCHIVE,
                "date_creation" => TRAINER_OPE_CREATION_DATE,
            ],
            [
                "id"            => APPRENTICE_DEV_ID,
                "fk_user_type"  => APPRENTICE_USER_TYPE,
                "username"      => APPRENTICE_DEV_NAME,
                "password"      => APPRENTICE_DEV_HASHED_PW,
                "archive"       => APPRENTICE_DEV_ARCHIVE,
                "date_creation" => APPRENTICE_DEV_CREATION_DATE,
            ],
            [
                "id"            => APPRENTICE_SYS_ID,
                "fk_user_type"  => APPRENTICE_USER_TYPE,
                "username"      => APPRENTICE_SYS_NAME,
                "password"      => APPRENTICE_SYS_HASHED_PW,
                "archive"       => APPRENTICE_SYS_ARCHIVE,
                "date_creation" => APPRENTICE_SYS_CREATION_DATE,
            ],
            [
                "id"            => APPRENTICE_OPE_ID,
                "fk_user_type"  => APPRENTICE_USER_TYPE,
                "username"      => APPRENTICE_OPE_NAME,
                "password"      => APPRENTICE_OPE_HASHED_PW,
                "archive"       => APPRENTICE_OPE_ARCHIVE,
                "date_creation" => APPRENTICE_OPE_CREATION_DATE,
            ],
        ];
        foreach ($users as $user){
            $this->db->table('user')->insert($user);
        }
    }



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
            [
                'id' => 101,
                'fk_user_course' => 101,
                'fk_teaching_subject' => 1,
                'fk_teaching_module' => null,
                'date' => '2024-08-22',
                'grade' => 4,
                'is_school' => true,
                'archive' => null
            ],
        ];



        $this->insertUserCourses();
        $this->insertUsers();
        $this->insertTrainerApprentice();
        foreach ($grades as $grade) $this->db->table('grade')->insert($grade);
    }

}
