<?php

/**
 * Shows the details of an apprentice, his progress
 * in his course plans, and associated school report.
 *
 * Called by Apprentice/view_apprentice($apprentice_id)
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */



/**
 * *** Data needed for this view ***
 *
 * // TODO : Directly put the title in the view, then delete this param
 * @param string $title Page title.
 *
 * // TODO : Only give apprentice ID, username and email : only values needed
 * @param array $apprentice Apprentice.
 * All fields from table.
 *
 * // TODO : Only give trainer ID and username : only values needed
 * // TODO : Format the data in an array of key-values where keys are trainers IDs and values are trainers usernames
 * @param array $trainers Trainers of the apprentice.
 * Array of key-values where keys are trainers IDs and values contains all trainers data.
 *
 * // TODO : Only give link ID and fk_trainer : only values needed
 * // TODO : Format the data in an array of key-values where keys are links IDs and values are links fk_trainers
 * @param array $links Links between an apprentice and a trainer.
 * Array of key-values where keys are links IDs and values contains all links data.
 *
 * @param array $user_courses List of user courses.
 * Array of key-values where keys are user courses IDs and values contains all user courses data.
 *
 * // TODO : Directly put the name of the user course status as a user course parameter
 * @param array $user_course_status List of user course statuses.
 * Array of key-values where keys are user course statuses IDs and values contains all user courses statuses data.
 *
 * // TODO : Only give course plan official name : only value needed
 * // TODO : Format the data in an array of key-values where keys are course plans IDs and values are course plans official names
 * @param array $course_plans List of course plans.
 * Array of key-values where keys are course plans IDs and values contains all course plans data.
 *
 */



/**
 * No data is sent by this view.
 *
 */

?>

<div class="container">
    <?= view('\Plafor\templates\navigator', ['title' => lang('plafor_lang.title_view_apprentice')]) ?>

    <?php

    // TODO : Move this code into the controller
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

    <!-- Page title -->
    <?= view('\Plafor/common/page_title', ['title' => $title]) ?>

    <!-- Apprentice details -->
    <div class="row">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?= lang('plafor_lang.title_view_apprentice') ?></p>
        </div>

        <div class="col-sm-6">
            <p>
                <strong><?= $apprentice['username'] ?></strong><br>
                <?= $apprentice['email'] ?>
            </p>
        </div>

        <div class="col-sm-6">
            <p><strong><?= lang('plafor_lang.title_trainer_linked') ?></strong></p>

            <?php if(service('session')->get('user_access') >= config('\User\Config\UserConfig')->access_lvl_trainer): ?>
                <!-- List with ADMIN buttons, accessible for trainers or admin only -->
                <table class="table table-hover table-borderless">
                    <tbody>
                        <?php foreach ($links as $link): ?>
                            <?php foreach ($trainers as $trainer): ?>
                                <?php if($link['fk_trainer'] == $trainer['id']): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url('plafor/apprentice/list_apprentice/'.$trainer['id']); ?>">
                                                <?= $trainer['username']; ?>
                                            </a>
                                        </td>

                                        <td>
                                            <a href="<?= base_url('plafor/apprentice/save_apprentice_link/'.$apprentice['id'].'/'.$link['id']) ?>">
                                                <i class="bi-pencil" style="font-size: 20px;"></i>
                                            </a>
                                        </td>

                                        <td>
                                            <a href="<?= base_url('plafor/apprentice/delete_apprentice_link/'.$link['id']) ?>">
                                                <i class="bi-trash" style="font-size: 20px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php endforeach ?>
                    </tbody>
                </table>

                <a class="btn btn-primary text-white" href="<?= base_url('plafor/apprentice/save_apprentice_link/'.$apprentice['id']) ?>">
                    <?= lang('plafor_lang.title_apprentice_link_new') ?>
                </a>
            <?php else: ?>
                <?php foreach ($links as $link): ?>
                    <?php foreach ($trainers as $trainer): ?>
                        <?php if($link['fk_trainer'] == $trainer['id']): ?>
                            <?= $trainer['username'] ?><br>
                        <?php endif ?>
                    <?php endforeach ?>
                <?php endforeach ?>
            <?php endif ?>
        </div>

        <!-- Followed course plans -->
        <div class="col-12 mt-2">
            <p><strong><?= lang('plafor_lang.title_apprentice_followed_courses') ?></strong></p>

            <select class="form-control" id="usercourseSelector">
                <?php foreach ($user_courses as $user_course): ?>
                    <?php if ($user_course_id == $user_course['id'] ): ?>
                        <option value="<?= $user_course['id'] ?>" selected>
                            <?= $course_plans[$user_course['fk_course_plan']]['official_name'] ?>
                        </option>
                    <?php else: ?>
                        <option value="<?= $user_course['id'] ?>">
                            <?= $course_plans[$user_course['fk_course_plan']]['official_name'] ?>
                        </option>
                    <?php endif ?>
                <?php endforeach ?>
            </select>

            <div class="row mt-2">
                <div class="col-4 text-center">
                    <strong><?= lang('plafor_lang.field_user_course_date_begin_short') ?></strong>

                    <p class="user-course-details-begin-date">
                        <?= isset($userCourseMax) ? $userCourseMax['date_begin'] : null ?>
                    </p>
                </div>

                <div class="col-4 text-center">
                    <strong><?= lang('plafor_lang.field_user_course_date_end_short') ?></strong>

                    <p class="user-course-details-end-date">
                        <?= isset($userCourseMax) ? $userCourseMax['date_end'] : null ?>
                    </p>
                </div>

                <div class="col-4 text-center">
                    <strong><?= lang('plafor_lang.field_user_course_status') ?></strong>
                    <p class="user-course-details-status">
                        <?= isset($userCourseMax) ? $user_course_status[$userCourseMax['fk_status']]['name'] : null ?>
                    </p>
                </div>
            </div>

            <?php if(service('session')->get('user_access') >= config('\User\Config\UserConfig')->access_lvl_trainer): ?>
                <a class="btn btn-primary text-white" href="<?= base_url('plafor/apprentice/list_user_courses/'.$apprentice['id']) ?>">
                    <?= lang('plafor_lang.btn_user_course_manage') ?>
                </a>
            <?php endif ?>
        </div>
    </div>

    <!-- Current course plan detailed status -->
    <div class="row mt-2">
        <div class="col-md-12">
            <p class="bg-primary text-white"><?=lang('plafor_lang.title_course_plan_status')?></p>

            <div id="detailsArray" apprentice_id="<?= $apprentice['id'] ?>"
                course_plan_id="<?= isset($userCourseMax) ? $userCourseMax['fk_course_plan'] : null ?>">
            </div>
        </div>
    </div>

    <!-- Current course plan school report -->
    <div class="row mt-2">
        <!-- TODO : Insert data needed for the view -->
        <?= view('\Plafor/grade/school_report') ?>
    </div>
</div>

<script type="text/babel">
    const invokeDisplayDetails = () => {
        try {
            displayDetails(null, <?= json_encode($userCourseMax) ?>, 'integrated',
                "<?= base_url("plafor/apprentice/getcourseplanprogress/") ?>",
                "<?= base_url('plafor/apprentice/view_user_course') ?>"
            );
        } catch (e) {
            new Promise(resolve => setTimeout(resolve, 300))
                .then(invokeDisplayDetails);
        }
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

    $(document).ready(() => {
        // TODO this in controller
        let urlMethod = '<?=base_url('plafor/apprentice/view_apprentice') ?>';
        let apprenticeId = '<?= $apprentice['id'] ?>';
        // $('#usercourseSelector').val(<?= isset($userCourseMax)
        //     ? $userCourseMax['id'] : null ?>);
        let usercourseSelector = document.querySelector('#usercourseSelector');

        usercourseSelector.addEventListener("change", (event) => {
            let userCourseId = usercourseSelector.value;
            let url = urlMethod + '/' + apprenticeId + '/' + userCourseId;
            window.location.replace(url);
        });

        // let userCourses = <?= json_encode($user_courses) ?>;
        // let coursePlans = <?= json_encode($course_plans) ?>;
        // let userCoursesStatus = <?= json_encode($user_course_status) ?>;

        // invokeHydrationBeginDate(event, userCourses);

        // invokeHydrationEndDate(event, userCourses);

        // invokeHydrationStatus(event, userCourses, userCoursesStatus);

        // document.querySelector('#detailsArray').setAttribute('course_plan_id',
        //     userCourses[event.target.value].fk_course_plan);

        // displayDetails(null, userCourses[event.target.value], 'integrated',
        //     "<?= base_url("plafor/apprentice/getcourseplanprogress/")?>",
        //     "<?=base_url('plafor/apprentice/view_user_course')?>"
        // );

        invokeDisplayDetails();
    });
</script>
