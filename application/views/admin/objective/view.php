<div class="container">
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=$this->lang->line('details_objective')?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=$this->lang->line('field_objective_symbol')?></p>
            <p><?=$objective->symbol?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=$this->lang->line('field_objective_taxonomy')?></p>
            <p><?=$objective->taxonomy?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=$this->lang->line('field_objective_name')?></p>
            <p><?=$objective->name?></p>
        </div>
    </div>
</div>