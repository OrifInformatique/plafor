<?php
/**
 * Fichier de vue pour delete_apprentice
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
        <div class="row">
            <div class="col-12">
                <div>
                    <h1><?= lang('plafor_lang.apprentice_link') ?></h1>
                    <h2><?= lang('plafor_lang.apprentice').' "'.$apprentice['username'].'"' ?></h2>
                    <h2><?= lang('plafor_lang.trainer').' "'.$trainer['username'].'"' ?></h2>
                    <h4><?= lang('user_lang.what_to_do')?></h4>
                    <div class = "alert alert-info" ><?= lang('plafor_lang.apprentice_link_'.($apprentice['archive']==null?'disable_explanation':'enable_explanation'))?></div>
                </div>
                <div class="text-right">
                    <a href="<?= base_url('plafor/apprentice/view_apprentice/'.$apprentice['id']); ?>" class="btn btn-secondary">
                        <?= lang('common_lang.btn_cancel'); ?>
                    </a>
                    <a href="<?= base_url(uri_string().'/1'); ?>" class="btn btn-danger">
                        <?= lang('common_lang.btn_disable'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
