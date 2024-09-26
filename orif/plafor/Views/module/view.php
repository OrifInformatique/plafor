<?php

/**
 * Lists all teaching modules.
 *
 * Called by TeachingDomainController/getAllTeachingModule($with_archived)
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param array $modules List of all the modules.
 * All fields from table.
 *
 */



/**
 * No data is sent by this view.
 *
 */

helper('form');

?>

<div class="container">
    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => lang('Grades.modules_catalog')]) ?>

    <div class="row">
        <?= view('Common\Views\items_list',
        [
            'items'   => $modules,
            'columns' =>
            [
                'number_module'  => lang('Grades.module_number'),
                'name_module'    => lang('Grades.module_name'),
                'version_module' => lang('Grades.module_version'),
            ],
            'with_deleted'      => true,
            'allow_hard_delete' => true,
            'url_create'        => 'plafor/teachingdomain/saveTeachingModule',
            'url_update'        => 'plafor/teachingdomain/saveTeachingModule/',
            'url_restore'       => 'plafor/teachingdomain/deleteTeachingModule/3/',
            'url_delete'        => 'plafor/teachingdomain/deleteTeachingModule/1/',
            'url_hard_delete'   => 'plafor/teachingdomain/deleteTeachingModule/2/',
            'url_getView'       => 'plafor/teachingdomain/getAllTeachingModule',
        ])
        ?>
    </div>
</div>