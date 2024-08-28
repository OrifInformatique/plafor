<?php

/**
 * Domain save view
 *
 * @author      Orif (Dedy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

helper('form')

?>

<div class="container">
    <div class="row">
        <h2 class="title-section"><?= lang('plafor_lang.create_domain') ?></h2>
    </div>

    <?= form_open(base_url('plafor/grade/saveTeachingDomain'), [], ['id' => $id]) ?>
        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('plafor_lang.name_domain'), 'domain', ['class' => 'form-label']) ?><br>
                <!-- TODO : Insert domains list as form_dropdown $options -->
                <?= form_dropdown('domain', 'Insert domains here', null, ['class' => 'form-control', 'id' => 'domain']) ?>
            </div>

            <div class="col form-group">
                <?= form_label(lang('plafor_lang.field_user_course_course_plan'), 'user_course', ['class' => 'form-label']) ?><br>
                <!-- TODO : Insert user_courses list as form_dropdown $options -->
                <?= form_dropdown('user_course', 'Insert user_courses here', null, ['class' => 'form-control', 'id' => 'user_course']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2 form-group">
                <?= form_label(lang('plafor_lang.weight_domain'), 'domain_weight', ['class' => 'form-label']) ?><br>
                <?= form_input(null, '', ['class' => 'form-control', 'id' => 'domain_weight'], 'number') ?>
            </div>

            <div class="col-sm-2 form-group form-check form-check-inline">
                <?= form_label(lang('plafor_lang.eliminatory_domain'), 'is_domain_eliminatory', ['class' => 'form-check-label mr-2']) ?><br>
                <?= form_checkbox('is_domain_eliminatory', '', false, ['class' => 'form-check-input', 'id' => 'is_domain_eliminatory']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/grade/showAllTeachingDomain') ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit('', lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>