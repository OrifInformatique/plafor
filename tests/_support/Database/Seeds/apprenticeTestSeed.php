<?php

namespace App\Database\Seeds;


use CodeIgniter\Database\Seeder;

class apprenticeTestSeed extends Seeder
{

    public function insertUsers() {
        $users=[
            array (
                'fk_user_type' => '1',
                'username' => 'admin2',
                'password' => '$2y$10$tUB5R1MGgbO.zD//WArnceTY8IgnFkVVsudIdHBxIrEXJ2z3WBvcK',
                'archive' => NULL,
                'date_creation' => '2020-07-09 08:11:05',
            ),
            array (
                'fk_user_type' => '2',
                'username' => 'FormateurDev',
                'password' => '$2y$10$Q3H8WodgKonQ60SIcu.eWuVKXmxqBw1X5hMpZzwjRKyCTB1H1l.pe',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:15:24',
            ),
            array (
                'fk_user_type' => '2',
                'username' => 'FormateurSysteme',
                'password' => '$2y$10$Br7mIRYfLufWkrSpi2SyB.Wz0vHZQp7dQf7f2bKy5i/CkhHomSvli',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:15:47',
            ),
            array (
                'fk_user_type' => '3',
                'username' => 'ApprentiDev',
                'password' => '$2y$10$6TLaMd5ljshybxANKgIYGOjY0Xur9EgdzcEPy1bgy2b8uyWYeVoEm',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:16:05',
            ),
            array (
                'fk_user_type' => '3',
                'username' => 'ApprentiSysteme',
                'password' => '$2y$10$0ljkGcDQpTc0RDaN7Y2XcOhS8OB0t0QIhquLv9NcR79IVO9rCR/0.',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:16:27',
            ),
            array (
                'fk_user_type' => '2',
                'username' => 'FormateurOperateur',
                'password' => '$2y$10$SbMYPxqnngLjxVGlG4hW..lrc.pr5Dd74nY.KqdANtEESIvmGRpWi',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:24:22',
            ),
            array (
                'fk_user_type' => '3',
                'username' => 'ApprentiOperateur',
                'password' => '$2y$10$jPNxV2ZZ6Il2LiBQ.CWhNOoud6NsMRFILwHN8kpD410shWeiGpuxK',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:24:45',
            ),
        ];
        foreach ($users as $user){
            $this->db->table('user')->insert($user);
        }
    }

    public function insertUserCourses() {
        $user_course = array(
            # dév
            array('fk_user' => '6','fk_course_plan' => '6','fk_status' => '1','date_begin' => '2020-07-09','date_end' => '0000-00-00'),
            # sys
            array('fk_user' => '7','fk_course_plan' => '5','fk_status' => '1','date_begin' => '2020-07-09','date_end' => '0000-00-00'),
            # op
            array('fk_user' => '9','fk_course_plan' => '4','fk_status' => '1','date_begin' => '2020-07-09','date_end' => '0000-00-00')
        );
        foreach ($user_course as $user_coursee){
            $this->db->table('user_course')->insert($user_coursee);
        }
    }

    public function insertTrainerApprentice() {
        $trainer_apprentice = array(
            array('fk_trainer' => '4','fk_apprentice' => '6'),
            array('fk_trainer' => '5','fk_apprentice' => '7'),
            array('fk_trainer' => '8','fk_apprentice' => '9')
        );
        foreach ($trainer_apprentice as $trainer_apprenticee)
        $this->db->table('trainer_apprentice')->insert($trainer_apprenticee);
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
