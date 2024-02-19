<?php
/**
 * Fichier de migration créant la table
 * competence_domain avec les nouveaux plans si n'existent pas
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Migrations\mig2021;
use CodeIgniter\Database\Migration;

class AddCoursePlan extends Migration {

    public function up() {


        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id' => [
                'type' => 'int',
                'contraint' => '11',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'formation_number' => [
                'type' => 'varchar',
                'constraint' => '5',
            ],
            'official_name' => [
                'type' => 'varchar',
                'constraint' => '100',
            ],
            'date_begin' => [
                'type' => 'date',

            ],
            'archive timestamp null',
        ]);

        $this->forge->addKey('id', true, true);
        try {
            $this->db->tableExists('course_plan')===true?:$this->forge->createTable('course_plan');
        }catch (\Exception $e){
            echo $e->getMessage();
        }
        $seeder = \Config\Database::seeder();
        $seeder->call('\Plafor\Database\Seeds\addCoursePlan2021Datas');
    }

    public function down() {
        $this->forge->dropTable('course_plan');
    }
}