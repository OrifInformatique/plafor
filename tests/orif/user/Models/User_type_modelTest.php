<?php
/**
 * Unit / Integration tests User_type_modelTest
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace User\Models;

use CodeIgniter\Test\CIUnitTestCase;

class User_type_modelTest extends CIUnitTestCase
{
    /**
     * Asserts that getInstance method of User_type_model returns an instance of User_type_model
     */
    public function testgetUser_type_modelInstance()
    {
        $userTypeModel = User_type_model::getInstance();
        $this->assertTrue($userTypeModel instanceof User_type_model); 
        $this->assertInstanceOf(User_type_model::class, $userTypeModel);
    }

    /**
     * Asserts that getInstance method of User_type_model does not return an instance of User_model
     */
    public function testgetUser_modelInstance()
    {
        $userTypeModel = User_type_model::getInstance();
        $this->assertFalse($userTypeModel instanceof User_model);
    }
}