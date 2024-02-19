<?php
/**
 * Fichier de migration créant la table acquisition_level
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddAcquisitionLevel extends Migration {

    public function up() {

        $this->db->disableForeignKeyChecks();

        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'contraint' => '11',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'varchar',
                'constraint' => '20',
            ],
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->createTable('acquisition_level');
        $seeder = \Config\Database::seeder();
        $seeder->call('\Plafor\Database\Seeds\addAcquisitionLevelDatas');
    }

    public function down() {
        $this->forge->dropTable('acquisition_level');
    }
}