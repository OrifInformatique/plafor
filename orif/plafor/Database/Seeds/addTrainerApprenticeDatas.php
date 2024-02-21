<?php


namespace Plafor\Database\Seeds;


use CodeIgniter\Database\Seeder;

class addTrainerApprenticeDatas extends Seeder
{

    public function run()
    {
        //trainer_apprentice//
        $trainer_apprentice = array(
            array('id' => '1','fk_trainer' => '2','fk_apprentice' => '3'),
        );
        foreach ($trainer_apprentice as $trainer_apprenticee)
        $this->db->table('trainer_apprentice')->insert($trainer_apprenticee);
    }

}