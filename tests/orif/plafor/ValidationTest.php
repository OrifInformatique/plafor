<?php
/**
 * Unit tests PlaforRulesTest
 * 
 * @author      Orif (ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Validation;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

use Plafor\Validation\PlaforRules;
use Plafor\Models\TrainerApprenticeModel;


class PlaforRulesTest extends CIUnitTestCase {

    /**
     * Test that the Trainer is NOT already linked to the Apprentice
     */
    public function test_ApprenticeAndTrainerAlreadyLinked() {

        // Create an instance
        $new_link = TrainerApprenticeModel::getInstance();

        // Fake fk
        $trainer_id = 5;
        $apprentice_id = 5;
        $error = ""; // error is a reference, so need to be set before
    
        // Check if without data the result is CORRECT
        $validation = new PlaforRules;
        $correct_result = $validation->AreApprenticeAndTrainerNotLinked($trainer_id, $apprentice_id, null, $error);
        $this->assertTrue($correct_result);

        // Insert data in the DB
        $data = array(
            'fk_trainer' => $trainer_id,
            'fk_apprentice' => $apprentice_id
        );
        $new_link->insert($data);

        // Check if with the data the result is INCORRECT
        $validation = new PlaforRules;
        $wrong_result = $validation->AreApprenticeAndTrainerNotLinked($trainer_id, $apprentice_id, null, $error);
        $this->assertFalse($wrong_result);

        // Delete data
        $new_link->delete($new_link->getInsertID());
    }
}