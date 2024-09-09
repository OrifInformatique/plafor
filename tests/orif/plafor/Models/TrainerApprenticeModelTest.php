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
        $trainerApprenticeModel = model('TrainerApprenticeModel');
        $this->assertTrue($trainerApprenticeModel instanceof TrainerApprenticeModel);
        $this->assertInstanceOf(TrainerApprenticeModel::class, $trainerApprenticeModel);
    }



}
