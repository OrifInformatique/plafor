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

class CoursePlanModelTest extends CIUnitTestCase
{
    /**
     * Asserts that getInstance method of CoursePlanModel returns an instance of CoursePlanModel
     */
    public function testgetCoursePlanModelInstance()
    {
        $coursePlanModel = CoursePlanModel::getInstance();
        $this->assertTrue($coursePlanModel instanceof CoursePlanModel);
        $this->assertInstanceOf(CoursePlanModel::class, $coursePlanModel);
    }

    /**
     * Checks that the getCompetenceDomains method of CoursePlanModel returns the expected competence domains
     */
    public function testgetCompetenceDomains()
    {
        // Gets the competence domains with the course plan id 1
        $competenceDomains = CoursePlanModel::getCompetenceDomains(1);

        // Assertions
        $this->assertIsArray($competenceDomains);

        // For each competence domain, asserts that the course plan id is 1
        foreach ($competenceDomains as $competenceDomain) {
            $this->assertEquals($competenceDomain['fk_course_plan'], 1);
        }
    }

    /**
     * Checks that the getUserCourses method of CoursePlanModel returns the expected user courses
     */
    public function testgetUserCourses()
    {
        // Gets the user courses with the course plan id 1
        $userCourses = CoursePlanModel::getUserCourses(1);

        // Assertions
        $this->assertIsArray($userCourses);

        // For each user course, asserts that the course plan id is 1
        foreach ($userCourses as $userCourse) {
            $this->assertEquals($userCourse['fk_course_plan'], 1);
        }
    }

    /**
     * Checks that the getCoursePlanProgress method of CoursePlanModel returns the expected course plan progress
     */
    public function testgetCoursePlanProgress()
    {
        // Gets the user plan progress for the user id 4
        $coursePlanProgress = CoursePlanModel::getInstance()->getCoursePlanProgress(4);

        // Assertions
        $this->assertIsArray($coursePlanProgress);
        $this->assertCount(1, $coursePlanProgress);

        $this->assertIsArray($coursePlanProgress[1]);
        $this->assertEquals($coursePlanProgress[1]['formation_number'], '88601');
        $this->assertEquals($coursePlanProgress[1]['official_name'], ' Informaticien/-ne CFC Développement d\'applications');
        $this->assertEquals($coursePlanProgress[1]['fk_acquisition_status'], 1);

        $this->assertIsArray($coursePlanProgress[1]['competenceDomains']);
        $this->assertIsArray($coursePlanProgress[1]['competenceDomains'][1]);
        $this->assertEquals($coursePlanProgress[1]['competenceDomains'][1]['symbol'], 'A');
        $this->assertEquals($coursePlanProgress[1]['competenceDomains'][1]['name'], 'Saisie, interprétation et mise en œuvre des exigences des applications');

        $this->assertIsArray($coursePlanProgress[1]['competenceDomains'][1]['operationalCompetences']);
        $this->assertIsArray($coursePlanProgress[1]['competenceDomains'][1]['operationalCompetences'][1]);
        $this->assertEquals($coursePlanProgress[1]['competenceDomains'][1]['operationalCompetences'][1]['symbol'], 'A1');
        $this->assertEquals($coursePlanProgress[1]['competenceDomains'][1]['operationalCompetences'][1]['name'], 'Analyser, structurer et documenter les exigences ainsi que les besoins');

        $this->assertIsArray($coursePlanProgress[1]['competenceDomains'][1]['operationalCompetences'][1]['objectives']);
        $this->assertIsArray($coursePlanProgress[1]['competenceDomains'][1]['operationalCompetences'][1]['objectives'][1]);
        $this->assertEquals($coursePlanProgress[1]['competenceDomains'][1]['operationalCompetences'][1]['objectives'][1]['symbol'], 'A.1.2');
        $this->assertEquals($coursePlanProgress[1]['competenceDomains'][1]['operationalCompetences'][1]['objectives'][1]['name'], ' Confirmer les exigences en ses propres termes (traiter et en déduire, lister les questions)');
    }

    /**
     * Checks that the getCoursePlanProgress method of CoursePlanModel returns null
     */
    public function testgetCoursePlanProgressWithNoUserId()
    {
        // Gets the user plan progress
        $coursePlanProgress = CoursePlanModel::getInstance()->getCoursePlanProgress(null);

        // Assertions
        $this->assertNull($coursePlanProgress);
    }
}