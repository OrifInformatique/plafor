<?php


use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * Test case for the Grade_Helper class.
 */
class Grade_HelperTest extends CIUnitTestCase
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
     * Tests the getModules method.
     *
     * @covers getModules
     */
    public function testGetModules(): void
    {
        // Arrange
        helper('grade_helper');
        $userCourseId = 101;
        // Act
        $modules = getModules($userCourseId);
        // Assert
        $this->assertTrue(is_array($modules));
    }

    /**
     * Tests the getSubjectsAll method.
     *
     * @covers getSubjectsAll
     */
    public function testGetSubjectsAll(): void
    {
        // Arrange
        helper('grade_helper');
        $userCourseId = 101;
        // Act
        $subjects = getSubjectsAll($userCourseId);
        // Assert
        $this->assertTrue(is_array($subjects));
    }


    /**
     * Tests the getsubjectsAndModulesList method.
     *
     * @covers getsubjectsAndModulesList
     */
    public function testGetsubjectsAndModulesList(): void
    {
        // Arrange
        helper('grade_helper');
        $userCourseId = 101;
        // Act
        $list = getsubjectsAndModulesList($userCourseId);
        // Assert
        $this->assertTrue(is_array($list));
    }

    /**
     * Tests the getApprentice method.
     *
     * @covers getApprentice
     */
    public function testGetApprentice(): void
    {
        // Arrange
        helper('grade_helper');
        $userCourseId = 101;
        // Act
        $list = getApprentice($userCourseId);
        // Assert
        $this->assertTrue(is_array($list));
    }

    /**
     * Tests the getSelectedEntry method.
     *
     * @covers getSelectedEntry
     */
    public function testgetSelectedEntry(): void
    {
        // Arrange
        helper('grade_helper');
        $userCourseId = 101;
        $selectedDomain = 'tpi';
        // Act
        $formatedId = getSelectedEntry($userCourseId, $selectedDomain);
        // Assert
        $this->assertTrue(is_string($formatedId));
    }

    /**
     * Tests the getSelectedEntryForModules method.
     *
     * @covers getSelectedEntryForModules
     */
    public function testGetSelectedEntryForModules(): void
    {
        // Arrange
        helper('grade_helper');
        $userCourseId = 101;
        // Act
        $formatedId = getSelectedEntryForModules($userCourseId);
        // Assert
        $this->assertTrue(is_string($formatedId));
    }

    /**
     * Tests the isGradeInCourse method.
     *
     * @covers isGradeInCourse
     */
    public function testIsGradeInCourse(): void
    {
        // Arrange
        helper('grade_helper');
        $userCourseId = 101;
        $gradeId = 5;
        // Act
        $isGradeInCourse = isGradeInCourse($userCourseId, $gradeId);
        // Assert
        $this->assertTrue(is_bool($isGradeInCourse));
    }

    /**
     * Tests the getSubjectsAndModulesListAll method.
     *
     * @covers getSubjectsAndModulesListAll
     */
    public function testGetSubjectsAndModulesListAll():void
    {
        // Arrange
        helper('grade_helper');
        $userCourseId = 101;
        // Act
        $list = getSubjectsAndModulesListAll($userCourseId);
        // Assert
        $this->assertTrue(is_array($list));
    }

    /**
     * Tests the getSubjects method.
     *
     * @covers getSubjects
     */
    public function testGetSubjects():void
    {
        // Arrange
        helper('grade_helper');
        $subjectIds[0] = 1;
        $subjectIds[2] = 2;
        // Act
        $list = getSubjects($subjectIds);
        // Assert
        $this->assertTrue(is_array($list));
    }

    /**
     * Tests the getSelectedEntryForSubject method.
     *
     * @covers getSelectedEntryForSubject
     */
    public function testGetSelectedEntryForSubject(): void
    {
        // Arrange
        helper('grade_helper');
        $userCourseId = 101;
        $selectedDomain = 'tpi';
        // Act
        $formatedId = getSelectedEntryForSubject($userCourseId,
            $selectedDomain);
        // Assert
        $this->assertTrue(is_string($formatedId));
    }

    /**
     * Tests the getCoursePlanName method.
     *
     * @covers getCoursePlanName
     */
    public function testGetCoursePlanName(): void
    {
        // Arrange
        helper('grade_helper');
        $userCourseId = 101;
        // Act
        $name = getCoursePlanName($userCourseId);
        // Assert
        $this->assertTrue(is_string($name));
    }

    /**
     * Tests the hasGrade method.
     *
     * @covers hasGrade
     */
    public function testHasGrade(): void
    {
        // Arrange
        helper('grade_helper');
        $userCourseId = 101;
        $moduleId = 1;
        // Act
        $hasGrade = hasGrade($userCourseId, $moduleId);
        // Assert
        $this->assertTrue(is_bool($hasGrade));
    }

    /**
     * Tests the addSubject method.
     *
     * @covers addSubject
     */
    public function testAddSubject(): void
    {
        // Arrange
        helper('grade_helper');
        // Act
        $list = addSubject(1, []);
        // Assert
        $this->assertTrue(is_array($list));
    }

    /**
     * Tests the addModule method.
     *
     * @covers addModule
     */
    public function testAddModule(): void
    {
        // Arrange
        helper('grade_helper');
        // Act
        $list = addModule(1, []);
        // Assert
        $this->assertTrue(is_array($list));
    }

    /**
     * Tests the addHimself method.
     *
     * @covers addHimself
     */
    public function testAddHimself(): void
    {
        // Arrange
        helper('grade_helper');
        // Act
        $list = addHimself(0, []);
        $list = addHimself(1, []);
        $list = addHimself(2, []);
        // Assert
        $this->assertTrue(is_array($list));
    }

    /**
     * Tests the mround method.
     *
     * @covers mround
     */
    public function testMround(): void
    {
        // arrange
        helper('grade_helper');
        $number = 3.25;
        $expectedNumber = 3.3;
        // act
        $roundedNumber = mround($number, 0.1);
        // assert
        $this->assertEquals($expectedNumber, $roundedNumber);
    }

    /**
     * Tests the getRoundFunction method.
     *
     * @covers getRoundFunction
     */
    public function testGetRoundFunction(): void
    {
        // Arrange
        helper('grade_helper');
        $subjectModel = model('TeachingSubjectModel');
        // Act
        $function = getRoundFunction($subjectModel, 1);
        // Assert
        $this->assertTrue(is_callable($function));
    }

    /**
     * Tests the getSubjectRoundFunction method.
     *
     * @covers getSubjectRoundFunction
     */
    public function testGetSubjectRoundFunction(): void
    {
        // Arrange
        helper('grade_helper');
        // Act
        $function = getSubjectRoundFunction(1);
        // Assert
        $this->assertTrue(is_callable($function));
    }

    /**
     * Tests the getDomainRoundFunction method.
     *
     * @covers getDomainRoundFunction
     */
    public function testGetDomainRoundFunction(): void
    {
        // Arrange
        helper('grade_helper');
        // Act
        $function = getDomainRoundFunction(1);
        // Assert
        $this->assertTrue(is_callable($function));
    }

    public function testGetModulesInArrayKey(): void
    {
        // Arrange
        helper('grade_helper');
        $userCourseId = 101;
        // Act
        $list = getModulesInArrayKey($userCourseId);
        // Assert
        $this->assertTrue(is_array($list));
        $this->assertTrue(is_array($list['Modules']));
    }

    public function testGetSubjectsByDomainId():void
    {
        // Arrange
        helper('grade_helper');
        helper("UnitTest_helper");
        $domainId = DEV_ECG_DOMAIN_ID;
        $userCourseId = USER_COURSE_DEV_ID;
        // Act
        $list = getSubjectsByDomainId($domainId, $userCourseId);
        // Assert
        $this->assertTrue(is_array($list));
    }

    public function testGetSubjectsOrModulesListByGradeIdWithCBEGrade(): void
    {
        // Arrange
        helper('grade_helper');
        $gradeId = 1;
        // Act
        $list = getSubjectsOrModulesListByGradeId($gradeId);
        // Assert
        $this->assertTrue(is_array($list));
    }

    public function testGetSubjectsOrModulesListByGradeIdWithModuleGrade(): void
    {
        // Arrange
        helper('grade_helper');
        $gradeId = 2;
        // Act
        $list = getSubjectsOrModulesListByGradeId($gradeId);
        // Assert
        $this->assertTrue(is_array($list));
    }

}
