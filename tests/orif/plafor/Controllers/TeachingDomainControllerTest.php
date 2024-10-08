<?php
/**
 * Unit tests TeachingDomainControllerTest
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

 namespace Plafor\Controllers;

 use CodeIgniter\Test\CIUnitTestCase;
 use CodeIgniter\Test\ControllerTestTrait;
 use CodeIgniter\Test\DatabaseTestTrait;

 use Plafor\Models;

 class TeachingDomainControllerTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;

    const m_TRAINER_USER_TYPE = 2;
    const m_APPRENTICE_USER_TYPE = 3;

    protected $migrate     = true;
    protected $migrateOnce = true; // true if there is no modification in DB
    protected $refresh     = true;
    protected $namespace   = null;

    protected $seedOnce = false;
    protected $basePath = 'tests/_support/Database';
    protected $seed     = 'TeachingDomainControllerSeed';

    // Command to launch for ONLY this file: vendor/bin/phpunit tests/orif/plafor/Controllers/TeachingDomainControllerTest.php 

    // TODO: assert for check the User Session Access for domain title, domain, subject, module
    /**
     * Asserts that the getAllDomainsTitle page redirects to the 403 error view
     * when an apprentice session user access is set
     */
    public function test_getAllDomainsTitle_UserAccessApprentice() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute getAllDomainsTitle method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('getAllDomainsTitle');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

    /**
     * Asserts that the saveTeachingDomainTitle page redirects to the 403 error view
     * when an apprentice session user access is set
     */
    public function test_saveTeachingDomainTitle_UserAccessApprentice() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute saveTeachingDomainTitle method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('saveTeachingDomainTitle');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

        /**
     * Asserts that the saveTeachingDomainTitle page redirects to the 403 error view
     * when an Trainer session user access is set
     */
    public function test_saveTeachingDomainTitle_UserAccessTrainer() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute saveTeachingDomainTitle method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('saveTeachingDomainTitle');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

    /**
     * Asserts that the deleteTeachingDomainTitle page redirects to the 403 error view
     * when an apprentice session user access is set
     */
    public function test_deleteTeachingDomainTitle_UserAccessApprentice() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute deleteTeachingDomainTitle method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('deleteTeachingDomainTitle');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

        /**
     * Asserts that the deleteTeachingDomainTitle page redirects to the 403 error view
     * when an Trainer session user access is set
     */
    public function test_deleteTeachingDomainTitle_UserAccessTrainer() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute deleteTeachingDomainTitle method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('deleteTeachingDomainTitle');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

    /**
     * Asserts that the saveTeachingDomain page redirects to the 403 error view
     * when an apprentice session user access is set
     */
    public function test_saveTeachingDomain_UserAccessApprentice() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute saveTeachingDomain method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('saveTeachingDomain');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

        /**
     * Asserts that the saveTeachingDomain page redirects to the 403 error view
     * when an Trainer session user access is set
     */
    public function test_saveTeachingDomain_UserAccessTrainer() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute saveTeachingDomain method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('saveTeachingDomain');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

   /**
     * Asserts that the deleteTeachingDomain page redirects to the 403 error view
     * when an apprentice session user access is set
     */
    public function test_deleteTeachingDomain_UserAccessApprentice() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute deleteTeachingDomain method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('deleteTeachingDomain');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

        /**
     * Asserts that the deleteTeachingDomain page redirects to the 403 error view
     * when an Trainer session user access is set
     */
    public function test_deleteTeachingDomain_UserAccessTrainer() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute deleteTeachingDomain method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('deleteTeachingDomain');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

   /**
     * Asserts that the saveTeachingSubject page redirects to the 403 error view
     * when an apprentice session user access is set
     */
    public function test_saveTeachingSubject_UserAccessApprentice() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute saveTeachingSubject method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('saveTeachingSubject');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

        /**
     * Asserts that the saveTeachingSubject page redirects to the 403 error view
     * when an Trainer session user access is set
     */
    public function test_saveTeachingSubject_UserAccessTrainer() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute saveTeachingSubject method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('saveTeachingSubject');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

   /**
     * Asserts that the deleteTeachingSubject page redirects to the 403 error view
     * when an apprentice session user access is set
     */
    public function test_deleteTeachingSubject_UserAccessApprentice() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute deleteTeachingSubject method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('deleteTeachingSubject');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

        /**
     * Asserts that the deleteTeachingSubject page redirects to the 403 error view
     * when an Trainer session user access is set
     */
    public function test_deleteTeachingSubject_UserAccessTrainer() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute deleteTeachingSubject method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('deleteTeachingSubject');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

   /**
     * Asserts that the getAllTeachingModule page redirects to the 403 error view
     * when an apprentice session user access is set
     */
    public function test_getAllTeachingModule_UserAccessApprentice() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute getAllTeachingModule method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('getAllTeachingModule');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

   /**
     * Asserts that the saveTeachingModule page redirects to the 403 error view
     * when an apprentice session user access is set
     */
    public function test_saveTeachingModule_UserAccessApprentice() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute saveTeachingModule method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('saveTeachingModule');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

        /**
     * Asserts that the saveTeachingModule page redirects to the 403 error view
     * when an Trainer session user access is set
     */
    public function test_saveTeachingModule_UserAccessTrainer() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute saveTeachingModule method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('saveTeachingModule');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

   /**
     * Asserts that the deleteTeachingModule page redirects to the 403 error view
     * when an apprentice session user access is set
     */
    public function test_deleteTeachingModule_UserAccessApprentice() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute deleteTeachingModule method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('deleteTeachingModule');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

        /**
     * Asserts that the deleteTeachingModule page redirects to the 403 error view
     * when an Trainer session user access is set
     */
    public function test_deleteTeachingModule_UserAccessTrainer() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute deleteTeachingModule method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('deleteTeachingModule');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

   /**
     * Asserts that the saveTeachingModuleLink page redirects to the 403 error view
     * when an apprentice session user access is set
     */
    public function test_saveTeachingModuleLink_UserAccessApprentice() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute saveTeachingModuleLink method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('saveTeachingModuleLink');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

        /**
     * Asserts that the saveTeachingModuleLink page redirects to the 403 error view
     * when an Trainer session user access is set
     */
    public function test_saveTeachingModuleLink_UserAccessTrainer() {

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute saveTeachingModuleLink method of TeachingDomainController class
        $result = $this->controller(TeachingDomainController::class)
            ->execute('saveTeachingModuleLink');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }
}