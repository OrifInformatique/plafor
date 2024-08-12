<?php
/**
 * Migration file for the table "teaching_domain_module"
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeachingDomainModule extends Migration{

    public function up(){
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint" => "11",
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "fk_teaching_domain" => [
                "type" => "INT",
                "null" => true,
                "unsigned" => true,
            ],
            "fk_teaching_module" => [
                "type" => "INT",
                "null" => true,
                "unsigned" => true,
            ],
            "archive TIMESTAMP NULL",
        ]);

        $this->forge->addKey("id", true, true);
        $this->forge->addForeignKey("fk_teaching_domain", "teaching_domain", "id");
        $this->forge->addForeignKey("fk_teaching_module", "teaching_module", "id");
        $this->forge->createTable("teaching_domain_module");
        $seeder = \Config\Database::seeder();
        $seeder->call("\Plafor\Database\Seeds\addTeachingDomainModule");
    }

    public function down(){
        $this->forge->dropTable("teaching_domain_module", true);
    }
}