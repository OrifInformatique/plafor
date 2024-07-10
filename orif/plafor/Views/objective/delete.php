<?php
/**
 * Fichier de vue pour delete_objective
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
?>
<?php
?>
<div id="page-content-wrapper">
    <div class="container">
        <!-- TITLE -->
        <div class="row">
            <div class="col">
                <h1 class="title-section"><?= lang('plafor_lang.title_objective_'.(is_null($objective['archive'])?'delete':'enable')); ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div>
                    <h2><?= lang('plafor_lang.objective').' "'.$objective['name'].'"' ?></h2>
                    <h4><?= lang('user_lang.what_to_do')?></h4>
                    <div class = "alert alert-info" ><?= lang('plafor_lang.objective_'.($objective['archive']==null?'disable_explanation':'enable_explanation'))?></div>
                </div>
                <div class="text-right">
                    <a href="<?= base_url('plafor/courseplan/view_operational_competence/'.$objective['fk_operational_competence']); ?>" class="btn btn-secondary">
                        <?= lang('common_lang.btn_cancel'); ?>
                    </a>
                    <?php 
                    echo $objective['archive']!=null?"<a href=".base_url('plafor/courseplan/delete_objective/'.$objective['id'].'/3').">".lang('common_lang.reactivate')."</a>"
                    :
                    "<a href=".base_url(uri_string().'/1')." class={btn btn-danger} >".
                        lang('common_lang.btn_disable');"
                    </a> "?>
                </div>
            </div>
        </div>
    </div>
</div>
