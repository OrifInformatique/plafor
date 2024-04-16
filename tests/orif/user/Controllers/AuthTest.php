<?php
/**
 * Unit tests AuthTest
 *
 * @author      Orif (CaLa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

 namespace User\Controllers;

 use CodeIgniter\Test\CIUnitTestCase;
 use CodeIgniter\Test\ControllerTestTrait;
 
 class AuthTest extends CIUnitTestCase
{
    use ControllerTestTrait;

    const APPRENTICE_USER_TYPE = 3;

    /**
     * Asserts that the login page is loaded correctly (no session)
     */
    public function testloginPageWithoutSession()
    {
        // Execute login method of Auth class
        $result = $this->controller(Auth::class)
        ->execute('login');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSeeElement('#username');
        $result->assertSeeElement('#password');
        $result->assertSeeElement('#btn_cancel');
        $result->assertSeeElement('#btn_login');
        $result->assertDontSeeElement('#fake_element');
        $result->assertSeeInField('username', '');
        $result->assertSeeInField('password', '');
        $result->assertSeeLink('Se connecter');
    }

    /**
     * Asserts that the session variable after_login_redirect is correctly set when posting the login page
     */
    public function testloginPagePostedAfterLoginRedirectWithoutSession()
    {
        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['after_login_redirect'] = 'test';
        $_REQUEST['after_login_redirect'] = 'test';

        // Execute login method of Auth class
        $result = $this->controller(Auth::class)
            ->execute('login');

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $this->assertEquals($_SESSION['after_login_redirect'], 'test');
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    }
    
    /**
     * Asserts that the session variable is correctly set when posting the login page (simulates a click on button login)
     * Username and incorrect password are specified (meaning that a warning message is displayed)
     */
    public function testloginPagePostedWithoutSessionWithUsernameAndIncorrectPassword()
    {
        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['btn_login'] = 'true';
        $_REQUEST['btn_login'] = 'true';
        $_POST['username'] = 'admin';
        $_REQUEST['username'] = 'admin';
        $_POST['password'] = 'adminPwd';
        $_REQUEST['password'] = 'adminPwd';

        // Execute login method of Auth class
        $result = $this->controller(Auth::class)
            ->execute('login');

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $this->assertEquals($_SESSION['message-danger'], 'L\'identifiant et le mot de passe ne sont pas valides');
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    /**
     * Asserts that the session variables are correctly set when posting the login page (simulates a click on button login)
     * Username and password are specified (meaning that the login works)
     */
    public function testloginPagePostedWithoutSessionWithUsernameAndPassword()
    {
        // Inserts user into database
        $userType = self::APPRENTICE_USER_TYPE;
        $username = 'ApprenticeUnitTest';
        $userPassword = 'ApprenticeUnitTestPassword';
        $userId = self::insertUser($userType, $username, NULL, $userPassword);

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['btn_login'] = 'true';
        $_REQUEST['btn_login'] = 'true';
        $_POST['username'] = $username;
        $_REQUEST['username'] = $username;
        $_POST['password'] = $userPassword;
        $_REQUEST['password'] = $userPassword;

        // Execute login method of Auth class
        $result = $this->controller(Auth::class)
            ->execute('login');

        // Deletes inserted user 
        \User\Models\User_model::getInstance()->delete($userId, TRUE);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Assertions
        $this->assertEquals($_SESSION['user_id'], $userId);
        $this->assertEquals($_SESSION['username'], $username);
        $this->assertTrue($_SESSION['logged_in']);
    }

    /**
     * Asserts that the session variables are correctly set when posting the login page (simulates a click on button login)
     * User email and password are specified (meaning that the login works)
     */
    public function testloginPagePostedWithoutSessionWithUserEmailAndPassword()
    {
        // Inserts user into database
        $userType = self::APPRENTICE_USER_TYPE;
        $username = 'ApprenticeEmailUnitTest';
        $userEmail = 'apprenticeemailunittest@unittest.com';
        $userPassword = 'ApprenticeEmailUnitTestPassword';
        $userId = self::insertUser($userType, $username, $userEmail, $userPassword);

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['btn_login'] = 'true';
        $_REQUEST['btn_login'] = 'true';
        $_POST['username'] = $userEmail;
        $_REQUEST['username'] = $userEmail;
        $_POST['password'] = $userPassword;
        $_REQUEST['password'] = $userPassword;

        // Execute login method of Auth class
        $result = $this->controller(Auth::class)
            ->execute('login');

        // Deletes inserted user 
        \User\Models\User_model::getInstance()->delete($userId, TRUE);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();     

        // Assertions
        $this->assertEquals($_SESSION['user_id'], $userId);
        $this->assertEquals($_SESSION['username'], $username);
        $this->assertTrue($_SESSION['logged_in']);
    }

    /**
     * Asserts that the login page is redirected 
     */
    public function testloginPageWithSession()
    {
        // Initialize session
        $_SESSION['logged_in'] = true;

        // Execute login method of Auth class
        $result = $this->controller(Auth::class)
        ->execute('login');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url());
    }

    /**
     * Asserts that the change_password page is redirected (no session)
     */
    public function testchange_passwordPageWithoutSession()
    {
        // Execute change_password method of Auth class
        $result = $this->controller(Auth::class)
        ->execute('change_password');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url('user/auth/login'));
    }

    /**
     * Asserts that the change_password page is loaded correctly (with session)
     */
    public function testchange_passwordPageWithSession()
    {
        // Initialize session
        $_SESSION['logged_in'] = true;
        $_SESSION['user_access'] = config('\User\Config\UserConfig')->access_lvl_guest;

        // Execute change_password method of Auth class
        $result = $this->controller(Auth::class)
        ->execute('change_password');

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\Response::class, $response);
        $this->assertNotEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertSeeElement('#old_password');
        $result->assertSeeElement('#new_password');
        $result->assertSeeElement('#confirm_password');
        $result->assertSeeElement('#btn_change_password');
        $result->assertDontSeeElement('#fake_element');
        $result->assertSeeInField('old_password', '');
        $result->assertSeeInField('new_password', '');
        $result->assertSeeLink('Annuler');
    }
    
    /**
     * Asserts that the change_password page redirects to the base url when the password is changed successfully
     */
    public function testchange_passwordPagePostedWithSessionWithOldAndNewPasswords()
    {
        // Inserts user into database
        $userType = self::APPRENTICE_USER_TYPE;
        $username = 'ApprenticeUnitTest';
        $userPassword = 'ApprenticeUnitTestPassword';
        
        $userId = self::insertUser($userType, $username, NULL, $userPassword);

        // Prepare the POST request
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_POST['btn_change_password'] = 'true';
        $_REQUEST['btn_change_password'] = 'true';
        $_POST['old_password'] = $userPassword;
        $_REQUEST['old_password'] = $userPassword;
        $_POST['new_password'] = 'PasswordChanged';
        $_REQUEST['new_password'] = 'PasswordChanged';
        $_POST['confirm_password'] = 'PasswordChanged';
        $_REQUEST['confirm_password'] = 'PasswordChanged';

        // Initialize the session
        $_SESSION['logged_in'] = true;
        $_SESSION["username"] = $username;
        $_SESSION['user_id'] = $userId;

        // Execute change_password method of Auth class
        $result = $this->controller(Auth::class)
            ->execute('change_password');

        // Deletes inserted user
        \User\Models\User_model::getInstance()->delete($userId, TRUE);

        // Reset $_POST and $_REQUEST variables
        $_POST = array();
        $_REQUEST = array();

        // Assertions
        $response = $result->response();
        $this->assertInstanceOf(\CodeIgniter\HTTP\RedirectResponse::class, $response);
        $this->assertEmpty($response->getBody());
        $result->assertOK();
        $result->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $result->assertRedirectTo(base_url());
    }

    /**
     * Asserts that logout resets the session
     */
    public function testlogout()
    {
        // Initialize session
        $_SESSION['logged_in'] = true;

        // Assertion
        $this->assertNotEmpty($_SESSION);

        // Execute logout method of Auth class
        $result = $this->controller(Auth::class)
        ->execute('logout');

        // Assertion
        $this->assertEmpty($_SESSION);
    }

    /**
     * Insert a new user into database
     */
    private static function insertUser($userType, $username, $userEmail, $userPassword) {
        $user = array(
            'fk_user_type' => $userType,
            'username' => $username,
            'email' => $userEmail,
            'password' => password_hash($userPassword, config('\User\Config\UserConfig')->password_hash_algorithm),
        );

        return \User\Models\User_model::getInstance()->insert($user);
    }
}