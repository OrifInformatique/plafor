<?php


namespace Plafor\Database\Seeds;


use CodeIgniter\Database\Seeder;

class addUserCoursesDatas extends Seeder
{
    public function run()
    {
        //user_course//
        $user_course = [
            ["fk_user" => "4", "fk_course_plan" => "5", "fk_status" => "1", "date_begin" => "2024-08-01", "date_end" => "2028-08-01"],
            ["fk_user" => "5", "fk_course_plan" => "6", "fk_status" => "1", "date_begin" => "2024-08-01", "date_end" => "2028-08-01"],
        ];
        foreach ($user_course as $user_coursee){
            $this->db->table('user_course')->insert($user_coursee);
        }
    }

}