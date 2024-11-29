<?php
/**
 * Unit / Integration tests CompetenceDomainModelTest
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;

helper("UnitTest_helper"); // The helper hold all Constants -> Plafor\orif\plafor\Helpers\UnitTest_helper.php

class CompetenceDomainModelTest extends CIUnitTestCase
{
    /**
     * Asserts that getInstance method of CompetenceDomainModel returns an
     * instance of CompetenceDomainModel
     */
    public function testgetCompetenceDomainModelInstance()
    {
        $competenceDomainModel = model('CompetenceDomainModel');
        $this->assertTrue($competenceDomainModel instanceof CompetenceDomainModel);
        $this->assertInstanceOf(CompetenceDomainModel::class, $competenceDomainModel);
    }

    /**
     * Checks that the getCoursePlan method of CompetenceDomainModel returns
     * the expected course plan
     */
    public function testgetCoursePlan()
    {
        // Gets the course plan with the id 1
        $competenceDomainModel = model('CompetenceDomainModel');
        $coursePlan = $competenceDomainModel->getCoursePlan(6);

        // Assertions
        $this->assertIsArray($coursePlan);
        $this->assertEquals($coursePlan['id'], 6);
        $this->assertEquals($coursePlan['formation_number'], 88611);
        $this->assertEquals($coursePlan['official_name'], 'Informaticienne / Informaticien avec CFC, orientation développement d\'applications');
        $this->assertEquals($coursePlan['date_begin'], '2021-08-01');
        $this->assertEquals($coursePlan['archive'], NULL);
    }

    /**
     * Checks that the getOperationalCompetences method of CompetenceDomainModel returns the expected operational competences
     */
    public function testgetOperationalCompetences()
    {
        // Gets the operational competences with the competence domain id 1
        $competenceDomainModel = model('CompetenceDomainModel');
        $operational_competences = $competenceDomainModel->getOperationalCompetences(1);

        // Assertions
        $this->assertIsArray($operational_competences);

        // For each operational competences, asserts that the competence domain is 1
        foreach ($operational_competences as $operational_competence) {
            $this->assertEquals($operational_competence['fk_competence_domain'], 1);
        }
    }

    /**
     * Checks that the getCompetenceDomains method of CompetenceDomainModel returns the expected competence domains
     */
    public function testgetCompetenceDomains()
    {
        // Gets the competence domains with the course plan id 1
        $competenceDomainModel = model('CompetenceDomainModel');
        $competence_domains = $competenceDomainModel->getCompetenceDomains(false, 1);

        // Assertions
        $this->assertIsArray($competence_domains);

        // For each competence domain, asserts that the course plan id is 1
        foreach ($competence_domains as $competence_domain) {
            $this->assertEquals($competence_domain['fk_course_plan'], 1);
        }
    }

    /**
     * Checks that the getCompetenceDomains method of CompetenceDomainModel returns the expected competence domains
     */
    public function testgetCompetenceDomainsWithNoCoursePlanId()
    {
        // Gets the competence domains with the course plan id 1
        $competenceDomainModel = model('CompetenceDomainModel');
        $competence_domains = $competenceDomainModel->getCompetenceDomains(false);

        // Assertions
        $this->assertIsArray($competence_domains);
    }
}
