<?php
/**
 * Migration file for the table "teaching_subject"
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeachingSubject extends Migration{

    public function up(){
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            "id" => [
                "type" => "int",
                "constraint" => "11",
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "fk_teaching_domain" => [
                "type" => "int",
                "null" => true,
                "unsigned" => true,
            ],
            "name" => [
                "type" => "VARCHAR",
                "constraint" => "50"
            ],
            "subject_weight" => [
                "type" => "DECIMAL",
                "constraint" => "2,1",
                "unsigned" => true,
            ],
            "archive TIMESTAMP NULL",
        ]);

        $this->forge->addKey("id", true, true);
        $this->forge->addForeignKey("fk_teaching_domain", "teaching_domain", "id");
        $this->forge->createTable("teaching_subject");
        $seeder = \Config\Database::seeder();
        $seeder->call("\Plafor\Database\Seeds\addTeachingSubject");
    }

    public function down(){
        $this->forge->dropTable("teaching_subject", true);
    }
}