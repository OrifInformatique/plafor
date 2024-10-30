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

    public function testSaveGrade()
    {

        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;

        $result = $this->withSession($session)->get('plafor/grade/save/1');
    }

    public function testDeleteGrade()
    {
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;

        $result = $this->withSession($session)
                       ->get('plafor/grade/delete/1/1');
    }

}
