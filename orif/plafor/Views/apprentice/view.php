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
    <?php
    echo view('\Plafor\templates\navigator', ['title' => lang('plafor_lang.title_view_apprentice')]);

    $maxdate = null;
    $userCourseMax = null;

    foreach ($user_courses as $user_course)
    {
        if($maxdate == null)
        {
            $maxdate = $user_course['date_begin'];
            $userCourseMax = $user_course;
        }

        if(strtotime($user_course['date_begin']) >= strtotime($maxdate)
            && $user_course['id'] > $userCourseMax['id'])
        {
            $maxdate = $user_course['date_begin'];
            $userCourseMax = $user_course;
        }
        else if(strtotime($user_course['date_begin']) > strtotime($maxdate))
        {
            $maxdate = $user_course['date_begin'];
            $userCourseMax = $user_course;
        }
    }
    ?>

    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h2 class="title-section"><?= $title; ?></h2>
        </div>
    </div>

    <!-- Apprentice details -->
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_view_apprentice')?></p>
        </div>

        <div class="col-sm-6">
            <p>
                <span class="font-weight-bold"><?=$apprentice['username']?></span><br>
                <?=$apprentice['email']?>
            </p>
        </div>

        <div class="col-sm-6">
            <p><span class="font-weight-bold"><?=lang('plafor_lang.title_trainer_linked')?></span></p>

            <?php if(service('session')->get('user_access')>=config('\User\Config\UserConfig')->access_lvl_trainer):?>
                <!-- List with ADMIN buttons, accessible for trainers or admin only -->
                <table class="table table-hover table-borderless">
                    <tbody>
                        <?php foreach ($links as $link):
                            foreach ($trainers as $trainer):
                                if($link['fk_trainer'] == $trainer['id']): ?>
                                    <tr>
                                        <td><a href="<?= base_url('plafor/apprentice/list_apprentice/'.$trainer['id']); ?>"><?= $trainer['username']; ?></a></td>
                                        <td><a href="<?= base_url('plafor/apprentice/save_apprentice_link/'.$apprentice['id'].'/'.$link['id']) ?>"><i class="bi-pencil" style="font-size: 20px;"></i></a></td>
                                        <td><a href="<?= base_url('plafor/apprentice/delete_apprentice_link/'.$link['id']) ?>"><i class="bi-trash" style="font-size: 20px;"></i></a></td>
                                    </tr>
                                <?php endif;
                            endforeach;
                        endforeach;?>
                    </tbody>
                </table>

                <a class="btn btn-primary text-white" href="<?= base_url('plafor/apprentice/save_apprentice_link/'.$apprentice['id'])?>">
                    <?= lang('plafor_lang.title_apprentice_link_new') ?>
                </a>
            <?php else:
                foreach ($links as $link):
                    foreach ($trainers as $trainer):
                        if($link['fk_trainer'] == $trainer['id']): ?>
                            <br><?php echo $trainer['username'];
                        endif;
                    endforeach;
                endforeach;
            endif;?>
        </div>

        <!-- Linked course plans -->
        <div class="col-12 mt-2">
            <p><span class="font-weight-bold"><?= lang('plafor_lang.title_apprentice_followed_courses') ?></span></p>

            <select class="form-control" id="usercourseSelector">
                <?php foreach ($user_courses as $user_course): ?>
                    <option value="<?= $user_course['id'] ?>">
                        <?= $course_plans[$user_course['fk_course_plan']]['official_name'] ?>
                    </option>
                <?php endforeach ?>
            </select>

            <table class="table table-hover table-borderless user-course-details-table">
                <tbody>
                    <tr>
                        <td class="user-course-details-begin-date">
                            <?= isset($userCourseMax) ? $userCourseMax['date_begin'] : null ?>
                        </td>
                        <td class="user-course-details-end-date">
                            <?= isset($userCourseMax )? $userCourseMax['date_end'] : null ?>
                        </td>
                        <td class="user-course-details-status">
                            <?= isset($userCourseMax) ? $user_course_status[$userCourseMax['fk_status']]['name'] : null ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <?php if(service('session')->get('user_access') >= config('\User\Config\UserConfig')->access_lvl_trainer): ?>
                <!-- List with ADMIN buttons, accessible for trainers or admin only -->
                <a class="btn btn-primary text-white" href="<?= base_url('plafor/apprentice/list_user_courses/'.$apprentice['id']) ?>">
                    <?= lang('plafor_lang.btn_user_course_manage') ?>
                </a>
            <?php endif?>
        </div>
    </div>

    <!-- Current course plan detailed status -->
    <div class="row mt-2">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_course_plan_status')?></p>

            <p class="font-weight-bold user-course-details-course-plan-name">
                <?= isset($userCourseMax) ? $course_plans[$userCourseMax['fk_course_plan']]['official_name'] : null ?>
            </p>

            <div id="detailsArray" apprentice_id="<?= $apprentice['id'] ?>"
                course_plan_id="<?= isset($userCourseMax) ? $userCourseMax['fk_course_plan'] : null ?>">
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <!-- TODO : Insert data needed for the view -->
        <?= view('\Plafor/grade/school_report') ?>
    </div>
</div>

<script type="text/babel">
const invokeDisplayDetails = () => {
    try {
        displayDetails(null, <?=json_encode($userCourseMax)?>, 'integrated',
            "<?=base_url("plafor/apprentice/getcourseplanprogress")?>" + '/' ,
            "<?=base_url('plafor/apprentice/view_user_course')?>"
        );
    } catch (e) {
        new Promise(resolve => setTimeout(resolve, 300))
            .then(invokeDisplayDetails);
    }
};

const invokeHydrationName = (event, userCourses, coursePlans) => {
    document.querySelectorAll('.user-course-details-course-plan-name')
        .forEach((node) =>
    {
        let coursePlanId = userCourses[event.target.value].fk_course_plan;
        let officialName = coursePlans[coursePlanId].official_name;
        node.innerHTML = new String(official_name);
    });
};

const invokeHydrationBeginDate = (event, userCourses) => {
    document.querySelectorAll('.user-course-details-begin-date').forEach(
        (node) =>
    {
        let dateBegin = userCourses[event.target.value].date_begin;
        node.innerHTML = new String(dateBegin);
    });
};

const invokeHydrationEndDate = (event, userCourses) => {
    document.querySelectorAll('.user-course-details-end-date').forEach(
        (node) =>
    {
        let dateEnd = userCourses[event.target.value].date_end;
        node.innerHTML = new String(dateEnd);
    });
};

const invokeHydrationStatus = (event, userCourses, userCoursesStatus) => {
    document.querySelectorAll('.user-course-details-status').forEach(
        (node) =>
    {
        let statusId = userCourses[event.target.value].fk_status;
        let name = userCoursesStatus[statusId].name;
        node.innerHTML = new String(name);
    });
}

$(document).ready(()=>{
    $('#usercourseSelector').val(<?=isset($userCourseMax)
        ? $userCourseMax['id'] : null?>);
    $('#usercourseSelector').change((event) => {
        let userCourses = <?=json_encode($user_courses)?>;
        let coursePlans = <?=json_encode($course_plans)?>;
        let userCoursesStatus = <?= json_encode($user_course_status)?>;
        invokeHydrationName(event, userCourses, coursePlans);
        invokeHydrationBeginDate(event, userCourses);
        invokeHydrationEndDate(event, userCourses);
        invokeHydrationStatus(event, userCourses, userCoursesStatus);
        document.querySelector('#detailsArray').setAttribute('course_plan_id',
            userCourses[event.target.value].fk_course_plan);
        displayDetails(null, userCourses[event.target.value], 'integrated',
            "<?=base_url("plafor/apprentice/getcourseplanprogress")?>"
            + '/' , "<?=base_url('plafor/apprentice/view_user_course')?>"
        );
    });
    invokeDisplayDetails();
});
</script>
