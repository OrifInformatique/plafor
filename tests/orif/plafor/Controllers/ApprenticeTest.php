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
use CodeIgniter\Test\DatabaseTestTrait;

use User\Models;

// The helper hold all Constants ->
// Plafor\orif\plafor\Helpers\UnitTest_helper.php
helper("UnitTest_helper");

class ApprenticeTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;

    // For Migrations
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = null;

    protected $seedOnce = false;
    protected $basePath = 'tests/_support/Database';
    protected $seed     = 'ApprenticeTestSeed';

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
        $_SESSION['user_id'] = ADMIN_ID;

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
        $_SESSION['user_id'] = TRAINER_DEV_ID;

        // Execute index method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('index');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice?trainer_id='. TRAINER_DEV_ID));
    }

    /**
     * Asserts that the default index route redirects to view_apprentice route (Apprentice session)
     */
    public function testindexWithApprenticeSession()
    {
        // Initialize session
        $_SESSION['logged_in'] = true;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_guest;
        $_SESSION['user_id'] = APPRENTICE_DEV_ID;

        // Execute index method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('index');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/view_apprentice/' . APPRENTICE_DEV_ID));
    }

    /**
     * Asserts that the list_apprentice page is loaded correctly for a given connected development trainer
     */
    public function testlist_apprenticeWithDevelopmentTrainerSession()
    {
        // Initialize session for a development trainer
        $_SESSION['logged_in'] = true;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;
        $_SESSION['user_id'] = TRAINER_DEV_ID;

        // Execute list_apprentice method of Apprentice class
        $result = $this->controller(Apprentice::class)
            ->execute('list_apprentice');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee(TRAINER_DEV_NAME, 'option');
        $result->assertSee(TRAINER_DEV_NAME, 'option');
        $result->assertSee(TRAINER_OPE_NAME, 'option');
        $result->assertSee('Liste des apprentis'); // BUG
        $result->assertSeeLink(APPRENTICE_DEV_NAME);
        $result->assertSee(COURSE_PLAN_DEV_NAME);
        $result->assertDontSee(APPRENTICE_SYS_NAME, 'a');
        $result->assertDontSee(COURSE_PLAN_SYS_NAME);
        $result->assertDontSee(APPRENTICE_OPE_NAME, 'a');
        $result->assertDontSee(COURSE_PLAN_OPE_NAME);
    }

    /**
     * Asserts that the view_apprentice page redirects to the list_apprentice view when no apprentice id is given
     */
    // TODO
    public function testview_apprenticeWithoutApprenticeId()
    {
        // Execute view_apprentice method of Apprentice class
        
        $_SESSION['user_access'] = '';
        $result = $this->controller(Apprentice::class)
            ->execute('view_apprentice');

        // Assertions
        // $response = $result->response();
        // $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        // $this->assertEmpty($response->getBody());
        // $result->assertOK();
        // $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        // $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the view_apprentice page redirects to the list_apprentice view when a trainer id is given
     */
    // TODO
    public function testview_apprenticeWithTrainerId()
    {
        $_SESSION['user_access'] = '';
        // Execute view_apprentice method of Apprentice class
        $result = $this->controller(Apprentice::class)
            ->execute('view_apprentice', TRAINER_DEV_ID);

        // Assertions
        // $response = $result->response();
        // $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        // $this->assertEmpty($response->getBody());
        // $result->assertOK();
        // $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        // $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the view_apprentice page is loaded correctly when an apprentice id is given
     */
    // TODO
    public function testview_apprenticeWithApprenticeId()
    {
        $_SESSION['user_access'] = '';
        // Execute view_apprentice method of Apprentice class
        $result = $this->controller(Apprentice::class)
            ->execute('view_apprentice', APPRENTICE_DEV_ID);

        // Assertions
        // $response = $result->response();
        // $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        // $this->assertNotEmpty($response->getBody());
        // $result->assertOK();
        // $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        // $result->assertSee('Détail de l\'apprenti', 'p');
        // $result->assertSee(APPRENTICE_DEV_NAME, 'span');
        // $result->assertSee(TRAINER_DEV_NAME);
        // $result->assertSeeElement('#usercourseSelector');
        // $CourseName = COURSE_PLAN_DEV_NAME;
        // $result->assertSee($CourseName, 'option');
        // $result->assertSee('09.07.2020', '.user-course-details-begin-date');
        // $result->assertSee('En cours', '.user-course-details-status');
        // $result->assertSee('Avancement du plan de formation', 'p');
        // $result->assertSee($CourseName, '.font-weight-bold user-course-details-course-plan-name');
    }

    /**
     * Asserts that the save_user_course page redirects to the 403 error view when no session user access is set
     */
    public function testsave_user_courseWithoutSessionUserAccess()
    {
        $_SESSION['user_access'] = '';
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
     * Asserts that the save_user_course page redirects to the view_apprentice
     * view when an apprentice id is given for a new user course (inserting an
     * user course and acquisition statuses)
     */
    public function testsave_user_coursePostedWithTrainerSessionWithApprenticeIdAndNewUserCourse()
    {
        $userId = APPRENTICE_DEV_ID;
        $coursePlanId = COURSE_PLAN_DEV_ID;

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

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
        $_POST = [];
        $_REQUEST = [];

        // Get user course from database
        $userCourseModel = model('\Plafor\Models\UserCourseModel');
        $userCourseDb = $userCourseModel->where("fk_user ", $userId)
                            ->where("fk_course_plan", $coursePlanId)->first();

        // Delete acquisition statuses linked to the inserted user course
        $acquisitionStatusModel = model('\Plafor\Models\AcquisitionStatusModel');
        $acquisitionStatusModel->where('fk_user_course', $userCourseDb['id'])
                               ->delete();

        // Delete inserted user course
        $userCourseModel = model('\Plafor\Models\userCourseModel');
        $userCourseModel->delete($userCourseDb['id'], true);

        // Assertions
         $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
         $this->assertEmpty($response->getBody());
         $result->assertOK();
         $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
         $result->assertRedirectTo(
             base_url('plafor/apprentice/list_user_courses/' . $userId));
    }

    /**
     * Asserts that the save_user_course page redirects to the view_apprentice view when an apprentice id is given (updating an existing user course)
     */
    public function testsave_user_coursePostedWithTrainerSessionWithApprenticeIdAndExistingUserCourse()
    {
        $userId = 6;

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
        $_POST = [];
        $_REQUEST = [];

        // Get user course from database
        $userCourseModel = model('\Plafor\Models\userCourseModel');
        $userCourseDb = $userCourseModel->where("fk_user ", $userId)
                        ->where("fk_course_plan", $coursePlanId)->first();

        // Delete acquisition statuses linked to the inserted user course
        $acquisitionStatusModel = model('\Plafor\Models\acquisitionStatusModel');
        $acquisitionStatusModel->where('fk_user_course', $userCourseDb['id'])
                               ->delete();

        // Delete inserted user course
        $userCourseModel->delete($userCourseDb['id'], true);

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
        //  $result->assertRedirectTo(base_url('plafor/apprentice/list_user_courses/' . $userId));// BUG
    }

    /**
     * Asserts that the save_apprentice_link page is not available when apprentice session is set
     */
    public function testsave_apprentice_linkWithApprenticeSession()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_guest;

        // Execute save_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
            ->execute('save_apprentice_link');
        // Assertions
        $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.');
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
        // $result->assertRedirectTo(base_url());// BUG
    }

    /**
     * Asserts that the save_apprentice_link page is loaded correctly when a
     * link between apprentice and trainer is provided
     */
    // Todo
    public function
        _testsave_apprentice_linkWithTrainerSessionWithApprenticeIdAndLinkId()
    {
        $apprenticeId = APPRENTICE_DEV_ID;
        $trainerId = TRAINER_DEV_ID;

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

        // Insert apprentice link
        $apprenticeLinkId = self::insertTrainerApprenticeLink($trainerId,
            $apprenticeId);

        // Execute save_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
                       ->execute('save_apprentice_link', $apprenticeId,
                           $apprenticeLinkId);

        // Delete inserted link
        $trainerApprenticeModel = model('\Plafor\Models\trainerApprenticeModel');
        $trainerApprenticeModel->delete($apprenticeLinkId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        // $this->assertNotEmpty($response->getBody());// BUG
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertDontSee('Ajouter un formateur', 'h1');
        $result->assertSee('Modifer le formateur', 'h1');
        $result->assertSeeElement('#apprentice_link_form');
        $result->assertSeeInField('id', APPRENTICE_DEV_ID);
        $result->assertSee('Nom de l\'apprenti', 'label');
        $result->assertSeeInField('apprentice', APPRENTICE_DEV_ID);
        $result->assertSee(APPRENTICE_DEV_NAME, 'p');
        $result->assertSee('Formateur(s) lié(s)', 'label');
        // $result->assertSee(TRAINER_DEV_NAME, 'option');
        $result->assertSee(TRAINER_DEV_NAME, 'option');
        $result->assertSee(TRAINER_OPE_NAME, 'option');
        $result->assertSeeLink('Annuler');
        $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_apprentice_link page redirects to the
     * view_apprentice view when inserting a new link between and apprentice
     * and a trainer
     */
    public function testsave_apprentice_linkPostedWithTrainerSessionAndApprenticeId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

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
        $_POST = [];
        $_REQUEST = [];

        // Get inserted apprentice link from database
        $trainerApprenticeModel = model('\Plafor\Models\trainerApprenticeModel');
        $apprenticeLink = $trainerApprenticeModel
            ->where("fk_trainer ", $trainerId)
            ->where("fk_apprentice", $apprenticeId)->first();

        // Delete inserted apprentice link
        $trainerApprenticeModel->delete($apprenticeLink['id'], true);

        // Delete inserted apprentice
        $user_model = model('\User\Models\user_model');
        $user_model->delete($apprenticeId, true);

        // Delete inserted trainer
        $user_model->delete($trainerId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(
            base_url('plafor/apprentice/view_apprentice/' . $apprenticeId));
    }

    /**
     * Asserts that the save_apprentice_link page redirects to the
     * view_apprentice view when updating a new link between and apprentice and
     * a trainer
     */
    public function
        testsave_apprentice_linkPostedWithTrainerSessionAndApprenticeIdUpdate()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

        // Insert new apprentice user
        $apprenticeId = self::insertApprentice('ApprenticeUnitTest');

        // Insert new trainer user
        $trainerId = self::insertTrainer('TrainerUnitTest');

        // Insert new second trainer user
        $trainer2Id = self::insertTrainer('Trainer2UnitTest');

        // Insert apprentice link
        $apprenticeLinkId = self::insertTrainerApprenticeLink($trainerId,
            $apprenticeId);

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
        $_POST = [];
        $_REQUEST = [];

        // Delete inserted and updated apprentice link
        $trainerApprenticeModel = model('\Plafor\Models\trainerApprenticeModel');
        $trainerApprenticeModel
            ->delete($apprenticeLinkId, true);

        // Delete inserted apprentice
        $user_model = model('\User\Models\user_model');
        $user_model->delete($apprenticeId, true);

        // Delete inserted trainer
        $user_model->delete($trainerId, true);

        // Delete second inserted trainer
        $user_model->delete($trainer2Id, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('plafor/apprentice/view_apprentice/'
            . $apprenticeId));
    }

    /**
     * Asserts that the delete_apprentice_link page redirects to the 403 error view
     *  when an apprentice session is set
     */
    public function testdelete_apprentice_linkWithApprenticeSession()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url';
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_guest;

        // Execute delete_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_apprentice_link', LINK_DEV_ID);

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
     * Asserts that the delete_apprentice_link redirects to the
     *  list_apprentice view (trainer session and fake action)
     */
    // TODO
    public function _testdelete_apprentice_linkWithTrainerSessionAndFakeAction()
    {
        $apprenticeId = 4;
        $trainerId = 2;

        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url';
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

        // Insert apprentice link
        $apprenticeLinkId = self::insertTrainerApprenticeLink($trainerId, $apprenticeId);

        // Execute delete_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_apprentice_link', $apprenticeLinkId, 9);

        // Delete inserted link
        $trainerApprenticeModel = model('\Plafor\Models\trainerApprenticeModel');
        $trainerApprenticeModel->delete($apprenticeLinkId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(
            base_url('plafor/apprentice/list_apprentice/' . $apprenticeId));
    }

    /**
     * Asserts that the delete_apprentice_link page redirects to the list_apprentice view
     *  when a non existing link is given
     */
    public function testdelete_apprentice_linkWithTrainerSessionForNonExistingLink()
    {
        // Initialize session
        $_SESSION['_ci_previous_url'] = 'url';
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Execute delete_apprentice_link method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_apprentice_link', FAKE_LINK_ID);

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
    // TODO
    public function testview_acquisition_statusWithoutStatusId()
    {
        $_SESSION['user_access'] = '';
        // Execute view_acquisition_status method of Apprentice class
        $result = $this->controller(Apprentice::class)
            ->execute('view_acquisition_status', 1);

        // Assertions
        // $response = $result->response();
        // $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        // $this->assertEmpty($response->getBody());
        // $result->assertOK();
        // $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        // $result->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the save_acquisition_status page redirects to the list_apprentice view when no status id is provided (no session)
     */
    public function testsave_acquisition_statusWithoutStatusIdWithoutSession()
    {
        $_SESSION['user_access'] = '';
        // Execute save_acquisition_status method of Apprentice class
        $result = $this->controller(Apprentice::class)
            ->execute('save_acquisition_status');
        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result
            ->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the save_acquisition_status page redirects to the list_apprentice view when a status id is provided (system apprentice session)
     */
    public function testsave_acquisition_statusWithStatusIdWithSystemApprenticeSession()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['user_id'] = 7;   // System Apprentice

        // Execute save_acquisition_status method of Apprentice class
        // Acquisition status linked to user course linked to development
        // apprentice
        $result = $this->controller(Apprentice::class)
            ->execute('save_acquisition_status', 1);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result
            ->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the save_acquisition_status page is loaded correctly when a
     * status id is provided (development apprentice session)
     */
    // TODO
    public function testsave_acquisition_statusWithStatusIdWithDevelopmentApprenticeSession()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['user_id'] = 6;

        // Execute save_acquisition_status method of Apprentice class
        // Acquisition status linked to user course linked to development
        // apprentice
        $result = $this->controller(Apprentice::class)
            ->execute('save_acquisition_status', 1);

        // Assertions
        // $response = $result->response();
        // $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        // $this->assertNotEmpty($response->getBody());
        // $result->assertOK();
        // $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        // $result->assertSee('Modifier un statut d\'acquisition', 'h1');
        // $result->assertSeeElement('#edit_acquisition_status');
        // $result->assertSee('Niveau d\'acquisition', 'div');
        // $result->assertSeeElement('#field_acquisition_level');
        // $result->assertSee('Non expliqué', 'option');
        // $result->assertSee('Expliqué', 'option');
        // $result->assertSee('Exercé', 'option');
        // $result->assertSee('Autonome', 'option');
        // $result->assertSeeLink('Annuler');
        // $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_acquisition_status function returns a status code
     * 200 when a status id is provided with a new level (development
     * apprentice session)
     */
    public function _testsave_acquisition_statusPostedWithStatusIdAndNewLevelWithDevelopmentApprenticeSession()
    {
        $acquisitionStatusId = 1;
        $acquisitionLevel = 1;

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['user_id'] = APPRENTICE_DEV_ID;

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['field_acquisition_level'] = 3;
        $_REQUEST['field_acquisition_level'] = 3;

        // Execute save_acquisition_status method of Apprentice class
        // Acquisition status linked to user course linked to development
        // apprentice
        $result = $this->controller(Apprentice::class)
            ->execute('save_acquisition_status', $acquisitionStatusId);

        // Reset $_POST and $_REQUEST variables
        $_POST = [];
        $_REQUEST = [];

        // Reset acquisition status level
        $acquisitionStatus = [
            'fk_acquisition_level' => $acquisitionLevel
        ];

        $acquisitionStatusModel = model('\Plafor\Models\acquisitionStatusModel');
        $acquisitionStatusModel->update($acquisitionStatusId,
            $acquisitionStatus);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertStatusCode(200);
    }

    /**
     * Asserts that the save_acquisition_status function returns a status code 200
     *  when a status id is provided with a new level (development apprentice session)
     */
    // TODO
    public function testsave_acquisition_statusPostedWithStatusIdAndNewLevelWithDevelopmentApprenticeSessionAndNonExistingStatusLevel()
    {
        $acquisitionStatusId = 1;

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;
        $_SESSION['user_id'] = APPRENTICE_DEV_ID;

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['field_acquisition_level'] = 5;
        $_REQUEST['field_acquisition_level'] = 5;

        // Execute save_acquisition_status method of Apprentice class
        // Acquisition status linked to user course linked to development
        // apprentice
        $result = $this->controller(Apprentice::class)
        ->execute('save_acquisition_status', $acquisitionStatusId);

        // Reset $_POST and $_REQUEST variables
        $_POST = [];
        $_REQUEST = [];

        // Assertions
        // $response = $result->response();
        // $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        // $this->assertNotEmpty($response->getBody());
        // $result->assertOK();
        // $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        // $result->assertSee('Modifier un statut d\'acquisition', 'h1');
        // $result->assertSeeElement('#edit_acquisition_status');
        // $result->assertSee('Le champ Niveau d\'acquisition doit être un élément de la liste suivante : 1,2,3,4.', 'div');
        // $result->assertSee('Niveau d\'acquisition', 'div');
        // $result->assertSeeElement('#field_acquisition_level');
        // $result->assertSee('Non expliqué', 'option');
        // $result->assertSee('Expliqué', 'option');
        // $result->assertSee('Exercé', 'option');
        // $result->assertSee('Autonome', 'option');
        // $result->assertSeeLink('Annuler');
        // $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_comment page redirects to the list_apprentice view
     * when no status id is provided (no session)
     */
    // TODO
    public function testsave_commentWithoutStatusIdWithoutSession()
    {
        $_SESSION['user_access'] = '';
        // Execute save_comment method of Apprentice class
        $result = $this->controller(Apprentice::class)
            ->execute('save_comment');

        // Assertions
        // $response = $result->response();
        // $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
        //     $response);
        // $this->assertEmpty($response->getBody());
        // $result->assertOK();
        // $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        // $result
        //     ->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the save_comment page redirects to the list_apprentice view
     * when a status id is provided (apprentice session)
     */
    // TODO
    public function testsave_commentWithStatusIdWithApprenticeSession()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_guest;

        // Execute save_comment method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_comment', APPRENTICE_DEV_ID);

        // Assertions
        // $response = $result->response();
        // $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
        //     $response);
        // $this->assertEmpty($response->getBody());
        // $result->assertOK();
        // $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        // $result
        //     ->assertRedirectTo(base_url('plafor/apprentice/list_apprentice'));
    }

    /**
     * Asserts that the save_comment page is loaded correctly when a status id is provided (trainer session)
     */
    // TODO
    public function testsave_commentWithStatusIdWithTrainerSession()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

        // Execute save_comment method of Apprentice class
        $result = $this->controller(Apprentice::class)
            ->execute('save_comment', APPRENTICE_DEV_ID);

        // Assertions
        // $response = $result->response();
        // $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        // $this->assertNotEmpty($response->getBody());
        // $result->assertOK();
        // $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        // $result->assertSee('Ajouter un commentaire', 'h1');
        // $result->assertSee('Commentaire', 'label');
        // $result->assertSeeElement('#comment');
        // $result->assertSeeLink('Annuler');
        // $result->assertSeeInField('save', 'Enregistrer');
    }

    /**
     * Asserts that the save_comment page redirects to the
     * view_acquisition_status view when a status id is provided for a trainer
     * session (inserting a new comment)
     */
    public function _testsave_commentPostedtWithStatusIdWithTrainerSession()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;
        $_SESSION['user_id'] = APPRENTICE_DEV_ID;

        // Insert a new course plan
        $coursePlanId = self::insertCoursePlan();

        // Insert a new competence domain linked to the inserted course plan
        $competenceDomainId = self::insertCompetenceDomain($coursePlanId);

        // Insert a new operational competence linked to the inserted
        // competence domain
        $operational_competenceId = self::insertOperationalCompetence(
            $competenceDomainId);

        // Insert a new objective linked to the inserted operational competence
        $objectiveId = self::insertObjective($operational_competenceId);

        // Insert a new user course linked to the inserted course plan
        $userCourseId = self::insertUserCourse(4, $coursePlanId);

        // Insert a new acquisition status linked to the inserted objective and
        // to the inserted user course
        $acquisitionStatusId = self::insertAcquisitionStatus($objectiveId,
            $userCourseId);

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['comment'] = 'Comment Unit Test';
        $_REQUEST['comment'] = 'Comment Unit Test';

        // Execute save_comment method of Apprentice class
        $result = $this->controller(Apprentice::class)
            ->execute('save_comment', $acquisitionStatusId);

        // Reset $_POST and $_REQUEST variables
        $_POST = [];
        $_REQUEST = [];

        // Delete inserted comment
        $commentModel = model('\Plafor\Models\commentModel');
        $commentModel->where('comment', 'Comment Unit Test')->delete();

        // Delete inserted acquisition status
        $acquisitionStatusModel = model('\Plafor\Models\acquisitionStatusModel');
        $acquisitionStatusModel->delete($acquisitionStatusId, true);

        // Delete inserted user course
        $userCourseModel = model('\Plafor\Models\userCourseModel');
        $userCourseModel->delete($userCourseId, true);

        // Delete inserted objective
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $objectiveModel->delete($objectiveId, true);

        // Delete inserted operational competence
        $operational_competenceModel = model('\Plafor\Models\operationalCompetenceModel');
        $operational_competenceModel->delete($operational_competenceId, true);

        // Delete inserted competence domain
        $competenceDomainModel = model('\Plafor\Models\competenceDomainModel');
        $competenceDomainModel->delete($competenceDomainId, true);

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
        $result->assertRedirectTo(base_url(
            'plafor/apprentice/view_acquisition_status/'
            . $acquisitionStatusId));
    }

    /**
     * Asserts that the save_comment page redirects to the
     * view_acquisition_status view when a status id is provided for a trainer
     * session (inserting a new empty comment)
     */
    public function _testsave_commentPostedtWithStatusIdWithTrainerSessionAndEmptyComment()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;
        $_SESSION['user_id'] = TRAINER_DEV_ID;

        // Insert a new course plan
        $coursePlanId = self::insertCoursePlan();

        // Insert a new competence domain linked to the inserted course plan
        $competenceDomainId = self::insertCompetenceDomain($coursePlanId);

        // Insert a new operational competence linked to the inserted competence domain
        $operational_competenceId = self::insertOperationalCompetence($competenceDomainId);

        // Insert a new objective linked to the inserted operational competence
        $objectiveId = self::insertObjective($operational_competenceId);

        // Insert a new user course linked to the inserted course plan
        $userCourseId = self::insertUserCourse(4, $coursePlanId);

        // Insert a new acquisition status linked to the inserted objective and to the inserted user course
        $acquisitionStatusId = self::insertAcquisitionStatus($objectiveId, $userCourseId);

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['comment'] = '';
        $_REQUEST['comment'] = '';

        // Execute save_comment method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_comment', $acquisitionStatusId);

        // Reset $_POST and $_REQUEST variables
        $_POST = [];
        $_REQUEST = [];

        // Delete inserted acquisition status
        $acquisitionStatusModel = model('\Plafor\Models\acquisitionStatusModel');
        $acquisitionStatusModel->delete($acquisitionStatusId, true);

        // Delete inserted user course
        $userCourseModel = model('\Plafor\Models\userCourseModel');
        $userCourseModel->delete($userCourseId, true);

        // Delete inserted objective
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $objectiveModel->delete($objectiveId, true);

        // Delete inserted operational competence
        $operational_competenceModel = model('\Plafor\Models\operationalCompetenceModel');
        $operational_competenceModel->delete($operational_competenceId, true);

        // Delete inserted competence domain
        $competenceDomainModel = model('\Plafor\Models\competenceDomainModel');
        $competenceDomainModel->delete($competenceDomainId, true);

        // Delete inserted course plan
        $coursePlanModel = model('\Plafor\Models\coursePlanModel');
        $coursePlanModel->delete($coursePlanId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSee('Le champ Commentaire est requis.', 'div');
    }

    /**
     * Asserts that the save_comment page redirects to the
     * view_acquisition_status view when a status id is provided for a trainer
     * session (updating an existing comment)
     */
    public function _testsave_commentPostedtWithStatusIdWithTrainerSessionAndExistingComment()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;
        $_SESSION['user_id'] = TRAINER_DEV_ID;

        // Insert a new course plan
        $coursePlanId = self::insertCoursePlan();

        // Insert a new competence domain linked to the inserted course plan
        $competenceDomainId = self::insertCompetenceDomain($coursePlanId);

        // Insert a new operational competence linked to the inserted competence domain
        $operational_competenceId = self::insertOperationalCompetence($competenceDomainId);

        // Insert a new objective linked to the inserted operational competence
        $objectiveId = self::insertObjective($operational_competenceId);

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

        // Execute save_comment method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('save_comment', $acquisitionStatusId, $commentId);

        // Reset $_POST and $_REQUEST variables
        $_POST = [];
        $_REQUEST = [];

        // Delete inserted comment
        $commentModel = model('\Plafor\Models\commentModel');
        $commentModel->delete($commentId, true);

        // Delete inserted acquisition status
        $acquisitionStatusModel = model('\Plafor\Models\acquisitionStatusModel');
        $acquisitionStatusModel->delete($acquisitionStatusId, true);

        // Delete inserted user course
        $userCourseModel = model('\Plafor\Models\userCourseModel');
        $userCourseModel->delete($userCourseId, true);

        // Delete inserted objective
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $objectiveModel->delete($objectiveId, true);

        // Delete inserted operational competence
        $operational_competenceModel = model('\Plafor\Models\operationalCompetenceModel');
        $operational_competenceModel->delete($operational_competenceId, true);

        // Delete inserted competence domain
        $competenceDomainModel = model('\Plafor\Models\competenceDomainModel');
        $competenceDomainModel->delete($competenceDomainId, true);

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
        $result->assertRedirectTo(base_url('plafor/apprentice/view_acquisition_status/'
            . $acquisitionStatusId));
    }

    /**
     * Asserts that getCoursePlanProgress method returns empty body when
     * no user id and no course plan id are given (no session)
     */
    public function testgetCoursePlanProgressWithoutUserIdAndCoursePlanIdWithoutSession()
    {
        // Execute getCoursePlanProgress method of Apprentice class
        $result = $this->controller(Apprentice::class)
            ->execute('getCoursePlanProgress');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        // $this->assertEmpty($response->getBody());// BUG
    }

    /**
     * Asserts that getCoursePlanProgress method returns a JSON object when an user id (development apprentice)
     *  and no course plan id are given (development apprentice session)
     */
    public function testgetCoursePlanProgressWithDevelopmentApprenticeUserIdWithoutCoursePlanIdWithDevelopmentApprenticeSession()
    {
        // Initialize session
        $_SESSION['user_id'] = APPRENTICE_DEV_ID;

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
     * Asserts that getCoursePlanProgress method returns a JSON object when an
     * user id (development apprentice) and a course plan id are given
     * (development apprentice session)
     */
    public function testgetCoursePlanProgressWithDevelopmentApprenticeUserIdWithCoursePlanIdWithDevelopmentApprenticeSession()
    {
        // Initialize session
        $_SESSION['user_id'] = 4;

        // Execute getCoursePlanProgress method of Apprentice class
        $result = $this->controller(Apprentice::class)
            ->execute('getCoursePlanProgress', 4, 5);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $this->assertJSON($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type',
            'application/json; charset=UTF-8');
    }

    /**
     * Asserts that getCoursePlanProgress method returns a JSON object
     *  when an user id (development apprentice) and no course plan id are given (administrator session)
     */
    public function testgetCoursePlanProgressWithDevelopmentApprenticeUserIdWithoutCoursePlanIdWithAdministratorSession()
    {
        // Initialize session
        $_SESSION['user_id'] = ADMIN_ID;
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
     * Asserts that getCoursePlanProgress method returns a JSON object
     *  when an user id (development apprentice) and a course plan id are given (administrator session)
     */
    public function testgetCoursePlanProgressWithDevelopmentApprenticeUserIdWithCoursePlanIdWithAdministratorSession()
    {
        // Initialize session
        $_SESSION['user_id'] = ADMIN_ID;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;

        // Execute getCoursePlanProgress method of Apprentice class
        $result = $this->controller(Apprentice::class)
            ->execute('getCoursePlanProgress', 4, 5);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $this->assertJSON($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'application/json; charset=UTF-8');
    }

    /**
     * Asserts that getCoursePlanProgress method returns a JSON object
     * when an user id (development apprentice) and no course plan id are given (trainer session)
     */
    public function testgetCoursePlanProgressWithDevelopmentApprenticeUserIdWithoutCoursePlanIdWithTrainerSession()
    {
        // Initialize session
        $_SESSION['user_id'] = TRAINER_DEV_ID;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_trainer;

        // Execute getCoursePlanProgress method of Apprentice class
        $result = $this->controller(Apprentice::class)
            ->execute('getCoursePlanProgress', 4);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $this->assertJSON($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type',
            'application/json; charset=UTF-8');
    }

    /**
     * Asserts that getCoursePlanProgress method returns a JSON object when an user id (development apprentice) and a course plan id are given (trainer session)
     */
    public function testgetCoursePlanProgressWithhDevelopmentApprenticeUserIdWithCoursePlanIdWithTrainerSession()
    {
        // Initialize session
        $_SESSION['user_id'] = APPRENTICE_DEV_ID;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_trainer;

        // Execute getCoursePlanProgress method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('getCoursePlanProgress', 4, 5);

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
     * Asserts that view_user_course page is loaded correctly when an user
     * course id is given for a given system apprentice
     */
    public function _testview_user_courseWithUserCourseIdLinkedToASystemApprentice()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_level_apprentice;
        $_SESSION['user_id'] = 6;

        $_SERVER['QUERY_STRING'] = 'fake';

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
        $result->assertSeeLink(APPRENTICE_DEV_NAME);
        $result->assertSee('Statuts d\'acquisition des objectifs', 'p');
        $result->assertSeeLink('A.1.1');
        $result->assertSeeLink('Enregistrer les besoins et discuter les ' .
            'solutions possibles, s’entretenir avec le client/supérieur sur ' .
            'les restrictions des exigences');

    }

    /**
     * Asserts that view_user_course page is loaded correctly when an user
     * course id is given for a given system apprentice (connexion with an
     * administrator account)
     */
    public function _testview_user_courseWithUserCourseIdLinkedToASystemApprenticeWithAdministratorAccount()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;
        $_SESSION['user_id'] = ADMIN_ID;
        $_SERVER['QUERY_STRING'] = 'fake';

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
        $result->assertSeeLink(APPRENTICE_SYS_NAME);
        $result->assertSee('Statuts d\'acquisition des objectifs', 'p');
        $result->assertSeeLink('A.1.1');
        $result->assertSeeLink('Etre capable de recevoir, comprendre, planifier et mettre en œuvre un mandat client (organisation, méthodologie, ergonomie, optimisation de l’énergie)');
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
        $_SESSION['user_id'] = ADMIN_ID;

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
     * Asserts that the delete_user redirects to the list_user view (when the action is not equal to 0, 1 or 2)
     */
    public function testdelete_userWithFakeAction()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;
        $_SESSION['user_id'] = ADMIN_ID;

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
        $_SESSION['user_id'] = ADMIN_ID;

        // Execute delete_user method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_user', 1, 1);

        // Assertions
        // $response = $result->response();
        // $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        // $this->assertEmpty($response->getBody());
        // $result->assertOK();
        // $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        // $result->assertRedirectTo(base_url('/user/admin/list_user'));
    }

    /**
     * Asserts that the delete_user redirects to the list_user view (when the action equals 1)
     */
    public function testdelete_userWithDisableAction()
    {
        $user_id = 4;

        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;
        $_SESSION['user_id'] = ADMIN_ID;

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
        $user_model = model('\User\Models\user_model');
        $user_model->update($user_id, ['archive' => NULL]);
    }

    /**
     * Asserts that the delete_user redirects to the list_user view (when the action equals 2)
     * The user won't be deleted because the user_id is equal to the session user id
     */
    // TODO
    public function testdelete_userWithDeleteActionForSameUserId()
    {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_admin;
        $_SESSION['user_id'] = ADMIN_ID;

        // Execute delete_user method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_user', 1, 2);

        // Assertions
        // $response = $result->response();
        // $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        // $this->assertEmpty($response->getBody());
        // $result->assertOK();
        // $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        // $result->assertRedirectTo(base_url('/user/admin/list_user'));
    }

    /**
     * Asserts that the delete_user redirects to the list_user view (when the
     * action equals 2)
     */
    public function _testdelete_userWithDeleteAction() {
        // Initialize session
        $_SESSION['user_access'] = config('\User\Config\UserConfig')
            ->access_lvl_admin;
        $_SESSION['user_id'] = ADMIN_ID;

        // Insert new apprentice user
        $apprenticeId = self::insertApprentice('ApprenticeUnitTest');

        // Insert new trainer user
        $trainerId = self::insertTrainer('TrainerUnitTest');

        // Insert apprentice link
        $apprenticeLinkId = self::insertTrainerApprenticeLink($trainerId,
            $apprenticeId);

        // Insert a new course plan
        $coursePlanId = self::insertCoursePlan();

        // Insert a new competence domain linked to the inserted course plan
        $competenceDomainId = self::insertCompetenceDomain($coursePlanId);

        // Insert a new operational competence linked to the inserted
        // competence domain
        $operational_competenceId = self::insertOperationalCompetence($competenceDomainId);

        // Insert a new objective linked to the inserted operational competence
        $objectiveId = self::insertObjective($operational_competenceId);

        // Insert a new user course linked to the inserted course plan
        $userCourseId = self::insertUserCourse($apprenticeId, $coursePlanId);

        // Insert a new acquisition status linked to the inserted objective and
        // to the inserted user course
        $acquisitionStatusId = self::insertAcquisitionStatus($objectiveId,
            $userCourseId);

        // Insert a new comment linked to the acquisition status
        $commentId = self::insertComment($trainerId, $acquisitionStatusId);

        // Execute delete_user method of Apprentice class
        $result = $this->controller(Apprentice::class)
        ->execute('delete_user', $apprenticeId, 2);

        // Delete inserted objective
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $objectiveModel->delete($objectiveId, true);

        // Delete inserted operational competence
        $operational_competenceModel = model('\Plafor\Models\operationalCompetenceModel');
        $operational_competenceModel->delete($operational_competenceId, true);

        // Delete inserted competence domain
        $competenceDomainModel = model('\Plafor\Models\competenceDomainModel');
        $competenceDomainModel->delete($competenceDomainId, true);

        // Delete inserted course plan
        $coursePlanModel = model('\Plafor\Models\coursePlanModel');
        $coursePlanModel->delete($coursePlanId, true);

        // Delete inserted trainer user
        $user_model = model('\User\Models\user_model');
        $user_model->delete($trainerId, true);

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class,
            $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('/user/admin/list_user'));
    }

    /**
     * Assert that the delete_comment is error message.
     *
     */
    public function testdelete_comment() {
        $_SESSION['user_access'] = '';

        // Execute delete_comment method of Apprentice class
        $result = $this->controller(Apprentice::class)
            ->execute('delete_comment', 6, 1);
        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        // $result->assertSee('Vous n\'êtes pas autorisé à accéder à cette fonction.', 'p'); // BUG
    }

    /**
     * Insert a course plan into database
     */
    private static function insertCoursePlan() : int {
        $coursePlan = array(
            'formation_number' => 12345,
            'official_name' => 'Course Plan Unit Test',
            'date_begin' => '2023-04-05'
        );
        $coursePlanModel = model('\Plafor\Models\coursePlanModel');
        $id = $coursePlanModel->insert($coursePlan);
        assert($id, 'CoursePlan is not created.');
        return $id;
    }

    /**
     * Insert a competence domain linked to a course plan into database
     */
    private static function insertCompetenceDomain(int $coursePlanId) : int {
        $_POST['type'] = 'competence_domain';
        $_POST['id'] = 0;
        $_POST['fk_course_plan'] = $coursePlanId;
        $competence_domain = array(
            'symbol' => 'ZZZZZZZZZZ',
            'name' => 'Competence Domain Unit Test',
            'fk_course_plan' => $coursePlanId,
            'id' => 0
        );
        $model = model('\Plafor\Models\CompetenceDomainModel');

        $id = $model->insert($competence_domain);
        assert($id, 'CompetenceDomain is not created.');
        return $id;
    }

    /**
     * Insert an operational competence linked to a competence domain into
     * database
     */
    private static function insertOperationalCompetence(int $competenceDomainId) : int
    {
        $operational_competence = array(
            'id' => 0,
            'symbol' => 'ZZZZZZZZZZ',
            'name' => 'Operational Competence Unit Test',
            'methodologic' => 'Operational Competence Unit Test',
            'social' => 'Operational Competence Unit Test',
            'personal' => 'Operational Competence Unit Test',
            'fk_competence_domain' => $competenceDomainId
        );
        $operational_competenceModel = model('\Plafor\Models\operationalCompetenceModel');
        $id = $operational_competenceModel->insert($operational_competence);
        assert($id, 'OperationalCompetence is not created');
        return $id;
    }

    /**
     * Insert an objective linked to an operational competence into database
     */
    private static function insertObjective(int $operational_competenceId) : int
    {
        $objective = array(
            'symbol' => 'ZZZZZZZZZZ',
            'taxonomy' => 99999,
            'name' => 'Objective Unit Test',
            'fk_operational_competence' => $operational_competenceId
        );
        $objectiveModel = model('\Plafor\Models\objectiveModel');
        $id = $objectiveModel->insert($objective);
        assert($id, 'Objective is not created.');
        return $id;
    }

    /**
     * Insert an user course linked to a course plan into database
     */
    private static function insertUserCourse(int $userId,
        int $coursePlanId) : int
    {
        $userCourse = array(
            'fk_user' => $userId,
            'fk_course_plan' => $coursePlanId,
            'fk_status' => 1,
            'date_begin' => '2023-04-19',
            'date_end' => '0000-00-00',
        );
        $userCourseModel = model('\Plafor\Models\userCourseModel');
        $id = $userCourseModel->insert($userCourse);
        assert($id, 'UserCourse is not created.');
        return $id;
    }

    /**
     * Insert an acquisition status linked to an objective and an user course
     * into database
     */
    private static function insertAcquisitionStatus(int $objectiveId,
        int $userCourseId) : int
    {
        $acquisitionStatus = array(
            'fk_objective' => $objectiveId,
            'fk_user_course' => $userCourseId,
            'fk_acquisition_level' => 1
        );
        $acquisitionStatusModel = model('\Plafor\Models\acquisitionStatusModel');
        $id = $acquisitionStatusModel->insert($acquisitionStatus);
        assert($id, 'AcquisitionStatus is not created.');
        return $id;
    }

    /**
     * Insert a comment linked to an acquisition status into database
     */
    private static function insertComment($trainerId, $acquisitionStatusId) : int
    {
        $comment = array(
            'fk_trainer' => $trainerId,
            'fk_acquisition_status' => $acquisitionStatusId,
            'comment' => 'Comment Unit Test',
            'date_creation' => date('Y-m-d H:i:s'),
        );

        $commentModel = model('\Plafor\Models\commentModel');
        return $commentModel->insert($comment);
    }

    /**
     * Insert a link between a trainer and an apprentice into database
     */
    private static function insertTrainerApprenticeLink($trainerId,
        $apprenticeId) : int
    {
        $apprenticeLink = array(
            'fk_trainer' => $trainerId,
            'fk_apprentice' => $apprenticeId,
        );

        $trainerApprenticeModel = model('\Plafor\Models\trainerApprenticeModel');
        return $trainerApprenticeModel->insert($apprenticeLink);
    }

    /**
     * Insert an apprentice into database
     */
    private static function insertApprentice(string $username): int {
        assert($username);
        $apprentice = array(
            'fk_user_type' => APPRENTICE_USER_TYPE,
            'username' => $username,
            'password' => 'password',
            'password_confirm' => 'password',
            'email' => $username . '@apprentice.unitest'
        );
        $userModel = model('\User\Models\User_model');
        $id = $userModel->insert($apprentice);
        assert($id, 'Apprentice is not created.');
        return $id;
    }

    /**
     * Insert a trainer into database
     */
    private static function insertTrainer(string $username) : int
    {
        assert($username);
        $trainer = array(
            'fk_user_type' => TRAINER_USER_TYPE,
            'username' => $username,
            'password' => 'password',
            'password_confirm' => 'password',
            'email' => $username . '@trainer.unitest'
        );
        $userModel = model('\User\Models\User_model');
        $id = $userModel->insert($trainer);
        assert($id, 'Trainer is not created.');
        return $id;
    }
}
