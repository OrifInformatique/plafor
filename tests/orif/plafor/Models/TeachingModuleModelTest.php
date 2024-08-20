<?php

namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class TeachingModuleModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    // For Seeds
    // protected $seedOnce = false;
    // protected $seed     = 'apprenticeTestSeed';
    // protected $basePath = 'tests/_support/Database';

    public function testTeachingModuleModelInstance()
    {
        $teachingModuleModel = model('TeachingModuleModel');
        $this->assertTrue($teachingModuleModel instanceof
            TeachingModuleModel);
        $this->assertInstanceOf(TeachingModuleModel::class,
            $teachingModuleModel);
    }
}
