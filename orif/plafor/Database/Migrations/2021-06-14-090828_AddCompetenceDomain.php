<?php
namespace Plafor\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddCompetenceDomain extends Migration {

    public function up() {

        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'contraint' => '11',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'fk_course_plan' => [
                'type' => 'int',
                'null'=>true,
                'unsigned' => true,
            ],
            'symbol' => [
                'type' => 'varchar',
                'constraint' => '10',
            ],
            'name' => [
                'type' => 'varchar',
                'constraint' => '100',
            ],
            'archive TIMESTAMP NULL',
        ]);

        $this->forge->addKey('id', true, true);
        $this->forge->addForeignKey('fk_course_plan', 'course_plan','id');
        $this->forge->createTable('competence_domain');
        $seeder = \Config\Database::seeder();
        $seeder->call('\Plafor\Database\Seeds\addCompetenceDomainDatas');
    }

    public function down() {
        $this->forge->dropTable('competence_domain');
    }
}