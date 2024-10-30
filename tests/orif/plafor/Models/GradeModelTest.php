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
    // old test to repair
    /**
     * Tests the retrieval of a single record by ID using the find method.
     * (school subject)
     */
    public function testFindExpectSubject(): void
    {
        $id = 1;
        $gradeModel = model('GradeModel');
        $data = $gradeModel->find($id);
        $this->assertTrue(is_array($data));
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
        $this->assertTrue(is_array($data));
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
        $this->assertTrue(is_array($data));
    }

     /**
     * Tests the retrieval of the first record using the first method.
     */   
    public function testFirstCustom(): void
    {
        $gradeModel = model('GradeModel');
        $data = $gradeModel->select('grade')->first();
        $expect = [
            'grade' => 5.5,
        ];
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the insertion of a new subject grade using the insert method.
     */
    public function testInsertSubject(): void
    {
        $gradeModel = model('GradeModel');
        $grade = [
            'fk_user_course' => '1',
            'fk_teaching_subject' => '1',
            'date' => '2024-08-23',
            'grade' => '4.0',
            'is_school' => '1',
            'archive' => null,
            'teaching_subject_name' => 'Mathématiques',
        ];
        $isSuccess = $gradeModel->insert($grade, false);
        $this->assertTrue($isSuccess);
    }

    /**
     * Tests the insertion of a new module grade using the insert method.
     */
    public function testInsertModule(): void
    {
        $gradeModel = model('GradeModel');
        $grade = [
            'fk_user_course' => '1',
            'fk_teaching_module' => '1',
            'date' => '2024-08-23',
            'grade' => '4.0',
            'is_school' => '1',
            'archive' => null,
            'teaching_module_name' => 'Interroger'
        ];
        $isSuccess = $gradeModel->insert($grade, false);
        $this->assertTrue($isSuccess);
    }

    /**
     * Tests the insertion of a new invalide subject and module grade using the
     * insert method.
     */
    public function testInsertSubjectAndModule(): void
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
        $this->assertFalse($isSuccess);
    }


    /**
     * Tests the insertion of a new grade with no subject and no module
     * using the insert method.
     */
    public function testInsertWithoutSubjectAndModule(): void
    {
        $gradeModel = model('GradeModel');
        $grade = [
            'fk_user_course' => '1',
            'date' => '2024-08-23',
            'grade' => '4.0',
            'is_school' => '1',
            'archive' => null,
        ];
        $isSuccess = $gradeModel->insert($grade, false);
        $this->assertFalse($isSuccess);
    }

    /**
     * Tests the retrieval of apprentice subject grades.
     * @covers GradeModel::getApprenticeSubjectGrades
     */
    public function testGetApprenticeSubjectGrades(): void
    {
        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeSubjectGrades(1, 1);
        $this->assertTrue(is_array($data));
    }

    /**
     * Tests the retrieval of apprentice module grades for school grades.
     *
     * @covers GradeModel::getApprenticeModulesGrades
     */
    public function testGetApprenticeModulesGradesIsSchool(): void
    {
        $id_user_course = 1;
        $is_school = true;

        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeModulesGrades($id_user_course,
            $is_school);

        $this->assertTrue(is_array($data));
    }

    /**
     * Tests the retrieval of apprentice module grades for non-school
     * (interentreprises) grades.
     *
     * @covers GradeModel::getApprenticeModulesGrades
     */
    public function testGetApprenticeModulesGradesIsNotSchool(): void
    {
        $id_user_course = 1;
        $is_school = false;

        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeModulesGrades($id_user_course,
            $is_school);

        $this->assertTrue(is_array($data));

    }

    /**
     * Tests the retrieval of apprentice module grades for all grades.
     *
     * @covers GradeModel::getApprenticeModulesGrades
     */
    public function testGetApprenticeModulesGradesIsSchoolNull(): void
    {
        $id_user_course = 1;
        $is_school = null;

        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeModulesGrades($id_user_course,
            $is_school);

        $this->assertTrue(is_array($data));
    }

    /**
     * Tests the retrieval of apprentice module grade.
     *
     * @covers GradeModel::getApprenticeModuleGrade
     */
    public function testGetApprenticeModuleGrade(): void
    {
        $id_user_course = 1;
        $id_module = 1;
        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeModuleGrade($id_user_course,
            $id_module);

        $this->assertTrue(is_array($data));
    }

    /**
     * Tests the retrieval of apprentice subject average.
     *
     * @covers GradeModel::getApprenticeSubjectAverage
     */
    public function testGetApprenticeSubjectAverage(): void
    {
        $id_user_course = 1;
        $id_subject = 1;
        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeSubjectAverage($id_user_course,
            $id_subject, fn($e) => $e);
        $expect = 5.5;
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests that the average grade is correctly rounded to the nearest half
     * point.
     *
     * @covers GradeModel::getApprenticeSubjectAverage
     * @covers GradeModel::roundHalfPoint
     */
    public function testGetApprenticeSubjectAverageRoundHalfPoint(): void
    {
        $id_user_course = 1;
        $id_subject = 1;
        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeSubjectAverage($id_user_course,
            $id_subject, [$gradeModel, 'roundHalfPoint']);
        $expect = 5.5;
        $this->assertEquals($expect, $data);
    }
    /**
     * Tests that the average grade is correctly rounded to one decimal point
     * by default.
     *
     * @covers GradeModel::getApprenticeSubjectAverage
     */
    public function testGetApprenticeSubjectAverageRoundOneDecimalPoint(): void
    {
        $id_user_course = 1;
        $id_subject = 1;
        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeSubjectAverage($id_user_course,
            $id_subject);
        $expect = 5.5;
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the retrieval of apprentice module average for school grades.
     *
     * @covers GradeModel::getApprenticeModuleAverage
     */
    public function testGetApprenticeModuleAverageIsSchool(): void
    {
        $id_user_course = 1;
        $is_school = true;

        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeModuleAverage($id_user_course,
            $is_school, fn($e) => $e);
        $expect = 4.0;
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests that the getApprenticeModuleAverage method returns the correct
     * average
     * when the school rounding rule is half point.
     *
     * @covers GradeModel::getApprenticeModuleAverage
     * @covers GradeModel::roundHalfPoint
     */
    public function
        testGetApprenticeModuleAverageIsSchoolRoundHalfPoint(): void
    {
        $id_user_course = 1;
        $is_school = true;

        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeModuleAverage($id_user_course,
            $is_school, [$gradeModel, 'roundHalfPoint']);
        $expect = 4.0;
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the retrieval of apprentice module average for non-school
     * (interentreprises) grades.
     *
     * @covers GradeModel::getApprenticeModuleAverage
     */
    // TODO
    public function _testGetApprenticeModuleAverageIsNotSchool(): void
    {
        $id_user_course = 1;
        $is_school = false;

        $gradeModel = model('GradeModel');
        $data = $gradeModel->getApprenticeModuleAverage($id_user_course,
            $is_school);
        $expect = 3;
        $this->assertEquals($expect, $data);
    }

    /**
     * Test that deleting a grade removes it from the active records.
     */
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

    /**
     * Test that finding all grades with deleted records includes the deleted
     * records.
     */
    public function testFindAllWithDeleted(): void
    {
        $idToDelete = 1;
        $gradeModel = model('GradeModel');
        $gradeModel->delete($idToDelete);
        $domains = $gradeModel->withDeleted()->findAll();
        $this->assertEquals($domains[0]['id'], $idToDelete);
    }

    /**
     * Test that finding all grades with only deleted records returns only the
     * deleted records.
     */
    public function testFindAllOnlyDeleted(): void
    {
        $idToDelete = 1;
        $gradeModel = model('GradeModel');
        $gradeModel->delete($idToDelete);
        $domains = $gradeModel->onlyDeleted()->findAll();
        $this->assertEquals($domains[0]['id'], $idToDelete);
        $this->assertFalse(isset($domains[1]));
    }

    /**
     * Test that finding all grades without deleted records excludes the
     * deleted records.
     */
    public function testFindAllWithoutDeleted(): void
    {
        $idToDelete = 1;
        $gradeModel = model('GradeModel');
        $gradeModel->delete($idToDelete);
        $domains = $gradeModel->findAll();
        $this->assertNotEquals($domains[0]['id'], $idToDelete);
        $this->assertTrue(isset($domains[1]));
    }

    /**
     * Test that finding all grades is equivalent to finding without an ID.
     */
    public function testFindAllEqualsFindWithoutId(): void
    {
        $gradeModel = model('GradeModel');
        $domains = $gradeModel->findAll();
        $domains2 = $gradeModel->find();
        $this->assertEquals($domains, $domains2);
    }



    public function testGetWeightedModuleAverage(): void
    {
        $idUserCourse = 1;

        $gradeModel = model('GradeModel');
        $result = $gradeModel->getWeightedModuleAverage($idUserCourse);

        $expectedAverage = 4.0;
        $this->assertEquals($expectedAverage, $result);
    }

    public function testGetApprenticeDomainAverageNotModule(): void
    {
        $gradeModel = model('GradeModel');
        $result = $gradeModel->getApprenticeDomainAverageNotModule(1, 1);
        $this->assertEquals(5.5, $result);
    }
    // end old test to repair

    public function testGetApprenticeAverage(): void
    {
        $gradeModel = model('GradeModel');
        $result = $gradeModel->getApprenticeAverage(101);
        $this->assertEquals(4.3, $result);
    }

    public function testGetSchoolReportData(): void
    {
        // Arrange
        $userCourseId = 101;

        $gradeModel = model('GradeModel');


        // Act
        $result = $gradeModel->getSchoolReportData($userCourseId);

        // Assert
        $this->assertTrue(is_array($result));
    }
    
    public function testGetModuleArrayForView(): void
    {

        $userCourseId = 101;

        // Appel de la méthode à tester
        $gradeModel = model('GradeModel');
        $result = $gradeModel->getModuleArrayForView($userCourseId);


        $this->assertIsArray($result);
        $this->assertArrayHasKey('school', $result);
        $this->assertArrayHasKey('non-school', $result);
        $this->assertArrayHasKey('weighting', $result);


        $this->assertArrayHasKey('modules', $result['school']);
        $this->assertArrayHasKey('weighting', $result['school']);
        $this->assertIsInt($result['school']['weighting']);


        $this->assertArrayHasKey('modules', $result['non-school']);
        $this->assertArrayHasKey('weighting', $result['non-school']);
        $this->assertIsInt($result['non-school']['weighting']);


        $this->assertIsInt($result['weighting']);

    }

    public function testGetApprenticeModulesGradesForView()
    {

        $userCourseId = 101;
        $isSchool = true;

        $gradeModel = model('GradeModel');

        $result = $gradeModel->getApprenticeModulesGradesForView($userCourseId,
            $isSchool);


        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        
        foreach ($result as $record) {
            $this->assertArrayHasKey('number', $record);
            $this->assertArrayHasKey('name', $record);
            $this->assertArrayHasKey('grade', $record);
            $this->assertArrayHasKey('id', $record['grade']);
            $this->assertArrayHasKey('value', $record['grade']);
        }

    }

    public function testGetGradeIdForDomain()
    {
        // Arrange
        $userCourseId = 101;
        $domainId = 8;
        $expectedGradeId = 13;

        // Mock le modèle TeachingSubjectModel
        $gradeModel = model('GradeModel');
        // Act
        $result = $gradeModel->getGradeIdForDomain($userCourseId, $domainId);

        // Assert
        $this->assertEquals($expectedGradeId, $result);
    }

    public function testGetTpiGradeForView()
    {
        // Arrange
        $userCourseId = 101;
        $expectedGrade = 4.5;
        $expectedGradeId = 13;
        $gradeModel = model('GradeModel');

        // Act
        $result = $gradeModel->getTpiGradeForView($userCourseId);

        // Assert
        $this->assertEquals([
            'id' => $expectedGradeId,
            'value' => $expectedGrade
        ], $result);
    }


    public function testGetApprenticeSubjectGradesForView()
    {
        // Arrange
        $userCourseId = 101;
        $subjectId = 1;

        $gradeModel = model('GradeModel');
        // Act
        $result = $gradeModel->getApprenticeSubjectGradesForView($userCourseId,
            $subjectId);

        // Assert
        $this->assertTrue(is_array($result));
    }

    public function testGetCbeGradeForView()
    {
        // Arrange
        $gradeModel = model('GradeModel');
        $userCourseId = 101;

        // Act
        $result = $gradeModel->getCbeGradeForView($userCourseId);

        // Assert
        $this->assertTrue(is_array($result));
    }

    public function testGetEcgGradeForView()
    {
        // Arrange
        $gradeModel = model('GradeModel');
        $userCourseId = 101;

        // Act
        $result = $gradeModel->getEcgGradeForView($userCourseId);

        // Assert
        $this->assertTrue(is_array($result));
    }

    public function testGetSchoolReportDataKeys()
    {
        // Arrange
        $userCourseId = 101;
        $gradeModel = model('GradeModel');

        // Act
        $result = $gradeModel->getSchoolReportData($userCourseId);

        // Assert
        $this->assertArrayHasKey('cfc_average', $result);
        $this->assertArrayHasKey('modules', $result);
        $this->assertArrayHasKey('tpi_grade', $result);
        $this->assertArrayHasKey('cbe', $result);
        $this->assertArrayHasKey('ecg', $result);
    }

}
