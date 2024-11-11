<?php

namespace App\Database\Seeds;


use CodeIgniter\Database\Seeder;

helper("UnitTest_helper"); // The helper hold all Constants -> Plafor\orif\plafor\Helpers\UnitTest_helper.php

class ApprenticeTestSeed extends Seeder
{

    public function insertUsers() {
        $users=[
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


    public function insertUserCourses() {
        $user_course = [
            [
                "fk_user" => APPRENTICE_DEV_ID,
                "fk_course_plan" => COURSE_PLAN_DEV_ID,
                "fk_status" => "1",
                "date_begin" => "2020-07-09",
                "date_end" => "0000-00-00"],
            [
                "fk_user" => APPRENTICE_SYS_ID,
                "fk_course_plan" => COURSE_PLAN_SYS_ID,
                "fk_status" => "1",
                "date_begin" => "2020-07-09",
                "date_end" => "0000-00-00"],
            [
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


    public function insertAcquisisitionStatus() {
        $acquisition_status = array(
            array('fk_objective' => '1', 'fk_user_course' => '1', 'fk_acquisition_level' => '2'),
            array('fk_objective' => '2', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '3', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '507', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '508', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '509', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '510', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '511', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '512', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '513', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '514', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '515', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '516', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '517', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '518', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '519', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '520', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '521', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '522', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '523', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '524', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '525', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '526', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '527', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '528', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '529', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '530', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '531', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '532', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '533', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '534', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '535', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '536', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '537', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '538', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '539', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '540', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '541', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '542', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '543', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '544', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '545', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '546', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '547', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '548', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '549', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '550', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '551', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '552', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '553', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '554', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '555', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '556', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '557', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '558', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '559', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '560', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '561', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '562', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '563', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '564', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '565', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '566', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '567', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '568', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '569', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '570', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '571', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '572', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '573', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '574', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '575', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '576', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '577', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '578', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '579', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '580', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '581', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '582', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '583', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '584', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '585', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '586', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '587', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '588', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '589', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
            array('fk_objective' => '590', 'fk_user_course' => '1', 'fk_acquisition_level' => '1'),
        );
        foreach ($acquisition_status as $acquisitionStatuse){
            $this->db->table('acquisition_status')
                     ->insert($acquisitionStatuse);
        }
    }

    public function run() {
        $this->insertUserCourses();
        $this->insertUsers();
        $this->insertTrainerApprentice();
        $this->insertAcquisisitionStatus();
    }

}
