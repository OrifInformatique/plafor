<?php
/**
 * Unit tests Grade in view_apprentice in apprentice controller
 *
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

 namespace Plafor\Controllers;

 use CodeIgniter\Test\CIUnitTestCase;
 # use CodeIgniter\Test\ControllerTestTrait;
 use CodeIgniter\Test\DatabaseTestTrait;

use CodeIgniter\Test\FeatureTestTrait;

use Plafor\Models;

// The helper hold all Constants
// -> Plafor\orif\plafor\Helpers\UnitTest_helper.php
helper("UnitTest_helper");

class FullGradeViewTest extends CIUnitTestCase
{
    # use ControllerTestTrait;
    use DatabaseTestTrait;

    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    protected $seedOnce = false;
    protected $basePath = 'tests/_support/Database';
    protected $seed     = 'FullGradeTestSeed';


    public function testViewGrade(): void
    {
        // Arrange
        $session['logged_in'] = true;
        $session['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;

        // fake user
        $session['user_id'] = 999;
        $apprenticeId = APPRENTICE_DEV_ID;
        $userCourseId = USER_COURSE_DEV_ID;
        $url = "plafor/apprentice/view_apprentice/$apprenticeId/$userCourseId";
        // Act
        $result = $this->withSession($session)->get($url);
        // Assert
        $result->assertSee(5.2);
        $result->assertSee(5.5);
        $result->assertSee(5.0);
        $result->assertSee(5.1);
        $result->assertSee(6);
        $result->assertSee(4.5);
        $result->assertSee(4);
        $result->assertSee(3.5);
        $result->assertSee(3.0);
        $result->assertSee(4.8);
        $result->assertSee("Mettre en œuvre des systèmes de codification, de compression et d’encryptage");
        $result->assertSee("Mettre en place l'infrastructure informatique d'une petite entreprise");
        $result->assertSee("Automatiser des procédures à l’aide de scripts");
        $result->assertSee("Analyser et modéliser des données");
        $result->assertSee("Créer des bases de données et y insérer des données");
        $result->assertSee("Utiliser des bases de données NoSQL");
        $result->assertSee("Implémenter la sécurité d'une application");
        $result->assertSee("Appliquer la protection et la sécurité des données");
        $result->assertSee("Initialiser des solutions ICT innovantes");
        $result->assertSee("Mettre en œuvre des solutions ICT innovantes");
        $result->assertSee("Décrire des processus métier dans son propre environnement professionnel");
        $result->assertSee("Créer et publier un site Web");
        $result->assertSee("Réaliser de petits projets dans son propre environnement professionnel");
        $result->assertSee("Concevoir et implémenter des applications");
        $result->assertSee("Programmer orienté objet");
        $result->assertSee("Programmer des systèmes distribués");
        $result->assertSee("Concevoir et implémenter des interfaces utilisateur");
        $result->assertSee("Programmer de manière fonctionnelle");
        $result->assertSee("Prendre en charge des processus DevOps avec des outils logiciels");
        $result->assertSee("Concevoir et réaliser des solutions cloud");
        $result->assertSee("Utiliser un service avec des conteneurs");
        $result->assertSee("Développer un logiciel avec des méthodes agiles");
        $result->assertSee("Exécuter des mandats demandés autonome dans son propre environnement professionnel");
        $result->assertSee("Tester des applications");
        $result->assertSee("Interroger, traiter et assurer la maintenance des bases de données");
        $result->assertSee("Analyser et représenter des données avec des outils");
        $result->assertSee("Mettre en service un poste de travail ICT avec le système d’exploitation");
        $result->assertSee("Développer des solutions ICT avec le machine learning");
        $result->assertSee("Réaliser le front-end d’une application Web interactive");
        $result->assertSee("Réaliser le back-end pour des applications");
        $result->assertSee("Réaliser une application pour mobile");
    }

    public function testArrayGrade(): void
    {
        // Arrange
        $userCourseId = USER_COURSE_DEV_ID;
        $expect['cfc_average'] = 4.9;
        $expect['modules']['school']['average'] = 5.2;
        $expect['modules']['non-school']['average'] = 5.5;
        $expect['modules']['average'] = 5.1;
        // math
        $expect['cbe']['subjects'][0]['average'] = 5.6;
        // english
        $expect['cbe']['subjects'][1]['average'] = 5.3;
        $expect['cbe']['average'] = 5.5;

        $expect['tpi_grade']['value'] = 4.4;
        $expect['ecg']['subjects'][0]['average'] = 4.8;
        $expect['ecg']['subjects'][1]['average'] = 5.0;
        $expect['ecg']['subjects'][2]['average'] = 5.5;
        $expect['ecg']['average'] = 5.1;
        // Act
        $gradeModel = model('GradeModel');
        $schoolReportData = $gradeModel->getSchoolReportData($userCourseId);
        // Assert
        $this->assertEquals($expect['cfc_average'],
            $schoolReportData['cfc_average']);

        $this->assertEquals($expect['modules']['school']['average'],
            $schoolReportData['modules']['school']['average']);

        $this->assertEquals($expect['modules']['non-school']['average'],
            $schoolReportData['modules']['non-school']['average']);

        $this->assertEquals($expect['modules']['average'],
            $schoolReportData['modules']['average']);

        $this->assertEquals($expect['cbe']['subjects'][0]['average'],
            $schoolReportData['cbe']['subjects'][0]['average']);

        $this->assertEquals($expect['cbe']['subjects'][1]['average'],
            $schoolReportData['cbe']['subjects'][1]['average']);

        $this->assertEquals($expect['cbe']['average'],
            $schoolReportData['cbe']['average']);

        $this->assertEquals($expect['tpi_grade']['value'],
            $schoolReportData['tpi_grade']['value']);

        $this->assertEquals($expect['ecg']['subjects'][0]['average'],
            $schoolReportData['ecg']['subjects'][0]['average']);

        $this->assertEquals($expect['ecg']['subjects'][1]['average'],
            $schoolReportData['ecg']['subjects'][1]['average']);

        $this->assertEquals($expect['ecg']['subjects'][2]['average'],
            $schoolReportData['ecg']['subjects'][2]['average']);

        $this->assertEquals($expect['ecg']['average'],
            $schoolReportData['ecg']['average']);
    }
}
