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

    public function testTeachingSubjectModelInstance()
    {
        $teachingSubjectModel = model('TeachingSubjectModel');
        $this->assertTrue($teachingSubjectModel instanceof
            TeachingSubjectModel);
        $this->assertInstanceOf(TeachingSubjectModel::class,
            $teachingSubjectModel);
    }

    public function testFind()
    {
        $teachingSubjectModel = model('TeachingSubjectModel');
        $data = $teachingSubjectModel->find(1);
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

    public function testFindAll()
    {
        $teachingSubjectModel = model('TeachingSubjectModel');
        $data = $teachingSubjectModel->findAll();
        $this->assertIsArray($data);
    }

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

    public function testFirstCustom()
    {
        $teachingSubjectModel = model('TeachingSubjectModel');
        $data = $teachingSubjectModel->select('subject_weight')->first();
        $expect = [
            'subject_weight' => '0.0'
        ];
        $this->assertEquals($expect, $data);
    }

    public function testInsert()
    {
        $teachingSubjectModel = model('TeachingSubjectModel');
        $teachingDomain = [
            'fk_teaching_domain' => 1,
            'name' => 'test',
            'subject_weight' => 0.1,
        ];
        $isSuccess = $teachingSubjectModel->insert($teachingDomain, false);
        $this->assertTrue($isSuccess);
    }

}

