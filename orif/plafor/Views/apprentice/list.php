<?php
helper('Form');
/**
 * Users List View
 *
 * @author      Orif, section informatique (UlSi, ViDi)
 * @link        https://github.com/OrifInformatique/gestion_questionnaires
 * @copyright   Copyright (c) Orif (http://www.orif.ch)
 */
?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('plafor_lang.title_apprentice_list'); ?></h1>
            <div style="display:flex;flex-direction:row;align-items:center;justify-content:space-between;">
                <?php
                echo form_open(base_url('plafor/apprentice/list_apprentice/'), ['method' => 'GET']);
                echo form_dropdown('trainer_id', $trainers, $trainer_id, ['class' => 'form-control', 'style' => 'width:unset!important;display:unset!important;margin-left:-10px;']);
                echo form_submit(null, lang('common_lang.btn_search'), ['class' => 'btn btn-primary', 'style' => 'vertical-align:unset!important;']); ?>
                <?php
                echo form_close();
                ?>
                <span>

            <?= form_checkbox('toggle_deleted', '', $with_archived, [
                'id' => 'toggle_deleted', 'class' => 'form-check-input'
            ]); ?>
            <?= form_label(lang('common_lang.btn_show_disabled'), 'toggle_deleted', ['class' => 'form-check-label']); ?>
                </span>
            </div>


        </div>
    </div>
</div>

<div class="row mt-2">
    <table class="table table-hover">
        <thead>
        <tr>
            <th><?= lang('plafor_lang.field_apprentice_username'); ?></th>
            <th><?= lang('plafor_lang.field_followed_courses'); ?></th>
            <th><?= lang('plafor_lang.title_progress') ?></th>
        </tr>
        </thead>
        <tbody id="apprenticeslist">
        <?php foreach ($apprentices as $apprentice) { ?>
            <tr>
                <td>
                    <a href="<?= base_url('plafor/apprentice/view_apprentice/' . $apprentice['id']); ?>"><?= $apprentice['username']; ?>
                </td>
                <td><a href="<?= base_url('plafor/admin/list_course_plan/' . $apprentice['id']) ?>"><?php
                        $linkedCourses = "";
                        foreach ($courses as $course) {
                            $linkedCourses .= ($course['fk_user'] == $apprentice['id'] ? $coursesList[$course['fk_course_plan']]['official_name'] . "," : "");
                        }
                        echo rtrim($linkedCourses, ",");
                        ?></a></td>
                <td style="width: 30%;max-width: 300px;min-width: 200px">
                    <div class="progressContainer" apprentice_id="<?= $apprentice['id'] ?>"></div>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</div>

<script>
    $(document).ready(function () {
        $('#toggle_deleted').change(e => {
            let checked = e.currentTarget.checked;
            $.post('<?php echo base_url("plafor/apprentice/list_apprentice") . '/'?>' + ((checked == true ? '1' : '0')), {}, data => {
                $('#apprenticeslist').empty();
                $('#apprenticeslist')[0].innerHTML = $(data).find('#apprenticeslist')[0].innerHTML;
            });
        });
    });

</script>
<script type="text/babel">
    //because jquery use
    $(document).ready(async () => {
        //execute jquery code under
        const nodeList = document.querySelectorAll('.progressContainer');
        //add all nodes containing apprentice_id attribute
        let orderedArray = [];
        nodeList.forEach((element) => {
            orderedArray[parseInt(element.getAttribute('apprentice_id'))] = element;
        });
        //for each elements
        orderedArray.forEach((node, index) => {
            console.log(index);
            $.get("<?=base_url('plafor/apprentice/getCoursePlanProgress')?>/" + index, function () {

            }).done((element) => {
                //response received

                const objectives = JSON.parse(element);
                //count all objectives by acquisition status
                let statusCount = new Map();

                Object.keys(objectives).forEach((user_course_id) => {
                    /**<--Here comes all user_courses_id associated --> */
                    Object.keys(objectives[user_course_id]).forEach((objectiveId) => {
                        //if statusCount contains already key
                        try {
                            if (statusCount.get(objectives[user_course_id][objectiveId]['acquisition_level']) !== undefined) {
                                statusCount.set((objectives[user_course_id][objectiveId]['acquisition_level']), parseInt(statusCount.get((objectives[user_course_id][objectiveId]['acquisition_level']))) + 1);
                            } else {
                                statusCount.set((objectives[user_course_id][objectiveId]['acquisition_level']), 1);
                            }
                        } catch (e) {

                        }
                    })
                })
                ReactDOM.render(<Progressbar colors={['#6ca77f', '#AE9B70', '#d9af47', '#D9918D']}
                                             elements={[statusCount.get('4') != undefined ? statusCount.get('4') : 0, statusCount.get('3') != undefined ? statusCount.get('3') : 0, statusCount.get('2') != undefined ? statusCount.get('2') : 0, statusCount.get('1') != undefined ? statusCount.get('1') : 0]}
                                             timeToRefresh="10" elementToGroup={3} detailsLbl={"<?=lang('plafor_lang.details_progress')?>"}
                                             clickAction={displayDetails}
                />, node);
            })
            //use ~5% of items for group

        })
    });
    function displayDetails(parentElement,colors,elements){
        ReactDOM.render(<ProgressStats animatableImagePath={"<?=base_url('/images/floumin.svg');?>"} colors={colors} elements={elements}/>,parentElement)
    }
</script>

