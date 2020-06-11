<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=$this->lang->line('details_course_plan')?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=$this->lang->line('field_course_plan_formation_number')?></p>
            <a href="<?= base_url('apprentice/view_course_plan/'.$course_plan->id)?>"><?=$course_plan->formation_number?></a>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=$this->lang->line('field_course_plan_official_name')?></p>
            <a href="<?= base_url('apprentice/view_course_plan/'.$course_plan->id)?>"><?=$course_plan->official_name?></a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=$this->lang->line('details_competence_domain')?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=$this->lang->line('field_competence_domain_symbol')?></p>
            <p><?=$competence_domain->symbol?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=$this->lang->line('field_competence_domain_name')?></p>
            <p><?=$competence_domain->name?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=$this->lang->line('field_linked_operational_competences')?></p>
        </div>
        <div class="col-md-12">
            <table class="table table-hover">
            <thead>
                <tr>
                    <th><span class="font-weight-bold"><?=$this->lang->line('field_operational_competences_symbols')?></span></th>
                    <th><span class="font-weight-bold"><?=$this->lang->line('field_operational_competences_names')?></span></th>
                </tr>
            </thead>
            <tbody><?php
            foreach ($competence_domain->operational_competences as $operational_competence){
                ?><tr>
                    <td><a class="font-weight-bold" href="<?= base_url('apprentice/view_operational_competence/'.$operational_competence->id)?>"><?=$operational_competence->symbol?></a></td>
                    <td><a href="<?= base_url('apprentice/view_operational_competence/'.$operational_competence->id)?>"><?=$operational_competence->name?></a></td><?php
                }?></tr>
            </tbody>
            </table>
        </div>
    </div>
</div>