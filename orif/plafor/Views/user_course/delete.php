<div id="page-content-wrapper">
    <div class="container">
        <!-- TITLE -->
        <div class="row">
            <div class="col">
                <h1 class="title-section"><?= $title; ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div>
                    <h2><?= lang('plafor_lang.apprentice').' "'.$apprentice['username'].'"' ?></h2>
                    <h2><?= lang('plafor_lang.course_plan').' "'.$course_plan['official_name'].'"' ?></h2>
                    <h2><?= lang('plafor_lang.status').' "'.$status['name'].'"' ?></h2>
                    <h4><?= lang('user_lang.what_to_do')?></h4>
                    <div class = "alert alert-info" ><?= lang('plafor_lang.user_course_delete_explanation')?></div>
                </div>
                <div class="text-right">
                    <a href="<?= base_url('plafor/apprentice/view_user_course/'.$apprentice['id']); ?>" class="btn btn-default">
                        <?= lang('common_lang.btn_cancel'); ?>
                    </a>
                    <a href="<?= base_url(uri_string().'/1'); ?>" class="btn btn-danger">
                        <?= lang('common_lang.btn_delete'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
