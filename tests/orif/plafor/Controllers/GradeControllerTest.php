<?php
/**
 * Unit tests GradeControllerTest
 *
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

 namespace Plafor\Controllers;

 use CodeIgniter\Test\CIUnitTestCase;
 use CodeIgniter\Test\ControllerTestTrait;
 use CodeIgniter\Test\DatabaseTestTrait;

 use Plafor\Models;

class GradeControllerTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    protected $seedOnce = false;
    protected $basePath = 'tests/_support/Database';
    protected $seed     = 'GradeControllerSeed';

    public function testSaveGrade()
    {
        $_SESSION = [];
        $_SESSION['logged_in'] = true;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;

        $result = $this->controller(GradeController::class)
            ->execute('saveGrade', 1);
    }

    public function testDeleteGrade()
    {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;

        $result = $this->controller(GradeController::class)
            ->execute('deleteGrade', 0);
    }

}
