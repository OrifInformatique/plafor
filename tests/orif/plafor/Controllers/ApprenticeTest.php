<?php
/**
 * Unit tests ApprenticeTest
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

 namespace Plafor\Controllers;

 use CodeIgniter\Test\CIUnitTestCase;
 use CodeIgniter\Test\ControllerTestTrait;

 use User\Models;
 
 class ApprenticeTest extends CIUnitTestCase
{
    use ControllerTestTrait;

    const TRAINER_USER_TYPE = 2;
    const APPRENTICE_USER_TYPE = 3;

    /**
     * Asserts that the default index route redirects to login route (no session)
     */
    public function testindexWithoutSession()
    {
        // Execute index method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('index');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('user/auth/login'));
    }

    /**
     * Asserts that the default index route redirects to list_user route (Administrator session)
     */
    public function testindexWithAdministratorSession()
    {
        // Initialize session
        $_SESSION['logged_in'] = true;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;
        $_SESSION['user_id'] = 1;

        // Execute index method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('index');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('user/admin/list_user'));
    }

    /**
     * Asserts that the default index route redirects to list_apprentice route (Trainer session)
     */
    public function testindexWithTrainerSession()
    {
        // Initialize session
        $_SESSION['logged_in'] = true;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;
        $_SESSION['user_id'] = 2;

        // Execute index method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('index');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice?trainer_id=2'));
    }

    /**
     * Asserts that the default index route redirects to view_apprentice route (Apprentice session)
     */
    public function testindexWithApprenticeSession()
    {
        // Initialize session
        $_SESSION['logged_in'] = true;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_guest;
        $_SESSION['user_id'] = 4;

        // Execute index method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('index');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/view_apprentice/4'));
    }

    /**
     * Asserts that the list_apprentice page is loaded correctly
     */
    public function testlist_apprentice() 
    {
        // Execute list_apprentice method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('list_apprentice');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('FormateurDev', 'option');
        $result->assertSee('FormateurSysteme', 'option');
        $result->assertSee('FormateurOperateur', 'option');
        $result->assertSee('Liste des apprentis', 'h1');
        $result->assertSeeLink('ApprentiDev');
        $result->assertSeeLink(' Informaticien/-ne CFC Développement d\'applications');    
        $result->assertSeeLink('ApprentiSysteme');
        $result->assertSeeLink(' Informaticien/-ne CFC Technique des systèmes');
        $result->assertSeeLink('ApprentiOperateur');
        $result->assertSeeLink(' Opératrice en informatique / Opérateur en informatique CFC');
    }

    /**
     * Asserts that the list_apprentice page is loaded correctly for a given connected development trainer
     */
    public function testlist_apprenticeWithDevelopmentTrainerSession() 
    {
        // Initialize session for a development trainer
        $_SESSION['logged_in'] = true;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;
        $_SESSION['user_id'] = 2;
        
        // Execute list_apprentice method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('list_apprentice');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('FormateurDev', 'option');
        $result->assertSee('FormateurSysteme', 'option');
        $result->assertSee('FormateurOperateur', 'option');
        $result->assertSee('Liste des apprentis', 'h1');
        $result->assertSeeLink('ApprentiDev');
        $result->assertSeeLink(' Informaticien/-ne CFC Développement d\'applications');    
        $result->assertDontSee('ApprentiSysteme', 'a');
        $result->assertDontSee(' Informaticien/-ne CFC Technique des systèmes', 'a');
        $result->assertDontSee('ApprentiOperateur', 'a');
        $result->assertDontSee(' Opératrice en informatique / Opérateur en informatique CFC', 'a');
    }

    /**
     * Asserts that the view_apprentice page redirects to the list_apprentice view when no apprentice id is given
     */
    public function testview_apprenticeWithoutApprenticeId()
    {
        // Execute view_apprentice method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('view_apprentice');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the view_apprentice page redirects to the list_apprentice view when a trainer id is given
     */
    public function testview_apprenticeWithTrainerId()
    {
        // Execute view_apprentice method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('view_apprentice', 2);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the view_apprentice page is loaded correctly when an apprentice id is given
     */
    public function testview_apprenticeWithApprenticeId()
    {
        // Execute view_apprentice method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('view_apprentice', 4);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Détail de l\'apprenti', 'p');
        $result->assertSee('ApprentiDev', 'span');
        $result->assertSee('FormateurDev', 'p');
        $result->assertSeeElement('#usercourseSelector');
        $result->assertSee(' Informaticien/-ne CFC Développement d\'applications', 'option');
        $result->assertSee('09.07.2020', '.user-course-details-begin-date');
        $result->assertSee('En cours', '.user-course-details-status');
        $result->assertSee('Avancement du plan de formation', 'p');
        $result->assertSee(' Informaticien/-ne CFC Développement d\'applications', '.font-weight-bold user-course-details-course-plan-name');
    }

    /**
     * Asserts that the view_apprentice page is loaded correctly when an apprentice id is given (for a development trainer session)
     */
    public function testview_apprenticeWithApprenticeIdForDevelopmentTrainerSession()
    {
        // Initialize session for a development trainer
        $_SESSION['logged_in'] = true;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;
        $_SESSION['user_id'] = 2;

        // Execute view_apprentice method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('view_apprentice', 4);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Détail de l\'apprenti', 'p');
        $result->assertSee('ApprentiDev', 'span');
        $result->assertSeeLink('FormateurDev');
        $result->assertSeeLink('Ajouter un formateur');
        $result->assertSeeElement('#usercourseSelector');
        $result->assertSee(' Informaticien/-ne CFC Développement d\'applications', 'option');
        $result->assertSee('09.07.2020', '.user-course-details-begin-date');
        $result->assertSee('En cours', '.user-course-details-status');
        $result->assertSee('Avancement du plan de formation', 'p');
        $result->assertSee(' Informaticien/-ne CFC Développement d\'applications', '.font-weight-bold user-course-details-course-plan-name');
    }

    /**
     * Asserts that the save_user_course page redirects to the 403 error view when no session user access is set
     */
    public function testsave_user_courseWithoutSessionUserAccess()
    {
        // Initialize session previous url (needed for 403 error view)
        $_SESSION['_ci_previous_url'] = 'url';

        // Execute save_user_course method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_user_course');

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
     * Asserts that the save_user_course page redirects to the 403 error view when an apprentice session is set
     */
    public function testsave_user_courseWithApprenticeSession()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url';
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_guest;

        // Execute save_user_course method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_user_course');

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
     * Asserts that the save_user_course page redirects to the list_apprentice view when no apprentice id and user course id are given
     */
    public function testsave_user_courseWithTrainerSessionWithoutApprenticeIdAndUserCourseId()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url';
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Execute save_user_course method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_user_course');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the save_user_course page redirects to the list_apprentice view when a trainer id and user course id are given
     */
    public function testsave_user_courseWithTrainerSessionWithTrainerIdAndUserCourseId()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url';
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Execute save_user_course method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_user_course', 2, 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the save_user_course page redirects to the list_apprentice view when an apprentice id and user course id are given
     */
    public function testsave_user_courseWithTrainerSessionWithApprenticeIdAndUserCourseId()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url';
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Execute save_user_course method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_user_course', 4, 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Formation', 'label');
        $result->assertSeeElement('#course_plan');
        $result->assertSee(' Informaticien/-ne CFC Développement d\'applications', 'option');
        $result->assertSee('Statut de la formation', 'label');
        $result->assertSee('En cours', 'option');
        $result->assertSeeInField('date_begin', '2020-07-09');
        $result->assertSeeInField('date_end', '0000-00-00');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_user_course page redirects to the view_apprentice view when an apprentice id is given for a new user course (inserting an user course and acquisition statuses)
     */
    public function testsave_user_coursePostedWithTrainerSessionWithApprenticeIdAndNewUserCourse()
    {
        $userId = 4;
        $coursePlanId = 2;

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['course_plan'] = $coursePlanId;
        $_REQUEST['course_plan'] = $coursePlanId;
        $_POST['status'] = 1;
        $_REQUEST['status'] = 1;
        $_POST['date_begin'] = '2023-04-20';
        $_REQUEST['date_begin'] = '2023-04-20';
        $_POST['date_end'] = '0000-00-00';
        $_REQUEST['date_end'] = '0000-00-00';

        // Execute save_user_course method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_user_course', $userId);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();
        
        // Get user course from database
        $userCourseDb = \Plafor\Models\UserCourseModel::getInstance()->where("fk_user ", $userId)->where("fk_course_plan", $coursePlanId)->first();

        // Delete acquisition statuses linked to the inserted user course
        \Plafor\Models\AcquisitionStatusModel::getInstance()->where('fk_user_course', $userCourseDb['id'])->delete();

        // Delete inserted user course        
        \Plafor\Models\UserCourseModel::getInstance()->delete($userCourseDb['id'], TRUE);

        // Assertions
         $response = $result->response();
         $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
         $this->assertEmpty($response->getBody());
         $result->assertOK();
         $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
         $result->assertRedirectTo(base_url('plafor/apprentice/view_apprentice/' . $userId));
    }

    /**
     * Asserts that the save_user_course page redirects to the view_apprentice view when an apprentice id is given (updating an existing user course)
     */
    public function testsave_user_coursePostedWithTrainerSessionWithApprenticeIdAndExistingUserCourse()
    {
        $userId = 4;

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Insert a new course plan
        $coursePlanId = self::insertCoursePlan();

        // Insert a new user course linked to the inserted course plan
        $userCourseId = self::insertUserCourse($userId, $coursePlanId);

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['course_plan'] = $coursePlanId;
        $_REQUEST['course_plan'] = $coursePlanId;
        $_POST['status'] = 2;
        $_REQUEST['status'] = 2;
        $_POST['date_begin'] = '2023-04-05';
        $_REQUEST['date_begin'] = '2023-04-05';
        $_POST['date_end'] = '0000-00-00';
        $_REQUEST['date_end'] = '0000-00-00';

        // Execute save_user_course method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_user_course', $userId, $userCourseId);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();
        
        // Get user course from database
        $userCourseDb = \Plafor\Models\UserCourseModel::getInstance()->where("fk_user ", $userId)->where("fk_course_plan", $coursePlanId)->first();

        // Delete acquisition statuses linked to the inserted user course
        \Plafor\Models\AcquisitionStatusModel::getInstance()->where('fk_user_course', $userCourseDb['id'])->delete();

        // Delete inserted user course        
        \Plafor\Models\UserCourseModel::getInstance()->delete($userCourseDb['id'], TRUE);

        // Delete inserted course plan
        \Plafor\Models\CoursePlanModel::getInstance()->delete($coursePlanId, TRUE);

        // Assertions
         $response = $result->response();
         $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
         $this->assertEmpty($response->getBody());
         $result->assertOK();
         $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
         $result->assertRedirectTo(base_url('plafor/apprentice/view_apprentice/' . $userId));
    }

    /**
     * Asserts that the save_apprentice_link page redirects to the base url when an apprentice session is set
     */
    public function testsave_apprentice_linkWithApprenticeSession()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_guest;

        // Execute save_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_apprentice_link');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url());
    }

    /**
     * Asserts that the save_apprentice_link page redirects to the base url view when a trainer session is set without apprentice id
     */
    public function testsave_apprentice_linkWithTrainerSessionWithoutApprenticeId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Execute save_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_apprentice_link');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url());
    }

    /**
     * Asserts that the save_apprentice_link page redirects to the base url view when a trainer session is set with apprentice id
     */
    public function testsave_apprentice_linkWithTrainerSessionWithTrainerId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Execute save_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_apprentice_link', 2);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url());
    }

    /**
     * Asserts that the save_apprentice_link page is loaded correctly when no link between apprentice and trainer is provided
     */
    public function testsave_apprentice_linkWithTrainerSessionWithApprenticeId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Execute save_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_apprentice_link', 4);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Ajouter un formateur', 'h1');
        $result->assertDontSee('Modifer le formateur', 'h1');
        $result->assertSeeElement('#apprentice_link_form');
        $result->assertSeeInField('id', 4);
        $result->assertSee('Nom de l\'apprenti', 'label');
        $result->assertSeeInField('apprentice', 4);
        $result->assertSee('ApprentiDev', 'p');
        $result->assertSee('Formateur(s) lié(s)', 'label');
        $result->assertSee('FormateurDev', 'option');
        $result->assertSee('FormateurSysteme', 'option');
        $result->assertSee('FormateurOperateur', 'option');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_apprentice_link page is loaded correctly when a link between apprentice and trainer is provided
     */
    public function testsave_apprentice_linkWithTrainerSessionWithApprenticeIdAndLinkId()
    {
        $apprenticeId = 4;
        $trainerId = 2;

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Insert apprentice link 
        $apprenticeLinkId = self::insertTrainerApprenticeLink($trainerId, $apprenticeId);

        // Execute save_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_apprentice_link', $apprenticeId, $apprenticeLinkId);

        // Delete inserted link
        \Plafor\Models\TrainerApprenticeModel::getInstance()->delete($apprenticeLinkId, TRUE);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertDontSee('Ajouter un formateur', 'h1');
        $result->assertSee('Modifer le formateur', 'h1');
        $result->assertSeeElement('#apprentice_link_form');
        $result->assertSeeInField('id', 4);
        $result->assertSee('Nom de l\'apprenti', 'label');
        $result->assertSeeInField('apprentice', 4);
        $result->assertSee('ApprentiDev', 'p');
        $result->assertSee('Formateur(s) lié(s)', 'label');
        $result->assertSee('FormateurDev', 'option');
        $result->assertSee('FormateurSysteme', 'option');
        $result->assertSee('FormateurOperateur', 'option');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_apprentice_link page redirects to the view_apprentice view when inserting a new link between and apprentice and a trainer
     */
    public function testsave_apprentice_linkPostedWithTrainerSessionAndApprenticeId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Insert new apprentice user       
        $apprenticeId = self::insertApprentice('ApprenticeUnitTest');

        // Insert new trainer user
        $trainerId = self::insertTrainer('TrainerUnitTest');

        // Prepare the POST request 
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['trainer'] = $trainerId;
        $_REQUEST['trainer'] = $trainerId;
        $_POST['apprentice'] = $apprenticeId;
        $_REQUEST['apprentice'] = $apprenticeId;

        // Execute save_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_apprentice_link', $apprenticeId);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Get inserted apprentice link from database
        $apprenticeLink = \Plafor\Models\TrainerApprenticeModel::getInstance()->where("fk_trainer ", $trainerId)->where("fk_apprentice", $apprenticeId)->first();

        // Delete inserted apprentice link
        \Plafor\Models\TrainerApprenticeModel::getInstance()->delete($apprenticeLink['id'], TRUE);

        // Delete inserted apprentice
        \User\Models\User_model::getInstance()->delete($apprenticeId, TRUE);

        // Delete inserted trainer
        \User\Models\User_model::getInstance()->delete($trainerId, TRUE);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/view_apprentice/' . $apprenticeId));
    }

    /**
     * Asserts that the save_apprentice_link page redirects to the view_apprentice view when updating a new link between and apprentice and a trainer
     */
    public function testsave_apprentice_linkPostedWithTrainerSessionAndApprenticeIdUpdate()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Insert new apprentice user       
        $apprenticeId = self::insertApprentice('ApprenticeUnitTest');

        // Insert new trainer user
        $trainerId = self::insertTrainer('TrainerUnitTest');

        // Insert new second trainer user
        $trainer2Id = self::insertTrainer('Trainer2UnitTest');

        // Insert apprentice link 
        $apprenticeLinkId = self::insertTrainerApprenticeLink($trainerId, $apprenticeId);

        // Prepare the POST request 
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['trainer'] = $trainer2Id;
        $_REQUEST['trainer'] = $trainer2Id;
        $_POST['apprentice'] = $apprenticeId;
        $_REQUEST['apprentice'] = $apprenticeId;

        // Execute save_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_apprentice_link', $apprenticeId, $apprenticeLinkId);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Delete inserted and updated apprentice link
        \Plafor\Models\TrainerApprenticeModel::getInstance()->delete($apprenticeLinkId, TRUE);

        // Delete inserted apprentice
        \User\Models\User_model::getInstance()->delete($apprenticeId, TRUE);

        // Delete inserted trainer
        \User\Models\User_model::getInstance()->delete($trainerId, TRUE);

        // Delete second inserted trainer
        \User\Models\User_model::getInstance()->delete($trainer2Id, TRUE);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/view_apprentice/' . $apprenticeId));
    }

    /**
     * Asserts that the delete_apprentice_link page redirects to the 403 error view when an apprentice session is set
     */
    public function testdelete_apprentice_linkWithApprenticeSession()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url';
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_guest;

        // Execute delete_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_apprentice_link', 1);

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
     * Asserts that the delete_apprentice_link page (confirmation message) is loaded correctly (trainer session and default action)
     */
    public function testdelete_apprentice_linkWithTrainerSessionAndDefaultAction()
    {
        $apprenticeId = 4;
        $trainerId = 2;

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url';
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;     

        // Insert apprentice link 
        $apprenticeLinkId = self::insertTrainerApprenticeLink($trainerId, $apprenticeId);

        // Execute delete_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_apprentice_link', $apprenticeLinkId);

        // Delete inserted link
        \Plafor\Models\TrainerApprenticeModel::getInstance()->delete($apprenticeLinkId, TRUE);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Apprenti et formateur lié', 'h1');
        $result->assertSee('Apprenti \'ApprentiDev\'', 'h1');
        $result->assertSee('Formateur \'FormateurDev\'', 'h1');
        $result->assertSee('Toutes les informations concernant le lien entre cet apprenti et ce formateur seront désactivées.', 'div');
        $result->assertSeeLink('Annuler');
        $result->assertSeeLink('Désactiver');
    }

    /**
     * Asserts that the delete_apprentice_link redirects to the list_apprentice view (trainer session and fake action) 
     */
    public function testdelete_apprentice_linkWithTrainerSessionAndFakeAction()
    {
        $apprenticeId = 4;
        $trainerId = 2;

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url';
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Insert apprentice link 
        $apprenticeLinkId = self::insertTrainerApprenticeLink($trainerId, $apprenticeId);
        
        // Execute delete_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_apprentice_link', $apprenticeLinkId, 9);

        // Delete inserted link
        \Plafor\Models\TrainerApprenticeModel::getInstance()->delete($apprenticeLinkId, TRUE);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice/' . $apprenticeId));
    }

    /**
     * Asserts that the delete_apprentice_link redirects to the list_apprentice view (trainer session and delete action) 
     */
    public function testdelete_apprentice_linkWithTrainerSessionAndDeleteAction()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url';
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Insert new apprentice user       
        $apprenticeId = self::insertApprentice('ApprenticeUnitTest');

        // Insert new trainer user
        $trainerId = self::insertTrainer('TrainerUnitTest');

        // Insert apprentice link 
        $apprenticeLinkId = self::insertTrainerApprenticeLink($trainerId, $apprenticeId);

        // Execute delete_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_apprentice_link', $apprenticeLinkId, 1);

        // Delete inserted apprentice
        \User\Models\User_model::getInstance()->delete($apprenticeId, TRUE);

        // Delete inserted trainer
        \User\Models\User_model::getInstance()->delete($trainerId, TRUE);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice/' . $apprenticeId));
    }

    /**
     * Asserts that the delete_apprentice_link page redirects to the list_apprentice view when a non existing link is given
     */
    public function testdelete_apprentice_linkWithTrainerSessionForNonExistingLink()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url';
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Execute delete_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_apprentice_link', 999999);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the view_acquisition_status page redirects to the list_apprentice view when no status id is provided
     */
    public function testview_acquisition_statusWithoutStatusId() 
    {
        // Execute view_acquisition_status method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('view_acquisition_status');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the view_acquisition_status page is loaded correctly when a status id is provided (apprentice session)
     */
    public function testview_acquisition_statusWithStatusIdWithApprenticeSession() 
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_guest;

        // Execute view_acquisition_status method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('view_acquisition_status', 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Détail du statut d\'acquisition', 'p');
        $result->assertSee('Symboles de l\'objectif', 'p');
        $result->asserttSeeLink('A.1.1');
        $result->assertSee('Nom de l\'objectif', 'p');
        $result->asserttSeeLink('Enregistrer les besoins et discuter les solutions possibles, s’entretenir avec le client/supérieur sur les restrictions des exigences');
        $result->assertSee('Taxonomie de l\'objectif', 'p');
        $result->assertSeeLink('4');
        $result->assertSee('Niveau d\'acquisition', 'p');
        $result->assertSee('Non expliqué', 'p');
        $result->assertDontSee('Ajouter un commentaire', 'a');
        $result->assertSee('Commentaire', 'th');
        $result->assertSee('Créateur du commentaire', 'th');
        $result->assertSee('Date de création du commentaire', 'th');
    }

    /**
     * Asserts that the view_acquisition_status page is loaded correctly when a status id is provided (trainer session)
     */
    public function testview_acquisition_statusWithStatusIdWithTrainerSession() 
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Execute view_acquisition_status method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('view_acquisition_status', 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Détail du statut d\'acquisition', 'p');
        $result->assertSee('Symboles de l\'objectif', 'p');
        $result->asserttSeeLink('A.1.1');
        $result->assertSee('Nom de l\'objectif', 'p');
        $result->asserttSeeLink('Enregistrer les besoins et discuter les solutions possibles, s’entretenir avec le client/supérieur sur les restrictions des exigences');
        $result->assertSee('Taxonomie de l\'objectif', 'p');
        $result->assertSeeLink('4');
        $result->assertSee('Niveau d\'acquisition', 'p');
        $result->assertSee('Non expliqué', 'p');
        $result->assertSeeLink('Ajouter un commentaire');
        $result->assertSee('Commentaire', 'th');
        $result->assertSee('Créateur du commentaire', 'th');
        $result->assertSee('Date de création du commentaire', 'th');
    }

    /**
     * Asserts that the view_acquisition_status page is loaded correctly when a status id is provided (trainer session) after inserting a temporary comment
     */
    public function testview_acquisition_statusWithStatusIdWithTrainerSessionAfterCommentInsert() 
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Insert a new comment
        $acquisitionStatusId = 1;
        $commentId = self::insertComment(2, $acquisitionStatusId);

        // Execute view_acquisition_status method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('view_acquisition_status', $acquisitionStatusId);

        // Delete inserted comment
        \Plafor\Models\CommentModel::getInstance()->delete($commentId, TRUE);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Détail du statut d\'acquisition', 'p');
        $result->assertSee('Symboles de l\'objectif', 'p');
        $result->asserttSeeLink('A.1.1');
        $result->assertSee('Nom de l\'objectif', 'p');
        $result->asserttSeeLink('Enregistrer les besoins et discuter les solutions possibles, s’entretenir avec le client/supérieur sur les restrictions des exigences');
        $result->assertSee('Taxonomie de l\'objectif', 'p');
        $result->assertSeeLink('4');
        $result->assertSee('Niveau d\'acquisition', 'p');
        $result->assertSee('Non expliqué', 'p');
        $result->assertSeeLink('Ajouter un commentaire');
        $result->assertSee('Commentaire', 'th');
        $result->assertSee('Créateur du commentaire', 'th');
        $result->assertSee('Date de création du commentaire', 'th');
        $result->assertSeeLink('Comment Unit Test');
        $result->assertSee('FormateurDev', 'th');
    }

    /**
     * Asserts that the save_acquisition_status page redirects to the list_apprentice view when no status id is provided (no session)
     */
    public function testsave_acquisition_statusWithoutStatusIdWithoutSession() 
    {
        // Execute save_acquisition_status method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_acquisition_status');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the save_acquisition_status page redirects to the list_apprentice view when a status id is provided (system apprentice session)
     */
    public function testsave_acquisition_statusWithStatusIdWithSystemApprenticeSession() 
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_guest;
        $_SESSION['user_id'] = 5;   // System Apprentice

        // Execute save_acquisition_status method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_acquisition_status', 1);        // Acquisition status linked to user course linked to development apprentice

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the save_acquisition_status page is loaded correctly when a status id is provided (development apprentice session)
     */
    public function testsave_acquisition_statusWithStatusIdWithDevelopmentApprenticeSession() 
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_guest;
        $_SESSION['user_id'] = 4;

        // Execute save_acquisition_status method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_acquisition_status', 1);        // Acquisition status linked to user course linked to development apprentice

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Modifier un statut d\'acquisition', 'h1');
        $result->assertSeeElement('#edit_acquisition_status');
        $result->assertSee('Niveau d\'acquisition', 'div');
        $result->assertSeeElement('#field_acquisition_level');
        $result->assertSee('Non expliqué', 'option');
        $result->assertSee('Expliqué', 'option');
        $result->assertSee('Exercé', 'option');
        $result->assertSee('Autonome', 'option');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_acquisition_status function returns a status code 200 when a status id is provided with a new level (development apprentice session)
     */
    public function testsave_acquisition_statusPostedWithStatusIdAndNewLevelWithDevelopmentApprenticeSession()
    {
        $acquisitionStatusId = 1;
        $acquisitionLevel = 1;

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_guest;
        $_SESSION['user_id'] = 4;

        // Prepare the POST request 
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['field_acquisition_level'] = 3;
        $_REQUEST['field_acquisition_level'] = 3;

        // Execute save_acquisition_status method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_acquisition_status', $acquisitionStatusId);        // Acquisition status linked to user course linked to development apprentice

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Reset acquisition status level
        $acquisitionStatus = [
            'fk_acquisition_level' => $acquisitionLevel
        ];

        \Plafor\Models\AcquisitionStatusModel::getInstance()->update($acquisitionStatusId, $acquisitionStatus);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertStatusCode(200);
    }

    /**
     * Asserts that the save_acquisition_status function returns a status code 200 when a status id is provided with a new level (development apprentice session)
     */
    public function testsave_acquisition_statusPostedWithStatusIdAndNewLevelWithDevelopmentApprenticeSessionAndNonExistingStatusLevel()
    {
        $acquisitionStatusId = 1;

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_guest;
        $_SESSION['user_id'] = 4;

        // Prepare the POST request 
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['field_acquisition_level'] = 5;
        $_REQUEST['field_acquisition_level'] = 5;

        // Execute save_acquisition_status method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_acquisition_status', $acquisitionStatusId);        // Acquisition status linked to user course linked to development apprentice

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Modifier un statut d\'acquisition', 'h1');
        $result->assertSeeElement('#edit_acquisition_status');
        $result->assertSee('Le champ Niveau d\'acquisition doit être un élément de la liste suivante : 1,2,3,4.', 'div');
        $result->assertSee('Niveau d\'acquisition', 'div');
        $result->assertSeeElement('#field_acquisition_level');
        $result->assertSee('Non expliqué', 'option');
        $result->assertSee('Expliqué', 'option');
        $result->assertSee('Exercé', 'option');
        $result->assertSee('Autonome', 'option');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the add_comment page redirects to the list_apprentice view when no status id is provided (no session)
     */
    public function testadd_commentWithoutStatusIdWithoutSession() 
    {
        // Execute add_comment method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('add_comment');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the add_comment page redirects to the list_apprentice view when a status id is provided (apprentice session)
     */
    public function testadd_commentWithStatusIdWithApprenticeSession() 
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_guest;

        // Execute add_comment method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('add_comment', 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the add_comment page is loaded correctly when a status id is provided (trainer session)
     */
    public function testadd_commentWithStatusIdWithTrainerSession() 
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Execute add_comment method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('add_comment', 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Ajouter un commentaire', 'h1');
        $result->assertSee('Commentaire', 'label');
        $result->assertSeeElement('#comment');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the add_comment page redirects to the view_acquisition_status view when a status id is provided for a trainer session (inserting a new comment)
     */
    public function testadd_commentPostedtWithStatusIdWithTrainerSession()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;
        $_SESSION['user_id'] = 2;

        // Insert a new course plan
        $coursePlanId = self::insertCoursePlan();

        // Insert a new competence domain linked to the inserted course plan
        $competenceDomainId = self::insertCompetenceDomain($coursePlanId);
        
        // Insert a new operational competence linked to the inserted competence domain
        $operationalCompetenceId = self::insertOperationalCompetence($competenceDomainId);
        
        // Insert a new objective linked to the inserted operational competence
        $objectiveId = self::insertObjective($operationalCompetenceId);

        // Insert a new user course linked to the inserted course plan
        $userCourseId = self::insertUserCourse(4, $coursePlanId);

        // Insert a new acquisition status linked to the inserted objective and to the inserted user course
        $acquisitionStatusId = self::insertAcquisitionStatus($objectiveId, $userCourseId);

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['comment'] = 'Comment Unit Test';
        $_REQUEST['comment'] = 'Comment Unit Test';

        // Execute add_comment method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('add_comment', $acquisitionStatusId);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Delete inserted comment
        \Plafor\Models\CommentModel::getInstance()->where('comment', 'Comment Unit Test')->delete();

        // Delete inserted acquisition status
        \Plafor\Models\AcquisitionStatusModel::getInstance()->delete($acquisitionStatusId, TRUE);

        // Delete inserted user course
        \Plafor\Models\UserCourseModel::getInstance()->delete($userCourseId, TRUE);

        // Delete inserted objective
        \Plafor\Models\ObjectiveModel::getInstance()->delete($objectiveId, TRUE);

        // Delete inserted operational competence
        \Plafor\Models\OperationalCompetenceModel::getInstance()->delete($operationalCompetenceId, TRUE);

        // Delete inserted competence domain
        \Plafor\Models\CompetenceDomainModel::getInstance()->delete($competenceDomainId, TRUE);

        // Delete inserted course plan
        \Plafor\Models\CoursePlanModel::getInstance()->delete($coursePlanId, TRUE);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/view_acquisition_status/' . $acquisitionStatusId));
    }

    /**
     * Asserts that the add_comment page redirects to the view_acquisition_status view when a status id is provided for a trainer session (inserting a new empty comment)
     */
    public function testadd_commentPostedtWithStatusIdWithTrainerSessionAndEmptyComment()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;
        $_SESSION['user_id'] = 2;

        // Insert a new course plan
        $coursePlanId = self::insertCoursePlan();

        // Insert a new competence domain linked to the inserted course plan
        $competenceDomainId = self::insertCompetenceDomain($coursePlanId);
        
        // Insert a new operational competence linked to the inserted competence domain
        $operationalCompetenceId = self::insertOperationalCompetence($competenceDomainId);
        
        // Insert a new objective linked to the inserted operational competence
        $objectiveId = self::insertObjective($operationalCompetenceId);

        // Insert a new user course linked to the inserted course plan
        $userCourseId = self::insertUserCourse(4, $coursePlanId);

        // Insert a new acquisition status linked to the inserted objective and to the inserted user course
        $acquisitionStatusId = self::insertAcquisitionStatus($objectiveId, $userCourseId);

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['comment'] = '';
        $_REQUEST['comment'] = '';

        // Execute add_comment method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('add_comment', $acquisitionStatusId);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Delete inserted acquisition status
        \Plafor\Models\AcquisitionStatusModel::getInstance()->delete($acquisitionStatusId, TRUE);

        // Delete inserted user course
        \Plafor\Models\UserCourseModel::getInstance()->delete($userCourseId, TRUE);

        // Delete inserted objective
        \Plafor\Models\ObjectiveModel::getInstance()->delete($objectiveId, TRUE);

        // Delete inserted operational competence
        \Plafor\Models\OperationalCompetenceModel::getInstance()->delete($operationalCompetenceId, TRUE);

        // Delete inserted competence domain
        \Plafor\Models\CompetenceDomainModel::getInstance()->delete($competenceDomainId, TRUE);

        // Delete inserted course plan
        \Plafor\Models\CoursePlanModel::getInstance()->delete($coursePlanId, TRUE);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Le champ Commentaire est requis.', 'div');
    }

    /**
     * Asserts that the add_comment page redirects to the view_acquisition_status view when a status id is provided for a trainer session (updating an existing comment)
     */
    public function testadd_commentPostedtWithStatusIdWithTrainerSessionAndExistingComment()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;
        $_SESSION['user_id'] = 2;

        // Insert a new course plan
        $coursePlanId = self::insertCoursePlan();

        // Insert a new competence domain linked to the inserted course plan
        $competenceDomainId = self::insertCompetenceDomain($coursePlanId);
        
        // Insert a new operational competence linked to the inserted competence domain
        $operationalCompetenceId = self::insertOperationalCompetence($competenceDomainId);
        
        // Insert a new objective linked to the inserted operational competence
        $objectiveId = self::insertObjective($operationalCompetenceId);

        // Insert a new user course linked to the inserted course plan
        $userCourseId = self::insertUserCourse(4, $coursePlanId);

        // Insert a new acquisition status linked to the inserted objective and to the inserted user course
        $acquisitionStatusId = self::insertAcquisitionStatus($objectiveId, $userCourseId);

        // Insert a new comment linked to the inserted acquisition status
        $commentId = self::insertComment(2, $acquisitionStatusId);

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['comment'] = 'Comment Update Unit Test';
        $_REQUEST['comment'] = 'Comment Udpdate Unit Test';

        // Execute add_comment method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('add_comment', $acquisitionStatusId, $commentId);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Delete inserted comment
        \Plafor\Models\CommentModel::getInstance()->delete($commentId, TRUE);

        // Delete inserted acquisition status
        \Plafor\Models\AcquisitionStatusModel::getInstance()->delete($acquisitionStatusId, TRUE);

        // Delete inserted user course
        \Plafor\Models\UserCourseModel::getInstance()->delete($userCourseId, TRUE);

        // Delete inserted objective
        \Plafor\Models\ObjectiveModel::getInstance()->delete($objectiveId, TRUE);

        // Delete inserted operational competence
        \Plafor\Models\OperationalCompetenceModel::getInstance()->delete($operationalCompetenceId, TRUE);

        // Delete inserted competence domain
        \Plafor\Models\CompetenceDomainModel::getInstance()->delete($competenceDomainId, TRUE);

        // Delete inserted course plan
        \Plafor\Models\CoursePlanModel::getInstance()->delete($coursePlanId, TRUE);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/view_acquisition_status/' . $acquisitionStatusId));
    }

    /**
     * Asserts that getCoursePlanProgress method returns a 403 status code when no user id and no course plan id are given (no session)
     */
    public function testgetCoursePlanProgressWithoutUserIdAndCoursePlanIdWithoutSession() 
    {
        // Execute getCoursePlanProgress method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('getCoursePlanProgress');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertStatus(403);
    }

    /**
     * Asserts that getCoursePlanProgress method returns a 403 status code when an user id and no course plan id are given (no session)
     */
    public function testgetCoursePlanProgressWithUserIdWithoutCoursePlanIdWithoutSession() 
    {
        // Execute getCoursePlanProgress method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('getCoursePlanProgress', 4);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertStatus(403);
    }

    /**
     * Asserts that getCoursePlanProgress method returns a 403 status code when an user id (development apprentice) and no course plan id are given (system apprentice session)
     */
    public function testgetCoursePlanProgressWithDevelopmentApprenticeUserIdWithoutCoursePlanIdWithSystemApprenticeSession() 
    {
        // Initialize session
        $_SESSION['user_id'] = 5;

        // Execute getCoursePlanProgress method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('getCoursePlanProgress', 4);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertStatus(403);
    }

    /**
     * Asserts that getCoursePlanProgress method returns a JSON object when an user id (development apprentice) and no course plan id are given (development apprentice session)
     */
    public function testgetCoursePlanProgressWithDevelopmentApprenticeUserIdWithoutCoursePlanIdWithDevelopmentApprenticeSession() 
    {
        // Initialize session
        $_SESSION['user_id'] = 4;

        // Execute getCoursePlanProgress method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('getCoursePlanProgress', 4);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $this->assertJSON($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'application/json; charset=UTF-8');
    }

    /**
     * Asserts that getCoursePlanProgress method returns a JSON object when an user id (development apprentice) and a course plan id are given (development apprentice session)
     */
    public function testgetCoursePlanProgressWithDevelopmentApprenticeUserIdWithCoursePlanIdWithDevelopmentApprenticeSession() 
    {
        // Initialize session
        $_SESSION['user_id'] = 4;

        // Execute getCoursePlanProgress method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('getCoursePlanProgress', 4, 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $this->assertJSON($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'application/json; charset=UTF-8');
    }

    /**
     * Asserts that getCoursePlanProgress method returns a JSON object when an user id (development apprentice) and no course plan id are given (administrator session)
     */
    public function testgetCoursePlanProgressWithDevelopmentApprenticeUserIdWithoutCoursePlanIdWithAdministratorSession() 
    {
        // Initialize session
        $_SESSION['user_id'] = 1;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;

        // Execute getCoursePlanProgress method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('getCoursePlanProgress', 4);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $this->assertJSON($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'application/json; charset=UTF-8');
    }

    /**
     * Asserts that getCoursePlanProgress method returns a JSON object when an user id (development apprentice) and a course plan id are given (administrator session)
     */
    public function testgetCoursePlanProgressWithDevelopmentApprenticeUserIdWithCoursePlanIdWithAdministratorSession() 
    {
        // Initialize session
        $_SESSION['user_id'] = 1;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;

        // Execute getCoursePlanProgress method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('getCoursePlanProgress', 4, 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $this->assertJSON($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'application/json; charset=UTF-8');
    }

    /**
     * Asserts that getCoursePlanProgress method returns a JSON object when an user id (development apprentice) and no course plan id are given (trainer session)
     */
    public function testgetCoursePlanProgressWithDevelopmentApprenticeUserIdWithoutCoursePlanIdWithTrainerSession() 
    {
        // Initialize session
        $_SESSION['user_id'] = 2;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Execute getCoursePlanProgress method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('getCoursePlanProgress', 4);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $this->assertJSON($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'application/json; charset=UTF-8');
    }

    /**
     * Asserts that getCoursePlanProgress method returns a JSON object when an user id (development apprentice) and a course plan id are given (trainer session)
     */
    public function testgetCoursePlanProgressWithhDevelopmentApprenticeUserIdWithCoursePlanIdWithTrainerSession() 
    {
        // Initialize session
        $_SESSION['user_id'] = 2;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Execute getCoursePlanProgress method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('getCoursePlanProgress', 4, 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $this->assertJSON($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'application/json; charset=UTF-8');
    }

    /**
     * Asserts that view_user_course page redirects to list_apprentice view when no user course id is given
     */
    public function testview_user_courseWithoutUserCourseId()
    {
        // Execute view_user_course method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('view_user_course');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that view_user_course page redirects to list_apprentice view when an user course id is given but linked to an other apprentice
     */
    public function testview_user_courseWithUserCourseIdLinkedToAnOtherApprentice()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_level_apprentice;
        $_SESSION['user_id'] = 5;

        // Execute view_user_course method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('view_user_course', 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that view_user_course page is loaded correctly when an user course id is given for a given development apprentice
     */
    public function testview_user_courseWithUserCourseIdLinkedToADevelopmentApprentice()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_level_apprentice;
        $_SESSION['user_id'] = 4;

        // Execute view_user_course method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('view_user_course', 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Détail de la formation de l\'apprenti', 'p');
        $result->assertSeeLink('ApprentiDev');
        $result->assertSee('Statuts d\'acquisition des objectifs', 'p');
        $result->assertSeeLink('A.1.1');
        $result->assertSeeLink('Enregistrer les besoins et discuter les solutions possibles, s’entretenir avec le client/supérieur sur les restrictions des exigences');
    }

    /**
     * Asserts that view_user_course page is loaded correctly when an user course id is given for a given system apprentice
     */
    public function testview_user_courseWithUserCourseIdLinkedToASystemApprentice()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_level_apprentice;
        $_SESSION['user_id'] = 5;

        // Execute view_user_course method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('view_user_course', 2);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Détail de la formation de l\'apprenti', 'p');
        $result->assertSeeLink('ApprentiSysteme');
        $result->assertSee('Statuts d\'acquisition des objectifs', 'p');
        $result->assertSeeLink('A.1.1');
        $result->assertSeeLink('Etre capable de recevoir, comprendre, planifier et mettre en œuvre un mandat client (organisation, méthodologie, ergonomie, optimisation de l’énergie)');
    }

    /**
     * Asserts that view_user_course page is loaded correctly when an user course id is given for a given system apprentice (connexion with an administrator account)
     */
    public function testview_user_courseWithUserCourseIdLinkedToASystemApprenticeWithAdministratorAccount()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;
        $_SESSION['user_id'] = 1;

        // Execute view_user_course method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('view_user_course', 2);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Détail de la formation de l\'apprenti', 'p');
        $result->assertSeeLink('ApprentiSysteme');
        $result->assertSee('Statuts d\'acquisition des objectifs', 'p');
        $result->assertSeeLink('A.1.1');
        $result->assertSeeLink('Etre capable de recevoir, comprendre, planifier et mettre en œuvre un mandat client (organisation, méthodologie, ergonomie, optimisation de l’énergie)');
        $result->assertSeeLink('Modifer la formation');
        $result->assertSeeLink('Supprimer la formation');
    }

    /**
     * Asserts that view_user_course page is loaded correctly when an user course id is given for a given system apprentice and a given operational competence (connexion with an administrator account)
     */
    public function testview_user_courseWithUserCourseIdLinkedToASystemApprenticeWithAdministratorAccountForAGivenOperationalCompetence()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;
        $_SESSION['user_id'] = 1;

        // Prepare the GET request
        $_SERVER['REQUEST_METHOD'] = 'get';
        $_GET['operationalCompetenceId'] = 43;
        $_REQUEST['operationalCompetenceId'] = 43;

        // Execute view_user_course method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('view_user_course', 2);

        // Reset $_GET and $_REQUEST variables
        $_GET = array();
        $_REQUEST = array();

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Détail de la formation de l\'apprenti', 'p');
        $result->assertSeeLink('ApprentiSysteme');
        $result->assertSee('Statuts d\'acquisition des objectifs', 'p');
        $result->assertSeeLink('A.2.5');
        $result->assertSeeLink('Tester et documenter la configuration/disponibilité et la fonctionnalité des nouveaux matériels et logiciels installés');
        $result->assertSeeLink('Modifer la formation');
        $result->assertSeeLink('Supprimer la formation');
    }

    /**
     * Asserts that the delete_user redirects to the list_user view (with a non existing user id)
     */
    public function testdelete_userWithNonExistingUserId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;
        $_SESSION['user_id'] = 1;

        // Execute delete_user method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_user', 999999);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertRedirectTo(base_url('/user/admin/list_user'));
    }

    /**
     * Asserts that the delete_user confirmation message is displayed correctly (when the action equals 0)
     */
    public function testdelete_userConfirmationMessage()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;
        $_SESSION['user_id'] = 1;

        // Execute delete_user method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_user', 4, 0);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Utilisateur \'ApprentiDev\'', 'h1');
        $result->assertSee('Que souhaitez-vous faire ?', 'h4');
        $result->assertSeeLink('Annuler');
        $result->assertSeeLink('Désactiver cet utilisateur');
        $result->assertSeeLink('Supprimer cet utilisateur');
    }

    /**
     * Asserts that the delete_user redirects to the list_user view (when the action is not equal to 0, 1 or 2)
     */
    public function testdelete_userWithFakeAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;
        $_SESSION['user_id'] = 1;

        // Execute delete_user method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_user', 1, 9);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('/user/admin/list_user'));
    }

    /**
     * Asserts that the delete_user redirects to the list_user view (when the action equals 1)
     * The user won't be disabled because the user_id is equal to the session user id
     */
    public function testdelete_userWithDisableActionForSameUserId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;
        $_SESSION['user_id'] = 1;

        // Execute delete_user method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_user', 1, 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('/user/admin/list_user'));
    }

    /**
     * Asserts that the delete_user redirects to the list_user view (when the action equals 1)
     */
    public function testdelete_userWithDisableAction()
    {
        $user_id = 4;

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;
        $_SESSION['user_id'] = 1;

        // Execute delete_user method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_user', $user_id, 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('/user/admin/list_user'));

        // Enable user id 4
        \User\Models\User_model::getInstance()->update($user_id, ['archive' => NULL]);
    }

    /**
     * Asserts that the delete_user redirects to the list_user view (when the action equals 2)
     * The user won't be deleted because the user_id is equal to the session user id
     */
    public function testdelete_userWithDeleteActionForSameUserId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;
        $_SESSION['user_id'] = 1;

        // Execute delete_user method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_user', 1, 2);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('/user/admin/list_user'));
    }

    /**
     * Asserts that the delete_user redirects to the list_user view (when the action equals 2)
     */
    public function testdelete_userWithDeleteAction() {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;
        $_SESSION['user_id'] = 1;

        // Insert new apprentice user       
        $apprenticeId = self::insertApprentice('ApprenticeUnitTest');

        // Insert new trainer user
        $trainerId = self::insertTrainer('TrainerUnitTest');

        // Insert apprentice link 
        $apprenticeLinkId = self::insertTrainerApprenticeLink($trainerId, $apprenticeId);

        // Insert a new course plan
        $coursePlanId = self::insertCoursePlan();

        // Insert a new competence domain linked to the inserted course plan
        $competenceDomainId = self::insertCompetenceDomain($coursePlanId);
        
        // Insert a new operational competence linked to the inserted competence domain
        $operationalCompetenceId = self::insertOperationalCompetence($competenceDomainId);
        
        // Insert a new objective linked to the inserted operational competence
        $objectiveId = self::insertObjective($operationalCompetenceId); 

        // Insert a new user course linked to the inserted course plan
        $userCourseId = self::insertUserCourse($apprenticeId, $coursePlanId);

        // Insert a new acquisition status linked to the inserted objective and to the inserted user course
        $acquisitionStatusId = self::insertAcquisitionStatus($objectiveId, $userCourseId);

        // Insert a new comment linked to the acquisition status
        $commentId = self::insertComment($trainerId, $acquisitionStatusId);

        // Execute delete_user method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_user', $apprenticeId, 2);

        // Delete inserted objective
        \Plafor\Models\ObjectiveModel::getInstance()->delete($objectiveId, TRUE);

        // Delete inserted operational competence
        \Plafor\Models\OperationalCompetenceModel::getInstance()->delete($operationalCompetenceId, TRUE);

        // Delete inserted competence domain
        \Plafor\Models\CompetenceDomainModel::getInstance()->delete($competenceDomainId, TRUE);

        // Delete inserted course plan
        \Plafor\Models\CoursePlanModel::getInstance()->delete($coursePlanId, TRUE);

        // Delete inserted trainer user
        \User\Models\User_model::getInstance()->delete($trainerId, TRUE);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('/user/admin/list_user'));
    }

    /**
     * Assert that the delete_comment redirects to the view_acquisition_status view 
     */
    public function testdelete_comment() {
        // Execute delete_comment method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_comment', 1, 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/view_acquisition_status/1'));
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

        return \Plafor\Models\CoursePlanModel::getInstance()->insert($coursePlan);
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

        return \Plafor\Models\CompetenceDomainModel::getInstance()->insert($competenceDomain);
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

        return \Plafor\Models\OperationalCompetenceModel::getInstance()->insert($operationalCompetence);
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

        return \Plafor\Models\ObjectiveModel::getInstance()->insert($objective);
    }

    /**
     * Insert an user course linked to a course plan into database
     */
    private static function insertUserCourse($userId, $coursePlanId) {
        $userCourse = array(
            'fk_user' => $userId,
            'fk_course_plan' => $coursePlanId,
            'fk_status' => 1,
            'date_begin' => '2023-04-19',
            'date_end' => '0000-00-00',
        );

        return \Plafor\Models\UserCourseModel::getInstance()->insert($userCourse);
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

        return \Plafor\Models\AcquisitionStatusModel::getInstance()->insert($acquisitionStatus);
    }

    /**
     * Insert a comment linked to an acquisition status into database
     */
    private static function insertComment($trainerId, $acquisitionStatusId) {
        $comment = array(
            'fk_trainer' => $trainerId,
            'fk_acquisition_status' => $acquisitionStatusId,
            'comment' => 'Comment Unit Test',
            'date_creation' => date('Y-m-d H:i:s'),
        );

        return \Plafor\Models\CommentModel::getInstance()->insert($comment);
    }

    /**
     * Insert a link between a trainer and an apprentice into database
     */
    private static function insertTrainerApprenticeLink($trainerId, $apprenticeId) {
        $apprenticeLink = array(
            'fk_trainer' => $trainerId,
            'fk_apprentice' => $apprenticeId,
        );

        return \Plafor\Models\TrainerApprenticeModel::getInstance()->insert($apprenticeLink);
    }

    /**
     * Insert an apprentice into database
     */
    private static function insertApprentice($username) {
        $apprentice = array(
            'fk_user_type' => self::APPRENTICE_USER_TYPE,
            'username' => $username,
            'password' => password_hash('ApprenticeUnitTestPassword', config('\User\Config\UserConfig')->password_hash_algorithm),
        );

        return \User\Models\User_model::getInstance()->insert($apprentice);
    }

    /**
     * Insert a trainer into database
     */
    private static function insertTrainer($username) {
        $trainer = array(
            'fk_user_type' => self::TRAINER_USER_TYPE,
            'username' => $username,
            'password' => password_hash('TrainerUnitTestPassword', config('\User\Config\UserConfig')->password_hash_algorithm),
        );

        return \User\Models\User_model::getInstance()->insert($trainer);
    }
}