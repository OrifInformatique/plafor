<?php
namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

// The helper hold all Constants ->
// Plafor\orif\plafor\Helpers\UnitTest_helper.php
helper("UnitTest_helper");

class RefreshUserCourseModelTest extends CIUnitTestCase
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
     * Checks that the returned user is the expected one
     */
    public function testgetUser()
    {
        // Gets the user with the user id 4
        $userCourseModel = model('UserCourseModel');
        $userId = TRAINER_DEV_ID;
        $user = $userCourseModel->getUser($userId);

        // Assertions
        $this->assertIsArray($user);
        $this->assertEquals($user['id'], $userId);
        $this->assertEquals($user['fk_user_type'], TRAINER_USER_TYPE);
        $this->assertEquals($user['username'], TRAINER_DEV_NAME);
        $this->assertEquals($user['password'],TRAINER_DEV_HASHED_PW);
        $this->assertEquals($user['archive'], TRAINER_DEV_ARCHIVE);
        $this->assertEquals($user['date_creation'], TRAINER_DEV_CREATION_DATE);
    }
}
