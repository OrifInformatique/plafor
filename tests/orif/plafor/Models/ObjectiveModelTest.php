<?php
/**
 * Unit / Integration tests ObjectiveModelTest 
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;

class ObjectiveModelTest extends CIUnitTestCase
{
    /**
     * Asserts that getInstance method of ObjectiveModel returns an instance of ObjectiveModel
     */
    public function testgetObjectiveModelInstance()
    {
        $objectiveModel = ObjectiveModel::getInstance();
        $this->assertTrue($objectiveModel instanceof ObjectiveModel);
        $this->assertInstanceOf(ObjectiveModel::class, $objectiveModel);
    }

    /**
     * Checks that the getOperationalCompetence method of ObjectiveModel returns the expected operational competence
     */
    public function testgetOperationalCompetence()
    {
        // Gets the operational competence with the id 1
        $operationalCompetence = ObjectiveModel::getOperationalCompetence(1);

        // Assertions
        $this->assertIsArray($operationalCompetence);
        $this->assertEquals($operationalCompetence['id'], 1);
        $this->assertEquals($operationalCompetence['fk_competence_domain'], 1);
        $this->assertEquals($operationalCompetence['name'], 'Analyser, structurer et documenter les exigences ainsi que les besoins');
        $this->assertEquals($operationalCompetence['symbol'], 'A1');
        $this->assertEquals($operationalCompetence['methodologic'], 'Travail structuré, documentation adéquate');
        $this->assertEquals($operationalCompetence['social'], 'Comprendre et sentir les problèmes du client, communication avec des partenaires');
        $this->assertEquals($operationalCompetence['personal'], 'Fiabilité, autoréflexion, interrogation constructive du problème');
        $this->assertEquals($operationalCompetence['archive'], NULL);
    }

    /**
     * Checks that the getAcquisitionStatus method of ObjectiveModel returns the expected acquisition statuses
     */
    public function testgetAcquisitionStatus()
    {
        // Gets the acquisition statuses with the objective id 1
        $acquisitionStatuses = ObjectiveModel::getAcquisitionStatus(1);

        // Assertions
        $this->assertIsArray($acquisitionStatuses);

        // For each acquisition status, asserts that the objective id is 1
        foreach ($acquisitionStatuses as $acquisitionStatus) {
            $this->assertEquals($acquisitionStatus['fk_objective'], 1);
        }
    }

    /**
     * Checks that the getAcquisitionStatus method of ObjectiveModel returns the expected acquisition status
     */
    public function testgetAcquisitionStatusForAGivenUserCourse()
    {
        // Gets the acquisition statuses with the objective id 1 
        $acquisitionStatus = ObjectiveModel::getAcquisitionStatus(1, 1);

        // Assertions
        $this->assertIsArray($acquisitionStatus);
        $this->assertEquals($acquisitionStatus['fk_objective'], 1);
        $this->assertEquals($acquisitionStatus['fk_user_course'], 1);
    }

    /**
     * Checks that the getObjectives method of ObjectiveModel returns the expected objectives
     */
    public function testgetObjectives()
    {
        // Gets the objectives for the operational competence id 1
        $objectives = ObjectiveModel::getObjectives(false, 1);

        // Assertions
        $this->assertIsArray($objectives);

        // For each objective, asserts that the operational competence id is 1
        foreach ($objectives as $objective) {
            $this->assertEquals($objective['fk_operational_competence'], 1);
        }
    }

    /**
     * Checks that the getObjectives method of ObjectiveModel returns the expected objectives
     */
    public function testgetObjectivesWiNoOperationalCompetenceId()
    {
        // Gets the objectives
        $objectives = ObjectiveModel::getObjectives(false, 0);

        // Assertions
        $this->assertIsArray($objectives);
    }
}