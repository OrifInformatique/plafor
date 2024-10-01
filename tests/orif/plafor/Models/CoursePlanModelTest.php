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
        $coursePlanModel = model('CoursePlanModel');
        $this->assertTrue($coursePlanModel instanceof CoursePlanModel);
        $this->assertInstanceOf(CoursePlanModel::class, $coursePlanModel);
    }

    /**
     * Checks that the getCompetenceDomains method of CoursePlanModel returns the expected competence domains
     */
    public function testgetCompetenceDomains()
    {
        // Gets the competence domains with the course plan id 1
        $coursePlanModel = model('CoursePlanModel');
        $competence_domains = $coursePlanModel->getCompetenceDomains(1);

        // Assertions
        $this->assertIsArray($competence_domains);

        // For each competence domain, asserts that the course plan id is 1
        foreach ($competence_domains as $competence_domain) {
            $this->assertEquals($competence_domain['fk_course_plan'], 1);
        }
    }

    /**
     * Checks that the getUserCourses method of CoursePlanModel returns the expected user courses
     */
    public function testgetUserCourses()
    {
        // Gets the user courses with the course plan id 1
        $coursePlanModel = model('CoursePlanModel');
        $userCourses = $coursePlanModel->getUserCourses(1);

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
        $coursePlanModel = model('CoursePlanModel');
        $userId = 6;
        $coursePlanProgress = $coursePlanModel->getCoursePlanProgress($userId);

        // Assertions
        $this->assertIsArray($coursePlanProgress);
        $this->assertCount(1, $coursePlanProgress);

        $this->assertIsArray($coursePlanProgress[$userId]);
        $this->assertEquals($coursePlanProgress[$userId]['formation_number'], '88611');
        $this->assertEquals($coursePlanProgress[$userId]['official_name'], 'Informaticienne / Informaticien avec CFC, orientation développement d\'applications');
        $this->assertEquals($coursePlanProgress[$userId]['fk_acquisition_status'], 1);

        $this->assertIsArray($coursePlanProgress[$userId]['competenceDomains']);

        $competenceDomainId = 27;
        $competence_domain = $coursePlanProgress[$userId]['competenceDomains'][$competenceDomainId];

        $this->assertIsArray($competence_domain);
        $this->assertEquals($competence_domain['symbol'], 'A');
        $this->assertEquals($competence_domain['name'], 'Suivi des projets ICT');

        $this->assertIsArray($competence_domain['operationalCompetences']);
        $operational_competenceId = 108;
        $this->assertIsArray($competence_domain['operationalCompetences'][$operational_competenceId]);
        $operational_competence = $competence_domain['operationalCompetences'][$operational_competenceId];

        $this->assertEquals($operational_competence['symbol'], 'A1');
        $this->assertEquals($operational_competence['name'], 'Clarifier et documenter les besoins des parties prenantes dans le cadre d’un projet ICT');

        $this->assertIsArray($operational_competence['objectives']);

        $this->assertIsArray($operational_competence['objectives'][1]);
        $this->assertEquals($operational_competence['objectives'][1]['symbol'], 'A1.2');
        $this->assertEquals($operational_competence['objectives'][1]['name'], 'Ils appliquent diverses techniques d’audition et d’observation (p. ex. questions ouvertes, questions fermées, réunion, workshop, technique du shadowing, simulation de la solution à rechercher en opérant un saut dans le temps.');
    }

    /**
     * Checks that the getCoursePlanProgress method of CoursePlanModel returns null
     */
    public function testgetCoursePlanProgressWithNoUserId()
    {
        // Gets the user plan progress
        $coursePlanModel = model('CoursePlanModel');
        $coursePlanProgress = $coursePlanModel->getCoursePlanProgress(null);

        // Assertions
        $this->assertNull($coursePlanProgress);
    }
}
