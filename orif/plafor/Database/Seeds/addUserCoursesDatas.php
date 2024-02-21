<?php


namespace Plafor\Database\Seeds;


use CodeIgniter\Database\Seeder;

class addUserCoursesDatas extends Seeder
{
    public function run()
    {
        //user_course//
        $user_course = array(
            array('id' => '1','fk_user' => '3','fk_course_plan' => '6','fk_status' => '1','date_begin' => '2021-07-09','date_end' => '2025-07-09'),
        );
        foreach ($user_course as $user_coursee){
            $this->db->table('user_course')->insert($user_coursee);
        }
    }

}