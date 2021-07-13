<?php
namespace Plafor\Database\Migrations;
use CodeIgniter\Database\Migration;
class AddUserCourseStatus extends Migration{

    public function up()
    {

        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id'=>[
                'type'=>'int',
                'unsigned' => true,
                'auto_increment'=>true,

            ],
            'name'=>[
                'type'=>'varchar',
                'constraint'=>'20',
            ]
        ]);
        $this->forge->addKey('id',true,true);
        $this->forge->createTable('user_course_status');
        $seeder = \Config\Database::seeder();
        $seeder->call('\Plafor\Database\Seeds\addUserCoursesStatusDatas');
    }

    public function down()
    {
        $this->forge->dropTable('user_course_status');
    }
}