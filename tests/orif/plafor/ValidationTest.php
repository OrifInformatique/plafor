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

    use DatabaseTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'Plafor';

    protected function setUp(): void
    {
        parent::setUp();

    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test that the Trainer is NOT already linked to the Apprentice
     */
    public function test_ApprenticeAndTrainerAlreadyLinked() {

        // Create an instance
        $new_link = model('TrainerApprenticeModel');

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

    /**
     * Test if the is_module_xor_subject method returns true when the data
     * contains a subject.
     *
     * @test
     * @covers PlaforRules::is_module_xor_subject
     */
    public function testIsModuleXorSubjectWithSubject(): void
    {
        $moduleOrSubjectId = 1;
        $params = null;
        $data = [
            'fk_user_course' => 1,
            'fk_teaching_subject' => 1,
            'fk_teaching_module' => null,
            'date' => '2024-09-06',
            'grade' => 4,
            'is_school' => 1
        ];
        $validation = new PlaforRules;
        $result = $validation->is_module_xor_subject($moduleOrSubjectId,
            $params, $data);
        $this->assertTrue($result);
    }

    /**
     * Test if the is_module_xor_subject method returns true when the data
     * contains a module.
     *
     * @test
     * @covers PlaforRules::is_module_xor_subject
     */
    public function testIsModuleXorSubjectWithModule(): void
    {
        $moduleOrSubjectId = 1;
        $params = null;
        $data = [
            'fk_user_course' => 1,
            'fk_teaching_subject' => null,
            'fk_teaching_module' => 1,
            'date' => '2024-09-06',
            'grade' => 4,
            'is_school' => 1
        ];
        $validation = new PlaforRules;
        $result = $validation->is_module_xor_subject($moduleOrSubjectId,
            $params, $data);
        $this->assertTrue($result);
    }

    /**
     * Test if the is_module_xor_subject method returns false when the data
     * contains both a subject and a module.
     *
     * @test
     * @covers PlaforRules::is_module_xor_subject
     */
    public function testIsModuleXorSubjectWithTwo(): void
    {
        $moduleOrSubjectId = 1;
        $params = null;
        $data = [
            'fk_user_course' => 1,
            'fk_teaching_subject' => 1,
            'fk_teaching_module' => 1,
            'date' => '2024-09-06',
            'grade' => 4,
            'is_school' => 1
        ];
        $validation = new PlaforRules;
        $result = $validation->is_module_xor_subject($moduleOrSubjectId,
            $params, $data);
        $this->assertFalse($result);
    }

    /**
     * Test if the is_module_xor_subject method returns false when the data
     * contains neither a subject nor a module.
     *
     * @test
     * @covers PlaforRules::is_module_xor_subject
     */
    public function testIsModuleXorSubjectWithNone(): void
    {
        $moduleOrSubjectId = 1;
        $params = null;
        $data = [
            'fk_user_course' => 1,
            'fk_teaching_subject' => null,
            'fk_teaching_module' => null,
            'date' => '2024-09-06',
            'grade' => 4,
            'is_school' => 1
        ];
        $validation = new PlaforRules;
        $result = $validation->is_module_xor_subject($moduleOrSubjectId,
            $params, $data);
        $this->assertFalse($result);
    }


    /**
     * Tests that the is_boolean_or_binary_value method returns true for the
     * value 0.
     * @covers PlaforRules::is_boolean_or_binary_value
     */
    public function testIsBooleanOrBinaryValue_WithZero() :void
    {
        $data = 0;
        $validation = new PlaforRules;
        $result = $validation->is_boolean_or_binary_value($data);
        $this->assertTrue($result);
    }

    /**
     * Tests that the is_boolean_or_binary_value method returns true for the
     * string '1'.
     * @covers PlaforRules::is_boolean_or_binary_value
     */
    public function testIsBooleanOrBinaryValue_WithStringOne() :void
    {
        $data = '1';
        $validation = new PlaforRules;
        $result = $validation->is_boolean_or_binary_value($data);
        $this->assertTrue($result);
    }

    /**
     * Tests that the is_boolean_or_binary_value method returns true for the
     * value true.
     * @covers PlaforRules::is_boolean_or_binary_value
     */
    public function testIsBooleanOrBinaryValue_WithTrue() :void
    {
        $data = true;
        $validation = new PlaforRules;
        $result = $validation->is_boolean_or_binary_value($data);
        $this->assertTrue($result);
    }

    /**
     * Tests that the is_boolean_or_binary_value method returns true for the
     * value false.
     * @covers PlaforRules::is_boolean_or_binary_value
     */
    public function testIsBooleanOrBinaryValue_WithFalse() :void
    {
        $data = false;
        $validation = new PlaforRules;
        $result = $validation->is_boolean_or_binary_value($data);
        $this->assertTrue($result);
    }

    /**
     * Tests that the is_boolean_or_binary_value method returns false for an
     * invalid integer (3).
     * @covers PlaforRules::is_boolean_or_binary_value
     */
    public function testIsBooleanOrBinaryValue_WithInvalidInteger() :void
    {
        $data = 3;
        $validation = new PlaforRules;
        $result = $validation->is_boolean_or_binary_value($data);
        $this->assertFalse($result);
    }

    /**
     * Tests that the is_boolean_or_binary_value method returns false for an
     * empty string.
     * @covers PlaforRules::is_boolean_or_binary_value
     */
    public function testIsBooleanOrBinaryValue_WithEmptyString() :void
    {
        $data = '';
        $validation = new PlaforRules;
        $result = $validation->is_boolean_or_binary_value($data);
        $this->assertFalse($result);
    }

    /**
     * Tests that the is_boolean_or_binary_value method returns false for an
     * invalid string ('a').
     * @covers PlaforRules::is_boolean_or_binary_value
     */
    public function testIsBooleanOrBinaryValue_WithInvalidString() :void
    {
        $data = 'a';
        $validation = new PlaforRules;
        $result = $validation->is_boolean_or_binary_value($data);
        $this->assertFalse($result);
    }

    /**
     * Tests that the is_boolean_or_binary_value method returns false for an
     * array.
     * @covers PlaforRules::is_boolean_or_binary_value
     */
    public function testIsBooleanOrBinaryValue_WithInvalidArray() :void
    {
        $data = [];
        $validation = new PlaforRules;
        $result = $validation->is_boolean_or_binary_value($data);
        $this->assertFalse($result);
    }

}
