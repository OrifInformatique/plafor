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

    public function testTeachingDomainModelInstance(): void
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $this->assertTrue($teachingDomainModel instanceof
            TeachingDomainModel);
        $this->assertInstanceOf(TeachingDomainModel::class,
            $teachingDomainModel);
    }

    public function testFind(): void
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

    public function testFindAll(): void
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $data = $teachingDomainModel->findAll();
        $this->assertIsArray($data);
    }

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

    public function testFirstCustom(): void
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $data = $teachingDomainModel->select('domain_weight')->first();
        $expect = [
            'domain_weight' => 0.1,
        ];
        $this->assertEquals($expect, $data);
    }

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
}
