<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_apprentice')?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('user_lang.field_apprentice_username')?></p>
            <p><?=$apprentice['username']?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=lang('user_lang.field_apprentice_date_creation')?></p>
            <p><?=$apprentice['date_creation']?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_trainer_linked')?></p>
            <a class="btn btn-primary text-white" href="<?= base_url('plafor/apprentice/save_apprentice_link/'.$apprentice['id'])?>"><?= lang('user_lang.title_apprentice_link_new') ?></a>
        </div>
        <div class="col-md-12">
            <table class="table table-hover">
            <thead>
                <tr>
                    <th><?= lang('user_lang.field_trainers_name') ?></th>
                    <?php if($_SESSION['user_access']): ?>
                    <th></th>
                    <th></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($links as $link):
                foreach ($trainers as $trainer):
                    if($link['fk_trainer'] == $trainer['id']): ?>
                <tr>
                    <td><a href="<?= base_url('plafor/apprentice/list_apprentice/'.$trainer['id']); ?>"><?= $trainer['username']; ?></a></th>
                    <?php if($_SESSION['user_access']): ?>
                    <th><a href="<?= base_url('plafor/apprentice/save_apprentice_link/'.$apprentice['id'].'/'.$link['id']) ?>"><?= lang('user_lang.title_apprentice_link_update'); ?></a></th>
                    <th><a href="<?= base_url('plafor/admin/delete_apprentice_link/'.$link['id']) ?>"><?= lang('user_lang.title_apprentice_link_delete');?></a></th>
                    <?php endif; ?>
                </tr><?php
                    endif;
                endforeach;
            endforeach;
            ?>
            </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_apprentice_followed_courses')?></p>
            <a class="btn btn-primary text-white" href="<?= base_url('plafor/apprentice/save_user_course/'.$apprentice['id'])?>"><?= lang('user_lang.title_user_course_new') ?></a>
        </div>
        <div class="col-md-12">
            <table class="table table-hover">
            <thead>
                <tr>
                    <th><span class="font-weight-bold"><?=lang('user_lang.field_course_plans_formation_numbers')?></span></th>
                    <th><span class="font-weight-bold"><?=lang('user_lang.field_course_plans_official_names')?></span></th>
                    <th><span class="font-weight-bold"><?=lang('user_lang.course_status')?></span></th>
                </tr>
            </thead>
            <tbody><?php

            foreach ($user_courses as $user_course){
                ?><tr>
                    <td><a class="font-weight-bold" href="<?= base_url('plafor/apprentice/view_course_plan/'.$course_plans[$user_course['fk_course_plan']]['id'])?>"><?=$course_plans[$user_course['fk_course_plan']]['formation_number']?></a></td>
                    <td><a href="<?= base_url('plafor/apprentice/view_course_plan/'.$course_plans[$user_course['fk_course_plan']]['id'])?>"><?=$course_plans[$user_course['fk_course_plan']]['official_name']?></a></td>
                    <td><a href="<?= base_url('plafor/apprentice/view_user_course/'.$user_course['id']) ?>"><?=$user_course_status[$user_course['fk_status']]['name']?></a></td><?php
                }?></tr>
            </tbody>
            </table>
        </div>
    </div>
</div>