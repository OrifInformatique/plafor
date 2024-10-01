<?php
/**
 * Unit / Integration tests UserCourseModelTest 
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;

class UserCourseModelTest extends CIUnitTestCase
{
    const TRAINER_USER_TYPE = 2;

    /**
     * Asserts that getInstance method of UserCourseModel returns an instance of UserCourseModel
     */
    public function testgetUserCourseModelInstance()
    {
        $userCourseModel = model('UserCourseModel');
        $this->assertTrue($userCourseModel instanceof UserCourseModel);
        $this->assertInstanceOf(UserCourseModel::class, $userCourseModel);
    }

    /**
     * Checks that the returned course plan is the expected one
     */
    public function testgetCoursePlan()
    {
        // Gets the course plan with the course plan id 6
        $userCourseModel = model('UserCourseModel');
        $coursePlanId = 6;
        $coursePlan = $userCourseModel->getCoursePlan($coursePlanId);

        // Assertions
        $this->assertIsArray($coursePlan);
        $this->assertEquals($coursePlan['id'], $coursePlanId);
        $this->assertEquals($coursePlan['formation_number'], '88611');
        $this->assertEquals($coursePlan['official_name'], 'Informaticienne / Informaticien avec CFC, orientation développement d\'applications');
        $this->assertEquals($coursePlan['date_begin'], '2021-08-01');
        $this->assertEquals($coursePlan['archive'], null);
    }

    /**
     * Checks that the returned user course status is the expected one
     */
    public function testgetUserCourseStatus()
    {
        // Gets the user course status with the user course status id 1
        $userCourseModel = model('UserCourseModel');
        $userCourseStatus = $userCourseModel->getUserCourseStatus(1);

        // Assertions
        $this->assertIsArray($userCourseStatus);
        $this->assertEquals($userCourseStatus['id'], 1);
        $this->assertEquals($userCourseStatus['name'], 'En cours');
    }

    /**
     * Checks that the returned list of acquisition statuses is the expected one
     */
    public function testgetAcquisitionStatuses()
    {
        // Gets the list of acquisition statuses for the user course id 1
        $userCourseModel = model('UserCourseModel');
        $acquisitionStatuses = $userCourseModel->getAcquisitionStatus(1);

        // Asserts that the list of acquisition statuses is an array
        $this->assertIsArray($acquisitionStatuses);
        
        // For each acquisition status, asserts that the user course id is 1
        foreach ($acquisitionStatuses as $acquisitionStatus) {
            $this->assertEquals($acquisitionStatus['fk_user_course'], 1);
        }
    }
}
