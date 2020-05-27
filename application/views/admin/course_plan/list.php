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
        <div class="col">
            <h1 class="title-section"><?= lang('title_course_plan_list'); ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 text-left">
            <a href="<?= base_url('admin/save_course_plan'); ?>" class="btn btn-primary">
                <?= lang('btn_add_m'); ?>
            </a>
        </div>
    </div>
    <div class="row mt-2">
        <table class="table table-hover">
        <thead>
            <tr>
                <th><?= lang('field_course_plan_name'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="course_planslist">
            <?php foreach($course_plans as $course_plan) { ?>
                <tr>
                    <td><a href="<?= base_url('admin/save_course_plan/'.$course_plan->id); ?>"><?= $course_plan->official_name; ?></td>
                    <td><a href="<?= base_url('admin/delete_course_plan/'.$course_plan->id); ?>" class="close">×</td>
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
        $.post('<?=base_url();?>admin/list_course_plan/'+(+checked), {}, data => {
            $('#course_planslist').empty();
            $('#course_planslist')[0].innerHTML = $(data).find('#course_planslist')[0].innerHTML;
        });
    });
});
</script>
