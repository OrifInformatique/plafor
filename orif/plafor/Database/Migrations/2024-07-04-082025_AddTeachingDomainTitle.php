<?php
/**
 * Migration file for the table "teaching_domain_title"
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTeachingDomainTitle extends Migration{

    public function up(){
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint" => "11",
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "title" => [
                "type" => "VARCHAR",
                "constraint" => "50"
            ]
        ]);

        $this->forge->addKey("id", true, true);
        $this->forge->createTable("teaching_domain_title");
        $seeder = \Config\Database::seeder();
        $seeder->call("\Plafor\Database\Seeds\addTeachingDomainTitle");
    }

    public function down(){
        $this->forge->dropTable("teaching_domain_title", true);
    }
}