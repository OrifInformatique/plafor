<?php
$update = !is_null($competence_domain);
helper('form');
$validation = \CodeIgniter\Config\Services::validation();
$session=\CodeIgniter\Config\Services::session();
?>
<?php
    // For some reasons, you can only set a type to input made with form_input if done with only a array as param, may need to be checked for later uses.

    $data_symbol = array(
        'name' => 'symbol',
        'value' => $competence_domain_symbol ?? $competence_domain['symbol'] ?? '',
        'max' => config('\Plafor\Config\PlaforConfig')->SYMBOL_MAX_LENGTH,
        'class' => 'form-control',
        'id' => 'competence_domain_symbol'
    );
    
    $data_name = array(
        'name' => 'name',
        'value' => $competence_domain_name ?? $competence_domain['name'] ?? '',
        'max' => config('\Plafor\Config\PlaforConfig')->COMPETENCE_DOMAIN_NAME_MAX_LENGTH,
        'class' => 'form-control', 'id' => 'competence_domain_name'
    );
?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('user_lang.title_competence_domain_'.($update ? 'update' : 'new')); ?></h1>
        </div>
    </div>
    
    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'competence_domain_form',
        'name' => 'competence_domain_form'
    );
    echo form_open(base_url('plafor/admin/save_competence_domain'), $attributes, [
        'id' => $competence_domain['id'] ?? 0
    ]);
    ?>

        <!-- ERROR MESSAGES -->
        <?php
        foreach ($errors!=null?$errors:[] as $error){ ?>
            <div class="alert alert-danger"><?=$error?></div>
        <?php }?>

        <!-- USER FIELDS -->
        <div class="row">
            <div class="col-sm-12 form-group">
                <?= form_label(lang('user_lang.field_competence_domain_course_plan'), 'course_plan', ['class' => 'form-label']); ?>
                <br />
                <?= form_dropdown('course_plan',$course_plans,$fk_course_plan_id ?? '','id="course_plan" class="form-control"')?>
            </div>
            <div class="col-sm-12 form-group">
                <?= form_label(lang('user_lang.field_competence_domain_symbol'), 'competence_domain_symbol', ['class' => 'form-label']); ?>
                <?= form_input($data_symbol); ?>
                <?= form_label(lang('user_lang.field_competence_domain_name'), 'competence_domain_name', ['class' => 'form-label']); ?>
                <?= form_input($data_name); ?>
            </div>
        </div>
                    
        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-default" href="<?= base_url('plafor/admin/list_competence_domain/'.($fk_course_plan_id==null?'':$fk_course_plan_id)) ?>"><?= lang('common_lang.btn_cancel'); ?></a>
                <?= form_submit('save', lang('common_lang.btn_save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    <?= form_close(); ?>
</div>
