<div id="page-content-wrapper">
    <div class="container">
        <!-- TITLE -->
        <div class="row">
            <div class="col">
                <h2 class="title-section"><?= $title; ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div>
                    <p>
                        <?= lang('plafor_lang.apprentice').' : '.$apprentice['username'] ?><br>
                        <?= lang('plafor_lang.course_plan').' : '.$course_plan['official_name'] ?><br>
                        <?= lang('plafor_lang.status').' : '.$status['name'] ?>
                    </p>
                    <div class = "alert alert-warning" ><?= lang('plafor_lang.user_course_delete_explanation')?></div>
                </div>
                <div class="text-right">
                    <a href="<?= base_url('plafor/apprentice/list_user_courses/'.$apprentice['id']); ?>" class="btn btn-secondary">
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
