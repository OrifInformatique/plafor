<div class="container">
    <?=view('\Plafor\templates\navigator',['title'=>lang('plafor_lang.title_view_user_course')])?>

    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_user_course')?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_user_course_date_begin')?></p>
            <p><?=$user_course['date_begin']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_user_course_date_end')?></p>
            <p><?=$user_course['date_end']?></p>
        </div>
        <div class="col-md-4">
            <p class="font-weight-bold"><?=lang('plafor_lang.field_user_course_status')?></p>
            <p><?=$user_course_status['name']?></p>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?= lang('plafor_lang.apprentice') ?></p>
            <a href="<?= base_url('plafor/apprentice/view_apprentice/'.$apprentice['id'])?>"><?=$apprentice['username']?></a>
        </div>
        <div class="col-md-6">
            <p class="font-weight-bold"><?= lang('plafor_lang.course_plan') ?></p>
            <a href="<?= base_url('plafor/courseplan/view_course_plan/'.$course_plan['id'])?>"><span class="font-weight-bold"><?=$course_plan['formation_number']?> </span><?=$course_plan['official_name']?></a>
        </div>
    </div>
    <?php $trainers_id = array();
        foreach($trainers_apprentice as $trainer_apprentice):
            $trainers_id[] = $trainer_apprentice['fk_trainer'];
        endforeach;

        if(($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_lvl_admin)
        || ($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_lvl_trainer && in_array($_SESSION['user_id'], $trainers_id))
        || ($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_level_apprentice && $user_course['fk_user'] == $apprentice['id'])): ?>
    <div class="row">
        <p style="margin-left: 15px" class="font-weight-bold"><?= lang('plafor_lang.field_user_course_objectives_status') ?></p>
        <div class="col-md-12" >
            <table class="table table-hover" id="objectiveListContent">
                <thead>
                    <tr>
                        <th><?= lang('plafor_lang.field_symbol'); ?></th>
                        <th><?= lang('plafor_lang.field_objective_name'); ?></th>
                        <th><?= lang('plafor_lang.field_taxonomy'); ?></th>
                        <th><?= lang('plafor_lang.field_acquisition_level'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach($acquisition_status as $acquisition): ?>
                    <tr>
                        <td><a href="<?= base_url('plafor/apprentice/view_acquisition_status/'.$acquisition['id'])?>"><?= $objectives[$acquisition['fk_objective']]['symbol']; ?></a></td>
                        <td><a href="<?= base_url('plafor/apprentice/view_acquisition_status/'.$acquisition['id'])?>"><?= $objectives[$acquisition['fk_objective']]['name']; ?></a></td>
                        <td><a href="<?= base_url('plafor/apprentice/view_acquisition_status/'.$acquisition['id'])?>"><?= $objectives[$acquisition['fk_objective']]['taxonomy']; ?></a></td>
                        <td style="padding: .75rem 0 .75rem 0"> <select class="form-control acquisitionStatusSelect" data-acquisition-status-id="<?=$acquisition['id']?>">
                                <?php foreach($acquisition_levels as $acquisition_level):?>
                                    <option <?=$acquisition_level['id']==$acquisition['fk_acquisition_level']?'selected':''?> value="<?=$acquisition_level['id']?>"><?=$acquisition_level['name']?></option>
                                <?php endforeach; ?>
                            </select></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div id="objectiveListContentResponsive">
            <?php foreach($acquisition_status as $acquisition): ?>
                <div class="objectiveCardContainer card">
                    <header class="objectiveCardHeader bg-primary text-white"><a href="<?= base_url('plafor/apprentice/view_acquisition_status/'.$acquisition['id'])?>"><?= $objectives[$acquisition['fk_objective']]['symbol']; ?></a></header>
                    <div class="objectiveCardContentContainer">
                        <p class="objectiveCardDescription">
                            <a href="<?= base_url('plafor/apprentice/view_acquisition_status/'.$acquisition['id'])?>"><?= $objectives[$acquisition['fk_objective']]['name']; ?></a>
                        </p>
                    </div>
                    <footer class="objectiveCardFooter">
                        <p class="objectiveCardTaxonomy bg-secondary text-white"><a href="<?= base_url('plafor/apprentice/view_acquisition_status/'.$acquisition['id'])?>"><?= $objectives[$acquisition['fk_objective']]['taxonomy']; ?></a></p>
                        <select class="form-control acquisitionStatusSelect" data-acquisition-status-id="<?=$acquisition['id']?>">
                            <?php foreach($acquisition_levels as $acquisition_level):?>
                                <option <?=$acquisition_level['id']==$acquisition['fk_acquisition_level']?'selected':''?> value="<?=$acquisition_level['id']?>"><?=$acquisition_level['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </footer>
                </div>
            <?php endforeach;?>
        </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_lvl_admin): ?>
    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-primary text-white" href="<?= base_url('plafor/apprentice/save_user_course/'.$apprentice['id']."/".$user_course['id'])?>"><?= lang('plafor_lang.title_user_course_update') ?></a>
            <a class="btn btn-danger text-white" href="<?= base_url('plafor/courseplan/delete_user_course/'.$user_course['id'])?>"><?= lang('plafor_lang.title_user_course_delete') ?></a>
        </div>
    </div>
    <?php endif; ?>
</div>
<script defer>
    window.onload=()=>{
        fixCardDescriptionLength();
    }
    window.onresize=()=>{
        fixCardDescriptionLength();
    }
    function fixCardDescriptionLength(){
        let maxHeight=0;
        document.querySelectorAll('.objectiveCardDescription').forEach((node)=> {
            if (node.clientHeight>maxHeight){
                maxHeight=node.clientHeight;
            }
        })
        document.querySelectorAll('.objectiveCardDescription').forEach((node)=>{
            node.style.minHeight=`${maxHeight}px`;
        })
    }
        document.querySelectorAll('.acquisitionStatusSelect').forEach((element)=>{
            element.addEventListener('change',(e)=>{
               $.post(`<?=base_url('plafor/apprentice/save_acquisition_status')?>/${e.target.getAttribute('data-acquisition-status-id')}`,{field_acquisition_level:e.target.value}).done((response)=>{
                })




            })
        })


</script>