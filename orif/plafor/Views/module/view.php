<?php

/**
 * Lists all teaching modules.
 *
 * Called by TeachingDomainController/getAllTeachingModule($with_deleted)
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
    <div class="row">
        <div class="col">
            <h2><?= lang('Grades.modules_catalog') ?></h2>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="col-sm-12 text-right d-flex justify-content-between">
                <div>
                    <?php if(service('session')->get('user_access')>=config('\User\Config\UserConfig')->access_lvl_admin): ?>
                        <a href="<?= base_url('plafor/teachingdomain/saveTeachingModule') ?>" class="btn btn-primary">
                            <?= lang('common_lang.btn_new_m') ?>
                        </a>
                    <?php endif ?>
                </div>

                <div>
                    // TODO : Display deleted modules when checked
                    <?= form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted',
                        ['class' => 'form-check-label','style' => 'padding-right: 30px;']) ?>

                    <?=form_checkbox('toggle_deleted', '', $with_archived ?? false,
                        ['class' => 'form-check-input', 'id' => 'toggle_deleted']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?= view('Common\Views\items_list',
        [
            'columns' =>
            [
                'number_module'        => lang('Grades.module_number'),
                'name_module'          => lang('Grades.module_name'),
                'version_module'       => lang('Grades.module_version'),
            ],
            // TODO : Add data
            // 'items'             => $modules,
            'primary_key_field' => 'id',
            'url_update'        => 'plafor/teachingdomain/saveTeachingModule/',
            'url_delete'        => 'plafor/teachingdomain/deleteTeachingModule/'
        ])
        ?>
    </div>
</div>