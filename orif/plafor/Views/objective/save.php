<?php

/**
 * Let the edition of an objective.
 *
 * Called by CoursePlan/save_objective($objective_id, $operationel_comp_id)
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * @param string $title Page title.
 * Should be lang('plafor_lang.title_objective_new') or lang('plafor_lang.title_objective_update').
 *
 * @param array $objective Existing objective.
 * All fields from table.
 *
 * @param array $parent_operational_competence Parent operational competence.
 *
 * @param ?array $errors objective_model errors.
 *
 */



/**
 * *** Data sent by this view ***
 *
 * method POST
 *
 * action CoursePlan/save_objective($objective_id, $operationel_comp_id)
 *
 * @param int $id ID of the objective.
 * For plafor validation rules.
 *
 * @param int $parent_operational_competence_id ID of the parent operational competence.
 *
 * @param string $type Type of the entry.
 * For plafor validation rules.
 *
 * @param string $symbol Symbol of the objective.
 *
 * @param int $taxonomy Taxonomy of the objective.
 *
 * @param string $name Name of the objective.
 *
 */

helper('form');

$symbol_max_length  = config('\Plafor\Config\PlaforConfig')->SYMBOL_MAX_LENGTH;
$taxonomy_max_value = config('\Plafor\Config\PlaforConfig')->TAXONOMY_MAX_VALUE;
$name_max_length    = config('\Plafor\Config\PlaforConfig')->OBJECTIVE_NAME_MAX_LENGTH;

?>

<div class="container">
    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <?= form_open(base_url('plafor/courseplan/save_objective/'.$parent_operational_competence["id"].'/'.($objective['id'] ?? 0)), null,
        ['id' => $objective['id'] ?? 0, 'parent_operational_competence_id' => $parent_operational_competence["id"],
             'type' => 'objective']) ?>

        <!-- Form errors -->
        <?= view('\Plafor/common/form_errors', ['errors' => $errors]) ?>

        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_objective_operational_competence'), 'parent_operational_competence',
                    ['class' => 'form-label']) ?>

                <?= form_input('parent_operational_competence', $parent_operational_competence["name"],
                    ['class' => 'form-control', 'id' => 'parent_operational_competence', 'disabled' => true]) ?>
            </div>

            <div class="col-sm-6 form-group">
                <?= form_label(lang('plafor_lang.field_objective_symbol'), 'objective_symbol',
                    ['class' => 'form-label']) ?>

                <?= form_input('symbol', $objective['symbol'] ?? '',
                    ['class' => 'form-control', 'id' => 'objective_symbol', 'maxlength' => $symbol_max_length]) ?>
            </div>

            <div class="col-sm-6 form-group">
                <?= form_label(lang('plafor_lang.field_objective_taxonomy'), 'objective_taxonomy',
                    ['class' => 'form-label']) ?>

                <?= form_input('taxonomy', $objective['taxonomy'] ?? '',
                    ['class' => 'form-control', 'id' => 'objective_taxonomy', 'max' => $taxonomy_max_value], 'number') ?>
            </div>

            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_objective_name'), 'objective_name',
                    ['class' => 'form-label']) ?>

                <?= form_input('name', $objective['name'] ?? '',
                    ['class' => 'form-control', 'id' => 'objective_name', 'maxlength' => $name_max_length]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col text-right">
                <a class="btn btn-secondary" href="<?= base_url('plafor/courseplan/view_operational_competence/'.$parent_operational_competence["id"]) ?>">
                    <?= lang('common_lang.btn_cancel') ?>
                </a>

                <?= form_submit(null, lang('common_lang.btn_save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= form_close() ?>
</div>