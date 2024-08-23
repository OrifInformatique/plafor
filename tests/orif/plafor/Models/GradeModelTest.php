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

    public function testGradeModelInstance(): void
    {
        $gradeModel = model('GradeModel');
        $this->assertTrue($gradeModel instanceof GradeModel);
        $this->assertInstanceOf(GradeModel::class, $gradeModel);
    }

    public function testFind(): void
    {
        $gradeModel = model('GradeModel');
        $data = $gradeModel->find(1);
        $expect = [
            'id' => '1',
            'fk_user_course' => '1',
            'fk_teaching_subject' => '1',
            'date' => '2024-08-22',
            'grade' => '4.0',
            'is_school' => '1',
            'archive' => null,
            'teaching_subject_name' => 'Mathématiques',
        ];
        $this->assertEquals($expect, $data);
    }

    public function testFind2(): void
    {
        $gradeModel = model('GradeModel');
        $data = $gradeModel->find(2);
        $expect = [
            'id' => '2',
            'fk_user_course' => '1',
            'date' => '2024-08-23',
            'grade' => '4.5',
            'is_school' => '1',
            'archive' => null,
            'fk_teaching_module' => '1',
            'teaching_module_name' => 'Interroger, traiter et assurer la '
                . 'maintenance des bases de données'
        ];
        $this->assertEquals($expect, $data);
    }

    public function testFindAll(): void
    {
        $gradeModel = model('GradeModel');
        $data = $gradeModel->findAll();
        $this->assertIsArray($data);
    }

    public function testFirst(): void
    {
        $gradeModel = model('GradeModel');
        $data = $gradeModel->first();
        $expect = [
            'id' => '1',
            'fk_user_course' => '1',
            'fk_teaching_subject' => '1',
            'date' => '2024-08-22',
            'grade' => '4.0',
            'is_school' => '1',
            'archive' => null,
            'teaching_subject_name' => 'Mathématiques',
        ];
        $this->assertEquals($expect, $data);
    }
    
    public function testFirstCustom(): void
    {
        $gradeModel = model('GradeModel');
        $data = $gradeModel->select('grade')->first();
        $expect = [
            'grade' => 4,
        ];
        $this->assertEquals($expect, $data);
    }

    public function testInsert(): void
    {
        $gradeModel = model('GradeModel');
        $grade = [
            'fk_user_course' => '1',
            'fk_teaching_subject' => '1',
            'fk_teaching_module' => '1',
            'date' => '2024-08-23',
            'grade' => '4.0',
            'is_school' => '1',
            'archive' => null,
            'teaching_subject_name' => 'Mathématiques',
            'teaching_module_name' => 'Interroger'
        ];
        $isSuccess = $gradeModel->insert($grade, false);
        $this->assertTrue($isSuccess);
    }

    public function testGetApprenticeSubjectGrades(): void
    {
        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeSubjectGrades(1, 1);
        $expect = [
            0 => [
                'fk_user_course' => '1',
                'fk_teaching_subject' => '1',
                'date' => '2024-08-22',
                'grade' => '4.0',
                'archive' => null,
                'name' => 'Mathématiques'
            ],
            1 => [
                'fk_user_course' => '1',
                'fk_teaching_subject' => '1',
                'date' => '2024-08-22',
                'grade' => '4.5',
                'archive' => null,
                'name' => 'Mathématiques'
            ]
        ];
        $this->assertEquals($expect, $data);
    }

    public function testGetApprenticeModulesGradesIsSchool(): void
    {
        $id_user_course = 1;
        $is_school = true;

        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeModulesGrades($id_user_course,
            $is_school);

        $expect = [
            0 => [
                'fk_user_course' => '1',
                'fk_teaching_module' => '1',
                'date' => '2024-08-23',
                'grade' => '4.5',
                'archive' => null,
                'official_name' => 'Interroger, traiter et assurer la'
                . ' maintenance des bases de données'
            ],
            1 => [
                'fk_user_course' => '1',
                'fk_teaching_module' => '2',
                'date' => '2024-08-23',
                'grade' => '5.0',
                'archive' => null,
                'official_name' => 'Mettre en œuvre des solutions ICT avec la'
                . ' technologie blockchain'
            ]
        ];
        $this->assertEquals($expect, $data);

    }

    public function testGetApprenticeModulesGradesIsNotSchool(): void
    {
        $id_user_course = 1;
        $is_school = false;

        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeModulesGrades($id_user_course,
            $is_school);

        $expect = [
            0 => [
                'fk_user_course' => '1',
                'fk_teaching_module' => '3',
                'date' => '2024-08-23',
                'grade' => '3.0',
                'archive' => null,
                'official_name' => 'Exploiter et surveiller des services dans'
                . ' le cloud public'
            ],
        ];
        $this->assertEquals($expect, $data);

    }

    public function testGetApprenticeModulesGradesIsSchoolNull(): void
    {
        $id_user_course = 1;
        $is_school = null;

        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeModulesGrades($id_user_course,
            $is_school);

        $expect = [
            0 => [
                'fk_user_course' => '1',
                'fk_teaching_module' => '1',
                'date' => '2024-08-23',
                'grade' => '4.5',
                'archive' => null,
                'official_name' => 'Interroger, traiter et assurer la'
                . ' maintenance des bases de données',
               'is_school' => '1'
            ],
            1 => [
                'fk_user_course' => '1',
                'fk_teaching_module' => '2',
                'date' => '2024-08-23',
                'grade' => '5.0',
                'archive' => null,
                'official_name' => 'Mettre en œuvre des solutions ICT avec la'
                    . ' technologie blockchain',
               'is_school' => '1'
            ],
            2 => [
                'fk_user_course' => '1',
                'fk_teaching_module' => '3',
                'date' => '2024-08-23',
                'grade' => '3.0',
                'archive' => null,
                'official_name' => 'Exploiter et surveiller des services dans'
                    . ' le cloud public',
                'is_school' => '0'
            ],
        ];
        $this->assertEquals($expect, $data);

    }

    public function testGetApprenticeModuleGrade(): void
    {
        $id_user_course = 1;
        $id_module = 1;
        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeModuleGrade($id_user_course,
            $id_module);
        $expect = [
            'fk_user_course' => "1",
            'fk_teaching_module' => "1",
            'date' => "2024-08-23",
            'grade' => "4.5",
            'is_school' => "1",
            'archive' => null,
            'official_name' => 'Interroger, traiter et assurer la maintenance'
                . ' des bases de données'
        ];
        $this->assertEquals($expect, $data);
    }

    public function testGetApprenticeSubjectAverage(): void
    {
        $id_user_course = 1;
        $id_subject = 1;
        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeSubjectAverage($id_user_course,
            $id_subject);
        $expect = 4.25;
        $this->assertEquals($expect, $data);
    }

    public function testGetApprenticeModuleAverageIsSchool(): void
    {
        $id_user_course = 1;
        $is_school = true;

        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeModuleAverage($id_user_course,
            $is_school);
        $expect = 4.75;
        $this->assertEquals($expect, $data);
    }

    public function testGetApprenticeModuleAverageIsNotSchool(): void
    {
        $id_user_course = 1;
        $is_school = false;

        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeModuleAverage($id_user_course,
            $is_school);
        $expect = 3;
        $this->assertEquals($expect, $data);
    }
}

