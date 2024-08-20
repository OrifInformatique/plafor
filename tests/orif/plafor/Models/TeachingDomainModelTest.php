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
            'archive' => null,
            'fk_teaching_domain_title' => 1,
            'fk_course_plan' => 5
        ];
        $this->assertEquals($expect, $data);
    }

    public function testFindAll()
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $data = $teachingDomainModel->findAll();
        $this->assertIsArray($data);
    }

    public function testFirst()
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

    public function testFirstCustom()
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $data = $teachingDomainModel->select('domain_weight')->first();
        $expect = [
            'domain_weight' => 0.1,
        ];
        $this->assertEquals($expect, $data);
    }

    public function testInsert()
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $teachingDomain = [
            'domain_weight' => 0.1,
            'is_eliminatory' => 0,
        ];
        $isSuccess = $teachingDomainModel->insert($teachingDomain, false);
        $this->assertTrue($isSuccess);
    }
}
