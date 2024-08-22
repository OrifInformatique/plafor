<?php

namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class GradeModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    // For Seeds
    protected $seedOnce = false;
    protected $seed     = 'gradeModelTestSeed';
    protected $basePath = 'tests/_support/Database';

    public function testGradeModelInstance()
    {
        $gradeModel = model('GradeModel');
        $this->assertTrue($gradeModel instanceof GradeModel);
        $this->assertInstanceOf(GradeModel::class, $gradeModel);
    }

    public function testFind()
    {
        $gradeModel = model('GradeModel');
        $data = $gradeModel->find(1);
        $expect = [
            'id' => '1',
            'fk_user_course' => '1',
            'fk_teaching_subject' => '1',
            'fk_teaching_module' => '1',
            'date' => '2024-08-22',
            'grade' => '4.0',
            'is_school' => '1',
            'archive' => null,
            'teaching_subject_name' => 'Mathématiques',
            'teaching_module_name' => 'Interroger, traiter et assurer la '
            . 'maintenance des bases de données'
        ];
        $this->assertEquals($expect, $data);
    }
}

