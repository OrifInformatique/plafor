<?php
/**
 * Fichier de migration créant la table
 * competence_domain avec les nouveaux domaines de compétences
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Migrations\mig2021;
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
        try {
            $this->db->tableExists('competence_domain')===true?:$this->forge->createTable('competence_domain');
        }catch (\Exception $e){
            echo $e->getMessage();
        }
        $seeder = \Config\Database::seeder();
        $seeder->call('\Plafor\Database\Seeds\addCompetenceDomain2021Datas');
    }

    public function down() {
        $this->forge->dropTable('competence_domain');
    }
}