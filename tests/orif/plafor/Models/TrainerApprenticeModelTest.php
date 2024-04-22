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

class TrainerApprenticeModelTest extends CIUnitTestCase
{
    const TRAINER_USER_TYPE = 2;
    const APPRENTICE_USER_TYPE = 3;

    /**
     * Asserts that getInstance method of TrainerApprenticeModel returns an instance of TrainerApprenticeModel
     */
    public function testgetTrainerApprenticeModelInstance()
    {
        $trainerApprenticeModel = TrainerApprenticeModel::getInstance();
        $this->assertTrue($trainerApprenticeModel instanceof TrainerApprenticeModel);
        $this->assertInstanceOf(TrainerApprenticeModel::class, $trainerApprenticeModel);
    }

    /**
     * Checks that the returned user is a trainer
     * Actually, it depends on the user id as the trainer is an user...
     * In fact, the getTrainer method returns an user and not always a trainer...
     */
    public function testgetTrainer()
    {
        // Gets the user (trainer...) with the user id 2 (who is a trainer...)
        $trainer = TrainerApprenticeModel::getTrainer(2);

        // Assertions
        $this->assertIsArray($trainer);
        $this->assertEquals($trainer['id'], 2);
        $this->assertEquals($trainer['fk_user_type'], self::TRAINER_USER_TYPE);
        $this->assertEquals($trainer['username'], 'FormateurDev');
        $this->assertEquals($trainer['password'], '$2y$10$Q3H8WodgKonQ60SIcu.eWuVKXmxqBw1X5hMpZzwjRKyCTB1H1l.pe');
        $this->assertEquals($trainer['archive'], NULL);
        $this->assertEquals($trainer['date_creation'], '2020-07-09 13:15:24');
    }

    /**
     * Checks that the returned user is an apprentice
     * Actually, it depends on the user id as the apprentice is an user...
     * In fact, the getApprentice method returns an user and not always an apprentice...
     */
    public function testgetApprentice()
    {
        // Gets the user (apprentice...) with the user id 4 (who is an apprentice...)
        $apprentice = TrainerApprenticeModel::getApprentice(4);

        // Assertions
        $this->assertIsArray($apprentice);
        $this->assertEquals($apprentice['id'], 4);
        $this->assertEquals($apprentice['fk_user_type'], self::APPRENTICE_USER_TYPE);
        $this->assertEquals($apprentice['username'], 'ApprentiDev');
        $this->assertEquals($apprentice['password'], '$2y$10$6TLaMd5ljshybxANKgIYGOjY0Xur9EgdzcEPy1bgy2b8uyWYeVoEm');
        $this->assertEquals($apprentice['archive'], NULL);
        $this->assertEquals($apprentice['date_creation'], '2020-07-09 13:16:05');
    }

    /**
     * Checks that the list of returned apprentice ids is really a list of apprentices
     */
    public function testgetApprenticeIdsFromTrainer()
    {
        // Gets the list of apprentice ids linked to a trainer id
        $apprenticeIds = TrainerApprenticeModel::getApprenticeIdsFromTrainer(2);

        // Assertions
        $this->assertIsArray($apprenticeIds);

        foreach ($apprenticeIds as $apprenticeId)
        {
            $apprentice = TrainerApprenticeModel::getApprentice($apprenticeId);
            $this->assertEquals($apprentice['fk_user_type'], self::APPRENTICE_USER_TYPE);
        }
    }
}