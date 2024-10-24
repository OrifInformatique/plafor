<?php


use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

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
    protected $seed     = 'gradeModelTestSeed2';
    protected $basePath = 'tests/_support/Database';

    
    public function testGetModules(): void
    {
        helper('grade_helper');
        $userCourseId = 101;
        $modules = getModules($userCourseId);
        $this->assertTrue(is_array($modules));
    }

    public function testGetSubjectsAll(): void
    {
        helper('grade_helper');
        $userCourseId = 101;
        $subjects = getSubjectsAll($userCourseId);
        $this->assertTrue(is_array($subjects));
    }


    public function testGetsubjectsAndModulesList(): void
    {
        helper('grade_helper');
        $userCourseId = 101;
        $list = getsubjectsAndModulesList($userCourseId);
        $this->assertTrue(is_array($list));
    }

    public function testGetApprentice(): void
    {
        helper('grade_helper');
        $userCourseId = 101;
        $list = getApprentice($userCourseId);
        $this->assertTrue(is_array($list));
    }

    public function testgetSelectedEntry(): void
    {
        helper('grade_helper');
        $userCourseId = 101;
        $selectedDomain = 'tpi';
        $formatedId = getSelectedEntry($userCourseId, $selectedDomain);
        $this->assertTrue(is_string($formatedId));
    }

    public function testGetSelectedEntryForModules(): void
    {
        helper('grade_helper');
        $userCourseId = 101;
        $formatedId = getSelectedEntryForModules($userCourseId);
        $this->assertTrue(is_string($formatedId));
    }

    public function testIsGradeInCourse(): void
    {
        helper('grade_helper');
        $userCourseId = 101;
        $gradeId = 5;
        $isGradeInCourse = isGradeInCourse($userCourseId, $gradeId);
        $this->assertTrue(is_bool($isGradeInCourse));
    }

    public function testGetSubjectsAndModulesListAll():void
    {
        helper('grade_helper');
        $userCourseId = 101;
        $list = getSubjectsAndModulesListAll($userCourseId);
        $this->assertTrue(is_array($list));
    }

    public function testGetSubjects():void
    {
        helper('grade_helper');
        $subjectIds[0] = 1;
        $subjectIds[2] = 2;
        $list = getSubjects($subjectIds);
        $this->assertTrue(is_array($list));
    }

    public function testGetSelectedEntryForSubject(): void
    {
        helper('grade_helper');
        $userCourseId = 101;
        $selectedDomain = 'tpi';
        $formatedId = getSelectedEntryForSubject($userCourseId,
            $selectedDomain);
        $this->assertTrue(is_string($formatedId));
    }

}
