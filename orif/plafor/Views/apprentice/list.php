<?php
helper('Form');
/**
 * Users List View
 *
 * @author      Orif, section informatique (UlSi, ViDi)
 * @link        https://github.com/OrifInformatique/gestion_questionnaires
 * @copyright   Copyright (c) Orif (http://www.orif.ch)
 */
?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('user_lang.title_apprentice_list'); ?></h1>
            <div style="display:flex;flex-direction:row;align-items:center;justify-content:space-between;">
            <?php
            echo form_open(base_url('plafor/apprentice/list_apprentice/'), ['method' => 'GET']);
            echo form_dropdown('trainer_id', $trainers, $trainer_id, ['class' => 'form-control', 'style' => 'width:unset!important;display:unset!important;margin-left:-10px;']);
            echo form_submit(null, lang('common_lang.btn_search'), ['class' => 'btn btn-primary', 'style' => 'vertical-align:unset!important;']);?>
            <?php
            echo form_close();
            ?>
            <span>

            <?=form_checkbox('toggle_deleted', '', $with_archived, [
                'id' => 'toggle_deleted', 'class' => 'form-check-input'
            ]);?>
            <?=form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted', ['class' => 'form-check-label']);?>
                </span>
            </div>
       

            </div>
        </div>
    </div>
       
    <div class="row mt-2">
        <table class="table table-hover">
        <thead>
            <tr>
                <th><?= lang('user_lang.field_apprentice_username'); ?></th>
                <th><?= lang('user_lang.field_followed_courses'); ?></th>
            </tr>
        </thead>
        <tbody id="apprenticeslist">
            <?php foreach($apprentices as $apprentice) { ?>
                <tr>
                    <td><a href="<?= base_url('plafor/apprentice/view_apprentice/'.$apprentice['id']); ?>"><?= $apprentice['username']; ?></td>
                    <td><a href="<?= base_url('plafor/admin/list_course_plan/'.$apprentice['id'])?>"><?php
                        $linkedCourses = "";
                            foreach ($courses as $course){
                            $linkedCourses .= ($course['fk_user'] == $apprentice['id']?$coursesList[$course['fk_course_plan']]['official_name'].",":"");
                        } 
                        echo rtrim($linkedCourses,",");
                        ?></a></td>
                </tr>
            <?php } ?>
        </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#toggle_deleted').change(e => {
        let checked = e.currentTarget.checked;
        $.post('<?php echo base_url("plafor/apprentice/list_apprentice").'/'?>'+((checked==true?'1':'0')), {}, data => {
            $('#apprenticeslist').empty();
            $('#apprenticeslist')[0].innerHTML = $(data).find('#apprenticeslist')[0].innerHTML;
        });
    });
});
</script>
