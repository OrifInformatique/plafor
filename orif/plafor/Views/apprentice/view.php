<?php
/**
 * Fichier de vue pour view_apprentice
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
?>
<div class="container">
    <?=view('\Plafor\templates\navigator',['title'=>lang('plafor_lang.title_view_apprentice')])?>
    <?php
    $maxdate=null;
    $userCourseMax=null;
    foreach ($user_courses as $user_course){
        if($maxdate==null){
            $maxdate=$user_course['date_begin'];
            $userCourseMax=$user_course;
        }
        if(strtotime($user_course['date_begin'])>=strtotime($maxdate)&&$user_course['id']>$userCourseMax['id']){
            $maxdate=$user_course['date_begin'];
            $userCourseMax=$user_course;
        }
        elseif (strtotime($user_course['date_begin'])>strtotime($maxdate)){
            $maxdate=$user_course['date_begin'];
            $userCourseMax=$user_course;
        }
    }
    ?>
    <!-- Apprentice details -->
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_apprentice')?></p>
        </div>
        <div class="col-sm-6">
            <p><span class="font-weight-bold"><?=$apprentice['username']?></span>
            <br><?=$apprentice['email']?></p>
        </div>
        <div class="col-sm-6">
            <p><span class="font-weight-bold"><?=lang('plafor_lang.title_trainer_linked')?></span>

            <?php if(service('session')->get('user_access')>=config('\User\Config\UserConfig')->access_lvl_trainer):?>
                <!-- List with ADMIN buttons, accessible for trainers or admin only -->
                </p><table class="table table-hover table-borderless">
                <tbody>
                    <?php
                    foreach ($links as $link):
                        foreach ($trainers as $trainer):
                            if($link['fk_trainer'] == $trainer['id']): ?>
                                <tr>
                                    <td><a href="<?= base_url('plafor/apprentice/list_apprentice/'.$trainer['id']); ?>"><?= $trainer['username']; ?></a></td>
                                    <td><a href="<?= base_url('plafor/apprentice/save_apprentice_link/'.$apprentice['id'].'/'.$link['id']) ?>"><i class="bi-pencil" style="font-size: 20px;"></i></a></td>
                                    <td><a href="<?= base_url('plafor/apprentice/delete_apprentice_link/'.$link['id']) ?>"><i class="bi-trash" style="font-size: 20px;"></i></a></td>
                                </tr>
                            <?php endif;
                        endforeach;
                    endforeach;
                    ?>
                </tbody>
                </table>

                <a class="btn btn-primary text-white" href="<?= base_url('plafor/apprentice/save_apprentice_link/'.$apprentice['id'])?>"><?= lang('plafor_lang.title_apprentice_link_new') ?></a>
            <?php else:?>
                <?php
                foreach ($links as $link):
                    foreach ($trainers as $trainer):
                        if($link['fk_trainer'] == $trainer['id']): ?>
                            <br><?= $trainer['username']; ?>
                        <?php endif;
                    endforeach;
                endforeach;
                ?>
                </p>
            <?php endif ?>
        </div>

        <!-- Linked course plans -->
        <div class="col-12 mt-2">
            <p><span class="font-weight-bold"><?=lang('plafor_lang.title_apprentice_followed_courses')?></span></p>
            <select class="form-control" id="usercourseSelector">
                <?php foreach ($user_courses as $user_course) { ?>
                    <option value="<?=$user_course['id']?>"><?=$course_plans[$user_course['fk_course_plan']]['official_name']?></option>
                <?php } ?>
            </select>
            <table class="table table-hover table-borderless user-course-details-table">
                <tbody>
                <tr>
                    <td class="user-course-details-begin-date"><?=isset($userCourseMax)?$userCourseMax['date_begin']:null?></td>
                    <td class="user-course-details-end-date"><?=isset($userCourseMax)?$userCourseMax['date_end']:null?></td>
                    <td class="user-course-details-status"><?=isset($userCourseMax)?$user_course_status[$userCourseMax['fk_status']]['name']:null?></td>

                </tr>
                </tbody>
            </table>
            <?php if(service('session')->get('user_access')>=config('\User\Config\UserConfig')->access_lvl_trainer):?>
                <!-- List with ADMIN buttons, accessible for trainers or admin only -->
                <a class="btn btn-primary text-white" href="<?= base_url('plafor/apprentice/save_user_course/'.$apprentice['id'])?>"><?= lang('plafor_lang.title_user_course_new') ?></a>
            <?php else:?>

            <?php endif;

            ?>
        </div>
    </div>
    
    <!-- Current course plan detailed status -->
    <div class="row mt-2">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_course_plan_status')?></p>
            <p class="font-weight-bold user-course-details-course-plan-name"><?= isset($userCourseMax)?$course_plans[$userCourseMax['fk_course_plan']]['official_name']:null ?></p>
            <div id="detailsArray" apprentice_id="<?= $apprentice['id'] ?>" course_plan_id="<?=isset($userCourseMax)?$userCourseMax['fk_course_plan']:null?>"></div>
        </div>
    </div>
</div>

<script type="text/babel">
    $(document).ready(()=>{
        $('#usercourseSelector').val(<?=isset($userCourseMax)?$userCourseMax['id']:null?>);
            setTimeout(()=>{displayDetails(null,<?=json_encode($userCourseMax)?>,'integrated',"<?=base_url("plafor/apprentice/getcourseplanprogress")?>"+'/',"<?=base_url('plafor/apprentice/view_user_course')?>");},200)
            $('#usercourseSelector').change((event)=>{
                let userCourses=<?= json_encode($user_courses)?>;
                let coursePlans=<?= json_encode($course_plans)?>;
                let userCoursesStatus=<?= json_encode($user_course_status)?>;
                document.querySelectorAll('.user-course-details-course-plan-name').forEach((node)=>{
                    node.innerHTML=`${coursePlans[userCourses[event.target.value].fk_course_plan].official_name}`;
                })
                document.querySelectorAll('.user-course-details-begin-date').forEach((node)=>{
                    node.innerHTML=`${userCourses[event.target.value].date_begin}`;
                })
                document.querySelectorAll('.user-course-details-end-date').forEach((node)=>{
                    node.innerHTML=`${userCourses[event.target.value].date_end}`;
                })
                document.querySelectorAll('.user-course-details-status').forEach((node)=>{
                    node.innerHTML=`${userCoursesStatus[userCourses[event.target.value].fk_status].name}`;
                })
                document.getElementById('detailsArray').setAttribute('course_plan_id',userCourses[event.target.value].fk_course_plan);
                displayDetails(null,userCourses[event.target.value],'integrated',"<?=base_url("plafor/apprentice/getcourseplanprogress")?>"+'/',"<?=base_url('plafor/apprentice/view_user_course')?>");

            })
    })
</script>