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

    public function testGetTeachingDomainIdByUserCourse()
    {
        $userCourseId = 101;
        $expectedDomainIds = [5, 6, 7, 8, 14];


        $teachingDomainModel = model('TeachingDomainModel');
        $domainIds = $teachingDomainModel
            ->getTeachingDomainIdByUserCourse($userCourseId);

        $this->assertEquals($expectedDomainIds, $domainIds);
    }

    public function testGetTeachingDomainIdByUserCourseNotFound()
    {
        $userCourseId = 999;


        $teachingDomainModel = model('TeachingDomainModel');
        $domainIds = $teachingDomainModel
            ->getTeachingDomainIdByUserCourse($userCourseId);

        $this->assertEmpty($domainIds);
    }

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
