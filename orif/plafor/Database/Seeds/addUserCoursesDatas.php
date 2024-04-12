<?php


namespace Plafor\Database\Seeds;


use CodeIgniter\Database\Seeder;

class addUserCoursesDatas extends Seeder
{
    public function run()
    {
        //user_course//
        $user_course = array();
        foreach ($user_course as $user_coursee){
            $this->db->table('user_course')->insert($user_coursee);
        }
    }

}