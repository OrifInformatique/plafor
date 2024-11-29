<?php

/**
 * Let the edition of a operational competence.
 *
 * Called by CoursePlan/save_operational_competence($competence_domain_id, $operational_competence_id)
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed fot this view ***
 *
 * @param string $title Page title.
 * Should be lang('plafor_lang.title_operational_competence_new') or lang('plafor_lang.title_operational_competence_update').
 *
 * @param array $operational_competence Existing operational competence.
 * All fields from table.
 *
 * @param array $parent_competence_domain Parent competence domain.
 *
 * @param ?array $errors operational_comp_model errors.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method POST
 *
 * action CoursePlan/save_operational_competence($competence_domain_id, $operational_competence_id)
 *
 * @param int $id ID of the operational competence.
 * For plafor validation rules.
 *
 * @param int $parent_competence_domain_id ID of the parent competence domain.
 *
 * @param string $type Type of the entry.
 * For plafor validation rules.
 *
 * @param string $symbol Symbol of the operational competence.
 *
 * @param string $name Name of the operational competence.
 *
 * @param string $methodologic Methologoic competence.
 *
 * @param string $social Social competence.
 *
 * @param string $personal Personal competence.
 *
 */

helper('form');

$symbol_max_length = config('\Plafor\Config\PlaforConfig')->SYMBOL_MAX_LENGTH;
$name_max_length = config('\Plafor\Config\PlaforConfig')->OPERATIONAL_COMPETENCE_NAME_MAX_LENGTH;
$textarea_max_length = config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH;

?>

<div class="container">
    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <?= form_open(base_url('plafor/courseplan/save_operational_competence/'.
        $parent_competence_domain["id"].'/'.($operational_competence["id"] ?? '0')), null,
        ['id' => $operational_competence["id"] ?? 0, 'parent_competence_domain_id' => $parent_competence_domain["id"],
            'type' => 'operational_competence']) ?>

        <!-- Form errors -->
        <?= view('\Plafor/common/form_errors', ['errors' => $errors]) ?>

        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_operational_competence_domain'), 'parent_competence_domain',
                    ['class' => 'form-label']) ?>

                <?= form_input('competence_domain', $parent_competence_domain["name"],
                    ['class' => 'form-control', 'id' => 'parent_competence_domain', 'disabled' => true])?>
            </div>

            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_operational_competence_symbol'), 'operational_competence_symbol',
                    ['class' => 'form-label']) ?>

                <?= form_input('symbol', $operational_competence['symbol'] ?? '',
                    ['class' => 'form-control', 'id' => 'operational_competence_symbol', 'maxlength' => $symbol_max_length]) ?>
            </div>

            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_operational_competence_name'), 'operational_competence_name',
                    ['class' => 'form-label']) ?>

                <?= form_input('name', $operational_competence['name'] ?? '',
                    ['class' => 'form-control', 'id' => 'operational_competence_name', 'maxlength' => $name_max_length]) ?>
            </div>

            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_operational_competence_methodologic'), 'operational_competence_methodologic', ['class' => 'form-label']) ?>

                <?= form_textarea('methodologic', $operational_competence['methodologic'] ?? '',
                    ['class' => 'form-control', 'id' => 'operational_competence_methodologic', 'maxlength' => $textarea_max_length]) ?>
            </div>

            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_operational_competence_social'), 'operational_competence_social',
                    ['class' => 'form-label']) ?>

                <?= form_textarea('social', $operational_competence['social'] ?? '',
                    ['class' => 'form-control', 'id' => 'operational_competence_social', 'maxlength' => $textarea_max_length]) ?>
            </div>

            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_operational_competence_personal'), 'operational_competence_personal',
                    ['class' => 'form-label']) ?>

                <?= form_textarea('personal', $operational_competence['personal'] ?? '',
                    ['class' => 'form-control', 'id' => 'operational_competence_personal', 'maxlength' => $textarea_max_length]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/courseplan/view_competence_domain/'.$parent_competence_domain["id"]) ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>