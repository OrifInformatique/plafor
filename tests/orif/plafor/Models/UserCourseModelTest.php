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
        $userCourseModel = UserCourseModel::getInstance();
        $this->assertTrue($userCourseModel instanceof UserCourseModel);
        $this->assertInstanceOf(UserCourseModel::class, $userCourseModel);
    }

    /**
     * Checks that the returned user is the expected one
     */
    public function testgetUser()
    {
        // Gets the user with the user id 2
        $user = UserCourseModel::getUser(2);

        // Assertions
        $this->assertIsArray($user);
        $this->assertEquals($user['id'], 2);
        $this->assertEquals($user['fk_user_type'], self::TRAINER_USER_TYPE);
        $this->assertEquals($user['username'], 'FormateurDev');
        $this->assertEquals($user['password'], '$2y$10$Q3H8WodgKonQ60SIcu.eWuVKXmxqBw1X5hMpZzwjRKyCTB1H1l.pe');
        $this->assertEquals($user['archive'], NULL);
        $this->assertEquals($user['date_creation'], '2020-07-09 13:15:24');
    }

    /**
     * Checks that the returned course plan is the expected one
     */
    public function testgetCoursePlan()
    {
        // Gets the course plan with the course plan id 2
        $coursePlan = UserCourseModel::getCoursePlan(2);

        // Assertions
        $this->assertIsArray($coursePlan);
        $this->assertEquals($coursePlan['id'], 2);
        $this->assertEquals($coursePlan['formation_number'], '88602');
        $this->assertEquals($coursePlan['official_name'], ' Informaticien/-ne CFC Informatique d\'entreprise');
        $this->assertEquals($coursePlan['date_begin'], '2014-08-01');
        $this->assertEquals($coursePlan['archive'], NULL);
    }

    /**
     * Checks that the returned user course status is the expected one
     */
    public function testgetUserCourseStatus()
    {
        // Gets the user course status with the user course status id 1
        $userCourseStatus = UserCourseModel::getUserCourseStatus(1);

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
        $acquisitionStatuses = UserCourseModel::getAcquisitionStatus(1);

        // Asserts that the list of acquisition statuses is an array
        $this->assertIsArray($acquisitionStatuses);
        
        // For each acquisition status, asserts that the user course id is 1
        foreach ($acquisitionStatuses as $acquisitionStatus) {
            $this->assertEquals($acquisitionStatus['fk_user_course'], 1);
        }
    }
}