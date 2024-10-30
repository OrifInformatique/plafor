<?php
/**
 * Unit / Integration tests CoursePlanModelTest 
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class RefreshCoursePlanModelTest extends CIUnitTestCase
{ 
    use DatabaseTestTrait;

    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    protected $seedOnce = false;
    protected $basePath = 'tests/_support/Database';
    protected $seed     = 'TeachingDomainModelTestSeed';
    // protected $seed     = 'apprenticeTestSeed';

     public function testGetCoursePlanIdByUserCourse()
    {
        $coursePlanModel = model('CoursePlanModel');

        $userCourseId = 101;
        $expectedCoursePlanId = 6;

        $coursePlanId = $coursePlanModel
            ->getCoursePlanIdByUserCourse($userCourseId);

        $this->assertEquals($expectedCoursePlanId, $coursePlanId);
    }

    public function testGetCoursePlanIdByUserCourseNotFound()
    {
        $coursePlanModel = model('CoursePlanModel');
        $userCourseId = 999; //  nonexistent ID

        $coursePlanId = $coursePlanModel
            ->getCoursePlanIdByUserCourse($userCourseId);

        $this->assertNull($coursePlanId);
    }
}
