<?php


namespace User\Database\Migrations;


class AddCiSessions extends \CodeIgniter\Database\Migration
{

    /**
     * @inheritDoc
     */
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'              => 'INT',
                'constraint'        => '128',
                'unsigned'          => true,
            ],
            'ip_address'=>[
                'type'              => 'VARCHAR',
                'constraint'        => '45',
                'null'              => false,
            ],
            'timestamp timestamp DEFAULT CURRENT_TIMESTAMP',
            'data'=>[
                'type'              => 'BLOB',
                'null'              =>  false,
            ]

        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('timestamp');
        $this->forge->createTable('ci_sessions',true);
    }

    /**
     * @inheritDoc
     */
    public function down()
    {
        $this->forge->dropTable('ci_sessions');
    }
}