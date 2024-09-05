<?php

namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class TeachingDomainModelTest extends CIUnitTestCase
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
     * Test that the TeachingDomainModel instance is correctly created.
     */
    public function testTeachingDomainModelInstance(): void
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $this->assertTrue($teachingDomainModel instanceof
            TeachingDomainModel);
        $this->assertInstanceOf(TeachingDomainModel::class,
            $teachingDomainModel);
    }
 
    /**
     * Test that the find method returns the expected data for a given ID.
     */
    public function testFind(): void
    {
        $id = 1;
        $teachingDomainModel = model('TeachingDomainModel');
        $data = $teachingDomainModel->find($id);
        $expect = [
            'id' => $id,
            'title' => 'Compétences de base élargies',
            'course_plan_name' => 'Informaticienne / Informaticien avec CFC, '
            . 'orientation exploitation et infrastructure',
            'domain_weight' => 0.1,
            'is_eliminatory' => 0,
            'archive' => null,
            'fk_teaching_domain_title' => 1,
            'fk_course_plan' => 5
        ];
        $this->assertEquals($expect, $data);
    }


    /**
     * Test that the findAll method returns an array of data.
     */
    public function testFindAll(): void
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $data = $teachingDomainModel->findAll();
        $this->assertIsArray($data);
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(2, $data[1]['id']);
    }

    /**
     * Test that the first method returns the expected data for the first
     * record.
     */
    public function testFirst(): void
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $data = $teachingDomainModel->first();
        $expect = [
            'id' => 1,
            'title' => 'Compétences de base élargies',
            'course_plan_name' => 'Informaticienne / Informaticien avec CFC, '
            . 'orientation exploitation et infrastructure',
            'domain_weight' => 0.1,
            'is_eliminatory' => 0,
            'archive' => null,
            'fk_teaching_domain_title' => 1,
            'fk_course_plan' => 5
        ];
        $this->assertEquals($expect, $data);
    }

    /**
     * Test that the first method with a custom select returns the expected
     * data.
     */
    public function testFirstCustom(): void
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $data = $teachingDomainModel->select('domain_weight')->first();
        $expect = [
            'domain_weight' => 0.1,
        ];
        $this->assertEquals($expect, $data);
    }

    /**
     * Test that the insert method successfully inserts a new record.
     */
    public function testInsert(): void
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $teachingDomain = [
            'fk_teaching_domain_title' => 1,
            'fk_course_plan' => 1,
            'domain_weight' => 0.1,
            'is_eliminatory' => 1,
        ];
        $isSuccess = $teachingDomainModel->insert($teachingDomain, false);
        $this->assertTrue($isSuccess);
    }

    /**
     * Test that deleting a teaching domain removes it from the active records.
     */
    public function testDelete(): void
    {
        $id = 1;
        $teachingDomainModel = model('TeachingDomainModel');
        $teachingDomainModel->delete($id);
        $domain = $teachingDomainModel->find($id);
        $deletedDomain = $teachingDomainModel->withDeleted()->find($id);
        $this->assertNull($domain);
        $this->assertEquals($id, $deletedDomain['id']);
    }


    /**
     * Test that finding all teaching domains with deleted records includes the
     * deleted records.
     */
    public function testFindAllWithDeleted(): void
    {
        $idToDelete = 1;
        $teachingDomainModel = model('TeachingDomainModel');
        $teachingDomainModel->delete($idToDelete);
        $domains = $teachingDomainModel->withDeleted()->findAll();
        $this->assertEquals($domains[0]['id'], $idToDelete);
    }


    /**
     * Test that finding all teaching domains with only deleted records returns
     * only the deleted records.
     */
    public function testFindAllOnlyDeleted(): void
    {
        $idToDelete = 1;
        $teachingDomainModel = model('TeachingDomainModel');
        $teachingDomainModel->delete($idToDelete);
        $domains = $teachingDomainModel->onlyDeleted()->findAll();
        $this->assertEquals($domains[0]['id'], $idToDelete);
        $this->assertFalse(isset($domains[1]));
    }

    /**
     * Test that finding all teaching domains without deleted records excludes
     * the deleted records.
     */
    public function testFindAllWithoutDeleted(): void
    {
        $idToDelete = 1;
        $teachingDomainModel = model('TeachingDomainModel');
        $teachingDomainModel->delete($idToDelete);
        $domains = $teachingDomainModel->findAll();
        $this->assertNotEquals($domains[0]['id'], $idToDelete);
        $this->assertTrue(isset($domains[1]));
    }

    /**
     * Test that finding all teaching domains is equivalent to finding without
     * an ID.
     */
    public function testFindAllEqualsFindWithoutId(): void
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $domains = $teachingDomainModel->findAll();
        $domains2 = $teachingDomainModel->find();
        $this->assertEquals($domains, $domains2);
    }

}
