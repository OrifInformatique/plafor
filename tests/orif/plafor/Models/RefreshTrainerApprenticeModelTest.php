<?php
/**
 * Unit / Integration tests TrainerApprenticeModelTest 
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
helper("UnitTest_helper"); // The helper hold all Constants -> Plafor\orif\plafor\Helpers\UnitTest_helper.php

class RefreshTrainerApprenticeModelTest extends CIUnitTestCase
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


    /** Checks that the returned user is a trainer. Actually, it depends on the
     * user id as the trainer is an user... In fact, the getTrainer method
     * returns an user and not always a trainer...
     */
    public function testgetTrainer()
    {
        // Gets the user (trainer...) with the user id 4 (who is a trainer...)
        $trainerApprenticeModel = model('TrainerApprenticeModel');
        $trainerId = TRAINER_DEV_ID;
        $trainer = $trainerApprenticeModel->getTrainer($trainerId);

        // Assertions
        $this->assertIsArray($trainer);
        $this->assertEquals($trainer['id'], $trainerId);
        $this->assertEquals($trainer['fk_user_type'], TRAINER_USER_TYPE);
        $this->assertEquals($trainer['username'], TRAINER_DEV_NAME);
        $this->assertEquals($trainer['password'], TRAINER_DEV_HASHED_PW);
        $this->assertEquals($trainer['archive'], TRAINER_DEV_ARCHIVE);
        $this->assertEquals($trainer['date_creation'], TRAINER_DEV_CREATION_DATE);
    }

    /**
     * Checks that the returned user is an apprentice. Actually, it depends on
     * the user id as the apprentice is an user... In fact, the getApprentice
     * method returns an user and not always an apprentice...
     */
    public function testgetApprentice()
    {
        // Gets the user (apprentice...) with the user id 6 (who is an
        // apprentice...)
        $trainerApprenticeModel = model('TrainerApprenticeModel');
        $apprenticeId = APPRENTICE_DEV_ID;
        $apprentice = $trainerApprenticeModel->getApprentice($apprenticeId);

        // Assertions
        $this->assertIsArray($apprentice);
        $this->assertEquals($apprentice['id'], $apprenticeId);
        $this->assertEquals($apprentice['fk_user_type'], APPRENTICE_USER_TYPE);
        $this->assertEquals($apprentice['username'], APPRENTICE_DEV_NAME);
        $this->assertEquals($apprentice['password'], APPRENTICE_DEV_HASHED_PW);
        $this->assertEquals($apprentice['archive'], APPRENTICE_DEV_ARCHIVE);
        $this->assertEquals($apprentice['date_creation'], APPRENTICE_DEV_CREATION_DATE);
    }

    /**
     * Checks that the list of returned apprentice ids is really a list of
     * apprentices
     */
    public function testgetApprenticeIdsFromTrainer()
    {
        // Gets the list of apprentice ids linked to a trainer id
        $trainerApprenticeModel = model('TrainerApprenticeModel');
        $apprenticeIds = $trainerApprenticeModel->getApprenticeIdsFromTrainer(TRAINER_SYS_ID);

        // Assertions
        $this->assertIsArray($apprenticeIds);

        foreach ($apprenticeIds as $apprenticeId)
        {
            $trainerApprenticeModel = model('TrainerApprenticeModel');
            $apprentice = $trainerApprenticeModel->getApprentice($apprenticeId);

            $this->assertEquals($apprentice['fk_user_type'], APPRENTICE_USER_TYPE);
        }
    }

}
