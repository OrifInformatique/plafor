<?php
/**
 * Unit / Integration tests UserCourseStatusModelTest 
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;

class UserCourseStatusModelTest extends CIUnitTestCase
{
    /**
     * Asserts that getInstance method of UserCourseStatusModel returns an instance of UserCourseStatusModel
     */
    public function testgetUserCourseStatusModelInstance()
    {
        $userCourseStatusModel = UserCourseStatusModel::getInstance();
        $this->assertTrue($userCourseStatusModel instanceof UserCourseStatusModel);
        $this->assertInstanceOf(UserCourseStatusModel::class, $userCourseStatusModel);
    }

    /**
     * Checks that the returned list of user courses is the expected one
     */
    public function testgetUserCourses()
    {
        // Gets the list of user courses for the status id 1
        $userCourses = UserCourseStatusModel::getUserCourses(1);

        // Asserts that the list of user courses is an array
        $this->assertIsArray($userCourses);
        
        // For each user course, asserts that the status id is 1
        foreach ($userCourses as $userCourse) {
            $this->assertEquals($userCourse['fk_status'], 1);
        }
    }
}