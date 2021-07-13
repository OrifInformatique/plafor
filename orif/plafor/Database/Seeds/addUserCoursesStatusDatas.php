<?php


namespace Plafor\Database\Seeds;


use CodeIgniter\Database\Seeder;

class addUserCoursesStatusDatas extends Seeder
{
    public function run()
    {
        //user_course_status//
        $user_courses_status=[
            array (
                'id' => '1',
                'name' => 'En cours',
            ),
            array (
                'id' => '2',
                'name' => 'Réussi',
            ),
            array (
                'id' => '3',
                'name' => 'Échouée',
            ),
            array (
                'id' => '4',
                'name' => 'Suspendue',
            ),
            array (
                'id' => '5',
                'name' => 'Abandonnée',
            ),
        ];
        foreach ($user_courses_status as $user_course_status){
            $this->db->table('user_course_status')->insert($user_course_status);
        }
    }

}