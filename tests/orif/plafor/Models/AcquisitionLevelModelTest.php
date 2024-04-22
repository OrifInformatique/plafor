<?php
/**
 * Unit / Integration tests AcquisitionLevelModelTest 
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;

class AcquisitionLevelModelTest extends CIUnitTestCase
{
    const ACQUISITION_NOT_EXPLAINED = 1;

    /**
     * Asserts that getInstance method of AcquisitionLevelModel returns an instance of AcquisitionLevelModel
     */
    public function testgetAcquisitionLevelModelInstance()
    {
        $acquisitionLevelModel = AcquisitionLevelModel::getInstance();
        $this->assertTrue($acquisitionLevelModel instanceof AcquisitionLevelModel);
        $this->assertInstanceOf(AcquisitionLevelModel::class, $acquisitionLevelModel);
    }

    /**
     * Checks that the list of acquisition statuses is an array containing only ACQUISITION_NOT_EXPLAINED level
     */
    public function testgetAcquisitionStatus()
    {
        // Gets the list of acquisition statuses for level ACQUISITION_NOT_EXPLAINED
        $acquisitionStatuses = AcquisitionLevelModel::getAcquisitionStatus(self::ACQUISITION_NOT_EXPLAINED);

        // Asserts that the list of acquisition statuses is an array
        $this->assertIsArray($acquisitionStatuses);
        
        // For each acquisition status, asserts that the acquisition level is ACQUISITION_NOT_EXPLAINED
        foreach ($acquisitionStatuses as $acquisitionStatus) {
            $this->assertEquals($acquisitionStatus['fk_acquisition_level'], self::ACQUISITION_NOT_EXPLAINED);
        }
    }
}