<?php

namespace Plafor\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoundMultipleColumn extends Migration
{
    public function up()
    {
        $fields = [
            'round_multiple' => [
                'type' => 'DECIMAL',
                'constraint' => '4,3',
                'unsigned' => true,
            ],
        ];

        $this->forge->addColumn('teaching_domain', $fields);
        $this->forge->addColumn('teaching_subject', $fields);
        $seeder = \Config\Database::seeder();
        $seeder->call('\Plafor\Database\Seeds\addRoundMultipleColumn');
        $notNullfields = [
            'round_multiple' => [
                'type' => 'DECIMAL',
                'constraint' => '4,3',
                'unsigned' => true,
                'null' => false,
            ],
        ];

        $this->forge->modifyColumn('teaching_domain', $notNullfields);
        $this->forge->modifyColumn('teaching_subject', $notNullfields);
    }

    public function down()
    {
        $this->forge->dropColumn('teaching_domain', 'round_multiple');
        $this->forge->dropColumn('teaching_subject', 'round_multiple');
    }
}
