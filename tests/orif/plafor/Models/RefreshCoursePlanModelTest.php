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

    /**
     * Tests the getCoursePlanIdByUserCourse method to retrieve the course plan
     * ID by user course.
     *
     * @covers \Plafor\Models\CoursePlanModel::getCoursePlanIdByUserCourse
     */
     public function testGetCoursePlanIdByUserCourse()
    {
        $coursePlanModel = model('CoursePlanModel');

        $userCourseId = 101;
        $expectedCoursePlanId = 6;

        $coursePlanId = $coursePlanModel
            ->getCoursePlanIdByUserCourse($userCourseId);

        $this->assertEquals($expectedCoursePlanId, $coursePlanId);
    }

    /**
     * Tests the getCoursePlanIdByUserCourse method to retrieve the course plan
     * ID by user course when the user course is not found.
     *
     * @covers \Plafor\Models\CoursePlanModel::getCoursePlanIdByUserCourse
     */
    public function testGetCoursePlanIdByUserCourseNotFound()
    {
        $coursePlanModel = model('CoursePlanModel');
        $userCourseId = 999; //  nonexistent ID

        $coursePlanId = $coursePlanModel
            ->getCoursePlanIdByUserCourse($userCourseId);

        $this->assertNull($coursePlanId);
    }
}
