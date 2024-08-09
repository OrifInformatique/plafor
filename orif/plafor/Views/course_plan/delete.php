<?php
/**
 * Fichier de vue pour delete_course_plan
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
?>
<?php
# TODO: remove database call in view 
$coursePlanModel = model('\Plafor\Models\CoursePlanModel');
$courses = $coursePlanModel->getUserCourses($course_plan['id']);
$apprentices = [];
$userCourseStatus = [];
$session = session();

foreach ($courses as $course){
    $userCourseModel = model('\Plafor\Models\UserCourseModel');
    $apprentices[] = $userCourseModel->getUser($course['fk_user']);
    $userCourseStatus[] = $userCourseModel->getUserCourseStatus($course['fk_status']);

}
/**@TODO
 * Make situation when there are multiple apprentices associated with the same Course Plan
 **/
?>
<div id="page-content-wrapper">
    <div class="container">
        <!-- TITLE -->
        <div class="row">
            <div class="col">
                <h1 class="title-section"><?= lang('plafor_lang.title_course_plan_'.(is_null($course_plan['archive'])?'delete':'enable')); ?></h1>
                <h2><?= $course_plan['official_name'] ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div>
                    <p><?=count($apprentices)>0?lang('plafor_lang.apprentices_already_assigned_to_course_plan').' :':''?></p>
                    <ul>
                    <?php foreach ($apprentices as $apprentice): ?>
                        <?php
                        # TODO: remove database call in view 
                        $UserCourseStatusModel = model('\Plafor\Models\UserCourseStatusModel');
                        $userCourseModel = model('\Plafor\Models\UserCourseModel');
                        $userId = $userCourseModel
                            ->where('fk_user', $apprentice['id'])
                            ->where('fk_course_plan', $course_plan['id'])->first()['fk_status'];
                        $usercourse_name = $UserCourseStatusModel->find()['name'];
                        ?>
                        <li><?= ' "'.$apprentice['username'].'"'. lang('plafor_lang.with_status') ?> "<?= $usercourse_name ?>"</li>
                    <?php endforeach;?>
                    </ul>
                    <div class = "alert alert-info" ><?= lang('plafor_lang.course_plan_'.($course_plan['archive']==null?'disable_explanation':'enable_explanation'))?></div>
                </div>
                <div class="text-right">
                    <a href="<?= $session->get('_ci_previous_url')?>" class="btn btn-secondary">
                        <?= lang('common_lang.btn_cancel'); ?>
                    </a> 
                    <?php 
                    echo $course_plan['archive']!=null?"<a href=".base_url('plafor/courseplan/delete_course_plan/'.$course_plan['id'].'/3').">".lang('common_lang.reactivate')."</a>"
                    :
                    "<a href=".base_url(uri_string().'/1')." class={btn btn-danger} >".
                        lang('common_lang.btn_disable');"
                    </a> "?>
                </div>
            </div>
        </div>
    </div>
</div>
