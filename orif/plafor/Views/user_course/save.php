<?php
$update = !is_null($user_course);
$validation=\CodeIgniter\Config\Services::validation();
helper('form');
?>
<?php
    // For some reasons, you can only set a type to input made with form_input if done with only a array as param, may need to be checked for later uses.

    $data_date_begin = array(
        'name' => 'date_begin',
        'value' => $user_course_date_begin ?? $user_course['date_begin'] ?? date("Y-m-d"),
        'class' => 'form-control',
        'type' => 'date',
        'id' => 'user_course_date_begin'
    );
    
    $data_date_end = array(
        'name' => 'date_end',
        'value' => $user_course_date_end ?? $user_course['date_end'] ?? '',
        'class' => 'form-control', 'id' => 'competence_domain_name',
        'type' => 'date',
        'id' => 'user_course_date_begin'
    );
?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('plafor_lang.title_user_course_'.($update ? 'update' : 'new').''); ?></h1>
        </div>
    </div>
    
    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'user_course_form',
        'name' => 'user_course_form'
    );
    echo form_open('plafor/apprentice/save_user_course/'.$apprentice['id'].(isset($user_course['id'])?'/'.$user_course['id']:''), $attributes, [
        'id' => $apprentice->id ?? 0
    ]);
    ?>

        <!-- ERROR MESSAGES -->
        <?php
        foreach ($errors!=null?$errors:[] as $error){
            ?>
        <div class="alert alert-danger">
        <?=$error ?>
        </div>
        <?php }?>
        <!-- USER FIELDS -->
        <div class="row">
            <div class="col-sm-6 form-group">
                <?= form_label(lang('plafor_lang.field_user_course_course_plan'), 'course_plan', ['class' => 'form-label']); ?>
                <br />
                <?= form_dropdown('course_plan',$course_plans,isset($user_course['fk_course_plan'])?$user_course['fk_course_plan']:'','id="course_plan" class="form-control" '.($update?'style="pointer-events:none;background-color:rgba(0,0,0,0.2)"':''))?>
            </div>
            <div class="col-sm-6 form-group">
                <?= form_label(lang('plafor_lang.field_user_course_status'), 'status', ['class' => 'form-label']); ?>
                <br />
                <?= form_dropdown('status',$status,$user_course['fk_status'] ?? '','id="status" class="form-control"')?>
            </div>
            <div class="col-sm-6 form-group">
                <?= form_label(lang('plafor_lang.field_user_course_date_begin'), 'user_course_date_begin', ['class' => 'form-label']); ?>
                <?= form_input($data_date_begin); ?>
            </div>
            <div class="col-sm-6 form-group">
                <?= form_label(lang('plafor_lang.field_user_course_date_end'), 'user_course_date_end', ['class' => 'form-label']); ?>
                <?= form_input($data_date_end); ?>
            </div>
        </div>
                    
        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-default" href="<?= base_url('plafor/apprentice/view_apprentice/'.$apprentice['id']); ?>"><?= lang('common_lang.btn_cancel'); ?></a>
                <?= form_submit('save', lang('common_lang.btn_save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    <?= form_close(); ?>
</div>
