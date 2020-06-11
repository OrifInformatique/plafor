<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<div id="page-content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div>
                    <h1><?= lang('objective').' "'.$objective->name.'"' ?></h1>
                    <h4><?= lang('what_to_do')?></h4>
                    <div class = "alert alert-info" ><?= lang('objective_disable_explanation')?></div>
                </div>
                <div class="text-right">
                    <a href="<?= base_url('apprentice/list_objective'); ?>" class="btn btn-default">
                        <?= lang('btn_cancel'); ?>
                    </a>
                    <a href="<?= base_url(uri_string().'/1'); ?>" class="btn btn-danger">
                        <?= lang('btn_disable'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
