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

<div class="container">
    <!-- Page title -->
    <?= view('\Plafor/common/page_title',
        ['title' => lang('plafor_lang.title_operational_competence_'.(!empty($operational_competence) ? 'update' : 'new'))]) ?>

    <?= form_open(base_url('plafor/courseplan/save_operational_competence/'.$competence_domain_id.
        '/'.($operational_competence['id'] ?? 0)),
        [], ['id' => $operational_competence['id'] ?? 0, 'type' => 'operational_competence']) ?>

        <!-- ERROR MESSAGES -->
        <?php
        foreach ($errors!=null?$errors:[] as $error){ ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
        <?php } ?>

        <!-- USER FIELDS -->
        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_operational_competence_domain'), 'competence_domain', ['class' => 'form-label']); ?>
                <br />
                <?= form_dropdown('competence_domain',$competence_domains,$operational_competence['fk_competence_domain']?? '','id="competence_domain" class="form-control"')?>
            </div>
            <div class="col-sm-12 form-group">
                <?= form_label(lang('plafor_lang.field_operational_competence_symbol'), 'operational_competence_symbol', ['class' => 'form-label']); ?>
                <?= form_input($data_symbol); ?>
                <?= form_label(lang('plafor_lang.field_operational_competence_name'), 'operational_competence_name', ['class' => 'form-label']); ?>
                <?= form_input($data_name); ?>
                <?= form_label(lang('plafor_lang.field_operational_competence_methodologic'), 'operational_competence_methodologic', ['class' => 'form-label']); ?>
                <?= form_textarea($data_methodologic); ?>
                <?= form_label(lang('plafor_lang.field_operational_competence_social'), 'operational_competence_social', ['class' => 'form-label']); ?>
                <?= form_textarea($data_social); ?>
                <?= form_label(lang('plafor_lang.field_operational_competence_personal'), 'operational_competence_personal', ['class' => 'form-label']); ?>
                <?= form_textarea($data_personal); ?>
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
    <?= form_close(); ?>
</div>
