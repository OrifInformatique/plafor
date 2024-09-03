<?php

namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class TeachingModuleModelTest extends CIUnitTestCase
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
     * Verifies the creation of a TeachingModuleModel instance.
     */
    public function testTeachingModuleModelInstance()
    {
        $teachingModuleModel = model('TeachingModuleModel');
        $this->assertTrue($teachingModuleModel instanceof
            TeachingModuleModel);
        $this->assertInstanceOf(TeachingModuleModel::class,
            $teachingModuleModel);
    }

    /**
     * Tests the retrieval of a single record by ID using the find method.
     */
    public function testFind()
    {
        $id = 1;
        $teachingModuleModel = model('TeachingModuleModel');
        $data = $teachingModuleModel->find($id);
        $expect = [
            'id' => $id,
            'module_number' => '106',
            'official_name' => 'Interroger, traiter et assurer la maintenance '
            . 'des bases de données',
            'version' => '1',
            'archive' => null,
        ];
        foreach (array_keys($expect) as $key) {
            $this->assertEquals($expect[$key], $data[$key]);
        }
    }

    /**
     * Tests the retrieval of all records using the findAll method.
     */
    public function testFindAll()
    {
        $teachingModuleModel = model('TeachingModuleModel');
        $data = $teachingModuleModel->findAll();
        $this->assertIsArray($data);
        $this->assertEquals(1, $data[0]['id']);
        $this->assertEquals(2, $data[1]['id']);
    }

    /**
     * Tests the retrieval of the first record using the first method.
     */
    public function testFirst()
    {
        $teachingModuleModel = model('TeachingModuleModel');
        $data = $teachingModuleModel->first();
        $expect = [
            'id' => '1',
            'module_number' => '106',
            'official_name' => 'Interroger, traiter et assurer la maintenance '
            . 'des bases de données',
            'version' => '1',
            'archive' => null,
        ];
        foreach (array_keys($expect) as $key) {
            $this->assertEquals($expect[$key], $data[$key]);
        }
    }

    /**
     * Test that the first method with a custom select returns the expected
     * data.
     */
    public function testFirstCustom()
    {
        $teachingModuleModel = model('TeachingModuleModel');
        $data = $teachingModuleModel->select('official_name')->first();
        $expect = [
           'official_name' => 'Interroger, traiter et assurer la maintenance '
           . 'des bases de données'
        ];
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the insertion of a new record using the insert method.
     */
    public function testInsert()
    {
        $teachingModuleModel = model('TeachingModuleModel');
        $teachingModule = [
            'module_number' => '1',
            'official_name' => 'Modéliser',
            'version' => '4'
        ];
        $isSuccess = $teachingModuleModel->insert($teachingModule, false);
        $this->assertTrue($isSuccess);
    }

    public function testDelete(): void
    {
        $id = 1;
        $teachingModuleModel = model('TeachingModuleModel');
        $teachingModuleModel->delete($id);
        $module = $teachingModuleModel->find($id);
        $deletedModule = $teachingModuleModel->withDeleted()->find($id);
        $this->assertNull($module);
        $this->assertEquals($id, $deletedModule['id']);
    }
    public function testFindAllWithDeleted(): void
    {
        $idToDelete = 1;
        $teachingModuleModel = model('TeachingModuleModel');
        $teachingModuleModel->delete($idToDelete);
        $modules = $teachingModuleModel->withDeleted()->findAll();
        $this->assertEquals($modules[0]['id'], $idToDelete);
    }

    public function testFindAllOnlyDeleted(): void
    {
        $idToDelete = 1;
        $teachingModuleModel = model('TeachingModuleModel');
        $teachingModuleModel->delete($idToDelete);
        $modules = $teachingModuleModel->onlyDeleted()->findAll();
        $this->assertEquals($modules[0]['id'], $idToDelete);
        $this->assertFalse(isset($modules[1]));
    }
    public function testFindAllWithoutDeleted(): void
    {
        $idToDelete = 1;
        $teachingModuleModel = model('TeachingModuleModel');
        $teachingModuleModel->delete($idToDelete);
        $modules = $teachingModuleModel->findAll();
        $this->assertNotEquals($modules[0]['id'], $idToDelete);
        $this->assertTrue(isset($modules[1]));
    }
    public function testFindAllEqualsFindWithoutId(): void
    {
        $idToDelete = 1;
        $teachingModuleModel = model('TeachingModuleModel');
        $teachingModuleModel->delete($idToDelete);
        $modules = $teachingModuleModel->findAll();
        $modules2 = $teachingModuleModel->find();
        $this->assertEquals($modules, $modules2);
    }
}
