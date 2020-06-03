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