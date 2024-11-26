<?php
/**
 * Routes for Plafor Module
 *
 * @author      Orif (ViDi,HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

use \Plafor\Controllers\GradeController;

$routes->add('plafor/admin/(:any)', '\Plafor\Controllers\Admin::$1',
    ['filter' => 'login:'.config('\User\Config\UserConfig')->access_lvl_admin]);

$routes->add('plafor/apprentice/(:any)', '\Plafor\Controllers\Apprentice::$1',
    ['filter' => 'login:'.config('\User\Config\UserConfig')->access_level_apprentice]);

$routes->add('plafor/courseplan/(:any)', '\Plafor\Controllers\CoursePlan::$1');

// $routes->add('plafor/grade/(:any)','\Plafor\Controllers\GradeController::$1');

// TODO : Group routes leading to the same controller

$routes->get('plafor/grade/save/(:num)/(:any)',
    [GradeController::class, 'saveGrade'], ['as' => 'updateGrade']);

$routes->get('plafor/grade/save/(:num)',
    [GradeController::class, 'saveGrade'], ['as' => 'insertGrade']);

$routes->get('plafor/grade/save/(:num)/(:any)/(:any)',
    [GradeController::class, 'saveGrade'], ['as' => 'insertGradeWithPreselect']);

$routes->post('plafor/grade/save/(:num)/(:num)',
    [GradeController::class, 'saveGrade'], ['as' => 'saveGrade']);

$routes->get('plafor/grade/delete/(:num)/(:num)',
    [GradeController::class, 'deleteGrade'], ['as' => 'deleteGrade']);

$routes->post('plafor/grade/delete/(:num)/(:num)/(:any)',
[GradeController::class, 'deleteGrade'], ['as' => 'deleteGrade']);

$routes->add('plafor/teachingdomain/(:any)','\Plafor\Controllers\TeachingDomainController::$1');

$routes->add('migration','\Plafor\Controllers\Migration::index');
$routes->add('migration/(:any)','\Plafor\Controllers\Migration::$1');

$routes->resource('api/school_reports',
    ['namespace' => 'Plafor\Controllers\API',
        'controller' => 'SchoolReports',
        'only' => ['index']]);

$routes->resource('api/school_report',
    ['namespace' => 'Plafor\Controllers\API',
        'controller' => 'SchoolReports',
        'only' => ['show']]);
?>
