<?php

namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

// Temporary file for migrating GradeModelTest tests
// Tests need to be updated to use IDs greater than 100
// to avoid conflicts with data added by new seeds
class GradeModelTest2 extends CIUnitTestCase
{
    use DatabaseTestTrait;

    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    // For Seeds
    protected $seedOnce = false;
    protected $seed     = 'gradeModelTestSeed2';
    protected $basePath = 'tests/_support/Database';


    public function testGetApprenticeAverage(): void
    {
        $gradeModel = model('GradeModel');
        $result = $gradeModel->getApprenticeAverage(101);
        $this->assertEquals(4.3, $result);
    }

    // public function testGetSchoolReportData(): void
    // {
    //     // Arrange
    //     $userCourseId = 101;

    //     $gradeModel = model('GradeModel');

    //     $cfcAverage = 4.3;
    //     $modules = 4.4;
    //     $tpiGrade = 4.5;
    //     $cbeGrade = 4.4;
    //     $ecgGrade = 4.4;

    //     // Act
    //     $result = $gradeModel->getSchoolReportData($userCourseId);

    //     // Assert
    //     $this->assertEquals([
    //         "cfc_average" => $cfcAverage,
    //         "modules" => $modules,
    //         "tpi_grade" => $tpiGrade,
    //         "cbe" => $cbeGrade,
    //         "ecg" => $ecgGrade,
    //     ], $result);
    // }
    
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
        $expectedGradeId = 9;

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
        $expectedGradeId = 9;
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
        $expectedSubject = [
            'name' => 'Mathématiques',
            'weighting' => 0.0,
            'grades' => [
                ['id' => 11, 'value' => 4.5],
            ]
        ];

        $gradeModel = model('GradeModel');
        // Act
        $result = $gradeModel->getApprenticeSubjectGradesForView($userCourseId,
            $subjectId);

        // Assert
        $this->assertEquals($expectedSubject, $result);
    }

    public function testGetCbeGradeForView()
    {
        // Arrange
        $gradeModel = model('GradeModel');
        $userCourseId = 101;
        $expectedDomain = ['id' => 1];
        $expectedSubjectIds = [1, 2, 3];
        $expectedSubjectsWithGrades = [
            'subjects' => [
                [
                    'name' => 'Mathématiques',
                    'weighting' => 0,
                    'grades' => [
                        ['id' => 1, 'value' => 4.0],
                        ['id' => 2, 'value' => 5.0]
                    ]
                ],
                [
                    'name' => 'Anglais technique',
                    'weighting' => 0,
                    'grades' => [
                        ['id' => 3, 'value' => 3.5],
                        ['id' => 4, 'value' => 5.0],
                    ]
                ],
            ],
            'weighting' => 10
        ];

        // Act
        $result = $gradeModel->getCbeGradeForView($userCourseId);

        // Assert
        $this->assertEquals($expectedSubjectsWithGrades, $result);
    }

    public function testGetEcgGradeForView()
    {
        // Arrange
        $gradeModel = model('GradeModel');
        $userCourseId = 101;
        $expectedDomain = ['id' => 1];
        $expectedSubjectIds = [1, 2, 3];
        $expectedSubjectsWithGrades = [
            'subjects' => [
                [
                    'name' => 'ECG',
                    'weighting' => 0,
                    'grades' => [
                        ['id' => 5, 'value' => 2.5],
                        ['id' => 6, 'value' => 6.0]
                    ]
                ],
                [
                    'name' => 'Travail personnel d\'appronfondissement (TPA)',
                    'weighting' => 0,
                    'grades' => [
                        ['id' => 7, 'value' => 4.0],
                    ]
                ],
                [
                    'name' => 'Examen final',
                    'weighting' => 0,
                    'grades' => [
                        ['id' => 8, 'value' => 3.0],
                    ]
                ],
            ],
            'weighting' => '20'
        ];

        // Act
        $result = $gradeModel->getEcgGradeForView($userCourseId);

        // Assert
        $this->assertEquals($expectedSubjectsWithGrades, $result);
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
