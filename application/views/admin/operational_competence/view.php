<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=$this->lang->line('details_operational_competence')?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=$this->lang->line('field_operational_competence_symbol')?></p>
            <p><?=$operational_competence->symbol?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?=$this->lang->line('field_operational_competence_name')?></p>
            <p><?=$operational_competence->name?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=$this->lang->line('field_operational_competence_methodologic')?></p>
            <p><?=$operational_competence->methodologic?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=$this->lang->line('field_operational_competence_social')?></p>
            <p><?=$operational_competence->social?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=$this->lang->line('field_operational_competence_personal')?></p>
            <p><?=$operational_competence->personal?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=$this->lang->line('field_linked_objectives')?></p>
        </div>
        <div class="col-md-12">
            <table class="table table-hover">
            <thead>
                <tr>
                    <th><span class="font-weight-bold"><?=$this->lang->line('field_objectives_symbols')?></span></th>
                    <th><span class="font-weight-bold"><?=$this->lang->line('field_objectives_taxonomies')?></span></th>
                    <th><span class="font-weight-bold"><?=$this->lang->line('field_objectives_names')?></span></th>
                </tr>
            </thead>
            <tbody><?php
            foreach ($objectives as $objective){
                ?><tr>
                    <td><a class="font-weight-bold" href="<?= base_url('apprentice/view_objective/'.$objective->id)?>"><?=$objective->symbol?></a></td>
                    <td><a href="<?= base_url('apprentice/view_objective/'.$objective->id)?>"><?=$objective->taxonomy?></a></td>
                    <td><a href="<?= base_url('apprentice/view_objective/'.$objective->id)?>"><?=$objective->name?></a></td><?php
                }?></tr>
            </tbody>
            </table>
        </div>
    </div>
</div>