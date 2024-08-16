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

    public function testTeachingDomainModelInstance()
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $this->assertTrue($teachingDomainModel instanceof
            TeachingDomainModel);
        $this->assertInstanceOf(TeachingDomainModel::class,
            $teachingDomainModel);
    }

    public function testFind()
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $data = $teachingDomainModel->find(1);
        $expect = [
            'id' => 1,
            'title' => 'Compétences de base élargies',
            'course_plan_name' => 'Informaticienne / Informaticien avec CFC, '
            . 'orientation exploitation et infrastructure',
            'domain_weight' => 0.1,
            'is_eliminatory' => 0,
            'archive' => null
        ];
        $this->assertEquals($expect, $data);
    }
}
