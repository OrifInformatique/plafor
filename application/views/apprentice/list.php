<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a href="<?= base_url('apprentice/list_apprentice/') ?>" class="nav-link active"><?= lang('admin_apprentices'); ?></a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/list_course_plan/') ?>" class="nav-link"><?= lang('admin_course_plans'); ?></a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/list_competence_domain/') ?>" class="nav-link"><?= lang('admin_competence_domains'); ?></a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/list_operational_competence/') ?>" class="nav-link"><?= lang('admin_operational_competences'); ?></a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/list_objective/') ?>" class="nav-link"><?= lang('admin_objectives'); ?></a>
            </li>
        </ul>
  </div>
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('title_apprentice_list'); ?></h1>
        </div>
    </div>
    <div class="row mt-2">
        <table class="table table-hover">
        <thead>
            <tr>
                <th><?= lang('field_apprentice_username'); ?></th>
                <th><?= lang('field_followed_courses'); ?></th>
            </tr>
        </thead>
        <tbody id="apprenticeslist">
            <?php foreach($apprentices as $apprentice) { ?>
                <tr>
                    <td><a href="<?= base_url('apprentice/view_apprentice/'.$apprentice->id); ?>"><?= $apprentice->username; ?></td>
                    <td><a href="<?= base_url('admin/list_course_plan/'.$apprentice->id)?>"><?php 
                        $linkedCourses = "";
                        
                        foreach ($courses as $course){
                            $linkedCourses .= ($course->fk_user == $apprentice->id?$coursesList[$course->fk_course_plan-1]->official_name.",":"");
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
        $.post('<?=base_url();?>admin/list_objective/'+(+checked), {}, data => {
            $('#objectiveslist').empty();
            $('#objectiveslist')[0].innerHTML = $(data).find('#objectiveslist')[0].innerHTML;
        });
    });
});
</script>
