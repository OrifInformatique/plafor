<?php
/**
 * Migration file for the table "teaching_domain"
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeachingDomain extends Migration{

    public function up(){
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint" => "11",
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "fk_course_plan" => [
                "type" => "INT",
                "null" => true,
                "unsigned" => true,
            ],
            "fk_teaching_domain_title" => [
                "type" => "INT",
                "null" => true,
                "unsigned" => true,
            ],
            "domain_weight" => [
                "type" => "DECIMAL",
                "constraint" => "2,1",
                "unsigned" => true,
            ],
            "is_eliminatory" => [
                "type" => "BOOL"
            ],
            "archive TIMESTAMP NULL",
        ]);

        $this->forge->addKey("id", true, true);
        $this->forge->addForeignKey("fk_course_plan","course_plan","id");
        $this->forge->addForeignKey("fk_teaching_domain_title","teaching_domain_title","id");
        $this->forge->createTable("teaching_domain");
        $seeder = \Config\Database::seeder();
        $seeder->call("\Plafor\Database\Seeds\addTeachingDomain");
    }

    public function down(){
        $this->forge->dropTable("teaching_domain", true);
    }
}