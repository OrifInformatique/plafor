<?php
/**
 * Fichier de vue pour list_apprentice
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

view('\Plafor\templates\navigator',['reset'=>true]);
helper('form');
?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('plafor_lang.title_apprentice_list'); ?></h1>
            <div class="row" >
                <div class="col-sm-8">
                    <?php
                    echo form_open(base_url('plafor/apprentice/list_apprentice/'), ['method' => 'GET']);
                    echo form_dropdown('trainer_id', $trainers, strval($trainer_id), ['class' => 'form-control', 'style' => 'width:unset!important;display:unset!important;']);
                    echo form_submit(null, lang('common_lang.btn_search'), ['class' => 'btn btn-primary', 'style' => 'vertical-align:unset!important;']); ?>
                    <?php
                    echo form_close();
                    ?>
                </div>
                <span class="col-sm-4 text-right">
                    <?= form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted', ['class' => 'form-check-label','style'=>'padding-right:30px']); ?>

                    <?= form_checkbox('toggle_deleted', '', $with_archived, [
                        'id' => 'toggle_deleted', 'class' => 'form-check-input'
                    ]); ?>
                </span>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <table class="table table-hover">
            <thead class="list-apprentice-table-header">
                <tr>
                    <th><?= lang('plafor_lang.field_apprentice_username'); ?></th>
                    <th><?= lang('plafor_lang.field_followed_courses'); ?></th>
                    <th><?= lang('plafor_lang.title_progress') ?></th>
                </tr>
            </thead>
            <tbody id="apprenticeslist">
                <?php foreach ($apprentices as $apprentice) { ?>
                <tr>
                    <td>
                        <a href="<?= base_url('plafor/apprentice/view_apprentice/' . $apprentice['id']); ?>"><?= $apprentice['username']; ?>
                    </td>
                    <td>
                        <?php if(isset($courses) && !empty($courses)): ?>
                            <?php
                            $linkedCourses = "";
                            foreach ($courses as $course)
                                $linkedCourses .= ($course['fk_user'] == $apprentice['id'] ? $coursesList[$course['fk_course_plan']]['official_name'] . "," : "");

                            echo rtrim($linkedCourses, ",");
                            ?>
                        <?php else: ?>
                            <a href="<?= base_url('plafor/apprentice/save_user_course/'.$apprentice['id']); ?>" class="btn btn-primary">
                                <?= lang('common_lang.btn_new_m'); ?>
                            </a>
                        <?php endif ?>
                    </td>
                    <td style="width: 30%;max-width: 300px;min-width: 200px">
                        <div class="progressContainer" apprentice_id="<?= $apprentice['id'] ?>"></div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/babel" defer>
const invokeInitProgress = () => {
    try {
        initProgress("<?=base_url("plafor/apprentice/getcourseplanprogress")?>"
            + '/', "<?=lang('plafor_lang.details_progress')?>");
    } catch (e) {
      new Promise(resolve => setTimeout(resolve, 300))
            .then(invokeInitProgress);
    }
};

$(document).ready(function () {
    invokeInitProgress();
    $('#toggle_deleted').change(e => {
        let checked = e.currentTarget.checked;
        let url = '<?=base_url("plafor/apprentice/list_apprentice") . '/'?>'
            + (checked ? '1' : '0');
        $.post(url , {}, data => {
            $('#apprenticeslist').empty();
            $('#apprenticeslist')[0].innerHTML = $(data)
                .find('#apprenticeslist')[0].innerHTML;
        }).then(()=>{
            let url = "<?=base_url(
                "plafor/apprentice/getcourseplanprogress")?>" + '/';
            initProgress(url, "<?=lang('plafor_lang.details_progress')?>");
        });
    });
});
</script>

