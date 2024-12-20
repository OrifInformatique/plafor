<?php

namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class TeachingSubjectModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    // For Seeds
    // protected $seedOnce = false;
    // protected $seed     = 'ApprenticeTestSeed';
    // protected $basePath = 'tests/_support/Database';

    /**
     * Verifies the creation of a TeachingSubjectModel instance.
     */
    public function testTeachingSubjectModelInstance()
    {
        // Arrange
        // Act
        $teachingSubjectModel = model('TeachingSubjectModel');
        // Assert
        $this->assertTrue($teachingSubjectModel instanceof
            TeachingSubjectModel);
        $this->assertInstanceOf(TeachingSubjectModel::class,
            $teachingSubjectModel);
    }

    /**
     * Tests the retrieval of a single record by ID using the find method.
     */
    public function testFind()
    {
        // Arrange
        $id = 1;
        $teachingSubjectModel = model('TeachingSubjectModel');
        $expect = [
            'id' => $id,
            'name' => "Mathématiques",
            'subject_weight' => "0.00",
            'archive' => null,
            'teaching_domain' => [
                'id' => "1",
                'fk_course_plan' => "5",
                'fk_teaching_domain_title' => "1",
                'domain_weight' => "0.10",
                'is_eliminatory' => "0",
                'archive' => null,
                'title' => "Compétences de base élargies",
                'course_plan_name' => 'Informaticienne / Informaticien avec '
                . 'CFC, orientation exploitation et infrastructure',
                'round_multiple' => 0.5,
            ],
            'round_multiple' => 0.1,
        ];
        // Act
        $data = $teachingSubjectModel->find($id);
        // Assert
        $this->assertEquals($expect, $data);
    }

    /**
     * Test that the findAll method returns an array of data.
     */
    public function testFindAll()
    {
        // Arrange
        $teachingSubjectModel = model('TeachingSubjectModel');
        // Act
        $data = $teachingSubjectModel->findAll();
        // Assert
        $this->assertIsArray($data);
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(2, $data[1]['id']);
    }

    /**
     * Tests the retrieval of the first record using the first method.
     */
    public function testFirst()
    {
        // Arrange
        $teachingSubjectModel = model('TeachingSubjectModel');
        $expect = [
            'id' => "1",
            'name' => "Mathématiques",
            'subject_weight' => "0.00",
            'archive' => null,
            'teaching_domain' => [
                'id' => "1",
                'fk_course_plan' => "5",
                'fk_teaching_domain_title' => "1",
                'domain_weight' => "0.10",
                'is_eliminatory' => "0",
                'archive' => null,
                'title' => "Compétences de base élargies",
                'course_plan_name' => 'Informaticienne / Informaticien avec '
                . 'CFC, orientation exploitation et infrastructure',
                'round_multiple' => 0.5,
            ],
            'round_multiple' => 0.1,
        ];
        // Act
        $data = $teachingSubjectModel->first();
        // Assert
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the retrieval of the first record using the first method with a
     * select clause.
     */
    public function testFirstCustom()
    {
        // Arrange
        $teachingSubjectModel = model('TeachingSubjectModel');
        $expect = [
            'subject_weight' => '0.00'
        ];
        // Act
        $data = $teachingSubjectModel->select('subject_weight')->first();
        // Assert
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the insertion of a new record using the insert method.
     */
    public function testInsert()
    {
        // Arrange
        $teachingSubjectModel = model('TeachingSubjectModel');
        $teachingSubject = [
            'fk_teaching_domain' => 1,
            'name' => 'test',
            'subject_weight' => 0.1,
            'round_multiple' => 0.1,
        ];
        // Act
        $isSuccess = $teachingSubjectModel->insert($teachingSubject, false);
        // Assert
        $this->assertTrue($isSuccess);
    }

    /**
     * Test that deleting a teaching subject removes it from the active
     * records.
     */
    public function testDelete(): void
    {
        // Arrange
        $id = 1;
        $teachingSubjectModel = model('TeachingSubjectModel');
        // Act
        $teachingSubjectModel->delete($id);
        $subject = $teachingSubjectModel->find($id);
        $deletedSubject = $teachingSubjectModel->withDeleted()->find($id);
        // Assert
        $this->assertNull($subject);
        $this->assertEquals($id, $deletedSubject['id']);
    }

    /**
     * Test that finding all teaching subjects with deleted records includes
     * the deleted records.
     */
    public function testFindAllWithDeleted(): void
    {
        // Arrange
        $idToDelete = 1;
        $teachingSubjectModel = model('TeachingSubjectModel');
        // Act
        $teachingSubjectModel->delete($idToDelete);
        $subjects = $teachingSubjectModel->withDeleted()->findAll();
        // Assert
        $this->assertEquals($subjects[0]['id'], $idToDelete);
    }

    /**
     * Test that finding all teaching subjects with deleted records includes
     * the deleted records.
     */
    public function testFindAllOnlyDeleted(): void
    {
        // Arrange
        $idToDelete = 1;
        $teachingSubjectModel = model('TeachingSubjectModel');
        // Act
        $teachingSubjectModel->delete($idToDelete);
        $subjects = $teachingSubjectModel->onlyDeleted()->findAll();
        // Assert
        $this->assertEquals($subjects[0]['id'], $idToDelete);
        $this->assertFalse(isset($subjects[1]));
    }

    /**
     * Test that finding all teaching subjects with only deleted records
     * returns only the deleted records.
     */
    public function testFindAllWithoutDeleted(): void
    {
        // Arrange
        $idToDelete = 1;
        $teachingSubjectModel = model('TeachingSubjectModel');
        // Act
        $teachingSubjectModel->delete($idToDelete);
        $subjects = $teachingSubjectModel->findAll();
        // Assert
        $this->assertNotEquals($subjects[0]['id'], $idToDelete);
        $this->assertTrue(isset($subjects[1]));
    }

    /**
     * Test that finding all teaching subjects without deleted records excludes
     * the deleted records.
     */
    public function testFindAllEqualsFindWithoutId(): void
    {
        // Arrange
        $teachingSubjectModel = model('TeachingSubjectModel');
        // Act
        $subjects = $teachingSubjectModel->findAll();
        $subjects2 = $teachingSubjectModel->find();
        // Assert
        $this->assertEquals($subjects, $subjects2);
    }

     public function testGetTeachingSubjectIdByDomain()
    {
        // Arrange
        $teachingSubjectModel = model('TeachingSubjectModel');
        $domainId = 1;
        $expectedIds = [1, 2];
        // Act
        $result = $teachingSubjectModel
            ->getTeachingSubjectIdByDomain($domainId);

        // Assert
        $this->assertIsArray($result);
        $this->assertEquals($expectedIds, $result);
    }

}
