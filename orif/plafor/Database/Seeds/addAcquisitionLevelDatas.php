<?php


namespace Plafor\Database\Seeds;


use CodeIgniter\Database\Seeder;

class addAcquisitionLevelDatas extends Seeder
{
    public function run()
    {
        //Acquisition level//
        $acquisitionLevels=[
            array (
                'id' => '1',
                'name' => 'Non expliqué',
            ),
            array (
                'id' => '2',
                'name' => 'Expliqué',
            ),
            array (
                'id' => '3',
                'name' => 'Exercé',
            ),
            array (
                'id' => '4',
                'name' => 'Autonome',
            ),
        ];
        foreach ($acquisitionLevels as $acquisitionLevel)
        $this->db->table('acquisition_level')->insert($acquisitionLevel);
    }

}