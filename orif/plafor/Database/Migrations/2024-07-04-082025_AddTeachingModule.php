<?php
/**
 * Migration file for the table "teaching_module"
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeachingModule extends Migration{

    public function up(){
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint" => "11",
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "module_number" => [
                "type" => "SMALLINT",
                "unsigned" => true,
            ],
            "official_name" => [
                "type" => "VARCHAR",
                "constraint" => "200"
            ],
            "version" => [
                "type" => "INT",
                "unsigned" => true,
            ],
            "archive TIMESTAMP NULL",
        ]);

        $this->forge->addKey("id", true, true);
        $this->forge->createTable("teaching_module");
        $seeder = \Config\Database::seeder();
        $seeder->call("\Plafor\Database\Seeds\addTeachingModule");
    }

    public function down(){
        $this->forge->dropTable("teaching_module", true);
    }
}