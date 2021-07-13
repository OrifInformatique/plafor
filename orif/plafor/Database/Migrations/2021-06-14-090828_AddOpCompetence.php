<?php
namespace Plafor\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddOpCompetence extends Migration {

    public function up() {


        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'contraint' => '11',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'fk_competence_domain' => [
                'type' => 'int',
                'null'=>true,
                'unsigned' => true,
            ],
            'name' => [
                'type' => 'varchar',
                'constraint' => '150',
                'default' => 'null',
            ],
            'symbol' => [
                'type' => 'varchar',
                'constraint' => '10',
            ],
            'methodologic' => [
                'type' => 'text',
                'default' => 'null',
            ],
            'social' => [
                'type' => 'text',
                'default' => 'null',
            ],
            'personal' => [
                'type' => 'text',
                'default' => 'null',
            ],

            'archive timestamp null',
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addForeignKey('fk_competence_domain', 'competence_domain','id');
        $this->forge->createTable('operational_competence');
        $seeder = \Config\Database::seeder();
        $seeder->call('\Plafor\Database\Seeds\addOperationalCompetencesDatas');
    }

    public function down() {
        $this->forge->dropTable('operational_competence');
    }
}