<?php
/**
 * Unit tests CoursePlanTest
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

 class CoursePlanTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;

    const TRAINER_USER_TYPE = 2;
    const APPRENTICE_USER_TYPE = 3;

    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    protected $seedOnce = false;
    protected $basePath = 'tests/_support/Database';
    protected $seed     = 'apprenticeTestSeed';

    /**
     * Asserts that the save_course_plan page redirects to the 403 error view
     * when an apprentice session user access is set
     */
    public function testsave_course_planWithApprenticeSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute save_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_course_plan');

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
     * Asserts that the save_course_plan page redirects to the 403 error view
     * when a trainer session user access is set
     */
    public function testsave_course_planWithTrainerSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute save_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('save_course_plan');

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
     * Asserts that the save_course_plan page is loaded correctly when an
     * administrator session user access is set without course plan id
     */
    public function testsave_course_planWithAdministratorSessionUserAccessWithoutCoursePlanId()
    {
        // $_POST = array();
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute save_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_course_plan');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertSee('Ajouter un plan de formation', 'h1');
        $result->assertSeeElement('#course_plan_form');
        $result->assertSee('Numéro du plan de formation', 'label');
        $result->assertSeeInField('formation_number', '');
        $result->assertSee('Nom du plan de formation', 'label');
        $result->assertSeeInField('official_name', '');
        $result->assertSee('Date de création du plan de formation', 'label');
        $result->assertSeeInField('date_begin', '');
        $result->assertSeeInField('coursePlanId', '');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_course_plan page is loaded correctly when an
     * administrator session user access is set with a course plan id
     */
    public function testsave_course_planWithAdministratorSessionUserAccessWithCoursePlanId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        $coursePlanId = 6;
        // Execute save_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_course_plan', $coursePlanId);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertSee('Modifier le plan de formation', 'h1');
        $result->assertSeeElement('#course_plan_form');
        $result->assertSee('Numéro du plan de formation', 'label');
        $result->assertSeeInField('formation_number', '88611');
        $result->assertSee('Nom du plan de formation', 'label');
        $result->assertSeeInField('official_name', 'Informaticienne / Informaticien avec CFC, orientation développement d\'applications');
        $result->assertSee('Date de création du plan de formation', 'label');
        $result->assertSeeInField('date_begin', '2021-08-01');
        $result->assertSeeInField('coursePlanId', $coursePlanId);
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the delete_course_plan page redirects to the 403 error view
     * when an apprentice session user access is set
     */
    public function testdelete_course_planWithApprenticeSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute delete_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('delete_course_plan', 1);

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
     * Asserts that the delete_course_plan page redirects to the 403 error view
     * when a trainer session user access is set
     */
    public function testdelete_course_planWithTrainerSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;


        // Execute delete_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('delete_course_plan', 1);

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
     * Asserts that the delete_course_plan page is loaded correctly (confirmation message) when an administrator session user access is set (with action equals 0)
     */
    public function testdelete_course_planWithAdministratorSessionUserAccessAndActionToConfirm()
    {
        // Initialize session
        // (needed for delete_course_plan view)
        $_SESSION['_ci_previous_url'] = 'url';
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('delete_course_plan', 1, 0);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();

        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');

        $result->assertSee(lang('plafor_lang.title_manage_entry'), 'h1');
        $result->assertSee(lang('plafor_lang.manage_entry_confirmation'), 'p');

        $result->assertSee(lang('plafor_lang.course_plan'), 'strong');
        $result->assertSee('[2014-2020] Informaticienne, Informaticien avec '
            . 'CFC, orientation développement d\'applications', 'p');

        $result->assertDontSee(lang('plafor_lang.entries_linked_to_entry_being_managed'),
            'h2');
        $result->assertDontSeeElement('.alert-secondary');

        $result->assertSee(lang('plafor_lang.course_plan_disable_explanation'),
            '.alert alert-info');

        $result->assertSeeLink(lang('common_lang.btn_cancel'));
        $result->assertDontSeeElement('.btn-primary');
        $result->assertSeeLink(lang('common_lang.btn_disable'));
    }

    /**
     * Asserts that the delete_course_plan page is loaded correctly
     * (confirmation message) when an administrator session user access is set
     * (with action equals 0) for an archived course plan
     */
    public function _testdelete_course_planWithAdministratorSessionUserAccessAndActionToConfirmForAnArchivedCoursePlan()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for delete_course_plan view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;

        // Inserts a new archived course plan into database
        $coursePlanId = self::insertArchivedCoursePlan();

        // Insert a new user course
        $userCourseId = self::insertUserCourse($coursePlanId);

        // Execute delete_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_course_plan', $coursePlanId, 0);

        // Delete inserted user course
        $userCourseModel = model('\Plafor\Models\userCourseModel');
        $userCourseModel->delete($userCourseId, true);

        // Delete inserted archived course plan
        $coursePlanModel->delete($coursePlanId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();

        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Plan de formation \'Course Plan Unit Test\'', 'h1');

        $result->assertSee(lang('plafor_lang.title_manage_entry'), 'h1');
        $result->assertSee(lang('plafor_lang.manage_entry_confirmation'), 'p');

        $result->assertSee(lang('plafor_lang.course_plan'), 'strong');
        $result->assertSee('[2014-2020] Informaticienne, Informaticien avec '
            . 'CFC, orientation développement d\'applications', 'p');

        $result->assertSee(lang('plafor_lang.entries_linked_to_entry_being_managed'),
            'h2');
        $result->assertSee(lang('plafor_lang.apprentice'), '.alert-secondary');
        $result->assertSee('FormateurDev', '.alert-secondary');

        $result->assertSee(lang('plafor_lang.course_plan_enable_explanation'),
            '.alert alert-info');

        $result->assertSeeLink(lang('common_lang.btn_cancel'));
        $result->assertDontSeeElement('.btn-primary');
        $result->assertSeeLink(lang('common_lang.btn_reactivate'));
    }

    /**
     * Asserts that the delete_course_plan page redirects to the
     * list_course_plan view when an administrator session user access is set
     * (with a fake action)
     */
    public function testdelete_course_planWithAdministratorSessionUserAccessAndFakeAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_course_plan', 1, 9);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(
            base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the delete_course_plan page redirects to the
     * list_course_plan view when an administrator session user access is set
     * (with a non existing course plan)
     */
    public function testdelete_course_planWithAdministratorSessionUserAccessAndNonExistingCoursePlan()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_course_plan', 999999);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(
            base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the delete_course_plan page redirects to the
     * list_course_plan view when an administrator session user access is set
     * (with an  existing course plan and the enable action)
     */
    public function testdelete_course_planWithAdministratorSessionUserAccessAndExistingCoursePlanAndEnableAction()
    {
        $course_plan_id = 1;

        // Disable course plan
        $coursePlanModel = model('\Plafor\Models\coursePlanModel');
        $coursePlanModel->delete($course_plan_id, false);

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_course_plan', $course_plan_id, 3);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(
            base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the save_competence_domain page redirects to the 403 error
     * view when an apprentice session user access is set
     */
    public function testsave_competence_domainWithApprenticeSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute save_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_competence_domain');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee(
            'Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

    /**
     * Asserts that the save_competence_domain page redirects to the 403 error
     * view when a trainer session user access is set
     */
    public function testsave_competence_domainWitTrainerSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute save_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_competence_domain');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('403 - Accès refusé', 'h2');
        $result->assertSee(
            'Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p');
        $result->assertSeeLink('Retour');
    }

    /**
     * Asserts that the save_competence_domain page is loaded correctly when an
     * administrator session user access is set (no competenvce domain id and
     * course plan id)
     */
    public function testsave_competence_domainWitAdministratorSessionUserAccessWithoutCompetenceDomainIdAndCoursePlanId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;


        // Execute save_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_competence_domain');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
    }

    /**
     * Asserts that the save_competence_domain page is loaded correctly when an
     * administrator session user access is set (competence domain id and
     * course plan id are set)
     */
    public function testsave_competence_domainWitAdministratorSessionUserAccessWithCompetenceDomainIdAndCoursePlanId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute save_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_competence_domain', 1, 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Modifier le domaine de compétence', 'h1');
        $result->assertSeeElement('#competence_domain_form');
        $result->assertSee('Plan de formation lié au domaine de compétence',
            'label');
        $result->assertSeeElement('#course_plan');
        $result->assertSee('[2014-2020] Informaticienne, Informaticien avec '
            . 'CFC, orientation développement d\'applications', 'option');
        $result->assertSee('[2014-2020] Informaticienne, Informaticien avec '
            .'CFC, orientation informatique d\'entreprise', 'option');
        $result->assertSee('Symbole du domaine de compétence', 'label');
        $result->assertSeeElement('#competence_domain_symbol');
        $result->assertSee('Nom du domaine de compétence', 'label');
        $result->assertSeeElement('#competence_domain_name');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the delete_competence_domain page redirects to the 403 error view when an apprentice session user access is set
     */
    public function testdelete_competence_domainithApprenticeSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute delete_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_competence_domain');

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
     * Asserts that the delete_competence_domain page redirects to the 403 error view when a trainer session user access is set
     */
    public function testdelete_competence_domainWitTrainerSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute delete_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_competence_domain');

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
     * Asserts that the delete_competence_domain page redirects to the list_course_plan view when an administrator session user access is set (without competence domain id)
     */
    public function testdelete_competence_domainWitAdministratorSessionUserAccessWithoutCompetenceDomainId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_competence_domain');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the delete_competence_domain page redirects to the list_course_plan view when an administrator session user access is set (with a non existing competence domain id)
     */
    public function testdelete_competence_domainWitAdministratorSessionUserAccessWithNonExistingCompetenceDomainId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_competence_domain', 999999);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the delete_competence_domain page is loaded correctly when
     * an administrator session user access is set (with competence domain id
     * and no action)
     */
    public function testdelete_competence_domainWitAdministratorSessionUserAccessWithCompetenceDomainIdAndNoAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('delete_competence_domain', 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');

        $result->assertSee(lang('plafor_lang.title_manage_entry'), 'h1');
        $result->assertSee(lang('plafor_lang.manage_entry_confirmation'), 'p');

        $result->assertSee(lang('plafor_lang.competence_domain'), 'strong');
        $result->assertSee('Saisie, interprétation et mise en œuvre des '
            . 'exigences des applications', 'p');

        $result->assertDontSee(lang('plafor_lang.entries_linked_to_entry_being_managed'), 'h2');
        $result->assertDontSeeElement('.alert-secondary');

        $result->assertSee(lang('plafor_lang.competence_domain_disable_explanation'),
            '.alert alert-info');

        $result->assertSeeLink(lang('common_lang.btn_cancel'));
        $result->assertDontSeeElement('.btn-primary');
        $result->assertSeeLink(lang('common_lang.btn_disable'));
    }

    /**
     * Asserts that the delete_competence_domain page redirects to the view_course_plan view when an administrator session user access is set (with competence domain id and fake action)
     */
    public function testdelete_competence_domainWitAdministratorSessionUserAccessWithCompetenceDomainIdAndFakeAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_competence_domain', 1, 9);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/view_course_plan/1'));
    }

    /**
     * Asserts that the delete_competence_domain page redirects to the view_course_plan view when an administrator session user access is set (with competence domain id and enable action)
     */
    public function testdelete_competence_domainWitAdministratorSessionUserAccessWithCompetenceDomainIdAndEnableAction()
    {
        $competence_domain_id = 1;

        // Disable competence domain
        $competenceDomainModel = model('\Plafor\Models\competenceDomainModel');
        $competenceDomainModel->delete($competence_domain_id, false);

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_competence_domain', $competence_domain_id, 3);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/view_course_plan/1'));
    }

    /**
     * Asserts that the save_operational_competence page redirects to the 403 error view when an apprentice session user access is set
     */
    public function testsave_operational_competenceWithApprenticeSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute save_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('save_operational_competence');

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
     * Asserts that the save_operational_competence page redirects to the 403 error view when a trainer session user access is set
     */
    public function testsave_operational_competenceWitTrainerSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute save_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('save_operational_competence');

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
     * Asserts that the save_operational_competence page is redirect
     * when an administrator session user access is set (no operational
     * competence id)
     */
    public function testsave_operational_competenceWitAdministratorSessionUserAccessWithoutOperationalCompetenceId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute save_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_operational_competence');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
    }

    /**
     * Asserts that the save_operational_competence page is loaded correctly
     * when an administrator session user access is set (operational competence
     * id)
     */
    public function testsave_operational_competenceWitAdministratorSessionUserAccessWithOperationalCompetenceId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute save_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('save_operational_competence', 27);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        # $result->assertSee('Modifier la compétence opérationnelle', 'h1');
        $result->assertSeeElement('#operational_competence_form');
        $result->assertSee('Domaine de compétence lié à la compétence opérationnelle', 'label');
        $result->assertSeeElement('#competence_domain');
        $result->assertSee('Saisie, interprétation et mise en œuvre des exigences des applications', 'option');
        $result->assertSee('Symbole de la compétence opérationnelle', 'label');
        # $result->assertSeeInField('symbol', 'A1');
        $result->assertSee('Nom de la compétence opérationnelle', 'label');
        $result->assertSee('Compétence méthodologique', 'label');
        $result->assertSeeElement('#operational_competence_methodologic');
        $result->assertSeeElement('#operational_competence_social');
        $result->assertSeeElement('#operational_competence_personal');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the delete_operational_competence page redirects to the 403 error view when an apprentice session user access is set
     */
    public function testdelete_operational_competenceWithApprenticeSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute delete_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_operational_competence', 1);

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
     * Asserts that the delete_operational_competence page redirects to the 403 error view when a trainer session user access is set
     */
    public function testdelete_operational_competenceWitTrainerSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute delete_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_operational_competence', 1);

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
     * Asserts that the delete_operational_competence page is loaded correctly
     * when an administrator session user access is set (no action)
     */
    public function testdelete_operational_competenceWitAdministratorSessionUserAccessWithoutAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('delete_operational_competence', 108);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');

        $result->assertSee(lang('plafor_lang.title_manage_entry'), 'h1');
        $result->assertSee(lang('plafor_lang.manage_entry_confirmation'), 'p');

        $result->assertSee(lang('plafor_lang.operational_competence'), 'strong');
        $result->assertSee('Clarifier et documenter les besoins  des '
            . 'parties prenantes dans le cadre d’un projet ICT', 'p');

        $result->assertDontSee(lang('plafor_lang.entries_linked_to_entry_being_managed'), 'h2');
        $result->assertDontSeeElement('.alert-secondary');

        $result->assertSee(lang('plafor_lang.operational_competence_disable_explanation'),
            '.alert alert-info');

        $result->assertSeeLink(lang('common_lang.btn_cancel'));
        $result->assertDontSeeElement('.btn-primary');
        $result->assertSeeLink(lang('common_lang.btn_disable'));
    }

    /**
     * Asserts that the delete_operational_competence page redirects to the view_competence_domain view when an administrator session user access is set (fake action)
     */
    public function testdelete_operational_competenceWitAdministratorSessionUserAccessWithFakeAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_operational_competence', 1, 9);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/view_competence_domain/1'));
    }

    /**
     * Asserts that the delete_operational_competence page redirects to the view_competence_domain view when an administrator session user access is set (non existing operational competence)
     */
    public function testdelete_operational_competenceWitAdministratorSessionUserAccessForNonExistingOperationalCompetenceId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_operational_competence', 999999);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the delete_operational_competence page redirects to the view_competence_domain view when an administrator session user access is set (enable action)
     */
    public function testdelete_operational_competenceWitAdministratorSessionUserAccessWithEnableAction()
    {
        $operational_competence_id = 1;

        // Disable operational competence
        $operationalCompetenceModel = model('\Plafor\Models\operationalCompetenceModel');
        $operationalCompetenceModel->delete($operational_competence_id, false);

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_operational_competence', $operational_competence_id, 3);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/view_competence_domain/1'));
    }

    /**
     * Asserts that the delete_user_course page redirects to the 403 error view when an apprentice session user access is set
     */
    public function testdelete_user_courseWithApprenticeSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute delete_user_course method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_user_course', 1);

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
     * Asserts that the delete_user_course page redirects to the 403 error view when a trainer session user access is set
     */
    public function testdelete_user_courseWitTrainerSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = 1;

        // Execute delete_user_course method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('delete_user_course', 1);

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
     * Asserts that the delete_user_course page is loaded correctly when an
     * administrator session user access is set (no action)
     */
    public function testdelete_user_courseWitAdministratorSessionUserAccessAndNoAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_user_course method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('delete_user_course', 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');

        $result->assertSee(lang('plafor_lang.title_manage_entry'), 'h1');
        $result->assertSee(lang('plafor_lang.manage_entry_confirmation'), 'p');

        $result->assertSee(sprintf(lang('plafor_lang.course_plan_of'), 'ApprentiDev'),
            'strong');
        $result->assertSee('Informaticienne / Informaticien avec CFC, '
        . 'orientation développement d\'applications', 'p');
        $result->assertSee(lang('plafor_lang.status').' : '
            .lang('plafor_lang.title_in_progress'));

        $result->assertDontSee(lang('plafor_lang.entries_linked_to_entry_being_managed'),
        'h2');
        $result->assertDontSeeElement('.alert-secondary');

        $result->assertSee(lang('plafor_lang.user_course_delete_explanation'),
            '.alert alert-info');

        $result->assertSeeLink(lang('common_lang.btn_cancel'));
        $result->assertDontSeeElement('.btn-primary');
        $result->assertSeeLink(lang('common_lang.btn_delete'));
    }

    /**
     * Asserts that the delete_user_course page redirects to the list_apprentice view when an administrator session user access is set (fake action)
     */
    public function testdelete_user_courseWitAdministratorSessionUserAccessAndFakeAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_user_course method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_user_course', 1, 9);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the save_objective page redirects to the 403 error view when an apprentice session user access is set
     */
    public function testsave_objectiveWithApprenticeSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute save_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('save_objective');

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
     * Asserts that the save_objective page redirects to the 403 error view when a trainer session user access is set
     */
    public function testsave_objectiveWitTrainerSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute save_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('save_objective');

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
     * Asserts that the save_objective page is redirect when an
     * administrator session user access is set without objective_id
    */
    public function testsave_objectiveWitAdministratorSessionUserAccess()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute save_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_objective');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
    }

    /**
     * Asserts that the save_objective page is loaded correctly when an
     * administrator session user access is set (objective id is given)
     */
    public function testsave_objectiveWitAdministratorSessionUserAccessWithObjectiveId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute save_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_objective', 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Modifier l\'objectif', 'h1');
        $result->assertSeeElement('#objective_form');
        $result->assertSee('Compétence opérationnelle liée à l\'objectif', 'label');
        $result->assertSeeElement('#operational_competence');
        $result->assertSee('Analyser, structurer et documenter les exigences ainsi que les besoins', 'option');
        $result->assertSee('Symbole de l\'objectif', 'label');
        $result->assertSeeElement('#objective_symbol');
        $result->assertSeeInField('symbol', 'A.1.1');
        $result->assertSee('Taxonomie de l\'objectif', 'label');
        $result->assertSeeElement('#objective_taxonomy');
        $result->assertSeeInField('taxonomy', '4');
        $result->assertSee('Nom de l\'objectif', 'label');
        $result->assertSeeElement('#objective_name');
        $result->assertSeeInField('name', 'Enregistrer les besoins et discuter les solutions possibles, s’entretenir avec le client/supérieur sur les restrictions des exigences');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_objective page is loaded correctly when an administrator session user access is set (archived objective id is given)
     */
    public function _testsave_objectiveWitAdministratorSessionUserAccessWithArchivedObjectiveId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Insert a new archived objective into database
        $operationalCompetenceId = 1;
        $objectiveId = self::insertArchivedObjective($operationalCompetenceId);

        // Execute save_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('save_objective', $objectiveId);

        // Delete inserted archived objective
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $objectiveModel->delete($objectiveId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Modifier l\'objectif', 'h1');
        $result->assertSeeElement('#objective_form');
        $result->assertSee('Compétence opérationnelle liée à l\'objectif', 'label');
        $result->assertSeeElement('#operational_competence');
        $result->assertSee('Analyser, structurer et documenter les exigences ainsi que les besoins', 'option');
        $result->assertSee('Symboles de l\'objectif', 'label');
        $result->assertSeeElement('#objective_symbol');
        $result->assertSeeInField('symbol', 'ZZZZZZZZZZ');
        $result->assertSee('Taxonomie de l\'objectif', 'label');
        $result->assertSeeElement('#objective_taxonomy');
        $result->assertSeeInField('taxonomy', '99999');
        $result->assertSee('Nom de l\'objectif', 'label');
        $result->assertSeeElement('#objective_name');
        $result->assertSeeInField('name', 'Objective Unit Test');
        $result->assertSeeLink('Annuler');
        $result->assertSeeLink('Réactiver');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the delete_objective page redirects to the 403 error view when an apprentice session user access is set
     */
    public function testdelete_objectiveWithApprenticeSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['logged_in'] = true;

        // Execute delete_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_objective', 1);

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
     * Asserts that the delete_objective page redirects to the 403 error view when a trainer session user access is set
     */
    public function testdelete_objectiveWitTrainerSessionUserAccess()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url'; // (needed for 403 error view)
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['logged_in'] = true;

        // Execute delete_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_objective', 1);

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
     * Asserts that the delete_objective page is loaded correctly when an
     * administrator session user access is set
     */
    public function testdelete_objectiveWitAdministratorSessionUserAccess()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('delete_objective', 507);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');

        $result->assertSee(lang('plafor_lang.title_manage_entry'), 'h1');
        $result->assertSee(lang('plafor_lang.manage_entry_confirmation'), 'p');

        $result->assertSee(lang('plafor_lang.objective'), 'strong');
        $result->assertSee('Ils clarifient les objectifs du projet ICT et '
            . 'ses paramètres généraux tels que coûts, durée, qualité, '
            . 'périmètre, responsabilités et méthodologie.', 'p');

        $result->assertDontSee(lang('plafor_lang.entries_linked_to_entry_being_managed'), 'h2');
        $result->assertDontSeeElement('.alert-secondary');

        $result->assertSee(lang('plafor_lang.objective_disable_explanation'));

        $result->assertSeeLink(lang('common_lang.btn_cancel'));
        $result->assertDontSeeElement('.btn-primary');
        $result->assertSeeLink(lang('common_lang.btn_disable'));
    }

    /**
     * Asserts that the delete_objective page is loaded correctly when an
     * administrator session user access is set for an archived objective
     */
    public function _testdelete_objectiveWitAdministratorSessionUserAccessForAnArchivedObjective()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Insert a new archived objective into database
        $operationalCompetenceId = 1;
        $objectiveId = self::insertArchivedObjective($operationalCompetenceId);

        // Execute delete_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_objective', $objectiveId);

        // Delete inserted archived objective
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $objectiveModel->delete($objectiveId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();

        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');

        $result->assertSee(lang('plafor_lang.title_manage_entry'), 'h1');
        $result->assertSee(lang('plafor_lang.manage_entry_confirmation'), 'p');

        $result->assertSee(lang('plafor_lang.objective'), 'strong');
        $result->assertSee('Ils clarifient les objectifs du projet ICT et '
            . 'ses paramètres généraux tels que coûts, durée, qualité, '
            . 'périmètre, responsabilités et méthodologie.', 'p');

        $result->assertDontSee(lang('plafor_lang.entries_linked_to_entry_being_managed'), 'h2');
        $result->assertDontSeeElement('.alert-secondary');

        $result->assertSee(lang('plafor_lang.objective_enable_explanation'),
            '.alert alert-info');

        $result->assertSeeLink(lang('common_lang.btn_cancel'));
        $result->assertDontSeeElement('.btn-primary');
        $result->assertSeeLink(lang('common_lang.btn_reactivate'));
    }

    /**
     * Asserts that the delete_objective page redirects to the view_operational_competence view when an administrator session user access is set (fake action)
     */
    public function testdelete_objectiveWitAdministratorSessionUserAccessAndFakeAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_objective', 1, 9);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/view_operational_competence/1'));
    }

    /**
     * Asserts that the delete_objective page redirects to the view_operational_competence view when an administrator session user access is set (disable action)
     */
    public function testdelete_objectiveWitAdministratorSessionUserAccessAndDisableAction()
    {
        $objective_id = 1;

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('delete_objective', $objective_id, 1);

        // Enable objective
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $objectiveModel->withDeleted()
                       ->update($objective_id, ['archive' => null]);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/view_operational_competence/1'));
    }

    /**
     * Asserts that the delete_objective page redirects to the view_operational_competence view when an administrator session user access is set (enable action)
     */
    public function testdelete_objectiveWitAdministratorSessionUserAccessAndEnableAction()
    {
        $objective_id = 1;

        // Disable objective
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $objectiveModel->delete($objective_id, false);

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute delete_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_objective', $objective_id, 3);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(
            base_url('plafor/courseplan/view_operational_competence/1'));
    }

    /**
     * Asserts that the list_course_plan page is loaded correctly when no
     * apprentice id is given
     */
    public function testlist_course_planWithNoApprenticeId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Execute list_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('list_course_plan');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Liste des plans de formation', 'h1');
        $result->assertSeeLink('Nouveau');
        $result->assertSeeElement('#toggle_deleted');
        $result->assertSee('Afficher les éléments désactivés', 'label');
        $result->assertSee('Nom du plan de formation', 'th');
        # $result->assertSeeElement('#course_planslist');
        $result->assertSee('88601', 'td');
        $result->assertSee('88602', 'td');
        $result->assertSee('88603', 'td');
        $result->assertSee('88605', 'td');
        $result->assertSee('88611', 'td');        // Informaticienne / Informaticien Sys
        $result->assertSee('88611', 'td');        // Informaticienne / Informaticien Dev
        $result->assertSee('88614', 'td');
    }

    /**
     * Asserts that the list_course_plan page is loaded correctly when an
     * apprentice id is given
     */
    public function testlist_course_planWithApprenticeId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        // Execute list_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('list_course_plan', 6);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Liste des plans de formation', 'h1');
        $result->assertSeeLink('Nouveau');
        $result->assertSeeElement('#toggle_deleted');
        $result->assertSee('Afficher les éléments désactivés', 'label');
        $result->assertSee('Nom du plan de formation', 'th');
        # $result->assertSeeElement('#course_planslist');
        $result->assertSee('88611', 'td');
        $result->assertDontSee('88602', 'td');
        $result->assertDontSee('88603', 'td');
        $result->assertDontSee('88605', 'td');
        $result->assertDontSee('88601', 'td');
        $result->assertDontSee('88614', 'td');
    }

    /**
     * Asserts that the view_course_plan page redirects to the list_course_plan view when no course plan id is given
     */
    public function testview_course_planWithNoCoursePlanId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        // Execute view_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('view_course_plan');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the view_course_plan page redirects to the list_course_plan
     * view when a course plan id is given
     */
    public function testview_course_planWithCoursePlanId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        $_SERVER['QUERY_STRING'] = 'fake';

        // Execute view_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('view_course_plan', 6);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Détail du plan de formation', 'p');
        $result->assertSee('Informaticienne / Informaticien avec CFC, '
            . 'orientation développement d\'applications', 'p');
        $result->assertSee('No 88611, entré en vigueur le 01.08.2021', 'p');
        $result->assertSee('Domaines de compétences liés', 'p');
        $result->assertSeeLink('Nouveau');
        $result->assertSee('Symbole', 'th');
        $result->assertSee('Domaine de compétence', 'th');
        $result->assertSee('A', 'td');
        $result->assertSee('Suivi des projets ICT', 'td');
        $result->assertSee('B', 'td');
        $result->assertSee('Assistance et conseils dans l’environnement ICT',
            'td');
        $result->assertSee('C', 'td');
        $result->assertSee('Création et maintenance de données numériques',
            'td');
        $result->assertSee('G', 'td');
        $result->assertSee('Développement d’applications', 'td');
        $result->assertSee('H', 'td');
        $result->assertSee('Délivrance et fonctionnement des applications',
            'td');
    }

    /**
     * Asserts that the view_course_plan page redirects to the list_course_plan view when non existing course plan id is given
     */
    public function testview_course_planWithNonExistingCoursePlanId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        // Execute view_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('view_course_plan', 999999);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the view_competence_domain page redirects to the list_course_plan view when no competenvce domain id is given
     */
    public function testview_competence_domainWithNoCompetenceDomainId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        // Execute view_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('view_competence_domain');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the view_competence_domain page is loaded correctly when a
     * competence domain id is given
     */
    public function testview_competence_domainWithCompetenceDomainId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        $_SERVER['QUERY_STRING'] = 'fake';

        // Execute view_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('view_competence_domain', 27);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Détail du plan de formation', 'p');
        $result->assertSee('Informaticienne / Informaticien avec CFC, '
            . 'orientation développement d\'applications', 'p');
        $result->assertSee('No 88611, entré en vigueur le 01.08.2021', 'p');
        $result->assertSee('Détail du domaine de compétence', 'p');
        $result->assertSee('A : Suivi des projets ICT', 'p');
        $result->assertSee('Symbole', 'th');
        $result->assertSee('Compétence opérationnelle', 'th');
        $result->assertSee('A1', 'td');
        $result->assertSee('Clarifier et documenter les besoins  des '
            . 'parties prenantes dans le cadre d’un projet ICT', 'td');
        $result->assertSee('A2', 'td');
        $result->assertSee('Définir un modèle de procédure pour un projet ICT',
            'td');
        $result->assertSee('A3', 'td');
        $result->assertSee('Rechercher des informations sur des solutions '
            . 'ICT et sur les innovations', 'td');
    }

    /**
     * Asserts that the view_competence_domain page redirects to the list_course_plan view when non existing competenvce domain id is given
     */
    public function testview_competence_domainWithNonExistingCompetenceDomainId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        // Execute view_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('view_competence_domain', 999999);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the view_operational_competence page redirects to the
     * list_course_plan view when no operational competenvce id is given
     */
    public function testview_operational_competenceWithNoOperationalCompetenceId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        // Execute view_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('view_operational_competence');
        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(
            base_url('plafor/courseplan/list_course_plan') . '/');
    }

    /**
     * Asserts that the view_operational_competence page is loaded correctly
     * when an operational competenvce id is given
     */
    public function testview_operational_competenceWithOperationalCompetenceId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        $_SERVER['QUERY_STRING'] = 'fake';
        // Execute view_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('view_operational_competence', 108);
        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Détail du plan de formation', 'p');
        $result->assertSee('Numéro du plan de formation', 'p');
        $result->assertSeeLink('88611');
        $result->assertSeeLink('Informaticienne / Informaticien avec CFC, '
            . 'orientation développement d\'applications');
        $result->assertSee('Détail du domaine de compétence', 'p');
        $result->assertSee('Symbole du domaine de compétence', 'p');
        $result->assertSeeLink('A');
        $result->assertSee('Nom du domaine de compétence', 'p');
        $result->assertSeeLink('Suivi des projets ICT');
        $result->assertSee('Détail de la compétence opérationnelle', 'p');
        $result->assertSee('Symbole de la compétence opérationnelle', 'p');
        $result->assertSee('A1', 'p');
        $result->assertSee('Nom de la compétence opérationnelle', 'p');
        $result->assertSee('Clarifier et documenter les besoins  des parties '
            . 'prenantes dans le cadre d’un projet ICT', 'p');
        $result->assertSee('Compétence méthodologique', 'p');
        // $result->assertSee('Travail structuré, documentation adéquate', 'p');
        $result->assertSee('Compétence sociale', 'p');
        // $result->assertSee('Comprendre et sentir les problèmes du client, communication avec des partenaires', 'p');
        $result->assertSee('Compétence personnelle', 'p');
        // $result->assertSee('Fiabilité, autoréflexion, interrogation constructive du problème', 'p');
        $result->assertSee('Objectifs liés à la compétence opérationnelle',
            'p');
        // TODO fix typo in langfile
        // $result->assertSee('Symbole des objectifs', 'th');
        $result->assertSee('Taxonomie des objectifs', 'th');
        $result->assertSee('Nom des objectifs', 'th');
        $result->assertSee('A1.1', 'td');
        $result->assertSee('5', 'td');
        $result->assertSee('Ils appliquent diverses techniques '
            . 'd’audition et d’observation (p. ex. questions ouvertes, '
            . 'questions fermées, réunion, workshop, technique du '
            . 'shadowing, simulation de la solution à rechercher en '
            . 'opérant un saut dans le temps.', 'td');
    }

    /**
     * Asserts that the view_operational_competence page redirects to the
     * list_course_plan view when a non existing operational competenvce id is
     * given
     */
    public function testview_operational_competenceWithNonExistingOperationalCompetenceId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        // Execute view_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('view_operational_competence', 999999);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(
            base_url('plafor/courseplan/list_course_plan') . '/');
    }

    /**
     * Asserts that the view_objective page redirects to the list_course_plan view when no objective id is given
     */
    public function testview_objectiveWithNoObjectiveId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        // Execute view_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('view_objective');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the view_objective page is loaded correctly when an
     * objective id is given
     */
    public function testview_objectiveWithObjectiveId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        $_SERVER['QUERY_STRING'] = 'fake';
        // Execute view_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('view_objective', 507);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Détail du plan de formation', 'p');
        $result->assertSee('Numéro du plan de formation', 'p');
        $result->assertSeeLink('88611');
        $result->assertSeeLink('Informaticienne / Informaticien avec CFC, '
            . 'orientation développement d\'applications');
        $result->assertSee('Détail du domaine de compétence', 'p');
        $result->assertSee('Symbole du domaine de compétence', 'p');
        $result->assertSeeLink('A');
        $result->assertSee('Nom du domaine de compétence', 'p');
        $result->assertSeeLink('Suivi des projets ICT');
        $result->assertSee('Détail de la compétence opérationnelle', 'p');
        $result->assertSee('Symbole de la compétence opérationnelle', 'p');
        $result->assertSeeLink('A1');
        $result->assertSee('Nom de la compétence opérationnelle', 'p');
        $result->assertSeeLink('Clarifier et documenter les besoins  des '
            . 'parties prenantes dans le cadre d’un projet ICT');
        $result->assertSee('Compétence méthodologique', 'p');
        // $result->assertSeeLink('Travail structuré, documentation adéquate');
        $result->assertSee('Compétence sociale', 'p');
        // $result->assertSeeLink('Comprendre et sentir les problèmes du client, communication avec des partenaires');
        $result->assertSee('Compétence personnelle', 'p');
        // $result->assertSeeLink('Fiabilité, autoréflexion, interrogation constructive du problème');
        $result->assertSee('Détail de l\'objectif', 'p');
        $result->assertSee('Symbole de l\'objectif', 'p');
        $result->assertSee('A1.1', 'p');
        $result->assertSee('Taxonomie de l\'objectif', 'p');
        $result->assertSee('3', 'p');
        $result->assertSee('Nom de l\'objectif', 'p');
        $result->assertSee('Ils clarifient les objectifs du projet ICT et '
            . 'ses paramètres généraux tels que coûts, durée, qualité, '
            . 'périmètre, responsabilités et méthodologie.', 'p');
    }

    /**
     * Asserts that the view_objective page redirects to the list_course_plan view when a non existing objective id is given
     */
    public function testview_objectiveWithNonExistingObjectiveId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        // Execute view_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('view_objective', 999999);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the save_course_plan page redirects to list_course_plan when an administrator session user access is set with a posted new course plan
     */
    public function testsave_course_planPostedWithAdministratorSessionUserAccessWithPostedNewCoursePlan()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['coursePlanId'] = 0;
        $_REQUEST['coursePlanId'] = 0;
        $_POST['formation_number'] = 12345;
        $_REQUEST['formation_number'] = 12345;
        $_POST['official_name'] = 'Course Plan Unit Test';
        $_REQUEST['official_name'] = 'Course Plan Unit Test';
        $_POST['date_begin'] = '2023-04-05';
        $_REQUEST['date_begin'] = '2023-04-05';

        // Execute save_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('save_course_plan');

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Get course plan from database
        $coursePlanModel = model('\Plafor\Models\coursePlanModel');
        $coursePlanDb = $coursePlanModel->where("formation_number", 12345)
                                        ->first();
        // Delete inserted course plan
        $coursePlanModel->delete($coursePlanDb['id'], true);

         // Assertions
         $response = $result->response();
         $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
         $this->assertEmpty($response->getBody());
         $result->assertOK();
         $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
         $result->assertRedirectTo(base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the save_course_plan page is loaded with submitted data
     * when an administrator session user access is set with a posted new
     * course plan and an invalid formation number
     */
    public function testsave_course_planPostedWithAdministratorSessionUserAccessWithPostedNewCoursePlanAndInvalidFormationNumber()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        // Prepare the POST request (with an invalid course plan formation
        // number)
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['coursePlanId'] = 0;
        $_REQUEST['coursePlanId'] = 0;
        $_POST['formation_number'] = 12345678;
        $_REQUEST['formation_number'] = 12345678;
        $_POST['official_name'] = 'Course Plan Unit Test';
        $_REQUEST['official_name'] = 'Course Plan Unit Test';
        $_POST['date_begin'] = '2023-04-05';
        $_REQUEST['date_begin'] = '2023-04-05';
        $_POST['id'] = 0;
        $_REQUEST['id'] = 0;
        // Execute save_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_course_plan');
        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();
         // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertSee('Ajouter un plan de formation', 'h1');
        $result->assertSeeElement('#course_plan_form');
        $result->assertSee('Le champ Numéro du plan de formation ne peut pas dépasser une longueur de 5 caractères.', 'div');
        $result->assertSee('Numéro du plan de formation', 'label');
        $result->assertSeeInField('formation_number', '12345678');
        $result->assertSee('Nom du plan de formation', 'label');
        $result->assertSeeInField('official_name', 'Course Plan Unit Test');
        $result->assertSee('Date de création du plan de formation', 'label');
        $result->assertSeeInField('date_begin', '2023-04-05');
        $result->assertSeeInField('coursePlanId', '');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_course_plan page is loaded with submitted data
     * when an administrator session user access is set with a posted new
     * course plan and an existing formation number
     */
    public function testsave_course_planPostedWithAdministratorSessionUserAccessWithPostedNewCoursePlanAndExistingFormationNumber()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Prepare the POST request (with an existing course plan formation
        // number)
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['coursePlanId'] = 0;
        $_REQUEST['coursePlanId'] = 0;
        $_POST['formation_number'] = 88601;
        $_REQUEST['formation_number'] = 88601;
        $_POST['official_name'] = 'Course Plan Unit Test';
        $_REQUEST['official_name'] = 'Course Plan Unit Test';
        $_POST['date_begin'] = '2023-04-05';
        $_REQUEST['date_begin'] = '2023-04-05';
        $_POST['id'] = 0;
        $_REQUEST['id'] = 0;

        // Execute save_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_course_plan');

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

         // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertSee('Ajouter un plan de formation', 'h1');
        $result->assertSeeElement('#course_plan_form');
        $result->assertSee('Le numéro du plan de formation existe déjà', 'div');
        $result->assertSee('Numéro du plan de formation', 'label');
        $result->assertSeeInField('formation_number', '88601');
        $result->assertSee('Nom du plan de formation', 'label');
        $result->assertSee('Course Plan Unit Test');
        $result->assertSee('Date de création du plan de formation', 'label');
        $result->assertSeeInField('date_begin', '2023-04-05');
        $result->assertSeeInField('coursePlanId', '');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_course_plan page redirects to list_course_plan
     * when an administrator session user access is set with a posted existing
     * course plan
     */
    public function testsave_course_planPostedWithAdministratorSessionUserAccessWithPostedExistingCoursePlanId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Inserts a new course plan into database
        $coursePlanId = self::insertCoursePlan();

        // Prepare the POST request (to update the inserted course plan)
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['coursePlanId'] = $coursePlanId;
        $_REQUEST['coursePlanId'] = $coursePlanId;
        $_POST['formation_number'] = 12345;
        $_REQUEST['formation_number'] = 12345;
        $_POST['official_name'] = 'Course Plan Update Unit Test';
        $_REQUEST['official_name'] = 'Course Plan Update Unit Test';
        $_POST['date_begin'] = '2023-04-05';
        $_REQUEST['date_begin'] = '2023-04-05';
        $_POST['id'] = $coursePlanId;
        $_REQUEST['id'] = $coursePlanId;

        // Execute save_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_course_plan', $coursePlanId);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Delete inserted course plan
        $coursePlanModel = model('\Plafor\Models\coursePlanModel');
        $coursePlanModel->delete($coursePlanId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the save_competence_domain page redirects to
     * view_course_plan view when an administrator session user access is set
     * (competence domain id and course plan id are posted)
     */
    public function testsave_competence_domainPostedWitAdministratorSessionUserAccessWithPostedNewCompetenceDomainIdAndCoursePlanId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;
        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['id'] = 0;
        $_REQUEST['id'] = 0;
        $_POST['symbol'] = 'ZZZZZZZZZZ';
        $_REQUEST['symbol'] = 'ZZZZZZZZZZ';
        $_POST['name'] = 'Competence Domain Unit Test';
        $_REQUEST['name'] = 'Competence Domain Unit Test';
        $_POST['course_plan'] = 1;
        $_REQUEST['course_plan'] = 1;
        $_POST['type'] = 'competence_domain';
        // Execute save_competence_domain method of CoursePlan class (to insert
        // the competence domain)
        $result = $this->controller(CoursePlan::class)
            ->execute('save_competence_domain', 1);
        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();
        // Get competence domain from database
        $competenceDomainModel = model('\Plafor\Models\competenceDomainModel');
        $competenceDomainDb = $competenceDomainModel
            ->where("name", 'Competence Domain Unit Test')->first();

        // Delete inserted competence domain
        $competenceDomainModel->delete($competenceDomainDb['id'], true);
        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(
            base_url('plafor/courseplan/view_course_plan/1')
        );
    }

    /**
     * Asserts that the save_competence_domain page is loaded with submitted
     * data when an administrator session user access is set (competence domain
     * symbol is not valid)
     */
    public function testsave_competence_domainPostedWitAdministratorSessionUserAccessWithPostedNewCompetenceDomainAndInvalidSymbol()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['id'] = 0;
        $_REQUEST['id'] = 0;
        $_POST['symbol'] = 'ZZZZZZZZZZZ';
        $_REQUEST['symbol'] = 'ZZZZZZZZZZZ';
        $_POST['name'] = 'Competence Domain Unit Test';
        $_REQUEST['name'] = 'Competence Domain Unit Test';
        $_POST['course_plan'] = 1;
        $_REQUEST['course_plan'] = 1;

        // Execute save_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_competence_domain', 1);
        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertSee('Ajouter un domaine de compétence', 'h1');
        $result->assertSeeElement('#competence_domain_form');
        $result->assertSee('Le champ Symbole du domaine de compétence ne peut pas dépasser une longueur de 10 caractères.', 'div');
        $result->assertSee('Plan de formation lié au domaine de compétence', 'label');
        $result->assertSeeElement('#course_plan');
        $result->assertSee('Informaticienne / Informaticien avec CFC, orientation développement d\'applications', 'option');
        $result->assertSee('Symbole du domaine de compétence', 'label');
        $result->assertSeeElement('#competence_domain_symbol');
        $result->assertSee('Nom du domaine de compétence', 'label');
        $result->assertSeeElement('#competence_domain_name');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_competence_domain page is loaded with submitted data when an administrator session user access is set (competence domain already exists in the database)
     */
    public function _testsave_competence_domainPostedWitAdministratorSessionUserAccessWithPostedNewCompetenceDomainAlreadyExisting()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Insert a new competence domnain
        $coursePlanId = 1;
        $competenceDomainId = self::insertCompetenceDomain($coursePlanId);

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['id'] = 0;
        $_REQUEST['id'] = 0;
        $_POST['symbol'] = 'ZZZZZZZZZZ';
        $_REQUEST['symbol'] = 'ZZZZZZZZZZ';
        $_POST['name'] = 'Competence Domain Unit Test';
        $_REQUEST['name'] = 'Competence Domain Unit Test';
        $_POST['course_plan'] = $coursePlanId;
        $_REQUEST['course_plan'] = $coursePlanId;

        // Execute save_competence_domain method of CoursePlan class (to insert the already inserted competence domain)
        $result = $this->controller(CoursePlan::class)
        ->execute('save_competence_domain');

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Delete inserted competence domain
        $competenceDomainModel = model('\Plafor\Models\competenceDomainModel');
        $competenceDomainModel->delete($competenceDomainId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertSee('Ajouter un domaine de compétence', 'h1');
        $result->assertSeeElement('#competence_domain_form');
        $result->assertSee('Le domaine de compétences existe déjà', 'div');
        $result->assertSee('Plan de formation lié au domaine de compétence', 'label');
        $result->assertSeeElement('#course_plan');
        $result->assertSee(' Informaticien/-ne CFC Développement d\'applications', 'option');
        $result->assertSee(' Informaticien/-ne CFC Informatique d\'entreprise', 'option');
        $result->assertSee('Symbole du domaine de compétence', 'label');
        $result->assertSeeElement('#competence_domain_symbol');
        $result->assertSee('Nom du domaine de compétence', 'label');
        $result->assertSeeElement('#competence_domain_name');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_competence_domain page redirects to the view_course_plan view when an administrator session user access is set (updating an existing competence domain)
     */
    public function _testsave_competence_domainPostedWitAdministratorSessionUserAccessWithPostedExistingCompetenceDomain()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Insert a new competence domnain
        $coursePlanId = 1;
        $competenceDomainId = self::insertCompetenceDomain($coursePlanId);

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['id'] = $competenceDomainId;
        $_REQUEST['id'] = $competenceDomainId;
        $_POST['symbol'] = 'ZZZZZZZZZZ';
        $_REQUEST['symbol'] = 'ZZZZZZZZZZ';
        $_POST['name'] = 'Competence Domain Update Unit Test';
        $_REQUEST['name'] = 'Competence Domain Update Unit Test';
        $_POST['course_plan'] = $coursePlanId;
        $_REQUEST['course_plan'] = $coursePlanId;

        // Execute save_competence_domain method of CoursePlan class (to insert the already inserted competence domain)
        $result = $this->controller(CoursePlan::class)
        ->execute('save_competence_domain');

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Delete inserted competence domain
        $competenceDomainModel = model('\Plafor\Models\competenceDomainModel');
        $competenceDomainModel->delete($competenceDomainId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/view_course_plan/1'));
    }

    /**
     * Asserts that the save_operational_competence page redirects to the view_competence_domain view when an administrator session user access is set (inserting a new operational competence)
     */
    public function _testsave_operational_competencePostedWitAdministratorSessionUserAccessWithNewOperationalCompetence()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['id'] = 0;
        $_REQUEST['id'] = 0;
        $_POST['symbol'] = 'ZZZZZZZZZZ';
        $_REQUEST['symbol'] = 'ZZZZZZZZZZ';
        $_POST['name'] = 'Operational Competence Unit Test';
        $_REQUEST['name'] = 'Operational Competence Unit Test';
        $_POST['methodologic'] = 'Operational Competence Unit Test';
        $_REQUEST['methodologic'] = 'Operational Competence Unit Test';
        $_POST['social'] = 'Operational Competence Unit Test';
        $_REQUEST['social'] = 'Operational Competence Unit Test';
        $_POST['personal'] = 'Operational Competence Unit Test';
        $_REQUEST['personal'] = 'Operational Competence Unit Test';
        $_POST['competence_domain'] = 1;
        $_REQUEST['competence_domain'] = 1;

        // Execute save_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_operational_competence', 0, 1);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Get operational competence from database
        $operationalCompetenceModel = model('\Plafor\Models\operationalCompetenceModel');
        $operationalCompetenceDb = $operationalCompetenceModel
            ->where("name", 'Operational Competence Unit Test')->first();
        // Delete inserted operational competence
        $operationalCompetenceModel
            ->delete($operationalCompetenceDb['id'], true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/view_competence_domain/1'));
    }

    /**
     * Asserts that the save_operational_competence page is loaded correctly
     * with submitted data when an administrator session user access is set
     * (invalid symbol for the new operational competence)
     */
    public function testsave_operational_competencePostedWitAdministratorSessionUserAccessWithNewOperationalCompetenceAndInvalidSymbol()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['id'] = 0;
        $_REQUEST['id'] = 0;
        $_POST['symbol'] = 'ZZZZZZZZZZZ';
        $_REQUEST['symbol'] = 'ZZZZZZZZZZZ';
        $_POST['name'] = 'Operational Competence Unit Test';
        $_REQUEST['name'] = 'Operational Competence Unit Test';
        $_POST['methodologic'] = 'Operational Competence Unit Test';
        $_REQUEST['methodologic'] = 'Operational Competence Unit Test';
        $_POST['social'] = 'Operational Competence Unit Test';
        $_REQUEST['social'] = 'Operational Competence Unit Test';
        $_POST['personal'] = 'Operational Competence Unit Test';
        $_REQUEST['personal'] = 'Operational Competence Unit Test';
        $_POST['competence_domain'] = 1;
        $_REQUEST['competence_domain'] = 1;

        // Execute save_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('save_operational_competence', 1, 0);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertSee('Ajouter une compétence opérationnelle', 'h1');
        $result->assertSeeElement('#operational_competence_form');
        $result->assertSee('Le champ Symbole de la compétence opérationnelle ne peut pas dépasser une longueur de 10 caractères.', 'div');
        $result->assertSee('Domaine de compétence lié à la compétence opérationnelle', 'label');
        $result->assertSeeElement('#competence_domain');
        $result->assertSee('Saisie, interprétation et mise en œuvre des exigences des applications', 'option');
        $result->assertSee('Symbole de la compétence opérationnelle', 'label');
        $result->assertSeeInField('symbol', '');
        $result->assertSee('Nom de la compétence opérationnelle', 'label');
        $result->assertSeeInField('name', '');
        $result->assertSee('Compétence méthodologique', 'label');
        $result->assertSeeElement('#operational_competence_methodologic');
        $result->assertSee('Compétence sociale', 'label');
        $result->assertSeeElement('#operational_competence_social');
        $result->assertSee('Compétence personnelle', 'label');
        $result->assertSeeElement('#operational_competence_personal');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_operational_competence page redirects to the
     * view_competence_domain view when an administrator session user access is
     * set (update an existing operational competence)
     */
    public function testsave_operational_competencePostedWitAdministratorSessionUserAccessWithPostedExistingOperationalCompetence()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Insert a new operational competence
        $competenceDomainId = 1;
        $operationalCompetenceId = self::insertOperationalCompetence(
            $competenceDomainId);

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['id'] = $operationalCompetenceId;
        $_REQUEST['id'] = $operationalCompetenceId;
        $_POST['symbol'] = 'ZZZZZZZZZZ';
        $_REQUEST['symbol'] = 'ZZZZZZZZZZ';
        $_POST['name'] = 'Operational Competence Update Unit Test';
        $_REQUEST['name'] = 'Operational Competence Update Unit Test';
        $_POST['methodologic'] = 'Operational Competence Update Unit Test';
        $_REQUEST['methodologic'] = 'Operational Competence Update Unit Test';
        $_POST['social'] = 'Operational Competence Update Unit Test';
        $_REQUEST['social'] = 'Operational Competence Update Unit Test';
        $_POST['personal'] = 'Operational Competence Update Unit Test';
        $_REQUEST['personal'] = 'Operational Competence Update Unit Test';
        $_POST['competence_domain'] = $competenceDomainId;
        $_REQUEST['competence_domain'] = $competenceDomainId;

        // Execute save_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_operational_competence', $competenceDomainId,
                $operationalCompetenceId);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Delete inserted operational competence
        $operationalCompetenceModel = model('\Plafor\Models\operationalCompetenceModel');
        $operationalCompetenceModel->delete($operationalCompetenceId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(
            base_url('plafor/courseplan/view_competence_domain/1'));
    }

    /**
     * Asserts that the save_objective page redirects to the
     * view_operational_competence view when an administrator session user
     * access is set (inserting a new objective)
     */
    public function testsave_objectivePostedWitAdministratorSessionUserAccessWithNewObjective()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['id'] = 0;
        $_REQUEST['id'] = 0;
        $_POST['symbol'] = 'ZZZZZZZZZZ';
        $_REQUEST['symbol'] = 'ZZZZZZZZZZ';
        $_POST['name'] = 'Objective Unit Test';
        $_REQUEST['name'] = 'Objective Unit Test';
        $_POST['taxonomy'] = 99999;
        $_REQUEST['taxonomy'] = 99999;
        $_POST['operational_competence'] = 1;
        $_REQUEST['operational_competence'] = 1;

        $_POST['type'] = 'objective';

        // Execute save_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_objective', 0, 1);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Get objective from database
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $objectiveDb = $objectiveModel->where("name", 'Objective Unit Test')
                                      ->first();
        $objectiveId = $objectiveDb['id'];

        // Delete automatically inserted acquisition statuses for the inserted
        // objective
        $acquisitionStatusModel = model('\Plafor\Models\acquisitionStatusModel');
        $acquisitionStatusModel->where('fk_objective ', $objectiveId)
                               ->delete();

        // Delete inserted objective
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $objectiveModel->delete($objectiveId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(
            base_url('plafor/courseplan/view_operational_competence/1'));
    }

    /**
     * Asserts that the save_objective page is loaded correctly with submitted
     * data when an administrator session user access is set (inserting a new
     * objective with an invalid symbol)
     */
    public function testsave_objectivePostedWitAdministratorSessionUserAccessWithNewObjectiveAndInvalidSymbol()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['id'] = 0;
        $_REQUEST['id'] = 0;
        $_POST['symbol'] = 'ZZZZZZZZZZZ';
        $_REQUEST['symbol'] = 'ZZZZZZZZZZZ';
        $_POST['name'] = 'Objective Unit Test';
        $_REQUEST['name'] = 'Objective Unit Test';
        $_POST['taxonomy'] = 99999;
        $_REQUEST['taxonomy'] = 99999;
        $_POST['operational_competence'] = 1;
        $_REQUEST['operational_competence'] = 1;

        // Execute save_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
            ->execute('save_objective', 0, 1);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertSee('Ajouter un objectif', 'h1');
        $result->assertSeeElement('#objective_form');
        $result->assertSee('Le champ Symbole de l\'objectif ne peut pas dépasser une longueur de 10 caractères.', 'div');
        $result->assertSee('Compétence opérationnelle liée à l\'objectif', 'label');
        $result->assertSeeElement('#operational_competence');
        $result->assertSee('Analyser, structurer et documenter les exigences ainsi que les besoins', 'option');
        $result->assertSee('Symbole de l\'objectif', 'label');
        $result->assertSeeElement('#objective_symbol');
        $result->assertSeeInField('symbol', '');
        $result->assertSee('Taxonomie de l\'objectif', 'label');
        $result->assertSeeElement('#objective_taxonomy');
        $result->assertSeeInField('taxonomy', '');
        $result->assertSee('Nom de l\'objectif', 'label');
        $result->assertSeeElement('#objective_name');
        $result->assertSeeInField('name', '');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_objective page redirects to the view_operational_competence view when an administrator session user access is set (updating an existing objective)
     */
    public function testsave_objectivePostedWitAdministratorSessionUserAccessWithExistingObjective()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Insert a new objective into database
        $operationalCompetenceId = 1;
        $objectiveId = self::insertObjective($operationalCompetenceId);

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['id'] = $objectiveId;
        $_REQUEST['id'] = $objectiveId;
        $_POST['symbol'] = 'ZZZZZZZZZZ';
        $_REQUEST['symbol'] = 'ZZZZZZZZZZ';
        $_POST['name'] = 'Objective Update Unit Test';
        $_REQUEST['name'] = 'Objective Update Unit Test';
        $_POST['taxonomy'] = 99999;
        $_REQUEST['taxonomy'] = 99999;
        $_POST['operational_competence'] = $operationalCompetenceId;
        $_REQUEST['operational_competence'] = $operationalCompetenceId;

        // Execute save_objective method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('save_objective', $objectiveId, $operationalCompetenceId);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Delete inserted objective
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $objectiveModel->delete($objectiveId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/view_operational_competence/1'));
    }

    /**
     * Asserts that the delete_objective page redirects to the view_operational_competence view when an administrator session user access is set (delete action)
     */
    public function testdelete_objectiveWitAdministratorSessionUserAccessAndDeleteAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Insert a new objective into database
        $operationalCompetenceId = 1;
        $objectiveId = self::insertObjective($operationalCompetenceId);

        // Execute delete_objective method of CoursePlan class (to delete the inserted objective)
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_objective', $objectiveId, 2);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/view_operational_competence/1'));
    }

    /**
     * Asserts that the delete_operational_competence page redirects to the view_competence_domain view when an administrator session user access is set (disable action)
     */
    public function testdelete_operational_competenceWitAdministratorSessionUserAccessWithDisableAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Insert a new operational competence
        $competenceDomainId = 1;
        $operationalCompetenceId = self::insertOperationalCompetence($competenceDomainId);

        // Execute delete_operational_competence method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_operational_competence', $operationalCompetenceId, 1);

        // Delete disabled operational competence
        $operationalCompetenceModel = model('\Plafor\Models\operationalCompetenceModel');
        $operationalCompetenceModel->delete($operationalCompetenceId,
            true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/view_competence_domain/1'));
    }

    /**
     * Asserts that the delete_competence_domain page redirects to the view_course_plan view when an administrator session user access is set (with competence domain id and disable action)
     */
    public function testdelete_competence_domainWitAdministratorSessionUserAccessWithCompetenceDomainIdAndDisableAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Insert a new competence domain
        $coursePlanId = 1;
        $competenceDomainId = self::insertCompetenceDomain($coursePlanId);

        // Insert a new operational competence linked to the inserted competence domain
        $operationalCompetenceId = self::insertOperationalCompetence($competenceDomainId);

        // Insert a new objective linked to the inserted operational competence
        $objectiveId = self::insertObjective($operationalCompetenceId);

        // Execute delete_competence_domain method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_competence_domain', $competenceDomainId, 1);

        // Delete disabled objective
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $objectiveModel->delete($objectiveId, true);

        // Delete disabled operational competence
        $operationalCompetenceModel = model('\Plafor\Models\operationalCompetenceModel');
        $operationalCompetenceModel->delete($operationalCompetenceId, true);

        // Delete disabled competence domain
        $competenceDomainModel = model('\Plafor\Models\competenceDomainModel');
        $competenceDomainModel->delete($competenceDomainId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/view_course_plan/1'));
    }

    /**
     * Asserts that the delete_course_plan page redirects to the list_course_plan view when an administrator session user access is set (with an existing course plan and the disable action)
     */
    public function testdelete_course_planWithAdministratorSessionUserAccessAndExistingCoursePlanAndDisableAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Insert a new course plan
        $coursePlanId = self::insertCoursePlan();

        // Insert a new competence domain
        $competenceDomainId = self::insertCompetenceDomain($coursePlanId);

        // Insert a new operational competence linked to the inserted competence domain
        $operationalCompetenceId = self::insertOperationalCompetence($competenceDomainId);

        // Insert a new objective linked to the inserted operational competence
        $objectiveId = self::insertObjective($operationalCompetenceId);

        // Execute delete_course_plan method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_course_plan', $coursePlanId, 1);

        // Delete disabled objective
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $objectiveModel->delete($objectiveId, true);

        // Delete disabled operational competence
        $operationalCompetenceModel = model('\Plafor\Models\operationalCompetenceModel');
        $operationalCompetenceModel->delete($operationalCompetenceId, true);

        // Delete disabled competence domain
        $competenceDomainModel = model('\Plafor\Models\competenceDomainModel');
        $competenceDomainModel->delete($competenceDomainId, true);

        // Delete disabled course plan
        $coursePlanModel = model('\Plafor\Models\coursePlanModel');
        $coursePlanModel->delete($coursePlanId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/courseplan/list_course_plan'));
    }

    /**
     * Asserts that the delete_user_course page redirects to the list_apprentice view when an administrator session user access is set (delete action)
     */
    public function testdelete_user_courseWitAdministratorSessionUserAccessAndDeleteAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['logged_in'] = true;

        // Insert a new course plan
        $coursePlanId = self::insertCoursePlan();

        // Insert a new competence domain
        $competenceDomainId = self::insertCompetenceDomain($coursePlanId);

        // Insert a new operational competence linked to the inserted competence domain
        $operationalCompetenceId = self::insertOperationalCompetence($competenceDomainId);

        // Insert a new objective linked to the inserted operational competence
        $objectiveId = self::insertObjective($operationalCompetenceId);

        // Insert a new user course
        $userCourseId = self::insertUserCourse($coursePlanId);

        // Insert a new acquisition status linked to the inserted objective and to the inserted user course
        $acquisitionStatusId = self::insertAcquisitionStatus($objectiveId, $userCourseId);

        // Execute delete_user_course method of CoursePlan class
        $result = $this->controller(CoursePlan::class)
        ->execute('delete_user_course', $userCourseId, 1);

        // Delete inserted objective
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $objectiveModel->delete($objectiveId, true);

        // Delete inserted operational competence
        $operationalCompetenceModel = model('\Plafor\Models\operationalCompetenceModel');
        $operationalCompetenceModel->delete($operationalCompetenceId, true);

        // Delete inserted competence domain
        $competenceDomainModel = model('\Plafor\Models\competenceDomainModel');
        $competenceDomainModel->delete($competenceDomainId, true);

        // Delete inserted course plan
        $coursePlanModel = model('\Plafor\Models\coursePlanModel');
        $coursePlanModel->delete($coursePlanId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(
            base_url('plafor/apprentice/list_user_courses/') . '4');
    }

    /**
     * Insert a course plan into database
     */
    private static function insertCoursePlan() {
        $coursePlan = array(
            'formation_number' => 12345,
            'official_name' => 'Course Plan Unit Test',
            'date_begin' => '2023-04-05'
        );
        $coursePlanModel = model('\Plafor\Models\coursePlanModel');
        return $coursePlanModel->insert($coursePlan);
    }

    /**
     * Insert an archived course plan into database
     */
    private static function insertArchivedCoursePlan() {
        $coursePlan = array(
            'formation_number' => 12345,
            'official_name' => 'Course Plan Unit Test',
            'date_begin' => '2023-04-05',
            'archive' => '2023-04-26'
        );

        $coursePlanModel = model('\Plafor\Models\coursePlanModel');
        return $coursePlanModel->insert($coursePlan);
    }

    /**
     * Insert a competence domain linked to a course plan into database
     */
    private static function insertCompetenceDomain($coursePlanId) {
        $competenceDomain = array(
            'symbol' => 'ZZZZZZZZZZ',
            'name' => 'Competence Domain Unit Test',
            'fk_course_plan' => $coursePlanId,
            'id' => 0
        );
        $_POST['id'] = 0;
        $_POST['type'] = 'competence_domain';
        $_POST['fk_course_plan'] = $coursePlanId;
        $model = model('\Plafor\Models\CompetenceDomainModel');
        return $model->insert($competenceDomain);
    }

    /**
     * Insert an operational competence linked to a competence domain into database
     */
    private static function insertOperationalCompetence($competenceDomainId) {
        $operationalCompetence = array(
            'id' => 0,
            'symbol' => 'ZZZZZZZZZZ',
            'name' => 'Operational Competence Unit Test',
            'methodologic' => 'Operational Competence Unit Test',
            'social' => 'Operational Competence Unit Test',
            'personal' => 'Operational Competence Unit Test',
            'fk_competence_domain' => $competenceDomainId
        );

        $model = model('\Plafor\Models\OperationalCompetenceModel');
        $_POST['type'] = 'operational_competence';
        $_POST['id'] = 0;
        $_POST['fk_competence_domain'] = $competenceDomainId;
        return $model->insert($operationalCompetence);
    }

    /**
     * Insert an objective linked to an operational competence into database
     */
    private static function insertObjective($operationalCompetenceId) {
        $objective = array(
            'symbol' => 'ZZZZZZZZZZ',
            'taxonomy' => 99999,
            'name' => 'Objective Unit Test',
            'fk_operational_competence' => $operationalCompetenceId
        );
        $_POST['type'] = 'objective';
        $_POST['id'] = 0;
        $model = model('\Plafor\Models\ObjectiveModel');
        return $model->insert($objective);
    }

    /**
     * Insert an archived objective linked to an operational competence into database
     */
    private static function insertArchivedObjective($operationalCompetenceId) {
        $objective = array(
            'symbol' => 'ZZZZZZZZZZ',
            'taxonomy' => 99999,
            'name' => 'Objective Unit Test',
            'archive' => '2023-04-24',
            'fk_operational_competence' => $operationalCompetenceId
        );

        $objectiveModel = model('\Plafor\Models\objectiveModel');
        return $objectiveModel->insert($objective);
    }

    /**
     * Insert an user course linked to a course plan into database
     */
    private static function insertUserCourse($coursePlanId) {
        $userCourse = array(
            'fk_user' => 4,
            'fk_course_plan' => $coursePlanId,
            'fk_status' => 1,
            'date_begin' => '2023-04-19',
            'date_end' => '0000-00-00',
        );

        $userCourseModel = model('\Plafor\Models\userCourseModel');
        return $userCourseModel->insert($userCourse);
    }

    /**
     * Insert an acquisition status linked to an objective and an user course into database
     */
    private static function insertAcquisitionStatus($objectiveId, $userCourseId) {
        $acquisitionStatus = array(
            'fk_objective' => $objectiveId,
            'fk_user_course' => $userCourseId,
            'fk_acquisition_level' => 1
        );

        $acquisitionStatusModel = model('\Plafor\Models\acquisitionStatusModel');
        return $acquisitionStatusModel->insert($acquisitionStatus);
    }
}
