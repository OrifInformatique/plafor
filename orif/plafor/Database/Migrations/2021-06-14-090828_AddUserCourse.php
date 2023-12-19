<?php
/**
 * Fichier de migration créant la table user_course
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Database\Migrations;
use CodeIgniter\Database\Migration;
class AddUserCourse extends Migration{

    public function up()
    {


        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id'=>[
                'type'=>'int',
                'unsigned'  =>true,
                'auto_increment'=>true,

            ],
            'fk_user'=>[
                'type'=>'int',
                'null'=>true,
                'unsigned' => true,
            ],
            'fk_course_plan'=>[
                'type'=>'int',
                'null'=>true,
                'unsigned' => true,
            ],
            'fk_status'=>[
                'type'=>'int',
                'null'=>true,
                'unsigned' => true,
            ],
            'date_begin'=>[
                'type'=>'date'
            ],
            'date_end'=>[
                'type'=>'date'
            ]
        ]);
        $this->forge->addKey('id',true,true);
        $this->forge->addForeignKey('fk_user','user','id');
        $this->forge->addForeignKey('fk_course_plan','course_plan','id');
        $this->forge->addForeignKey('fk_status','user_course_status','id');
        $this->forge->createTable('user_course');
        $seeder = \Config\Database::seeder();
        $seeder->call('\Plafor\Database\Seeds\addUserCoursesDatas');
    }

    public function down()
    {
        $this->forge->dropTable('user_course');
    }
}