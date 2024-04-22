<?php
/**
 * Unit / Integration tests CommentModelTest 
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;

class CommentModelTest extends CIUnitTestCase
{
    /**
     * Asserts that getInstance method of CommentModel returns an instance of CommentModel
     */
    public function testgetCommentModelInstance()
    {
        $commentModel = CommentModel::getInstance();
        $this->assertTrue($commentModel instanceof CommentModel);
        $this->assertInstanceOf(CommentModel::class, $commentModel);
    }

    /**
     * Checks that the getTrainer method of CommentModel returns the expected trainer
     */
    public function testgetTrainer()
    {
        // Gets the trainer with the id 1
        $trainer = CommentModel::getTrainer(1);

        // Assertions
        $this->assertIsArray($trainer);
        $this->assertEquals($trainer['id'], 1);
        $this->assertEquals($trainer['fk_user_type'], 1);
        $this->assertEquals($trainer['username'], 'admin');
        $this->assertEquals($trainer['password'], '$2y$10$tUB5R1MGgbO.zD//WArnceTY8IgnFkVVsudIdHBxIrEXJ2z3WBvcK');
        $this->assertEquals($trainer['email'], '');
        $this->assertEquals($trainer['archive'], NULL);
        $this->assertEquals($trainer['date_creation'], '2020-07-09 08:11:05');
    }

    /**
     * Checks that the getAcquisitionStatus method of CommentModel returns the expected acquisition status
     */
    public function testgetAcquisitionStatus()
    {
        // Gets the acquisition status with the id 1
        $acquisitionStatus = CommentModel::getAcquisitionStatus(1);

        // Assertions
        $this->assertIsArray($acquisitionStatus);
        $this->assertEquals($acquisitionStatus['id'], 1);
        $this->assertEquals($acquisitionStatus['fk_objective'], 1);
        $this->assertEquals($acquisitionStatus['fk_user_course'], 1);
        $this->assertEquals($acquisitionStatus['fk_acquisition_level'], 1);
    }
}