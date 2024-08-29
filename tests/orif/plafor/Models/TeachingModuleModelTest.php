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

    public function testTeachingModuleModelInstance()
    {
        $teachingModuleModel = model('TeachingModuleModel');
        $this->assertTrue($teachingModuleModel instanceof
            TeachingModuleModel);
        $this->assertInstanceOf(TeachingModuleModel::class,
            $teachingModuleModel);
    }
    public function testFind()
    {
        $teachingModuleModel = model('TeachingModuleModel');
        $data = $teachingModuleModel->find(1);
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

    public function testFindAll()
    {
        $teachingModuleModel = model('TeachingModuleModel');
        $data = $teachingModuleModel->findAll();
        $this->assertIsArray($data);
    }

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

    public function testInsert()
    {
        $teachingModuleModel = model('TeachingModuleModel');
        $teachingDomain = [
            'module_number' => '1',
            'official_name' => 'Modéliser',
            'version' => '4'
        ];
        $isSuccess = $teachingModuleModel->insert($teachingDomain, false);
        $this->assertTrue($isSuccess);
    }
}
