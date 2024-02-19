<?php


namespace Plafor\Database\Seeds;


use CodeIgniter\Database\Seeder;

class addAcquisitionLevelDatas extends Seeder
{
    public function run()
    {
        //Acquisition level//
        /**
         * Export to PHP Array plugin for PHPMyAdmin
         * @version 5.0.4
         */

        /**
         * Database `plafor`
         */

        /* `plafor`.`acquisition_level` */
        $acquisition_level = array(
            array('id' => '1','name' => 'Non expliqué'),
            array('id' => '2','name' => 'Expliqué'),
            array('id' => '3','name' => 'Exercé'),
            array('id' => '4','name' => 'Autonome')
        );
        foreach ($acquisition_level as $acquisitionLevel)
        $this->db->table('acquisition_level')->insert($acquisitionLevel);
    }

}