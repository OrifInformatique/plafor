<?php
?>
<div id="page-content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div>
                    <h1><?= lang('user_lang.objective').' "'.$objective['name'].'"' ?></h1>
                    <h4><?= lang('user_lang.what_to_do')?></h4>
                    <div class = "alert alert-info" ><?= lang('user_lang.objective_'.($objective['archive']==null?'disable_explanation':'enable_explanation'))?></div>
                </div>
                <div class="text-right">
                    <a href="<?= base_url('plafor/admin/list_objective'); ?>" class="btn btn-default">
                        <?= lang('common_lang.btn_cancel'); ?>
                    </a>
                    <?php 
                    echo $objective['archive']!=null?"<a href=".base_url('plafor/admin/delete_objective/'.$objective['id'].'/3').">".lang('common_lang.reactivate')."</a>"
                    :
                    "<a href=".base_url(uri_string().'/1')." class={btn btn-danger} >".
                        lang('common_lang.btn_disable');"
                    </a> "?>
                </div>
            </div>
        </div>
    </div>
</div>
