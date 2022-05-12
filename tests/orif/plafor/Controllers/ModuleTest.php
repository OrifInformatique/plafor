<?php

namespace Plafor\Controllers;

// CodeIgniter looks for it in a specific location, which isn't disclosed anywhere.
// As such, I've decided to not waste time searching for it.
include_once __DIR__ . '/../Models/ModuleFabricator.php';
include_once __DIR__ . '/../Models/CoursePlanFabricator.php';

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\Fabricator;
use Plafor\Models\CoursePlanModel;
use Plafor\Models\CoursePlanModuleModel;
use Plafor\Models\ModuleModel;
use Tests\Support\Models\CoursePlanFabricator;
use Tests\Support\Models\ModuleFabricator;

/**
 * Tests for the Module controller
 */
class ModuleTest extends CIUnitTestCase
{
    use ControllerTestTrait, DatabaseTestTrait;

    /** @var Fabricator */
    private static $module_fabricator;
    /** @var Fabricator */
    private static $course_fabricator;

    protected $namespace = ['User', 'Plafor'];
    protected $refresh = TRUE;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$module_fabricator = new Fabricator(new ModuleFabricator());
        self::$course_fabricator = new Fabricator(new CoursePlanFabricator());
    }

    public function setUp(): void
    {
        parent::setUp();

        session()->set('user_access', config('\User\Config\UserConfig')->access_lvl_admin);
        session()->set('logged_in', TRUE);
        session()->set('_ci_previous_url', base_url());

        // Insert dummy modules
        ModuleModel::getInstance()->skipValidation(TRUE);
        foreach (self::$module_fabricator->make(10) as $i => $module) {
            if ($i <= 7) {
                $module['archive'] = NULL;
            }
            $module['module_number'] = str_pad($i, config('\Plafor\Config\PlaforConfig')->MODULE_NUMBER_MIN_LENGTH, '0', STR_PAD_LEFT);
            ModuleModel::getInstance()->insert($module);
        }
        ModuleModel::getInstance()->skipValidation(FALSE);

        // Insert dummy course plans
        CoursePlanModel::getInstance()->skipValidation(TRUE);
        foreach (self::$course_fabricator->make(5) as $i => $course_plan) {
            if ($i < 4) {
                $course_plan['archive'] = NULL;
            }
            $course_plan['formation_number'] = str_pad($i, config('\Plafor\Config\PlaforConfig')->FORMATION_NUMBER_MAX_LENGTH, '0', STR_PAD_LEFT);
            CoursePlanModel::getInstance()->insert($course_plan);
        }
        CoursePlanModel::getInstance()->skipValidation(FALSE);

        // Link dummy course plans and dummy modules
        /** @var array<int,int[]> */
        $links = [
            1 => [1, 2, 3],
            2 => [1, 3, 5, 7],
            3 => [2, 4, 6],
        ];
        CoursePlanModuleModel::getInstance()->skipValidation(TRUE);
        foreach ($links as $course_plan_id => $modules_ids) {
            foreach ($modules_ids as $module_id) {
                CoursePlanModuleModel::getInstance()->insert([
                    'fk_course_plan' => $course_plan_id,
                    'fk_module' => $module_id,
                ]);
            }
        }
        CoursePlanModuleModel::getInstance()->skipValidation(FALSE);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        // Clear POST data
        global $_POST;
        foreach ($_POST as $key => $v) {
            unset($_POST[$key]);
        }
    }

    /**
     * Provider for testIndex
     *
     * @return array
     */
    public function indexProvider(): array
    {
        return [
            'Not logged in' => [
                'logged_in' => FALSE,
                'user_access' => 0,
                'redirected_to' => base_url('user/auth/login'),
            ],
            'Logged in as guest' => [
                'logged_in' => TRUE,
                'user_access' => config('\User\Config\UserConfig')->access_lvl_guest,
                'redirected_to' => base_url(),
            ],
            'Logged in as admin' => [
                'logged_in' => TRUE,
                'user_access' => config('\User\Config\UserConfig')->access_lvl_admin,
                'redirected_to' => base_url('plafor/module/list_modules'),
            ],
        ];
    }

    /**
     * Tests Module::index
     *
     * @dataProvider indexProvider
     * @group Modules
     * @param  boolean $logged_in Whether the test is done logged in
     * @param  integer $user_access Access given to the "user"
     * @param  string  $expected_redirect_url Expected redirection to
     * @return void
     */
    public function testIndex(bool $logged_in, int $user_access, string $expected_redirect_url): void
    {
        // Setup
        session()->set('logged_in', $logged_in);
        session()->set('user_access', $user_access);

        // Test
        /** @var \CodeIgniter\Test\TestResponse */
        $result = $this->withUri(base_url('plafor/module'))
            ->controller(\Plafor\Controllers\Module::class)
            ->execute('index');

        // Assert
        $result->assertRedirect();
        $result->assertRedirectTo($expected_redirect_url);
    }

    /**
     * Provider for testListModuleAccess
     *
     * @return array
     */
    public function accessProvider(): array
    {
        /** @var \Config\UserConfig */
        $user_config = config('\User\Config\UserConfig');

        return [
            'Not logged in' => [
                'user_access' => NULL,
                'expect_redirect' => TRUE,
                'expect_403' => FALSE,
            ],
            'Logged in as nothing' => [
                'user_access' => 0,
                'expect_redirect' => FALSE,
                'expect_403' => TRUE,
            ],
            'Logged in as apprentice' => [
                'user_access' => $user_config->access_level_apprentice,
                'expect_redirect' => FALSE,
                'expect_403' => TRUE,
            ],
            'Logged in as admin' => [
                'user_access' => $user_config->access_lvl_admin,
                'expect_redirect' => FALSE,
                'expect_403' => FALSE,
            ],
            'Logged in as guest' => [
                'user_access' => $user_config->access_lvl_guest,
                'expect_redirect' => FALSE,
                'expect_403' => TRUE,
            ],
            'Logged in as registered' => [
                'user_access' => $user_config->access_lvl_registered,
                'expect_redirect' => FALSE,
                'expect_403' => TRUE,
            ],
            'Logged in as trainer' => [
                'user_access' => $user_config->access_lvl_trainer,
                'expect_redirect' => FALSE,
                'expect_403' => TRUE,
            ],
        ];
    }

    /**
     * Tests whether Module::list_modules redirects with specific access
     *
     * @dataProvider accessProvider
     * @group Modules
     * @param  integer|null $user_access Access given to the "user", NULL if no access is set.
     * @param  boolean      $expect_redirect Whether redirection is expected
     * @param  boolean      $expect_403 Whether a 403 page is expected
     * @return void
     */
    public function testListModulesAccess(?int $user_access, bool $expect_redirect, bool $expect_403): void
    {
        // Setup
        if (is_null($user_access)) {
            session()->remove('user_access');
            session()->remove('logged_in');
        } else {
            session()->set('user_access', $user_access);
            session()->set('logged_in', TRUE);
        }

        // Test
        /** @var \CodeIgniter\Test\TestResponse */
        $result = $this->withUri(base_url('plafor/module/list_modules'))
            ->controller(\Plafor\Controllers\Module::class)
            ->execute('list_modules');

        // Assert
        if ($expect_redirect) {
            $result->assertRedirect();
        } else {
            $result->assertNotRedirect();

            if ($expect_403) {
                $result->assertSee('403');
            } else {
                $result->assertDontSee('403');
            }
        }
    }

    /**
     * Provider for testViewModule
     *
     * @return array
     */
    public function viewModuleProvider(): array
    {
        return [
            'Module 0' => [
                'module_id' => 0,
                'expect_redirect' => TRUE,
            ],
            'No module' => [
                'module_id' => NULL,
                'expect_redirect' => TRUE,
            ],
            'Module 1' => [
                'module_id' => 1,
                'expect_redirect' => FALSE,
            ],
            'Module 5' => [
                'module_id' => 5,
                'expect_redirect' => FALSE,
            ],
            'Module 9 (archived)' => [
                'module_id' => 9,
                'expect_redirect' => FALSE,
            ],
            'Inexistant Module' => [
                'module_id' => 999,
                'expect_redirect' => TRUE,
            ],
        ];
    }

    /**
     * Tests Module::view_module
     *
     * @dataProvider viewModuleProvider
     * @group Modules
     * @param  integer|null $module_id ID of the module to view
     * @param  boolean      $expect_redirect Whether a redirect is expected
     * @return void
     */
    public function testViewModule(?int $module_id, bool $expect_redirect): void
    {
        // Test
        /** @var \CodeIgniter\Test\TestResponse */
        $result = $this->withUri(base_url('plafor/module/view_module'))
            ->controller(\Plafor\Controllers\Module::class)
            ->execute('view_module', $module_id);

        // Assert
        if ($expect_redirect) {
            $result->assertRedirect();
        } else {
            $result->assertNotRedirect();
        }
    }

    /**
     * Tests whether Module::view_module redirects with specific access
     *
     * @dataProvider accessProvider
     * @group Modules
     * @param  integer|null $user_access Access given to the "user", NULL if no access is set.
     * @param  boolean      $expect_redirect Whether redirection is expected
     * @param  boolean      $expect_403 Whether a 403 page is expected
     * @return void
     */
    public function testViewModuleAccess(?int $user_access, bool $expect_redirect, bool $expect_403): void
    {
        // Setup
        if (is_null($user_access)) {
            session()->remove('user_access');
            session()->remove('logged_in');
        } else {
            session()->set('user_access', $user_access);
            session()->set('logged_in', TRUE);
        }

        // Test
        /** @var \CodeIgniter\Test\TestResponse */
        $result = $this->withUri(base_url('plafor/module/view_module'))
            ->controller(\Plafor\Controllers\Module::class)
            ->execute('view_module', 1);

        // Assert
        if ($expect_redirect) {
            $result->assertRedirect();
        } else {
            $result->assertNotRedirect();

            if ($expect_403) {
                $result->assertSee('403');
            } else {
                $result->assertDontSee('403');
            }
        }
    }

    /**
     * Provider for testSaveModule
     *
     * @return array
     */
    public function saveModuleProvider(): array
    {
        return [
            'Show new module' => [
                'module_id' => 0,
                'post_data' => NULL,
                'expect_redirect' => FALSE,
                'expect_errors' => FALSE,
            ],
            'Show module 3' => [
                'module_id' => 3,
                'post_data' => NULL,
                'expect_redirect' => FALSE,
                'expect_errors' => FALSE,
            ],
            'Insert dummy module' => [
                'module_id' => 0,
                'post_data' => [
                    'module_number' => '100',
                    'official_name' => 'Dummy module',
                ],
                'expect_redirect' => TRUE,
                'expect_errors' => FALSE,
            ],
            'Fail insert module with number too big' => [
                'module_id' => 0,
                'post_data' => [
                    'module_number' => '1111',
                    'official_name' => 'Dummy module',
                ],
                'expect_redirect' => FALSE,
                'expect_errors' => TRUE,
            ],
            'Fail insert module without name' => [
                'module_id' => 0,
                'post_data' => [
                    'module_number' => '111',
                    'official_name' => '',
                ],
                'expect_redirect' => FALSE,
                'expect_errors' => TRUE,
            ],
            'Fail insert module with number too big and no name' => [
                'module_id' => 0,
                'post_data' => [
                    'module_number' => '1111',
                    'official_name' => '',
                ],
                'expect_redirect' => FALSE,
                'expect_errors' => TRUE,
            ],
            'Update module 3' => [
                'module_id' => 3,
                'post_data' => [
                    'module_number' => '100',
                    'official_name' => 'Dummy module',
                ],
                'expect_redirect' => TRUE,
                'expect_errors' => FALSE,
            ],
            'Update module 3 with POST module id' => [
                'module_id' => 999,
                'post_data' => [
                    'module_number' => '100',
                    'official_name' => 'Dummy module',
                    'module_id' => 3,
                ],
                'expect_redirect' => TRUE,
                'expect_errors' => FALSE,
            ],
            'Fail update with number too big' => [
                'module_id' => 3,
                'post_data' => [
                    'module_number' => '1111',
                    'official_name' => 'Dummy module',
                ],
                'expect_redirect' => FALSE,
                'expect_errors' => TRUE,
            ],
            'Fail update without name' => [
                'module_id' => 3,
                'post_data' => [
                    'module_number' => '111',
                    'official_name' => '',
                ],
                'expect_redirect' => FALSE,
                'expect_errors' => TRUE,
            ],
            'Fail update with number too big and no name' => [
                'module_id' => 3,
                'post_data' => [
                    'module_number' => '1111',
                    'official_name' => '',
                ],
                'expect_redirect' => FALSE,
                'expect_errors' => TRUE,
            ],
            'Fail display inexistant module' => [
                'module_id' => 999,
                'post_data' => NULL,
                'expect_redirect' => TRUE,
                'expect_errors' => FALSE,
            ],
        ];
    }

    /**
     * Tests Module::save_module
     *
     * @dataProvider saveModuleProvider
     * @group Modules
     * @param  integer    $module_id ID of the module
     * @param  array|null $post_data Data to put in $_POST
     * @param  boolean    $expect_redirect Whether a redirect is expected
     * @param  boolean    $expect_errors Whether the module model has errors
     * @return void
     */
    public function testSaveModule(int $module_id, ?array $post_data, bool $expect_redirect, bool $expect_errors): void
    {
        // Setup
        global $_POST;
        $keys = ['module_number', 'official_name', 'is_school'];
        foreach ($keys as $key) {
            if (!is_null($post_data) && array_key_exists($key, $post_data)) {
                $_POST[$key] = $post_data[$key];
            }
        }
        // Reset errors by removing the existing instance
        (new \ReflectionClass(ModuleModel::class))->setStaticPropertyValue('moduleModel', null);

        // Test
        /** @var \CodeIgniter\Test\TestResponse */
        $result = $this->withUri(base_url('plafor/module/save_module/'))
            ->controller(\Plafor\Controllers\Module::class)
            ->execute('save_module', $module_id);

        // Assert
        if ($expect_redirect) {
            $this->assertEmpty(ModuleModel::getInstance()->errors());
            $result->assertRedirect();
        } else {
            $result->assertNotRedirect();

            $model = ModuleModel::getInstance();
            $errors = $model->errors();
            if ($expect_errors) {
                $this->assertNotEmpty($errors);
            } else {
                $this->assertEmpty($errors);
            }
        }
    }

    /**
     * Tests Module::save_module with a valid link
     *
     * @group Modules
     * @return void
     */
    public function testSaveModuleLink(): void
    {
        // Setup
        $course_plan_id = \Plafor\Models\CoursePlanModel::getInstance()->insert([
            'formation_number' => '10000',
            'official_name' => 'Fake course plan',
            'date_begin' => '2022-01-01 00:00:00',
        ]);

        global $_POST;
        $_POST['module_id'] = 0;
        $_POST['course_plan_id'] = $course_plan_id;
        $_POST['module_number'] = '100';
        $_POST['official_name'] = 'Fake module';

        $length = 0;
        if (!is_null($all = CoursePlanModuleModel::getInstance()->findAll())) {
            $length = count($all);
        }

        // Test
        /** @var \CodeIgniter\Test\TestResponse */
        $result = $this->withUri(base_url('plafor/module/save_module/'))
            ->controller(\Plafor\Controllers\Module::class)
            ->execute('save_module');

        // Assert
        $result->assertRedirect();
        $count = 0;
        if (!is_null($all = CoursePlanModuleModel::getInstance()->findAll())) {
            $count = count($all);
        }
        $this->assertEquals($length+1, $count, 'Failed asserting that the amount of links has increased.');
        $this->assertEmpty(ModuleModel::getInstance()->errors());
    }

    /**
     * Tests Module::save_module with an invalid link
     *
     * @group Modules
     * @return void
     */
    public function testSaveModuleNoLink(): void
    {
        // Setup
        $course_plan_id = CoursePlanModel::getInstance()->insert([
            'formation_number' => '00000',
            'official_name' => 'Fake course plan',
            'date_begin' => '2022-01-01 00:00:00',
        ]);

        global $_POST;
        $_POST['module_id'] = 0;
        $_POST['course_plan_id'] = $course_plan_id;
        $_POST['module_number'] = 999;
        $_POST['official_name'] = 'Fake module';

        $length = 0;
        if (!is_null($all = CoursePlanModuleModel::getInstance()->findAll())) {
            $length = count($all);
        }

        // Test
        /** @var \CodeIgniter\Test\TestResponse */
        $result = $this->withUri(base_url('plafor/module/save_module/'))
            ->controller(\Plafor\Controllers\Module::class)
            ->execute('save_module');

        // Assert
        $result->assertRedirect();
        $count = 0;
        if (!is_null($all = CoursePlanModuleModel::getInstance()->findAll())) {
            $count = count($all);
        }
        $this->assertEquals($length, $count, 'Failed asserting that the amount of links is the same as before.');
        $this->assertEmpty(ModuleModel::getInstance()->errors());
    }

    /**
     * Tests whether Module::save_module redirects with specific access
     *
     * @dataProvider accessProvider
     * @group Modules
     * @param  integer|null $user_access Access given to the "user", NULL if no access is set.
     * @param  boolean      $expect_redirect Whether redirection is expected
     * @param  boolean      $expect_403 Whether a 403 page is expected
     * @return void
     */
    public function testSaveModuleAccess(?int $user_access, bool $expect_redirect, bool $expect_403): void
    {
        // Setup
        if (is_null($user_access)) {
            session()->remove('user_access');
            session()->remove('logged_in');
        } else {
            session()->set('user_access', $user_access);
            session()->set('logged_in', TRUE);
        }

        // Test
        /** @var \CodeIgniter\Test\TestResponse */
        $result = $this->withUri(base_url('plafor/module/save_module'))
            ->controller(\Plafor\Controllers\Module::class)
            ->execute('save_module');

        // Assert
        if ($expect_redirect) {
            $result->assertRedirect();
        } else {
            $result->assertNotRedirect();

            if ($expect_403) {
                $result->assertSee('403');
            } else {
                $result->assertDontSee('403');
            }
        }
    }

    /**
     * Provider for testDeleteModule
     *
     * @return array
     */
    public function deleteModuleProvider(): array
    {
        return [
            'Show module deletion confirmation' => [
                'module_id' => 1,
                'action' => 0,
                'expect_redirect' => FALSE,
                'exists' => TRUE,
                'archived' => FALSE,
            ],
            'Archive module' => [
                'module_id' => 1,
                'action' => 1,
                'expect_redirect' => TRUE,
                'exists' => TRUE,
                'archived' => TRUE,
            ],
            'Delete module' => [
                'module_id' => 1,
                'action' => 2,
                'expect_redirect' => TRUE,
                'exists' => FALSE,
                'archived' => FALSE,
            ],
            'Reactive module' => [
                'module_id' => 9,
                'action' => 3,
                'expect_redirect' => TRUE,
                'exists' => TRUE,
                'archived' => FALSE,
            ],
            'Do not show inexistant module' => [
                'module_id' => 999,
                'action' => 0,
                'expect_redirect' => TRUE,
                'exists' => FALSE,
                'archived' => FALSE,
            ],
            'Do nothing on invalid action' => [
                'module_id' => 1,
                'action' => 999,
                'expect_redirect' => TRUE,
                'exists' => TRUE,
                'archived' => FALSE,
            ],
        ];
    }

    /**
     * Tests for Module::delete_module
     *
     * @dataProvider deleteModuleProvider
     * @group Modules
     * @param  integer $module_id ID of the module to delete
     * @param  integer $action Action to pass to delete_module, see Module::delete_module
     * @param  boolean $expect_redirect Whether a redirect is expected
     * @param  boolean $exists Whether the module exists at the end
     * @param  boolean $archived Whether is archived at the end, only if `$exists` is TRUE
     * @return void
     */
    public function testDeleteModule(int $module_id, int $action, bool $expect_redirect, bool $exists, bool $archived): void
    {
        // Test
        /** @var \CodeIgniter\Test\TestResponse */
        $result = $this->withUri(base_url('plafor/module/delete_module/'))
            ->controller(\Plafor\Controllers\Module::class)
            ->execute('delete_module', $module_id, $action);

        // Assert
        if ($expect_redirect) {
            $result->assertRedirect();
        } else {
            $result->assertNotRedirect();
        }

        $data = ModuleModel::getInstance()->withDeleted()->find($module_id);
        if (!$exists) {
            $this->assertEmpty($data);
        } else {
            $this->assertNotEmpty($data);

            if ($archived) {
                $this->assertNotNull($data['archive']);
            } else {
                $this->assertNull($data['archive']);
            }
        }
    }
}
