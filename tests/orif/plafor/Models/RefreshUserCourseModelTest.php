<?php
namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

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
    protected $seed     = 'apprenticeTestSeed';

    const TRAINER_USER_TYPE = 2;

    /**
     * Checks that the returned user is the expected one
     */
    public function testgetUser()
    {
        // Gets the user with the user id 4
        $userCourseModel = model('UserCourseModel');
        $userId = 4;
        $user = $userCourseModel->getUser($userId);

        // Assertions
        $this->assertIsArray($user);
        $this->assertEquals($user['id'], $userId);
        $this->assertEquals($user['fk_user_type'], self::TRAINER_USER_TYPE);
        $this->assertEquals($user['username'], 'FormateurDev');
        $this->assertEquals($user['password'],
            '$2y$10$Q3H8WodgKonQ60SIcu.eWuVKXmxqBw1X5hMpZzwjRKyCTB1H1l.pe');
        $this->assertEquals($user['archive'], NULL);
        $this->assertEquals($user['date_creation'], '2020-07-09 13:15:24');
    }
}
