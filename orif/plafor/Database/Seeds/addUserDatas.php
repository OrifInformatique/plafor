<?php


namespace Plafor\Database\Seeds;


use CodeIgniter\Database\Seeder;

class addUserDatas extends Seeder
{
    public function run(){
        //user//
        $users=[
            array (
                'id' => '1',
                'fk_user_type' => '1',
                'username' => 'admin',
                'password' => '$2y$10$tUB5R1MGgbO.zD//WArnceTY8IgnFkVVsudIdHBxIrEXJ2z3WBvcK',
                'archive' => NULL,
                'date_creation' => '2020-07-09 08:11:05',
            ),
            array (
                'id' => '2',
                'fk_user_type' => '2',
                'username' => 'FormateurDev',
                'password' => '$2y$10$Q3H8WodgKonQ60SIcu.eWuVKXmxqBw1X5hMpZzwjRKyCTB1H1l.pe',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:15:24',
            ),
            array (
                'id' => '3',
                'fk_user_type' => '2',
                'username' => 'FormateurSysteme',
                'password' => '$2y$10$Br7mIRYfLufWkrSpi2SyB.Wz0vHZQp7dQf7f2bKy5i/CkhHomSvli',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:15:47',
            ),
            array (
                'id' => '4',
                'fk_user_type' => '3',
                'username' => 'ApprentiDev',
                'password' => '$2y$10$6TLaMd5ljshybxANKgIYGOjY0Xur9EgdzcEPy1bgy2b8uyWYeVoEm',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:16:05',
            ),
            array (
                'id' => '5',
                'fk_user_type' => '3',
                'username' => 'ApprentiSysteme',
                'password' => '$2y$10$0ljkGcDQpTc0RDaN7Y2XcOhS8OB0t0QIhquLv9NcR79IVO9rCR/0.',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:16:27',
            ),
            array (
                'id' => '6',
                'fk_user_type' => '2',
                'username' => 'FormateurOperateur',
                'password' => '$2y$10$SbMYPxqnngLjxVGlG4hW..lrc.pr5Dd74nY.KqdANtEESIvmGRpWi',
                'archive' => NULL,
                'date_creation' => '2020-07-09 13:24:22',
            ),
            array (
                'id' => '7',
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

}