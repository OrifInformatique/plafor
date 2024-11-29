<?php

namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

// The helper hold all Constants
// -> Plafor\orif\plafor\Helpers\UnitTest_helper.php
helper("UnitTest_helper");

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
    protected $seed     = 'GradeModelTestSeed';
    protected $basePath = 'tests/_support/Database';


    /**
     * Verifies the creation of a GradeModel instance.
     */
    public function testGradeModelInstance(): void
    {
        // Act
        $gradeModel = model('GradeModel');
        // Assert
        $this->assertTrue($gradeModel instanceof GradeModel);
        $this->assertInstanceOf(GradeModel::class, $gradeModel);
    }

    /**
     * Tests the retrieval of a single record by ID using the find method.
     * (school subject)
     */
    public function testFindExpectSubject(): void
    {
        // Arrange
        $id = 1;
        $gradeModel = model('GradeModel');
        // Act
        $data = $gradeModel->find($id);
        // Assert
        $this->assertTrue(is_array($data));
    }

    /**
     * Tests the retrieval of a single record by ID using the find method.
     * (module)
     */
    public function testFindExpectModule(): void
    {
        // Arrange
        $id = 2;
        $gradeModel = model('GradeModel');
        // Act
        $data = $gradeModel->find($id);
        // Assert
        $this->assertTrue(is_array($data));
    }

    /**
     * Tests the retrieval of all records using the findAll method.
     */
    public function testFindAll(): void
    {
        // Arrange
        $gradeModel = model('GradeModel');
        // Act
        $data = $gradeModel->findAll();
        // Assert
        $this->assertIsArray($data);
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(2, $data[1]['id']);
    }

    /**
     * Tests the retrieval of the first record using the first method.
     */
    public function testFirst(): void
    {
        // Arrange
        $gradeModel = model('GradeModel');
        // Act
        $data = $gradeModel->first();
        // Assert
        $this->assertTrue(is_array($data));
    }

    /**
     * Tests the retrieval of the first record using the first method.
     */
    public function testFirstCustom(): void
    {
        // Arrange
        $gradeModel = model('GradeModel');
        // Act
        $data = $gradeModel->select('grade')->first();
        $expect = [
            'grade' => 5.5,
        ];
        // Assert
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the insertion of a new subject grade using the insert method.
     */
    public function testInsertSubject(): void
    {
        // Arrange
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
        // Act
        $isSuccess = $gradeModel->insert($grade, false);
        // Assert
        $this->assertTrue($isSuccess);
    }

    /**
     * Tests the insertion of a new module grade using the insert method.
     */
    public function testInsertModule(): void
    {
        // Arrange
        $gradeModel = model('GradeModel');
        // Act
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
        // Assert
        $this->assertTrue($isSuccess);
    }

    /**
     * Tests the insertion of a new invalide subject and module grade using the
     * insert method.
     */
    public function testInsertSubjectAndModule(): void
    {
        // Arrange
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
        // Act
        $isSuccess = $gradeModel->insert($grade, false);
        // Assert
        $this->assertFalse($isSuccess);
    }


    /**
     * Tests the insertion of a new grade with no subject and no module
     * using the insert method.
     */
    public function testInsertWithoutSubjectAndModule(): void
    {
        // Arrange
        $gradeModel = model('GradeModel');
        $grade = [
            'fk_user_course' => '1',
            'date' => '2024-08-23',
            'grade' => '4.0',
            'is_school' => '1',
            'archive' => null,
        ];
        // Act
        $isSuccess = $gradeModel->insert($grade, false);
        // Assert
        $this->assertFalse($isSuccess);
    }

    /**
     * Tests the retrieval of apprentice subject grades.
     * @covers GradeModel::getApprenticeSubjectGrades
     */
    public function testGetApprenticeSubjectGrades(): void
    {
        // Arrange
        $gradeModel = model('GradeModel');
        // Act
        $data = $gradeModel->getApprenticeSubjectGrades(1, 1);
        // Assert
        $this->assertTrue(is_array($data));
    }

    /**
     * Tests the retrieval of apprentice module grades for school grades.
     *
     * @covers GradeModel::getApprenticeModulesGrades
     */
    public function testGetApprenticeModulesGradesIsSchool(): void
    {
        // Arrange
        $id_user_course = 1;
        $is_school = true;
        $gradeModel = model('GradeModel');
        // Act
        $data = $gradeModel->getApprenticeModulesGrades($id_user_course,
            $is_school);

        // Assert
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
        // Arrange
        $id_user_course = 1;
        $is_school = false;
        $gradeModel = model('GradeModel');
        // Act
        $data = $gradeModel->getApprenticeModulesGrades($id_user_course,
            $is_school);

        // Assert
        $this->assertTrue(is_array($data));

    }

    /**
     * Tests the retrieval of apprentice module grades for all grades.
     *
     * @covers GradeModel::getApprenticeModulesGrades
     */
    public function testGetApprenticeModulesGradesIsSchoolNull(): void
    {
        // Arrange
        $id_user_course = 1;
        $is_school = null;
        $gradeModel = model('GradeModel');
        // Act
        $data = $gradeModel->getApprenticeModulesGrades($id_user_course,
            $is_school);

        // Assert
        $this->assertTrue(is_array($data));
    }

    /**
     * Tests the retrieval of apprentice module grade.
     *
     * @covers GradeModel::getApprenticeModuleGrade
     */
    public function testGetApprenticeModuleGrade(): void
    {
        // Arrange
        $id_user_course = 1;
        $id_module = 1;
        $gradeModel = model('GradeModel');
        // Act
        $data = $gradeModel->getApprenticeModuleGrade($id_user_course,
            $id_module);

        // Assert
        $this->assertTrue(is_array($data));
    }

    /**
     * Tests the retrieval of apprentice subject average.
     *
     * @covers GradeModel::getApprenticeSubjectAverage
     */
    public function testGetApprenticeSubjectAverage(): void
    {
        // Arrange
        $id_user_course = 1;
        $id_subject = 1;
        $gradeModel = model('GradeModel');
        $expect = 5.5;
        // Act
        $data = $gradeModel->getApprenticeSubjectAverage($id_user_course,
            $id_subject, fn($e) => $e);

        // Assert
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
        // Arrange
        $id_user_course = 1;
        $id_subject = 1;
        $gradeModel = model('GradeModel');
        $expect = 5.5;
        // Act
        $data = $gradeModel->getApprenticeSubjectAverage($id_user_course,
            $id_subject, [$gradeModel, 'roundHalfPoint']);

        // Assert
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
        // Arrange
        $id_user_course = 1;
        $id_subject = 1;
        $gradeModel = model('GradeModel');
        $expect = 5.5;
        // Act
        $data = $gradeModel->getApprenticeSubjectAverage($id_user_course,
            $id_subject);
        // Assert
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the retrieval of apprentice module average for school grades.
     *
     * @covers GradeModel::getApprenticeModuleAverage
     */
    public function testGetApprenticeModuleAverageIsSchool(): void
    {
        // Arrange
        $id_user_course = 1;
        $is_school = true;
        $expect = 4.0;
        $gradeModel = model('GradeModel');
        // Act
        $data = $gradeModel->getApprenticeModuleAverage($id_user_course,
            $is_school, fn($e) => $e);

        // Assert
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
        // Arrange
        $id_user_course = 1;
        $is_school = true;
        $expect = 4.0;
        $gradeModel = model('GradeModel');
        // Act
        $data = $gradeModel->getApprenticeModuleAverage($id_user_course,
            $is_school, [$gradeModel, 'roundHalfPoint']);

        // Assert
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the retrieval of apprentice module average for non-school
     * (interentreprises) grades.
     *
     * @covers GradeModel::getApprenticeModuleAverage
     */
    public function testGetApprenticeModuleAverageIsNotSchool(): void
    {
        // Arrange
        $id_user_course = USER_COURSE_DEV_ID;
        $is_school = false;
        $expect = 3;
        $gradeModel = model('GradeModel');
        // Act
        $data = $gradeModel->getApprenticeModuleAverage($id_user_course,
            $is_school);

        // Assert
        $this->assertEquals($expect, $data);
    }

    /**
     * Test that deleting a grade removes it from the active records.
     */
    public function testDelete(): void
    {
        // Arrange
        $id = 1;
        $gradeModel = model('GradeModel');
        // Act
        $gradeModel->delete($id);
        $grade = $gradeModel->find($id);
        $deletedGrade = $gradeModel->withDeleted()->find($id);
        // Assert
        $this->assertNull($grade);
        $this->assertEquals($id, $deletedGrade['id']);
    }

    /**
     * Test that finding all grades with deleted records includes the deleted
     * records.
     */
    public function testFindAllWithDeleted(): void
    {
        // Arrange
        $idToDelete = 1;
        $gradeModel = model('GradeModel');
        // Act
        $gradeModel->delete($idToDelete);
        $domains = $gradeModel->withDeleted()->findAll();
        // Assert
        $this->assertEquals($domains[0]['id'], $idToDelete);
    }

    /**
     * Test that finding all grades with only deleted records returns only the
     * deleted records.
     */
    public function testFindAllOnlyDeleted(): void
    {
        // Arrange
        $idToDelete = 1;
        $gradeModel = model('GradeModel');
        // Act
        $gradeModel->delete($idToDelete);
        $domains = $gradeModel->onlyDeleted()->findAll();
        // Assert
        $this->assertEquals($domains[0]['id'], $idToDelete);
        $this->assertFalse(isset($domains[1]));
    }

    /**
     * Test that finding all grades without deleted records excludes the
     * deleted records.
     */
    public function testFindAllWithoutDeleted(): void
    {
        // Arrange
        $idToDelete = 1;
        $gradeModel = model('GradeModel');
        // Act
        $gradeModel->delete($idToDelete);
        $domains = $gradeModel->findAll();
        // Assert
        $this->assertNotEquals($domains[0]['id'], $idToDelete);
        $this->assertTrue(isset($domains[1]));
    }

    /**
     * Test that finding all grades is equivalent to finding without an ID.
     */
    public function testFindAllEqualsFindWithoutId(): void
    {
        // Arrange
        $gradeModel = model('GradeModel');
        // Act
        $domains = $gradeModel->findAll();
        $domains2 = $gradeModel->find();
        // Assert
        $this->assertEquals($domains, $domains2);
    }



    /**
     * Tests the getWeightedModuleAverage method to retrieve the weighted
     * average of modules.
     *
     * @covers \Plafor\Models\GradeModel::getWeightedModuleAverage
     */
    public function testGetWeightedModuleAverage(): void
    {
        // Arrange
        $idUserCourse = 1;
        $expectedAverage = 4.0;
        $gradeModel = model('GradeModel');
        // Act
        $result = $gradeModel->getWeightedModuleAverage($idUserCourse);
        // Assert
        $this->assertEquals($expectedAverage, $result);
    }

    /**
     * Tests the getApprenticeDomainAverageNotModule method to retrieve the
     * average of a domain for an apprentice, excluding modules.
     *
     * @covers \Plafor\Models\GradeModel::getApprenticeDomainAverageNotModule
     */
    public function testGetApprenticeDomainAverageNotModule(): void
    {
        // Arrange
        $gradeModel = model('GradeModel');
        // Act
        $result = $gradeModel->getApprenticeDomainAverageNotModule(1, 1);
        // Assert
        $this->assertEquals(5.5, $result);
    }

    /**
     * Tests the getApprenticeAverage method to retrieve the average of an
     * apprentice.
     *
     * @covers \Plafor\Models\GradeModel::getApprenticeAverage
     */
    public function testGetApprenticeAverage(): void
    {
        // Arrange
        $gradeModel = model('GradeModel');
        // Act
        $result = $gradeModel->getApprenticeAverage(USER_COURSE_DEV_ID);
        // Assert
        $this->assertEquals(4.4, $result);
    }

    /**
     * Tests the getSchoolReportData method to retrieve the school report data.
     *
     * @covers \Plafor\Models\GradeModel::getSchoolReportData
     */
    public function testGetSchoolReportData(): void
    {
        // Arrange
        $userCourseId = USER_COURSE_DEV_ID;
        $gradeModel = model('GradeModel');
        // Act
        $result = $gradeModel->getSchoolReportData($userCourseId);
        // Assert
        $this->assertTrue(is_array($result));
    }

    /**
     * Tests the getModuleArrayForView method to retrieve the modules as an
     * array for the view.
     *
     * @covers \Plafor\Models\GradeModel::getModuleArrayForView
     */
    public function testGetModuleArrayForView(): void
    {
        // Arrange
        $userCourseId = USER_COURSE_DEV_ID;
        // Act
        $gradeModel = model('GradeModel');
        $result = $gradeModel->getModuleArrayForView($userCourseId);
        // Assert
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

    /**
     * Tests the getApprenticeModulesGradesForView method to retrieve the
     * grades of an apprentice's modules for the view.
     *
     * @covers \Plafor\Models\GradeModel::getApprenticeModulesGradesForView
     */
    public function testGetApprenticeModulesGradesForView()
    {
        // Arrange
        $userCourseId = USER_COURSE_DEV_ID;
        $isSchool = true;
        $gradeModel = model('GradeModel');
        // Act
        $result = $gradeModel->getApprenticeModulesGradesForView($userCourseId,
            $isSchool);

        // Assert
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

    /**
     * Tests the getGradeIdForDomain method to retrieve the grade ID for a
     * domain.
     *
     * @covers \Plafor\Models\GradeModel::getGradeIdForDomain
     */
    public function testGetGradeIdForDomain()
    {
        // Arrange
        $userCourseId = USER_COURSE_DEV_ID;
        $domainId = 8;
        $expectedGradeId = 13;
        $gradeModel = model('GradeModel');
        // Act
        $result = $gradeModel->getGradeIdForDomain($userCourseId, $domainId);
        // Assert
        $this->assertEquals($expectedGradeId, $result);
    }

    /**
     * Tests the getTpiGradeForView method to retrieve the TPI grade for the
     * view.
     *
     * @covers \Plafor\Models\GradeModel::getTpiGradeForView
     */
    public function testGetTpiGradeForView()
    {
        // Arrange
        $userCourseId = USER_COURSE_DEV_ID;
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


    /**
     * Tests the getApprenticeSubjectGradesForView method to retrieve the
     * grades of an apprentice's subjects for the view.
     *
     * @covers \Plafor\Models\GradeModel::getApprenticeSubjectGradesForView
     */
    public function testGetApprenticeSubjectGradesForView()
    {
        // Arrange
        $userCourseId = USER_COURSE_DEV_ID;
        $subjectId = 1;
        $gradeModel = model('GradeModel');
        // Act
        $result = $gradeModel->getApprenticeSubjectGradesForView($userCourseId,
            $subjectId);

        // Assert
        $this->assertTrue(is_array($result));
    }

    /**
     * Tests the getCbeGradeForView method to retrieve the CBE grade for the
     * view.
     *
     * @covers \Plafor\Models\GradeModel::getCbeGradeForView
     */
    public function testGetCbeGradeForView()
    {
        // Arrange
        $gradeModel = model('GradeModel');
        $userCourseId = USER_COURSE_DEV_ID;

        // Act
        $result = $gradeModel->getCbeGradeForView($userCourseId);

        // Assert
        $this->assertTrue(is_array($result));
    }

    /**
     * Tests the getEcgGradeForView method to retrieve the ECG grade for the
     * view.
     *
     * @covers \Plafor\Models\GradeModel::getEcgGradeForView
     */
    public function testGetEcgGradeForView()
    {
        // Arrange
        $gradeModel = model('GradeModel');
        $userCourseId = USER_COURSE_DEV_ID;
        // Act
        $result = $gradeModel->getEcgGradeForView($userCourseId);
        // Assert
        $this->assertTrue(is_array($result));
    }

    /**
     * Tests the getSchoolReportData method to retrieve the school report data.
     *
     * @covers \Plafor\Models\GradeModel::getSchoolReportData
     */
    public function testGetSchoolReportDataKeys()
    {
        // Arrange
        $userCourseId = USER_COURSE_DEV_ID;
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
