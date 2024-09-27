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


    public function testGetApprenticeAverage()
    {
        $gradeModel = model('GradeModel');
        $result = $gradeModel->getApprenticeAverage(101);
        $this->assertEquals(4.3, $result);
    }

}
