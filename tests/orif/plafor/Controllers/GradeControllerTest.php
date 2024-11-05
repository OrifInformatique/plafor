<?php
/**
 * Unit tests GradeControllerTest
 *
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

 namespace Plafor\Controllers;

 use CodeIgniter\Test\CIUnitTestCase;
 # use CodeIgniter\Test\ControllerTestTrait;
 use CodeIgniter\Test\DatabaseTestTrait;

use CodeIgniter\Test\FeatureTestTrait;

 use Plafor\Models;

class GradeControllerTest extends CIUnitTestCase
{
    # use ControllerTestTrait;
    use DatabaseTestTrait;

    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    protected $seedOnce = false;
    protected $basePath = 'tests/_support/Database';
    protected $seed     = 'GradeControllerSeed';

    // insert tests

    public function testSaveGradeWithAdminAccount(): void
    {
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;

        // fake user
        $session['user_id'] = 999;
        $result = $this->withSession($session)->get('plafor/grade/save/1');
        $result->assertSee(lang('Grades.add_grade'));
    }

    public function testSaveGradeWithApprenticeAccount(): void
    {
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_level_apprentice;
        // fake user
        $session['user_id'] = 999;
        $result = $this->withSession($session)->get('plafor/grade/save/1');
        $result->assertSee(lang('user_lang.code_error_403'));
    }

    public function testSaveGradeWithTrainerAccount(): void
    {
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        // fake user
        $session['user_id'] = 999;
        $result = $this->withSession($session)->get('plafor/grade/save/1');
        $result->assertSee(lang('user_lang.code_error_403'));
    }

    public function testSaveGradeWithTrainerAccountLinked(): void
    {
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        // fake user
        $session['user_id'] = TRAINER_DEV_ID;
        $result = $this->withSession($session)->get('plafor/grade/save/'
            . USER_COUSE_DEV_ID);
        $result->assertSee(lang('Grades.add_grade'));
    }

    public function testSaveGradeWithoutAccount(): void
    {
        try {
            $result = $this->get('plafor/grade/save/' . USER_COUSE_DEV_ID);
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    // update tests

    public function testSaveGradeWithAdminAccountUpdate(): void
    {
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;

        // fake user
        $session['user_id'] = 999;
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        $userCourseId = USER_COUSE_DEV_ID;

        $result = $this->withSession($session)->get('plafor/grade/save/' .
            $userCourseId . '/' . $gradeId);
        $result->assertSee(lang('Grades.update_grade'));
    }

    public function testSaveGradeWithApprenticeAccountUpdate(): void
    {
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_level_apprentice;

        // fake user
        $session['user_id'] = 999;
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        $userCourseId = USER_COUSE_DEV_ID;

        $result = $this->withSession($session)->get('plafor/grade/save/' .
            $userCourseId . '/' . $gradeId);
        $result->assertSee(lang('user_lang.code_error_403'));
    }

    public function testSaveGradeWithApprenticeAccountHisGradeUpdate(): void
    {
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_level_apprentice;

        $session['user_id'] = APPRENTICE_DEV_ID;
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        $userCourseId = USER_COUSE_DEV_ID;

        $result = $this->withSession($session)->get('plafor/grade/save/' .
            $userCourseId . '/' . $gradeId);
        $result->assertSee(lang('user_lang.code_error_403'));
    }

    public function testSaveGradeWithTrainerGradeUpdate(): void
    {
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

        // fake user
        $session['user_id'] = 999;
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        $userCourseId = USER_COUSE_DEV_ID;

        $result = $this->withSession($session)->get('plafor/grade/save/' .
            $userCourseId . '/' . $gradeId);
        $result->assertSee(lang('user_lang.code_error_403'));
    }

    public function testSaveGradeWithTrainerLinkedGradeUpdate(): void
    {
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

        $session['user_id'] = TRAINER_DEV_ID;
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        $userCourseId = USER_COUSE_DEV_ID;

        $result = $this->withSession($session)->get('plafor/grade/save/' .
            $userCourseId . '/' . $gradeId);
        $result->assertSee(lang('Grades.update_grade'));
    }

    public function testSaveGradeWithoutAccountGradeUpdate(): void
    {
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        $userCourseId = USER_COUSE_DEV_ID;
        try {
            $result = $this->withSession($session)->get('plafor/grade/save/' .
                $userCourseId . '/' . $gradeId);

        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    // delete tests

    public function testDeleteGradeWithAdminAccount(): void
    {
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;

        $result = $this->withSession($session)
                       ->get('plafor/grade/delete/2/1');

        $result->assertSee(lang('common_lang.php.title_delete_entry'));
    }

    public function testDeleteGradeWithApprenticeAccountHisGrade(): void
   	{
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_level_apprentice;

        $session['user_id'] = APPRENTICE_DEV_ID;
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        $result = $this->withSession($session)
                       ->get('plafor/grade/delete/2/' . $gradeId);

        $result->assertSee(lang('user_lang.code_error_403'));
    }

    public function testDeleteGradeWithApprenticeAccount(): void
    {
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_level_apprentice;

        $result = $this->withSession($session)
                       ->get('plafor/grade/delete/2/1');
        $result->assertSee(lang('user_lang.code_error_403'));
    }

    public function testDeleteGradeWithTrainerAccount(): void
    {
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        // fake user
        $session['user_id'] = 999;

        $result = $this->withSession($session)
                       ->get('plafor/grade/delete/2/1');

        $result->assertSee(lang('user_lang.code_error_403'));
    }

    public function testDeleteGradeWithTrainerAccountLinked(): void
    {
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $session['user_id'] = TRAINER_DEV_ID;
        // grade of the user course dev
        $gradeId = 101;

        $result = $this->withSession($session)
                       ->get('plafor/grade/delete/2/' . $gradeId);

        $result->assertSee(lang('common_lang.php.title_delete_entry'));
    }

    public function testDeleteGradeWithoutAccount(): void
    {
        try {
            $result = $this->get('plafor/grade/delete/2/1');
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

}
