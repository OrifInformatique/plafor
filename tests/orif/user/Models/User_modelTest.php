<?php
/**
 * Unit / Integration tests User_modelTest 
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace User\Models;

use CodeIgniter\Test\CIUnitTestCase;

class User_modelTest extends CIUnitTestCase
{
    const TRAINER_USER_TYPE = 2;
    const APPRENTICE_USER_TYPE = 3;

    /**
     * Asserts that getInstance method of User_model returns an instance of User_model
     */
    public function testgetUser_modelInstance()
    {
        $userModel = User_model::getInstance();
        $this->assertTrue($userModel instanceof User_model);
        $this->assertInstanceOf(User_model::class, $userModel);
    }

    /**
     * Asserts that getInstance method of User_model does not return an instance of User_type_model
     */
    public function testgetUser_type_modelInstance()
    {
        $userModel = User_model::getInstance();
        $this->assertFalse($userModel instanceof User_type_model);
    }

    /**
     * Tests that the check_password_name correctly checks the user password using the username
     */
    public function testcheck_password_name()
    {
        // Inserts user into database
        $userType = self::APPRENTICE_USER_TYPE;
        $username = 'ApprenticeUnitTest';
        $userPassword = 'ApprenticeUnitTestPassword';
        $userWrongPassword = 'ApprenticeUnitTestWrongPassword';
        
        $user = array(
            'fk_user_type' => $userType,
            'username' => $username,
            'password' => password_hash($userPassword, config('\User\Config\UserConfig')->password_hash_algorithm),
        );

        User_model::getInstance()->insert($user);

        // Checks user password using username (Assertion)
        $checkPasswordName = User_model::check_password_name($username, $userPassword);
        $this->assertTrue($checkPasswordName);

        // Checks wrong user password using username (Assertion)
        $checkPasswordName = User_model::check_password_name($username, $userWrongPassword);
        $this->assertFalse($checkPasswordName);

        // Deletes inserted user after assertions
        $userDb = User_model::getInstance()->where("username", $username)->first();
        User_model::getInstance()->delete($userDb['id'], TRUE);
    }

    /**
     * Tests that the check_password_name correctly checks the user password using the username when the user does not exist in the database
     */
    public function testcheck_password_nameWithNonExistingUser()
    {
        // Initialize non existing username and password
        $username = 'ApprenticeUnitTest';
        $userPassword = 'ApprenticeUnitTestPassword';

        // Checks user password using username (Assertion)
        $checkPasswordName = User_model::check_password_name($username, $userPassword);
        $this->assertFalse($checkPasswordName);
    }

    /**
     * Tests that the check_password_email correctly checks the user password using the user email
     */
    public function testcheck_password_email()
    {
        // Inserts user into database
        $userType = self::APPRENTICE_USER_TYPE;
        $username = 'ApprenticeUnitTest';
        $userEmail = 'apprenticeunittest@unittest.com';
        $userPassword = 'ApprenticeUnitTestPassword';
        $userWrongPassword = 'ApprenticeUnitTestWrongPassword';
        
        $user = array(
            'fk_user_type' => $userType,
            'username' => $username,
            'email' => $userEmail,
            'password' => password_hash($userPassword, config('\User\Config\UserConfig')->password_hash_algorithm),
        );

        User_model::getInstance()->insert($user);

        // Checks user password using user email address (Assertion)
        $checkPasswordEmail = User_model::check_password_email($userEmail, $userPassword);
        $this->assertTrue($checkPasswordEmail);

        // Checks wrong user password using user email address (Assertion)
        $checkPasswordEmail = User_model::check_password_email($userEmail, $userWrongPassword);
        $this->assertFalse($checkPasswordEmail);

        // Deletes inserted user after assertions
        $userDb=User_model::getInstance()->where("email", $userEmail)->first();
        User_model::getInstance()->delete($userDb['id'], TRUE);
    }

    /**
     * Tests that the check_password_email correctly checks the user password using the username when the user does not exist in the database
     */
    public function testcheck_password_emailWithInvalidEmail()
    {
        // Initialize invalid user email address and password
        $userEmail = 'apprenticeunittest';
        $userPassword = 'ApprenticeUnitTestPassword';

        // Checks user password using username (Assertion)
        $checkPasswordEmail = User_model::check_password_email($userEmail, $userPassword);
        $this->assertFalse($checkPasswordEmail);
    }

    /**
     * Tests that the check_password_email correctly checks the user password using the username when the user does not exist in the database
     */
    public function testcheck_password_emailWithNonExistingUserEmailAddress()
    {
        // Initialize non existing user email address and password
        $userEmail = 'apprenticeunittest@test.com';
        $userPassword = 'ApprenticeUnitTestPassword';

        // Checks user password using username (Assertion)
        $checkPasswordEmail = User_model::check_password_email($userEmail, $userPassword);
        $this->assertFalse($checkPasswordEmail);
    }

    /**
     * Tests that the get_access_level correctly returns the user access level
     */
    public function testget_access_level() {
        // Inserts user into database
        $userType = self::APPRENTICE_USER_TYPE;
        $username = 'ApprenticeUnitTest';
        $userEmail = 'apprenticeunittest@unittest.com';
        $userPassword = 'ApprenticeUnitTestPassword';
        
        $user = array(
            'fk_user_type' => $userType,
            'username' => $username,
            'email' => $userEmail,
            'password' => password_hash($userPassword, config('\User\Config\UserConfig')->password_hash_algorithm),
        );

        User_model::getInstance()->insert($user);

        // Gets user id
        $userDb=User_model::getInstance()->where("email", $userEmail)->first();

        // Gets user access level
        $userAccessLevel = User_model::get_access_level($userDb['id']);

        // Asserts that the new user has the guest access level
        $this->assertEquals($userAccessLevel, config("\User\Config\UserConfig")->access_lvl_guest);

        // Asserts that the new user does not have the administrator access level
        $this->assertNotEquals($userAccessLevel, config("\User\Config\UserConfig")->access_lvl_admin);

        // Deletes inserted user after assertions
        User_model::getInstance()->delete($userDb['id'], TRUE);
    }

    /**
     * Checks that the list of apprentices is an array containing only apprentices
     */
    public function testgetApprentices() {
        // Gets the list of apprentices
        $apprentices = User_model::getApprentices();

        // Asserts that the list of apprentices is an array
        $this->assertIsArray($apprentices);
        
        // For each apprentice, asserts that the user type is apprentice
        foreach ($apprentices as $apprentice) {
            $this->assertEquals($apprentice['fk_user_type'], self::APPRENTICE_USER_TYPE);
        }
    }

    /**
     * Checks that the list of apprentices with deleted ones is an array containing only apprentices
     */
    public function testgetApprenticesWithDeleted() {
        // Gets the list of all apprentices (including deleted)
        $apprentices = User_model::getApprentices(true);

        // Asserts that the list of apprentices is an array
        $this->assertIsArray($apprentices);
        
        // For each apprentice, asserts that the user type is apprentice
        foreach ($apprentices as $apprentice) {
            $this->assertEquals($apprentice['fk_user_type'], self::APPRENTICE_USER_TYPE);
        }
    }

    /**
     * Checks that the list of trainers is an array containing only trainers
     */
    public function testgetTrainers() {
        // Gets the list of trainers
        $trainers = User_model::getTrainers();

        // Asserts that the list of trainers is an array
        $this->assertIsArray($trainers);
        
        // For each trainer, asserts that the user type is trainer
        foreach ($trainers as $trainer) {
            $this->assertEquals($trainer['fk_user_type'], self::TRAINER_USER_TYPE);
        }
    }

    /**
     * Checks that the list of trainers with deleted ones is an array containing only trainers
     */
    public function testgetTrainersWithDeleted() {
        // Gets the list of all trainers (including deleted)
        $trainers = User_model::getTrainers(true);

        // Asserts that the list of trainers is an array
        $this->assertIsArray($trainers);
        
        // For each trainer, asserts that the user type is trainer
        foreach ($trainers as $trainer) {
            $this->assertEquals($trainer['fk_user_type'], self::TRAINER_USER_TYPE);
        }
    }
}