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
    // protected $seed     = 'apprenticeTestSeed';
    // protected $basePath = 'tests/_support/Database';

    /**
     * Verifies the creation of a TeachingSubjectModel instance.
     */
    public function testTeachingSubjectModelInstance()
    {
        $teachingSubjectModel = model('TeachingSubjectModel');
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
        $id = 1;
        $teachingSubjectModel = model('TeachingSubjectModel');
        $data = $teachingSubjectModel->find($id);
        $expect = [
            'id' => $id,
            'name' => "Mathématiques",
            'subject_weight' => "0.0",
            'archive' => null,
            'teaching_domain' => [
                'id' => "1",
                'fk_course_plan' => "5",
                'fk_teaching_domain_title' => "1",
                'domain_weight' => "0.1",
                'is_eliminatory' => "0",
                'archive' => null,
                'title' => "Compétences de base élargies",
                'course_plan_name' => 'Informaticienne / Informaticien avec '
                . 'CFC, orientation exploitation et infrastructure'
            ]
        ];

        $this->assertEquals($expect, $data);
    }

    /**
     * Test that the findAll method returns an array of data.
     */
    public function testFindAll()
    {
        $teachingSubjectModel = model('TeachingSubjectModel');
        $data = $teachingSubjectModel->findAll();
        $this->assertIsArray($data);
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(2, $data[1]['id']);
    }

    /**
     * Tests the retrieval of the first record using the first method.
     */
    public function testFirst()
    {
        $teachingSubjectModel = model('TeachingSubjectModel');
        $data = $teachingSubjectModel->first();
        $expect = [
            'id' => "1",
            'name' => "Mathématiques",
            'subject_weight' => "0.0",
            'archive' => null,
            'teaching_domain' => [
                'id' => "1",
                'fk_course_plan' => "5",
                'fk_teaching_domain_title' => "1",
                'domain_weight' => "0.1",
                'is_eliminatory' => "0",
                'archive' => null,
                'title' => "Compétences de base élargies",
                'course_plan_name' => 'Informaticienne / Informaticien avec '
                . 'CFC, orientation exploitation et infrastructure'
            ]
        ];
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the retrieval of the first record using the first method with a
     * select clause.
     */
    public function testFirstCustom()
    {
        $teachingSubjectModel = model('TeachingSubjectModel');
        $data = $teachingSubjectModel->select('subject_weight')->first();
        $expect = [
            'subject_weight' => '0.0'
        ];
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the insertion of a new record using the insert method.
     */
    public function testInsert()
    {
        $teachingSubjectModel = model('TeachingSubjectModel');
        $teachingSubject = [
            'fk_teaching_domain' => 1,
            'name' => 'test',
            'subject_weight' => 0.1,
        ];
        $isSuccess = $teachingSubjectModel->insert($teachingSubject, false);
        $this->assertTrue($isSuccess);
    }

    /**
     * Test that deleting a teaching subject removes it from the active
     * records.
     */
    public function testDelete(): void
    {
        $id = 1;
        $teachingSubjectModel = model('TeachingSubjectModel');
        $teachingSubjectModel->delete($id);
        $subject = $teachingSubjectModel->find($id);
        $deletedSubject = $teachingSubjectModel->withDeleted()->find($id);
        $this->assertNull($subject);
        $this->assertEquals($id, $deletedSubject['id']);
    }

    /**
     * Test that finding all teaching subjects with deleted records includes
     * the deleted records.
     */
    public function testFindAllWithDeleted(): void
    {
        $idToDelete = 1;
        $teachingSubjectModel = model('TeachingSubjectModel');
        $teachingSubjectModel->delete($idToDelete);
        $subjects = $teachingSubjectModel->withDeleted()->findAll();
        $this->assertEquals($subjects[0]['id'], $idToDelete);
    }

    /**
     * Test that finding all teaching subjects with deleted records includes
     * the deleted records.
     */
    public function testFindAllOnlyDeleted(): void
    {
        $idToDelete = 1;
        $teachingSubjectModel = model('TeachingSubjectModel');
        $teachingSubjectModel->delete($idToDelete);
        $subjects = $teachingSubjectModel->onlyDeleted()->findAll();
        $this->assertEquals($subjects[0]['id'], $idToDelete);
        $this->assertFalse(isset($subjects[1]));
    }

    /**
     * Test that finding all teaching subjects with only deleted records
     * returns only the deleted records.
     */
    public function testFindAllWithoutDeleted(): void
    {
        $idToDelete = 1;
        $teachingSubjectModel = model('TeachingSubjectModel');
        $teachingSubjectModel->delete($idToDelete);
        $subjects = $teachingSubjectModel->findAll();
        $this->assertNotEquals($subjects[0]['id'], $idToDelete);
        $this->assertTrue(isset($subjects[1]));
    }

    /**
     * Test that finding all teaching subjects without deleted records excludes
     * the deleted records.
     */
    public function testFindAllEqualsFindWithoutId(): void
    {
        $teachingSubjectModel = model('TeachingSubjectModel');
        $subjects = $teachingSubjectModel->findAll();
        $subjects2 = $teachingSubjectModel->find();
        $this->assertEquals($subjects, $subjects2);
    }

     public function testGetTeachingSubjectIdByDomain()
        {
            $teachingSubjectModel = model('TeachingSubjectModel');
            // Arrange
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

