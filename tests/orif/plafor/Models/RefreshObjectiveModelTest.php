<?php
namespace Plafor\Models;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class RefreshObjectiveModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    protected $seedOnce = false;
    protected $basePath = 'tests/_support/Database';
    protected $seed     = 'ApprenticeTestSeed';

    /**
     * Checks that the getAcquisitionStatus method of ObjectiveModel returns
     * the expected acquisition status
     */
    public function testgetAcquisitionStatusForAGivenUserCourse()
    {
        $objectiveId = 1;
        $userCourseId = 1;
        // Gets the acquisition statuses with the objective id 1
        $objectiveModel = model('ObjectiveModel');
        $acquisitionStatus = $objectiveModel
            ->getAcquisitionStatus($objectiveId, $userCourseId);

        // Assertions
        $this->assertIsArray($acquisitionStatus);
        $this->assertEquals($acquisitionStatus['fk_objective'], $objectiveId);
        $this->assertEquals($acquisitionStatus['fk_user_course'],
            $userCourseId);
    }
}
