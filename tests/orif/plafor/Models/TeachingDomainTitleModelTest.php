<?php

namespace Plafor\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class  TeachingDomainTitleModelTest extends CIUnitTestCase
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
     * Verifies the creation of a TeachingDomainTitleModel instance.
     */
    public function testTeachingDomainTitleModelInstance(): void
    {
        $teachingDomainTitleModel = model('TeachingDomainTitleModel');
        $this->assertTrue($teachingDomainTitleModel instanceof
            TeachingDomainTitleModel);
        $this->assertInstanceOf(TeachingDomainTitleModel::class,
            $teachingDomainTitleModel);
    }

    /**
     * Tests the retrieval of a single record by ID using the find method.
     */
    public function testFind(): void
    {
        $id = 1;
        $model = model('TeachingDomainTitleModel');
        $data = $model->find($id);
        $expect = [
            'id' => 1,
            'title' => 'Compétences de base élargies'
        ];
        $this->assertEquals($expect, $data);
    }

    /**
     * Tests the insertion of a new record using the insert method.
     */
    public function testInsert(): void
    {
        $model = model('TeachingDomainTitleModel');
        $domainTitle = [
            'title' => 'Compétence Sportive'
        ];
        $isSuccess = $model->insert($domainTitle, false);
        $this->assertTrue($isSuccess);
    }

}
