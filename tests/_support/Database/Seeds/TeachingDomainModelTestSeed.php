<?php

namespace App\Database\Seeds;


use CodeIgniter\Database\Seeder;

class TeachingDomainModelTestSeed extends Seeder
{
    public function insertUsers() {
        $users=[
            array (
                'id' => '103',
                'fk_user_type' => '1',
                'username' => 'admin2',
                'password' => '$2y$10$tUB5R1MGgbO.zD//WArnceTY8IgnFkVVsudIdHBxIrEXJ2z3WBvcK',
                'archive' => NULL,
                'date_creation' => '2020-07-09 08:11:05',
            ),
            array (
                'id' => '104',
                'fk_user_type' => '2',
                'username' => 'FormateurDev',
                'password' => '$2y$10$Q3H8WodgKonQ60SIcu.eWuVKXmxqBw1X5hMpZzwjRKyCTB1H1l.pe',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:15:24',
            ),
            array (
                'id' => '105',
                'fk_user_type' => '2',
                'username' => 'FormateurSysteme',
                'password' => '$2y$10$Br7mIRYfLufWkrSpi2SyB.Wz0vHZQp7dQf7f2bKy5i/CkhHomSvli',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:15:47',
            ),
            array (
                'id' => '106',
                'fk_user_type' => '3',
                'username' => 'ApprentiDev',
                'password' => '$2y$10$6TLaMd5ljshybxANKgIYGOjY0Xur9EgdzcEPy1bgy2b8uyWYeVoEm',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:16:05',
            ),
            array (
                'id' => '107',
                'fk_user_type' => '3',
                'username' => 'ApprentiSysteme',
                'password' => '$2y$10$0ljkGcDQpTc0RDaN7Y2XcOhS8OB0t0QIhquLv9NcR79IVO9rCR/0.',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:16:27',
            ),
            array (
                'id' => '108',
                'fk_user_type' => '2',
                'username' => 'FormateurOperateur',
                'password' => '$2y$10$SbMYPxqnngLjxVGlG4hW..lrc.pr5Dd74nY.KqdANtEESIvmGRpWi',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:24:22',
            ),
            array (
                'id' => '109',
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
            array('id' => '101','fk_user' => '106','fk_course_plan' => '6','fk_status' => '1','date_begin' => '2020-07-09','date_end' => '0000-00-00'),
            # sys
            array('id' => '102','fk_user' => '107','fk_course_plan' => '5','fk_status' => '1','date_begin' => '2020-07-09','date_end' => '0000-00-00'),
            # op
            array('id' => '103','fk_user' => '109','fk_course_plan' => '4','fk_status' => '1','date_begin' => '2020-07-09','date_end' => '0000-00-00')
        );
        foreach ($user_course as $user_coursee){
            $this->db->table('user_course')->insert($user_coursee);
        }
    }
    public function run() {
        $this->insertUserCourses();
        $this->insertUsers();
    }

}
