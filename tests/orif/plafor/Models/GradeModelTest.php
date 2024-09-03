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

    /**
     * Verifies the creation of a GradeModel instance.
     */
    public function testGradeModelInstance(): void
    {
        $gradeModel = model('GradeModel');
        $this->assertTrue($gradeModel instanceof GradeModel);
        $this->assertInstanceOf(GradeModel::class, $gradeModel);
    }

    /**
     * Tests the retrieval of a single record by ID using the find method.
     * (school subject)
     */
    public function testFindExpectSubject(): void
    {
        $id = 1;
        $gradeModel = model('GradeModel');
        $data = $gradeModel->find($id);
        $expect = [
            'id' => $id,
            'fk_user_course' => '1',
            'fk_teaching_subject' => '1',
            'date' => '2024-08-22',
            'grade' => '4.0',
            'is_school' => '1',
            'archive' => null,
            'teaching_subject_name' => 'Mathématiques',
            'user_id' => 6,
        ];
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the retrieval of a single record by ID using the find method.
     * (module)
     */
    public function testFindExpectModule(): void
    {
        $id = 2;
        $gradeModel = model('GradeModel');
        $data = $gradeModel->find($id);
        $expect = [
            'id' => $id,
            'fk_user_course' => '1',
            'date' => '2024-08-23',
            'grade' => '4.5',
            'is_school' => '1',
            'archive' => null,
            'fk_teaching_module' => '1',
            'teaching_module_name' => 'Interroger, traiter et assurer la '
                . 'maintenance des bases de données',
            'user_id' => 6,
        ];
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the retrieval of all records using the findAll method.
     */
    public function testFindAll(): void
    {
        $gradeModel = model('GradeModel');
        $data = $gradeModel->findAll();
        $this->assertIsArray($data);
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(2, $data[1]['id']);
    }

    /**
     * Tests the retrieval of the first record using the first method.
     */
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
            'user_id' => 6,
        ];
        $this->assertEquals($expect, $data);
    }

     /**
     * Tests the retrieval of the first record using the first method.
     */   
    public function testFirstCustom(): void
    {
        $gradeModel = model('GradeModel');
        $data = $gradeModel->select('grade')->first();
        $expect = [
            'grade' => 4,
        ];
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the insertion of a new record using the insert method.
     */
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

    /**
     * Tests the retrieval of apprentice subject grades.
     */
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

    /**
     * Tests the retrieval of apprentice module grades for school grades.
     */
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

    /**
     * Tests the retrieval of apprentice module grades for non-school
     * (interentreprises) grades.
     */
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

    /**
     * Tests the retrieval of apprentice module grades for all grades.
     */
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

    /**
     * Tests the retrieval of apprentice module grade.
     */
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

    /**
     * Tests the retrieval of apprentice subject average.
     */
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

    public function testGetApprenticeSubjectAverageRoundHalfPoint(): void
    {
        $id_user_course = 1;
        $id_subject = 1;
        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeSubjectAverage($id_user_course,
            $id_subject, [$gradeModel, 'roundHalfPoint']);
        $expect = 4.5;
        $this->assertEquals($expect, $data);
    }

    public function testGetApprenticeSubjectAverageRoundOneDecimalPoint(): void
    {
        $id_user_course = 1;
        $id_subject = 1;
        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeSubjectAverage($id_user_course,
            $id_subject, [$gradeModel, 'roundOneDecimalPoint']);
        $expect = 4.3;
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the retrieval of apprentice module average for school grades.
     */
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

    public function
        testGetApprenticeModuleAverageIsSchoolRoundHalfPoint(): void
    {
        $id_user_course = 1;
        $is_school = true;

        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeModuleAverage($id_user_course,
            $is_school, [$gradeModel, 'roundHalfPoint']);
        $expect = 5;
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the retrieval of apprentice module average for non-school
     * (interentreprises) grades.
     */
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

    public function testDelete(): void
    {
        $id = 1;
        $gradeModel = model('GradeModel');
        $gradeModel->delete($id);
        $grade = $gradeModel->find($id);
        $deletedGrade = $gradeModel->withDeleted()->find($id);
        $this->assertNull($grade);
        $this->assertEquals($id, $deletedGrade['id']);
    }

    public function testFindAllWithDeleted(): void
    {
        $idToDelete = 1;
        $gradeModel = model('GradeModel');
        $gradeModel->delete($idToDelete);
        $domains = $gradeModel->withDeleted()->findAll();
        $this->assertEquals($domains[0]['id'], $idToDelete);
    }

    public function testFindAllOnlyDeleted(): void
    {
        $idToDelete = 1;
        $gradeModel = model('GradeModel');
        $gradeModel->delete($idToDelete);
        $domains = $gradeModel->onlyDeleted()->findAll();
        $this->assertEquals($domains[0]['id'], $idToDelete);
        $this->assertFalse(isset($domains[1]));
    }
    public function testFindAllWithoutDeleted(): void
    {
        $idToDelete = 1;
        $gradeModel = model('GradeModel');
        $gradeModel->delete($idToDelete);
        $domains = $gradeModel->findAll();
        $this->assertNotEquals($domains[0]['id'], $idToDelete);
        $this->assertTrue(isset($domains[1]));
    }
    public function testFindAllEqualsFindWithoutId(): void
    {
        $gradeModel = model('GradeModel');
        $domains = $gradeModel->findAll();
        $domains2 = $gradeModel->find();
        $this->assertEquals($domains, $domains2);
    }

}

