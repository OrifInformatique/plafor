<?php
/**
 * Unit tests CoursePlanTest
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

 namespace Plafor\Controllers;

 use CodeIgniter\Test\CIUnitTestCase;
 use CodeIgniter\Test\ControllerTestTrait;
 use CodeIgniter\Test\DatabaseTestTrait;

 use Plafor\Models;

 class CoursePlanTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;

    const m_TRAINER_USER_TYPE = 2;
    const m_APPRENTICE_USER_TYPE = 3;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    protected $seedOnce = false;
    protected $basePath = 'tests/_support/Database';
    protected $seed     = 'TeachingDomainSeed';

    
}