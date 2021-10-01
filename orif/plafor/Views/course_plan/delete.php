<?php
$courses=\Plafor\Models\CoursePlanModel::getUserCourses($course_plan['id']);
$apprentices=[];
$userCourseStatus=[];
$session=session();

foreach ($courses as $course){
    $apprentices[]=\Plafor\Models\UserCourseModel::getUser($course['fk_user']);
    $userCourseStatus[]=\Plafor\Models\UserCourseModel::getUserCourseStatus($course['fk_status']);
}
/**@TODO
 * Make situation when there are multiple apprentices associated with the same Course Plan
 **/
?>
<div id="page-content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div>
                    <?php
                    if (count($apprentices)>0&&isset($apprentices[0])&&$apprentices[0]!=null){?>
                    <h1><?= lang('plafor_lang.apprentice').' "'.$apprentices[0]['username'].'"' ?></h1>
                    <?php } ?>

                    <h1><?= lang('plafor_lang.course_plan').' "'.$course_plan['official_name'].'"' ?></h1>
                    <?php if (count($userCourseStatus)>0&&isset($userCourseStatus[0])&&$userCourseStatus[0]!=null){?>
                    <h1><?= lang('plafor_lang.status').' "'.$userCourseStatus[0]['name'].'"' ?></h1>
                    <?php } ?>
                    <h4><?= lang('user_lang.what_to_do')?></h4>
                    <div class = "alert alert-info" ><?= lang('plafor_lang.user_course_'.($course_plan['archive']==null?'disable_explanation':'enable_explanation'))?></div>
                </div>
                <div class="text-right">
                    <a href="<?= /*base_url('apprentice/view_user_course/'.$apprentices[0]['id']);*/ $session->get('_ci_previous_url')?>" class="btn btn-default">
                        <?= lang('common_lang.btn_cancel'); ?>
                    </a> 
                    <?php 
                    echo $course_plan['archive']!=null?"<a href=".base_url('plafor/admin/delete_course_plan/'.$course_plan['id'].'/3').">".lang('common_lang.reactivate')."</a>"
                    :
                    "<a href=".base_url(uri_string().'/1')." class={btn btn-danger} >".
                        lang('common_lang.btn_disable');"
                    </a> "?>
                </div>
            </div>
        </div>
    </div>
</div>
