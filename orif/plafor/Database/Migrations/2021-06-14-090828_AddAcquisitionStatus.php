<?php
/**
 * Fichier de migration créant la table acquisition_status
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Database\Migrations;


class AddAcquisitionStatus extends \CodeIgniter\Database\Migration
{

    /**
     * @inheritDoc
     */
    public function up()
    {

        $this->forge->addField([
            'id'=>[
                'type'=>'int',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'fk_objective'=>[
                'type'=>'int',
                'null'  =>true,
                'unsigned' => true,

            ],
            'fk_user_course'=>[
                'type'=>'int',
                'null'=>true,
                'unsigned' => true,

            ],
            'fk_acquisition_level'=>[
                'type'=>'int',
                'null'=>true,
                'unsigned' => true,

            ]
        ]);
        $this->forge->addKey('id',true,true);
        $this->forge->addForeignKey('fk_objective','objective','id');
        $this->forge->addForeignKey('fk_user_course','user_course','id');
        $this->forge->addForeignKey('fk_acquisition_level','acquisition_level','id');
        $this->db->disableForeignKeyChecks();
        $this->forge->createTable('acquisition_status');
        $seeder = \Config\Database::seeder();
        $seeder->call('\Plafor\Database\Seeds\addAcquisitionStatuses');
    }


    /**
     * @inheritDoc
     */
    public function down()
    {
        $this->forge->dropTable('acquisition_status');
    }
}