<?php
/**
 * Migration file for the table "grade"
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGrade extends Migration{

    public function up(){
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            "id" => [
                "type" => "INT",
                "constraint" => "11",
                "unsigned" => true,
                "auto_increment" => true,
            ],
            "fk_user_course" => [
                "type" => "INT",
                "null" => true,
                "unsigned" => true,
            ],
            "fk_teaching_subject" => [
                "type" => "INT",
                "null" => true,
                "unsigned" => true,
            ],
            "fk_teaching_module" => [
                "type" => "INT",
                "null" => true,
                "unsigned" => true,
            ],
            "date" => [
                "TIMESTAMP",
            ],
            "grade" => [
                "type" => "DECIMAL",
                "constraint" => "2,1",
                "unsigned" => true,
            ],
            "is_school" => [
                "type" => "BOOL",
            ],
            "archive TIMESTAMP NULL",
        ]);

        $this->forge->addKey("id", true, true);
        $this->forge->addForeignKey("fk_user_course", "user_course", "id");
        $this->forge->addForeignKey("fk_teaching_subject", "teaching_subject", "id");
        $this->forge->addForeignKey("fk_teaching_module", "teaching_module", "id");
        $this->forge->createTable("grade");
    }

    public function down(){
        $this->forge->dropTable("grade", true);
    }
}