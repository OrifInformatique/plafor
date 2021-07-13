<?php


namespace Plafor\Database\Seeds;


use CodeIgniter\Database\Seeder;

class addUserTypeDatas extends Seeder
{
    public function run()
    {
        //user_type//
        $user_types=[
            array (
                'id' => '1',
                'name' => 'Administrateur',
                'access_level' => '4',
            ),
            array (
                'id' => '2',
                'name' => 'Formateur',
                'access_level' => '2',
            ),
            array (
                'id' => '3',
                'name' => 'Apprenti',
                'access_level' => '1',
            ),
        ];
        foreach ($user_types as $user_type){
            $this->db->table('user_type')->insert($user_type);
        }
    }
}