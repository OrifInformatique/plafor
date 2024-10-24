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

helper("UnitTest_helper"); // The helper hold all Constants -> Plafor\orif\plafor\Helpers\UnitTest_helper.php

class TrainerApprenticeModelTest extends CIUnitTestCase
{

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
