<?php
/**
 * Fichier de vue pour delete_competence_domain
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
?>

<?php
$session=\CodeIgniter\Config\Services::session();
?>
<div id="page-content-wrapper">
    <div class="container">
        <!-- TITLE -->
        <div class="row">
            <div class="col">
                <h1 class="title-section"><?= lang('plafor_lang.title_competence_domain_'.(is_null($competence_domain['archive'])?'delete':'enable')); ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div>
                    <h2><?= lang('plafor_lang.competence_domain').' "'.$competence_domain['name'].'"' ?></h2>
                    <h4><?= lang('user_lang.what_to_do')?></h4>
                    <div class = "alert alert-info" ><?= lang('plafor_lang.competence_domain_'.($competence_domain['archive']==null?'disable_explanation':'enable_explanation'))?></div>
                </div>
                <!-- BUTTONS -->
                <div class="text-right">
                    <!-- CANCEL -->
                    <a href="<?= base_url('plafor/courseplan/view_course_plan/'.$competence_domain['fk_course_plan']) ?>" class="btn btn-default">
                        <?= lang('common_lang.btn_cancel'); ?>
                    </a>
                    <!-- ENABLE / DISABLE -->
                    <?php 
                    echo $competence_domain['archive']!=null?"<a href=".base_url('plafor/courseplan/delete_competence_domain/'.$competence_domain['id'].'/3').">".lang('common_lang.reactivate')."</a>"
                    :
                    "<a href=".base_url(uri_string().'/1')." class={btn btn-danger} >".
                        lang('common_lang.btn_disable');"
                    </a> "?>
                </div>
            </div>
        </div>
    </div>
</div>
