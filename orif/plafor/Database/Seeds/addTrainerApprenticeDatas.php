<?php


namespace Plafor\Database\Seeds;


use CodeIgniter\Database\Seeder;

class addTrainerApprenticeDatas extends Seeder
{

    public function run()
    {
        //trainer_apprentice//
        $trainer_apprentices=[
            array (
                'id' => '10',
                'fk_trainer' => '2',
                'fk_apprentice' => '4',
            ),
            array (
                'id' => '11',
                'fk_trainer' => '3',
                'fk_apprentice' => '4',
            ),
            array (
                'id' => '2',
                'fk_trainer' => '3',
                'fk_apprentice' => '5',
            ),
            array (
                'id' => '3',
                'fk_trainer' => '6',
                'fk_apprentice' => '7',
            ),
        ];
        foreach ($trainer_apprentices as $trainer_apprentice)
        $this->db->table('trainer_apprentice')->insert($trainer_apprentice);
    }

}