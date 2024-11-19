<?php

namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class TeachingDomainModelWithSeedTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    // For Seeds
    protected $seedOnce = false;
    protected $seed     = 'TeachingDomainModelTestSeed';
    protected $basePath = 'tests/_support/Database';

    /**
     * Tests the getTeachingDomainIdByUserCourse method to retrieve the
     * teaching domain IDs by user course.
     *
     * @covers
     * \Plafor\Models\TeachingDomainModel::getTeachingDomainIdByUserCourse
     */
    public function testGetTeachingDomainIdByUserCourse()
    {
        // Arrange
        $userCourseId = 101;
        $expectedDomainIds = [5, 6, 7, 8, 14];
        $teachingDomainModel = model('TeachingDomainModel');
        // Act
        $domainIds = $teachingDomainModel
            ->getTeachingDomainIdByUserCourse($userCourseId);

        // Assert
        $this->assertEquals($expectedDomainIds, $domainIds);
    }

    /**
     * Tests the getTeachingDomainIdByUserCourse method to retrieve the
     * teaching domain IDs by user course when the user course is not found.
     *
     * @covers
     * \Plafor\Models\TeachingDomainModel::getTeachingDomainIdByUserCourse
     */
    public function testGetTeachingDomainIdByUserCourseNotFound()
    {
        // Arrange
        $userCourseId = 999;
        $teachingDomainModel = model('TeachingDomainModel');
        // Act
        $domainIds = $teachingDomainModel
            ->getTeachingDomainIdByUserCourse($userCourseId);

        // Assert
        $this->assertEmpty($domainIds);
    }

    /**
     * Tests the getITDomainWeight method to retrieve the weight of the IT
     * domain.
     *
     * @covers \Plafor\Models\TeachingDomainModel::getITDomainWeight
     */
    public function testGetITDomainWeight(): void
    {
        // Arrange
        $userCourseID = 101;
        $expectedWeight = 0.3;
        $teachingDomainModel = model('TeachingDomainModel');
        // Act
        $result = $teachingDomainModel->getITDomainWeight($userCourseID);
        // Assert
        $this->assertEquals($expectedWeight, $result);
    }

    /**
     * Tests the getTpiDomain method to retrieve the TPI domain.
     *
     * @covers \Plafor\Models\TeachingDomainModel::getTpiDomain
     */
    public function testGetTpiDomain()
    {
        // Arrange
        $userCourseId = 101;
        $tpiDomainName = 'Travail pratique individuel';
        $teachingDomainModel = model('TeachingDomainModel');
        // Act
        $domain = $teachingDomainModel->getTpiDomain($userCourseId);
        // Assert
        $this->assertNotNull($domain);
        $this->assertEquals($tpiDomainName, $domain['title']);
    }

}
