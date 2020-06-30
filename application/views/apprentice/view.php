<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=$this->lang->line('details_apprentice')?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=$this->lang->line('field_apprentice_username')?></p>
            <p><?=$apprentice->username?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=$this->lang->line('field_apprentice_date_creation')?></p>
            <p><?=$apprentice->date_creation?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=$this->lang->line('field_trainer_link')?></p>
            <a class="btn btn-primary text-white" href="<?= base_url('apprentice/save_apprentice_link/'.$apprentice->id)?>"><?= $this->lang->line('title_apprentice_link_new') ?></a>
        </div>
        <div class="col-md-12">
            <table class="table table-hover">
            <thead>
                <tr>
                    <th><?= $this->lang->line('field_trainers_name') ?></th>
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
                    if($link->fk_trainer == $trainer->id): ?>
                <tr>
                    <td><a href="<?= base_url('apprentice/list_apprentice/'.$trainer->id); ?>"><?= $trainer->username; ?></a></th>
                    <?php if($_SESSION['user_access']): ?>
                    <th><a href="<?= base_url('apprentice/save_apprentice_link/'.$apprentice->id.'/'.$link->id) ?>"><?= $this->lang->line('title_apprentice_link_update'); ?></a></th>
                    <th><a href="<?= base_url('admin/delete_apprentice_link/'.$link->id) ?>"><?= $this->lang->line('title_apprentice_link_delete');?></a></th>
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
            <p class="bg-primary text-white"><?=$this->lang->line('field_followed_courses')?></p>
            <a class="btn btn-primary text-white" href="<?= base_url('apprentice/save_user_course/'.$apprentice->id)?>"><?= $this->lang->line('title_user_course_new') ?></a>
        </div>
        <div class="col-md-12">
            <table class="table table-hover">
            <thead>
                <tr>
                    <th><span class="font-weight-bold"><?=$this->lang->line('field_course_plans_formation_numbers')?></span></th>
                    <th><span class="font-weight-bold"><?=$this->lang->line('field_course_plans_official_names')?></span></th>
                    <th><span class="font-weight-bold"><?=$this->lang->line('course_status')?></span></th>
                </tr>
            </thead>
            <tbody><?php
            foreach ($user_courses as $user_course){
                ?><tr>
                    <td><a class="font-weight-bold" href="<?= base_url('apprentice/view_course_plan/'.$course_plans[$user_course->fk_course_plan-1]->id)?>"><?=$course_plans[$user_course->fk_course_plan-1]->formation_number?></a></td>
                    <td><a href="<?= base_url('apprentice/view_course_plan/'.$course_plans[$user_course->fk_course_plan-1]->id)?>"><?=$course_plans[$user_course->fk_course_plan-1]->official_name?></a></td>
                    <td><a href="<?= base_url('apprentice/view_user_course/'.$user_course->id) ?>"><?=$user_course_status[$user_course->fk_status-1]->name?></a></td><?php
                }?></tr>
            </tbody>
            </table>
        </div>
    </div>
</div>