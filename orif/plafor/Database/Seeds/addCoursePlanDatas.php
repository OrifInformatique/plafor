<?php


namespace Plafor\Database\Seeds;


use CodeIgniter\Database\Seeder;

class addCoursePlanDatas extends Seeder
{
    public function run()
    {
        //course_plan//
        $course_plans=[
            array (
                'id' => '1',
                'formation_number' => '88601',
                'official_name' => ' Informaticien/-ne CFC Développement d\'applications',
                'date_begin' => '2014-08-01',
                'archive' => NULL,
            ),
            array (
                'id' => '2',
                'formation_number' => '88602',
                'official_name' => ' Informaticien/-ne CFC Informatique d\'entreprise',
                'date_begin' => '2014-08-01',
                'archive' => NULL,
            ),
            array (
                'id' => '3',
                'formation_number' => '88603',
                'official_name' => ' Informaticien/-ne CFC Technique des systèmes',
                'date_begin' => '2014-08-01',
                'archive' => NULL,
            ),
            array (
                'id' => '4',
                'formation_number' => '88605',
                'official_name' => ' Opératrice en informatique / Opérateur en informatique CFC',
                'date_begin' => '2018-08-01',
                'archive' => NULL,
            ),
            array (
                'id' => '5',
                'formation_number' => '123',
                'official_name' => 'tester',
                'date_begin' => '2021-06-02',
                'archive' => NULL,
            ),
            array (
                'id' => '6',
                'formation_number' => '12',
                'official_name' => 'a',
                'date_begin' => '2021-06-07',
                'archive' => '2021-06-07 13:49:26',
            ),
            array (
                'id' => '7',
                'formation_number' => '145',
                'official_name' => 'qwertz',
                'date_begin' => '2021-06-07',
                'archive' => NULL,
            ),
            array (
                'id' => '8',
                'formation_number' => '2211',
                'official_name' => 'dada',
                'date_begin' => '2021-06-07',
                'archive' => NULL,
            ),
            array (
                'id' => '9',
                'formation_number' => '12341',
                'official_name' => 'asaasassa',
                'date_begin' => '2021-06-07',
                'archive' => NULL,
            ),
        ];
        foreach ($course_plans as $course_plan){
            $this->db->table('course_plan')->insert($course_plan);
        }
    }
}