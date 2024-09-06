<?php
/**
 * Subject list view
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */

helper('form');

?>

<div class="container">
    <div class="row">
        <div class="col">
            <h2 class="title-section"><?= lang('plafor_lang.title_list_subject') ?></h2>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="col-sm-12 text-right d-flex justify-content-between">
            <?php if(service('session')->get('user_access')>=config('\User\Config\UserConfig')->access_lvl_admin):?>
                <a href="<?=base_url('plafor/teachingdomain/saveTeachingSubject')?>" class="btn btn-primary"><?=lang('common_lang.btn_new_m')?></a>
            <?php endif?>
                <span>
                <?=form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted', ['class' => 'form-check-label','style'=>'padding-right:30px']);?>
                <?=form_checkbox('toggle_deleted', '', isset($with_archived)?$with_archived:false, [
                    'id' => 'toggle_deleted', 'class' => 'form-check-input'
                ]);?>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <?= view('Common\Views\items_list',
        [
            'columns' =>
            [
                'name'                  => lang('plafor_lang.name_module'),
                'parent_domain_subject' => lang('plafor_lang.parent_domain_subject'),
                'weight_subject'        => lang('plafor_lang.weight_subject')
            ],
            // TODO : Add data
            //'items'             => $data,
            'primary_key_field' => 'id',
            'url_update'        => 'plafor/courseplan/saveTeachingSubject/',
            'url_delete'        => 'plafor/courseplan/deleteTeachingSubject/'
        ])?>
    </div>
</div>