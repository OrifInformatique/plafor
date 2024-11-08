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

// The helper hold all Constants
// -> Plafor\orif\plafor\Helpers\UnitTest_helper.php
helper("UnitTest_helper");

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
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;

        // fake user
        $session['user_id'] = 999;
        // Act
        $result = $this->withSession($session)->get('plafor/grade/save/1');
        // Assert
        $result->assertSee(lang('Grades.add_grade'));
    }

    public function testSaveGradeWithApprenticeAccount(): void
    {
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_level_apprentice;

        // fake user
        $session['user_id'] = 999;
        // Act
        $result = $this->withSession($session)->get('plafor/grade/save/1');
        // Assert
        $result->assertSee(lang('user_lang.code_error_403'));
    }

    public function testSaveGradeWithTrainerAccount(): void
    {
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

        // fake user
        $session['user_id'] = 999;
        // Act
        $result = $this->withSession($session)->get('plafor/grade/save/1');
        // Assert
        $result->assertSee(lang('user_lang.code_error_403'));
    }

    public function testSaveGradeWithTrainerAccountLinked(): void
    {
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

        // fake user
        $session['user_id'] = TRAINER_DEV_ID;
        // Act
        $result = $this->withSession($session)->get('plafor/grade/save/'
            . USER_COUSE_DEV_ID);

        // Assert
        $result->assertSee(lang('Grades.add_grade'));
    }

    public function testSaveGradeWithoutAccount(): void
    {
        try {
            // Act
            $result = $this->get('plafor/grade/save/' . USER_COUSE_DEV_ID);
            // Assert
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    // update tests

    public function testSaveGradeWithAdminAccountUpdate(): void
    {
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;

        // fake user
        $session['user_id'] = 999;
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        $userCourseId = USER_COUSE_DEV_ID;
        // Act
        $result = $this->withSession($session)->get('plafor/grade/save/' .
            $userCourseId . '/' . $gradeId);

        // Arrange
        $result->assertSee(lang('Grades.update_grade'));
    }

    public function testSaveGradeWithApprenticeAccountUpdate(): void
    {
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_level_apprentice;

        // fake user
        $session['user_id'] = 999;
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        $userCourseId = USER_COUSE_DEV_ID;
        // Act
        $result = $this->withSession($session)->get('plafor/grade/save/' .
            $userCourseId . '/' . $gradeId);

        // Assert
        $result->assertSee(lang('user_lang.code_error_403'));
    }

    public function testSaveGradeWithApprenticeAccountHisGradeUpdate(): void
    {
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_level_apprentice;

        $session['user_id'] = APPRENTICE_DEV_ID;
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        $userCourseId = USER_COUSE_DEV_ID;
        // Act
        $result = $this->withSession($session)->get('plafor/grade/save/' .
            $userCourseId . '/' . $gradeId);

        // Assert
        $result->assertSee(lang('Grades.update_grade'));
    }

    public function testSaveGradeWithTrainerGradeUpdate(): void
    {
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

        // fake user
        $session['user_id'] = 999;
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        $userCourseId = USER_COUSE_DEV_ID;
        // Act
        $result = $this->withSession($session)->get('plafor/grade/save/' .
            $userCourseId . '/' . $gradeId);

        // Assert
        $result->assertSee(lang('user_lang.code_error_403'));
    }

    public function testSaveGradeWithTrainerLinkedGradeUpdate(): void
    {
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

        $session['user_id'] = TRAINER_DEV_ID;
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        $userCourseId = USER_COUSE_DEV_ID;
        // Act
        $result = $this->withSession($session)->get('plafor/grade/save/' .
            $userCourseId . '/' . $gradeId);

        // Assert
        $result->assertSee(lang('Grades.update_grade'));
    }

    public function testSaveGradeWithoutAccountGradeUpdate(): void
    {
        // Arrange
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        $userCourseId = USER_COUSE_DEV_ID;
        try {
            // Act
            $result = $this->withSession($session)->get('plafor/grade/save/' .
                $userCourseId . '/' . $gradeId);

            // Assert
            $this->assertTrue(false);

        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    // delete tests

    public function testDeleteGradeWithAdminAccount(): void
    {
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;

        // Act
        $result = $this->withSession($session)
                       ->get('plafor/grade/delete/2/1');

        // Assert
        $result->assertSee(lang('common_lang.php.title_delete_entry'));
    }

    public function testDeleteGradeWithApprenticeAccountHisGrade(): void
   	{
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_level_apprentice;

        $session['user_id'] = APPRENTICE_DEV_ID;
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        // Act
        $result = $this->withSession($session)
                       ->get('plafor/grade/delete/2/' . $gradeId);

        // Assert
        $result->assertSee(lang('common_lang.php.title_delete_entry'));
    }

    public function testDeleteGradeWithApprenticeAccount(): void
    {
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_level_apprentice;
        $session['user_id'] = 999;
        // Act
        $result = $this->withSession($session)
                       ->get('plafor/grade/delete/2/1');

        // Assert
        $result->assertSee(lang('user_lang.code_error_403'));
    }

    public function testDeleteGradeWithTrainerAccount(): void
    {
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

        // fake user
        $session['user_id'] = 999;
        // Act
        $result = $this->withSession($session)
                       ->get('plafor/grade/delete/2/1');

        // Assert
        $result->assertSee(lang('user_lang.code_error_403'));
    }

    public function testDeleteGradeWithTrainerAccountLinked(): void
    {
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

        $session['user_id'] = TRAINER_DEV_ID;
        $gradeId = APPRENTICE_DEV_ID_GRADE_ID;
        // Act
        $result = $this->withSession($session)
                       ->get('plafor/grade/delete/2/' . $gradeId);

        // Assert
        $result->assertSee(lang('common_lang.php.title_delete_entry'));
    }

    public function testDeleteGradeWithoutAccount(): void
    {
        try {
            // Act
            $result = $this->get('plafor/grade/delete/2/1');
            // Assert
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }
}
