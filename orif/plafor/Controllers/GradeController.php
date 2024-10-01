<?php
/**
 * Controller who manage modules and subjects grades
 * Required level connected
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class GradeController extends \App\Controllers\BaseController{

    // Class Constant
    const m_ERROR_MISSING_PERMISSIONS = "\User/errors/403error";

    /**
     * Initializes controller attributes.
     *
     * @param RequestInterface $request
     *
     * @param ResponseInterface $response
     *
     * @param LoggerInterface $logger
     *
     * @return void
     *
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        $this->access_level = "@";

        parent::initController($request, $response, $logger);

        $this->m_grade_model              = model("GradeModel");
        $this->m_teaching_module_model    = model("TeachingModuleModel");
        $this->m_teaching_subject_model   = model("TeachingSubjectModel");
        $this->m_trainer_apprentice_model = model("TrainerApprenticeModel");
        $this->m_user_course_model        = model("UserCourseModel");
        $this->m_user_model               = model("User_model");

        helper("AccessPermissions_helper");
    }



    /**
     * Calculate the average of all grades.
     *
     * @param array $grades
     *
     * @return int
     *
     */
    private function calculateAverageGrade(array $grades): int
    {
        $nbr_grade = count($array);
        $all_grade = array_column($array, "grade");
        $total_grade = array_sum($all_grade);

        return $total_grade / $nbr_grade;
    }



    /**
     * Inserts or modifies the grade of an apprentice.
     *
     * @param int $apprentice_id ID of the apprentice.
     *
     * @param int $grade_id ID of the grade.
     *
     * @return string|RedirectResponse
     *
     */
    public function saveGrade(int $apprentice_id = 0, int $grade_id = 0): string|RedirectResponse
    {
        if (!isCurrentUserTrainerOfApprentice($apprentice_id)
            && !isCurrentUserSelfApprentice($apprentice_id))
        {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        $user_course_id = $this->request->getPost("user_course_id");

        if (count($_POST) > 0){
            d($_POST);
            // TODO: check if it's a subject or a module s or m (parse the first char of the string)
            $selected_entry = $this->request->getPost("selected_entry");

            // $grades = []; // TODO: check what is needed ??
            // foreach ($this->m_grade_model->where("fk_user_course", $user_course_id)->withDeleted($with_archived)->findAll() as $grade){
            //     dd($this->m_grade_model->where("fk_user_course", $user_course_id)->withDeleted($with_archived)->findAll());
            //     $grades [] = [
            //         "id"                        => $grade["id"],
            //         "user_course_id"            => $grade["module_number"],
            //         "apprentice"                => [
            //             "id"                        => int,
            //             "username"                  => string,
            //         ],
            //         "course_plan"               => $grade["official_name"],
            //         "subject_and_domains_list"  => [
            //             lang("Grades.subjects")     => [], // List of sujects contained in the course_plan. Required.
            //                 //Array of key-values where keys are subjects IDs with a "s" before and values are subject names.

            //             lang("Grades.modules")      => [],// List of modules contained in the course_plan. Required.
            //                 //Array of key-values where keys are modules IDs with a "m" before and values are modules names.
            //         ],
            //         "selected_entry"            => $grade["version"],
            //         "grade"                     => $grade["grade"],
            //         "exam_date"                 => $grade["date"],
            //         "is_exam_made_in_school"    => $grade["is_school"],
            //     ];
            // }

            $data_to_model =
            [
                "id"                    => $grade_id,
                "fk_user_course"        => $user_course_id,
                "fk_teaching_subject"   => $subject_id,
                "fk_teaching_module"    => $module_id,
                "date"                  => $this->request->getPost("exam_date"),
                "grade"                 => $this->request->getPost("grade"),
                "is_school"             => $this->request->getPost("is_exam_made_in_school"),
            ];

            $this->m_grade_model->save($data_to_model);

            if (empty($this->m_grade_model->errors()))
                return redirect()->to("plafor/grade/showAllGrade");
        }

        $data_from_model = $this->m_grade_model->withDeleted()->find($grade_id);

        $data_to_view =
        [
            "title"                 => $grade_id == 0 ? lang('Grades.add_grade') : lang('Grades.update_grade'),
            // "grade_id"              => $grade_id,
            // "user_course_id"        => $data_from_model["user_course_id"],
            // "subject" => $subject_id,
            // "module" => $module_id,
            // "date"  => $date,
            // "grade" => $grade,
            // "is_school" => $is_school,
            // "errors"  => $this->m_grade_model->errors()
        ];

        return $this->display_view("\Plafor/grade/save", $data_to_view);
    }



    /**
     * Alterate a grade depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int|null $action Action to apply on the grade.
     *      - 1 for deactivating (soft delete)
     *      - 2 for deleting (hard delete)
     *      - 3 for reactivating
     *
     * @param int $grade_id ID of the grade.
     *
     * @param bool $confirm Defines whether the action has been confirmed.
     *
     * @return string|RedirectResponse
     *
     */
    public function deleteGrade(int|null $action = null, int $grade_id = 0, bool $confirm = false): string|RedirectResponse
    {
        $grade = $this->m_grade_model->withDeleted()->find($grade_id);

        if(is_null($grade) || !isset($action))
            return redirect()->to("plafor/grade/save");

        $user_course = $this->m_user_course_model->find($grade["fk_user_course"]);
        $apprentice_id = $this->m_user_model->find($user_course["fk_user"]);

        if (!isCurrentUserTrainerOfApprentice($apprentice["id"]))
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        if($grade["fk_teaching_subject"] > 0)
            $subject = $this->m_teaching_subject_model->find($grade['fk_teaching_subject']);

        elseif($grade["fk_teaching_module"] > 0)
            $module = $this->m_teaching_module_model->find($grade['fk_teaching_module']);

        // No subject or module : prevents going further.
        else
            return redirect()->to("plafor/grade/save");

        if(!$confirm)
        {
            $output =
            [
                "entry" =>
                [
                    "type"  => lang("plafor_lang.name_grade"),
                    "name"  => "",
                    "data"  =>
                    [
                        [
                            "name"  => lang('Grades.grade'),
                            "value" => $grade["grade"]
                        ],
                        [
                            "name"  => lang('plafor_lang.apprentice'),
                            "value" => $apprentice["username"]
                        ],
                        [
                            "name"  => lang('Grades.grade').' '.lang('Grades.of'),
                            "value" => $subject["name"] ?? $module["module_number"].' - '.$module["official_name"]
                        ]
                    ]
                ],
                "cancel_btn_url" => base_url("plafor/grade/save/".$grade_id)
            ];
        }

        switch($action)
        {
            // Deactivates the grade
            case 1:
                if(!$confirm)
                {
                    $output['type'] = 'disable';
                    $output["entry"]["message"] = lang("Grades.grade_disable_explanation");

                    return $this->display_view('\Common/manage_entry', $output);
                }
                $this->m_grade_model->delete($grade_id);
                break;

            // Deletes the grade
            case 2:
                if(!$confirm)
                {
                    $output['type'] = 'delete';
                    $output["entry"]["message"] = lang("Grades.grade_delete_explanation");

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->m_grade_model->delete($grade_id, true);
                break;

            // Reactivates the grade
            case 3:
                if(!$confirm)
                {
                    $output['type'] = 'reactivate';
                    $output["entry"]["message"] = lang("Grades.grade_enable_explanation");
                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->m_grade_model->withDeleted()->update($grade_id, ["archive" => null]);
                break;
        }

        return redirect()->to("plafor/apprentice/view_apprentice/".$apprentice["id"]);
    }
}