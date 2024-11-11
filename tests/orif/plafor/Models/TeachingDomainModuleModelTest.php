<?php

namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class TeachingDomainModuleModelTest extends CIUnitTestCase
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
     * Test that the TeachingDomainModuleModel instance is correctly created.
     */
    public function testTeachingDomainModuleModelInstance(): void
    {
        $teachingDomainModuleModel = model('TeachingDomainModuleModel');
        $this->assertTrue($teachingDomainModuleModel instanceof
            TeachingDomainModuleModel);
        $this->assertInstanceOf(TeachingDomainModuleModel::class,
            $teachingDomainModuleModel);
    }

    /**
     * Test that the find method returns the expected data for a given ID.
     */
    public function testFind(): void
    {
        $id = 1;
        $teachingDomainModuleModel = model('TeachingDomainModuleModel');
        $data = $teachingDomainModuleModel->find($id);
        $expect = [
            'id' => 1,
            'archive' => null,
            'teachingDomain' => [
                'id' => 7
            ],
            'teachingModule' => [
                'id'=> 1,
            ],
        ];
        $this->assertEquals($expect['id'], $data['id']);
        $this->assertEquals($expect['archive'], $data['archive']);
        $this->assertEquals($expect['teachingDomain']['id'],
            $data['teachingDomain']['id']);
    }

    /**
     * Tests that the existsLinkBetweenDomainAndModule method returns false
     * when the domain and module are not linked.
     *
     * @covers TeachingDomainModuleModel::existsLinkBetweenDomainAndModule
     */
    public function testUnlinkedDomainAndModuleReturnsFalse(): void
    {
        $model = model('TeachingDomainModuleModel');
        $isLinked = $model->existsLinkBetweenDomainAndModule(1, 1);
        $this->assertFalse($isLinked);
    }

    /**
     * Tests that the existsLinkBetweenDomainAndModule method returns true when
     * the domain and module are linked.
     *
     * @covers TeachingDomainModuleModel::existsLinkBetweenDomainAndModule
     */
    public function testLinkedDomainAndModuleReturnsTrue(): void
    {
        $model = model('TeachingDomainModuleModel');
        $isLinked = $model->existsLinkBetweenDomainAndModule(3, 7);
        $this->assertTrue($isLinked);
    }

    /**
     * Test that the insert method successfully inserts a new record.
     */
    public function testInsert(): void
    {
        $TeachingDomainModuleModel = model('TeachingDomainModuleModel');
        $DomainLinkedModule = [
            'fk_teaching_domain' => 1,
            'fk_teaching_module' => 1,
        ];
        $isSuccess = $TeachingDomainModuleModel->insert($DomainLinkedModule,
            false);
        $this->assertTrue($isSuccess);
    }

    /**
     * Tests that inserting an existing domain-module link fails.
     *
     * This test verifies that the TeachingDomainModuleModel does not allow
     * inserting a link between a domain and a module that already exists.
     */
    public function testInsertExistingDomainModuleLink(): void
    {
        $TeachingDomainModuleModel = model('TeachingDomainModuleModel');
        $DomainLinkedModule = [
            'fk_teaching_domain' => 3,
            'fk_teaching_module' => 7,
        ];
        $isSuccess = $TeachingDomainModuleModel->insert($DomainLinkedModule,
            false);
        $this->assertFalse($isSuccess);
    }

}
