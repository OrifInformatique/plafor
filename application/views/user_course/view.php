<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=$this->lang->line('details_user_course')?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=$this->lang->line('field_user_course_date_begin')?></p>
            <p><?=$user_course->date_begin?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=$this->lang->line('field_user_course_date_end')?></p>
            <p><?=$user_course->date_end?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=$this->lang->line('field_user_course_status')?></p>
            <p><?=$user_course_status->name?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?= $this->lang->line('apprentice') ?></p>
            <a href="<?= base_url('apprentice/view_apprentice/'.$apprentice->id)?>"><?=$apprentice->username?></a>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?= $this->lang->line('course_plan') ?></p>
            <a href="<?= base_url('apprentice/view_course_plan/'.$course_plan->id)?>"><span class="font-weight-bold"><?=$course_plan->formation_number?> </span><?=$course_plan->official_name?></a>
        </div>
    </div>
    <?php $trainers_id = array();
        foreach($trainers_apprentice as $trainer_apprentice):
            $trainers_id[] = $trainer_apprentice->fk_trainer;
        endforeach;

        if(($_SESSION['user_access'] == ACCESS_LVL_ADMIN)
        || ($_SESSION['user_access'] == ACCESS_LVL_TRAINER && in_array($_SESSION['user_id'], $trainersId))
        || ($_SESSION['user_access'] == ACCESS_LVL_APPRENTICE && $user_course->fk_user == $apprentice->id)): ?>
    <div class="row">
        <p class="font-weight-bold"><?= $this->lang->line('field_user_course_objectives_status') ?></p>
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><?= $this->lang->line('field_symbol'); ?></th>
                        <th><?= $this->lang->line('field_objective_name'); ?></th>
                        <th><?= $this->lang->line('field_taxonomy'); ?></th>
                        <th><?= $this->lang->line('field_acquisition_level'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($acquisition_status as $acquisition): ?>
                    <tr>
                        <td><a href="<?= base_url('apprentice/view_acquisition_status/'.$acquisition->id)?>"><?= $acquisition->objective->symbol; ?></a></td>
                        <td><a href="<?= base_url('apprentice/view_acquisition_status/'.$acquisition->id)?>"><?= $acquisition->objective->name; ?></a></td>
                        <td><a href="<?= base_url('apprentice/view_acquisition_status/'.$acquisition->id)?>"><?= $acquisition->objective->taxonomy; ?></a></td>
                        <td><a href="<?= base_url('apprentice/view_acquisition_status/'.$acquisition->id)?>"><?= $acquisition->acquisition_level->name; ?></a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
    <?php if($_SESSION['user_access'] == ACCESS_LVL_ADMIN): ?>
    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-primary text-white" href="<?= base_url('apprentice/save_user_course/'.$apprentice->id."/".$user_course->id)?>"><?= $this->lang->line('title_user_course_update') ?></a>
            <a class="btn btn-danger text-white" href="<?= base_url('admin/delete_user_course/'.$user_course->id)?>"><?= $this->lang->line('title_user_course_delete') ?></a>
        </div>
    </div>
    <?php endif; ?>
</div>