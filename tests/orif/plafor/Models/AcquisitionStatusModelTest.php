<?php
/**
 * Unit / Integration tests AcquisitionStatusModel
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;

helper("UnitTest_helper"); // The helper hold all Constants -> Plafor\orif\plafor\Helpers\UnitTest_helper.php

class AcquisitionStatusModelTest extends CIUnitTestCase
{
    /**
     * Asserts that getInstance method of AcquisitionStatusModel returns an
     * instance of AcquisitionStatusModel
     */
    public function testgetAcquisitionStatusModelInstance()
    {
        $acquisitionStatusModel = model('AcquisitionStatusModel');
        $this->assertTrue($acquisitionStatusModel instanceof
            AcquisitionStatusModel);
        $this->assertInstanceOf(AcquisitionStatusModel::class,
            $acquisitionStatusModel);
    }

    /**
     * Checks that the getObjective method of AcquisitionStatusModel returns
     * the expected objective
     */
    public function testgetObjective()
    {
        // Gets the objective with the id 1
        $acquisitionStatusModel = model('AcquisitionStatusModel');
        $objective = $acquisitionStatusModel->getObjective(1);

        // Assertions
        $this->assertIsArray($objective);
        $this->assertEquals($objective['id'], 1);
        $this->assertEquals($objective['fk_operational_competence'], 1);
        $this->assertEquals($objective['symbol'], 'A.1.1');
        $this->assertEquals($objective['taxonomy'], 4);
        $this->assertEquals($objective['name'], 'Enregistrer les besoins et discuter les solutions possibles, s’entretenir avec le client/supérieur sur les restrictions des exigences');
        $this->assertEquals($objective['archive'], NULL);
    }

    /**
     * Checks that the getUserCourse method of AcquisitionStatusModel returns the expected user course
     */
    public function testgetUserCourse()
    {
        // Gets the user course with the id 1
        $acquisitionStatusModel = model('AcquisitionStatusModel');
        $userCourse = $acquisitionStatusModel->getUserCourse(1);

        // Assertions
        $this->assertIsArray($userCourse);
        $this->assertEquals($userCourse['id'], 1);
        $this->assertEquals($userCourse['fk_user'], 4);
        $this->assertEquals($userCourse['fk_course_plan'], 5);
        $this->assertEquals($userCourse['fk_status'], 1);
        $this->assertEquals($userCourse['date_begin'], '2024-08-01');
        $this->assertEquals($userCourse['date_end'], '2028-08-01');
    }

    /**
     * Checks that the getAcquisitionLevel method of AcquisitionStatusModel returns the expected acquisition level
     */
    public function testgetAcquisitionLevel()
    {
        // Gets the acquisition level with the id 1
        $acquisitionStatusModel = model('AcquisitionStatusModel');
        $acquisitionLevel = $acquisitionStatusModel->getAcquisitionLevel(1);

        // Assertions
        $this->assertIsArray($acquisitionLevel);
        $this->assertEquals($acquisitionLevel['id'], 1);
        $this->assertEquals($acquisitionLevel['name'], 'Non expliqué');
    }
}
