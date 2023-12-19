<?php
/**
 * Fichier de migration créant la table objective
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddObjective extends Migration {

    public function up() {

        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'contraint' => '11',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'fk_operational_competence' => [
                'type' => 'int',
                'null'=>true,
                'unsigned' => true,
            ],
            'symbol' => [
                'type' => 'varchar',
                'constraint' => '10',
            ],
            'taxonomy' => [
                'type' => 'int', 
                'constraint' => '5',
            ],
            'name' => [
                'type' => 'varchar',
                'constraint' => '350',
            ],
            'archive timestamp null',
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addForeignKey('fk_operational_competence','operational_competence','id');
        $this->forge->createTable('objective');
        $seeder = \Config\Database::seeder();
        $seeder->call('\Plafor\Database\Seeds\addObjectiveDatas');
    }

    public function down() {
        $this->forge->dropTable('objective');
    }
}