<?php

/**
 * Grade save view
 *
 * @author      Orif (Dedy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

helper('form')

?>

<div class="container">
    <div class="row">
        <h2 class="title-section"><?= lang('plafor_lang.title_save_grade') ?></h2>
    </div>

    <?= form_open(base_url('plafor/grade/saveGrade'), [], ['id' => $id]) ?>
        <div class="row">
            <div class="col form-group">
                <?= form_label(lang('plafor_lang.apprentice'), 'apprentice', ['class' => 'form-label']) ?><br>
                <!-- TODO : Insert apprentices list as form_dropdown $options -->
                <?= form_dropdown('apprentice', 'Insert apprentices here', null, ['class' => 'form-control', 'id' => 'apprentice']) ?>
            </div>
            <div class="col form-group">
                <?= form_label(lang('plafor_lang.subject/module'), 'subject', ['class' => 'form-label']) ?><br>
                <!-- TODO : Insert subjects/modules list as form_dropdown $options -->
                <!-- TODO : Insert only subjects list as form_dropdown $options when coming from subject_grades view -->
                <!-- TODO : Insert only modules list as form_dropdown $options when coming from module_grades view -->
                <?= form_dropdown('subject', 'Insert subject/modules here', null, ['class' => 'form-control', 'id' => 'subject']) ?>
            </div>

        </div>

        <div class="row">
            <div class="col-sm-2 form-group">
                <?= form_label(lang('plafor_lang.name_grade'), 'grade', ['class' => 'form-label']) ?><br>
                <?= form_input(null, '', ['class' => 'form-control', 'id' => 'grade'], 'number') ?>
            </div>

            <div class="col-sm-2 form-group">
                <?= form_label(lang('plafor_lang.date_grade'), 'date', ['class' => 'form-label']) ?><br>
                <?= form_input(null, '', ['class' => 'form-control', 'id' => 'date'], 'date') ?>
            </div>

            <div class="col-sm-4 form-group form-check form-check-inline">
                <?= form_label(lang('plafor_lang.to_school_grade'), 'is_school', ['class' => 'form-check-label mr-2']) ?>
                <?= form_checkbox('is_school', '', false, ['class' => 'form-check-input', 'id' => 'is_school']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/grade/showAllGrade') ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit('', lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>