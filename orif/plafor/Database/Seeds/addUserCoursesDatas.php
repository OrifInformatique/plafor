<?php


namespace Plafor\Database\Seeds;


use CodeIgniter\Database\Seeder;

class addUserCoursesDatas extends Seeder
{
    public function run()
    {
        //user_course//
        $user_course = array(
            array('id' => '1','fk_user' => '4','fk_course_plan' => '1','fk_status' => '1','date_begin' => '2020-07-09','date_end' => '0000-00-00'),
            array('id' => '2','fk_user' => '5','fk_course_plan' => '3','fk_status' => '1','date_begin' => '2020-07-09','date_end' => '0000-00-00'),
            array('id' => '3','fk_user' => '7','fk_course_plan' => '4','fk_status' => '1','date_begin' => '2020-07-09','date_end' => '0000-00-00')
        );
        foreach ($user_course as $user_coursee){
            $this->db->table('user_course')->insert($user_coursee);
        }
    }

}