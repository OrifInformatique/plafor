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
 * @param array $operational_competence Existing operational competence.
 * All fields from table.
 *
 * @param array $competence_domains List of all competence domains
 * Array of key-values where keys are competence domains IDs and values are competence domains names.
 *
 * @param array $competence_domain_id ID of the parent competence domain.
 * All fields from table.
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
 *
 * @param string $type Type of the entry.
 * For plafor validation rules.
 *
 * @param int $competence_domain Parent competence domain, stored as competence domain ID.
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


$symbol_max_length = config('\Plafor\Config\PlaforConfig')->SYMBOL_MAX_LENGTH;
$name_max_length = config('\Plafor\Config\PlaforConfig')->OPERATIONAL_COMPETENCE_NAME_MAX_LENGTH;
$textarea_max_length = config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH;

?>

<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('plafor_lang.title_operational_competence_'.($update ? 'update' : 'new')); ?></h1>
        </div>
    </div>

    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'operational_competence_form',
        'name' => 'operational_competence_form'
    );
    echo form_open(base_url('plafor/courseplan/save_operational_competence/'.$competence_domain['id'].'/'.($operational_competence['id'] ?? '0')), $attributes, [
        'id' => $operational_competence['id'] ?? 0,
        'type' => 'operational_competence',
    ]);
    ?>

        <!-- Form errors -->
        <?= view('\Plafor/common/form_errors', ['errors' => $errors]) ?>

        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_operational_competence_domain'), 'competence_domain',
                    ['class' => 'form-label']) ?>

                <?= form_dropdown('competence_domain', $competence_domains, $operational_competence['fk_competence_domain'] ?? '',
                    ['class' => 'form-control', 'id' => 'competence_domain'])?>
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
                <a class="btn btn-secondary" href="<?= base_url('plafor/courseplan/view_competence_domain/'.$competence_domain_id) ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>