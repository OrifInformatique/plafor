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
        $objectiveModel = model('ObjectiveModel');
        $this->assertTrue($objectiveModel instanceof ObjectiveModel);
        $this->assertInstanceOf(ObjectiveModel::class, $objectiveModel);
    }

    /**
     * Checks that the getOperationalCompetence method of ObjectiveModel returns the expected operational competence
     */
    public function testgetOperationalCompetence()
    {
        // Gets the operational competence with the id 1
        $objectiveModel = model('ObjectiveModel');
        $operational_competence = $objectiveModel->getOperationalCompetence(1);

        // Assertions
        $this->assertIsArray($operational_competence);
        $this->assertEquals($operational_competence['id'], 1);
        $this->assertEquals($operational_competence['fk_competence_domain'], 1);
        $this->assertEquals($operational_competence['name'], 'Analyser, structurer et documenter les exigences ainsi que les besoins');
        $this->assertEquals($operational_competence['symbol'], 'A1');
        $this->assertEquals($operational_competence['methodologic'], 'Travail structuré, documentation adéquate');
        $this->assertEquals($operational_competence['social'], 'Comprendre et sentir les problèmes du client, communication avec des partenaires');
        $this->assertEquals($operational_competence['personal'], 'Fiabilité, autoréflexion, interrogation constructive du problème');
        $this->assertEquals($operational_competence['archive'], NULL);
    }

    /**
     * Checks that the getAcquisitionStatus method of ObjectiveModel returns the expected acquisition statuses
     */
    public function testgetAcquisitionStatus()
    {
        // Gets the acquisition statuses with the objective id 1
        $objectiveModel = model('ObjectiveModel');
        $acquisitionStatuses = $objectiveModel->getAcquisitionStatus(1);

        // Assertions
        $this->assertIsArray($acquisitionStatuses);

        // For each acquisition status, asserts that the objective id is 1
        foreach ($acquisitionStatuses as $acquisitionStatus) {
            $this->assertEquals($acquisitionStatus['fk_objective'], 1);
        }
    }


    /**
     * Checks that the getObjectives method of ObjectiveModel returns the expected objectives
     */
    public function testgetObjectives()
    {
        // Gets the objectives for the operational competence id 1
        $objectiveModel = model('ObjectiveModel');
        $objectives = $objectiveModel->getObjectives(false, 1);

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
        $objectiveModel = model('ObjectiveModel');
        $objectives = $objectiveModel->getObjectives(false, 0);

        // Assertions
        $this->assertIsArray($objectives);
    }
}
