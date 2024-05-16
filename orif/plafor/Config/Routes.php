<?php
/**
 * Routes for Plafor Module
 *
 * @author      Orif (ViDi,HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

$routes->add('plafor/admin/(:any)','\Plafor\Controllers\Admin::$1',['filter'=>'login:'.config('\User\Config\UserConfig')->access_lvl_admin]);
$routes->add('plafor/apprentice/(:any)','\Plafor\Controllers\Apprentice::$1',['filter'=>'login:'.config('\User\Config\UserConfig')->access_level_apprentice]);
$routes->add('plafor/courseplan/(:any)','\Plafor\Controllers\CoursePlan::$1');
$routes->add('migration/(:any)','\Plafor\Controllers\Migration::$1');
$routes->add('migration','\Plafor\Controllers\Migration::index');

?>