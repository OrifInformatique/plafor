<?php

// TODO : Delete this view once the common delete view is implemented

/**
 * Fichier de vue pour delete_operational_competence
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */

?>

<div id="page-content-wrapper">
    <div class="container">
        <!-- TITLE -->
        <div class="row">
            <div class="col">
                <h2><?= lang('plafor_lang.title_operational_competence_'.(is_null($operational_competence['archive'])?'delete':'enable')); ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div>
                    <h2><?= lang('plafor_lang.operational_competence').' "'.$operational_competence['name'].'"' ?></h2>
                    <h4><?= lang('user_lang.what_to_do')?></h4>
                    <div class = "alert alert-info" ><?= lang('plafor_lang.operational_competence_'.($operational_competence['archive']==null?'disable_explanation':'enable_explanation'))?></div>
                </div>
                <div class="text-right">
                    <a href="<?= base_url('plafor/courseplan/view_competence_domain/'.$operational_competence['fk_competence_domain']); ?>" class="btn btn-secondary">
                        <?= lang('common_lang.btn_cancel'); ?>
                    </a>
                    <?php
                    echo $operational_competence['archive']!=null?"<a href=".base_url('plafor/courseplan/delete_operational_competence/'.$operational_competence['id'].'/3').">".lang('common_lang.reactivate')."</a>"
                    :
                    "<a href=".base_url(uri_string().'/1')." class={btn btn-danger} >".
                        lang('common_lang.btn_disable');"
                    </a> "?>
                </div>
            </div>
        </div>
    </div>
</div>
