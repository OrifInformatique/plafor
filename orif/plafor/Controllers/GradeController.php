<?php
/**
 * Controller who manage modules and subjects grades
 * Required level connected
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Controllers;

use App\Controllers\BaseController;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\RedirectResponse;
use Psr\Log\LoggerInterface;
use CodeIgniter\I18n\Time;

class GradeController extends BaseController
{
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
    public function initController(RequestInterface $request, ResponseInterface
        $response, LoggerInterface $logger): void
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

    private function getsubjectAndModulesList(int $userCourseId): array
    {
        helper('grade_helper');
        $list = getSubjectsAndModulesList($userCourseId);
        return $list;

    }

    private function getApprentice(int $userCourseId): array
    {
        helper('grade_helper');
        $apprentice = getApprentice($userCourseId);
        return $apprentice;
    }

    private function formatGradeForFormUpdate(int $gradeId): array
    {
        $grade = $this->m_grade_model->find($gradeId);
        if (isset($grade['fk_teaching_subject'])) {
            $data['selected_entry'] = 's' . $grade['fk_teaching_subject'];
        }
        if (isset($grade['fk_teaching_module'])) {
            $data['selected_entry'] = 'm' . $grade['fk_teaching_module'];
        }
        $data['is_exam_made_in_school'] = $grade['is_school'] === '1' ? true :
            false;
        $data['exam_date'] = $grade['date'];
        $data['grade'] = $grade['grade'];
        return $data;

    }

    private function saveGradeGet(int $userCourseId, int $gradeId): String
    {
        if ($gradeId !== 0) {
            $data = $this->formatGradeForFormUpdate($gradeId);
        }
        $data['grade_id'] = $gradeId;
        $data['user_course_id'] = $userCourseId;
        $data["errors"] = $this->m_grade_model->errors();
        $data['apprentice'] = $this->getApprentice($userCourseId);
        $data['course_plan'] = model('CoursePlanModel')
            ->getCoursePlanIdByUserCourse($userCourseId);
        $data['subject_and_domains_list'] = $this
            ->getsubjectAndModulesList($userCourseId);
        $data['title'] = lang(
            $gradeId == 0 ? 'Grades.add_grade' : 'Grades.update_grade');
        return $this->display_view("\Plafor/grade/save", $data);


        $data_to_view =
        [
            "title"                 => $title,
            "grade_id"              => $grade_id,
            // "user_course_id"        => $data_from_model["user_course_id"],
            // "subject" => $subject_id,
            // "module" => $module_id,
            // "date"  => $date,
            // "grade" => $grade,
            // "is_school" => $is_school,
            // "errors"  => $this->m_grade_model->errors()
        ];
    }

    private function saveGradePost(int $userCourseId,
        int $gradeId): RedirectResponse | string
    {
        $post = $this->request->getPost();
        
        if ($gradeId !== 0) {
            $grade['id'] = $gradeId;
        }
        $grade['fk_user_course'] = $userCourseId;
        $grade['fk_teaching_subject'] = $post['subject'][0] === 's' ?
            intval(substr($post['subject'], 1)) : null;

        $grade['fk_teaching_module'] = $post['subject'][0] === 'm' ?
            intval(substr($post['subject'], 1)) : null;

        $grade['date'] = $post['exam_date'] ?? null;
        $grade['grade'] = $post['grade'];
        $grade['is_school'] = $post['is_exam_made_at_school'] ?? null;
        $grade['is_school'] = $grade['is_school'] === '1' ? 1 : 0;
        

        // $user_course_id = $this->request->getPost("user_course_id");
        // $selected_entry = $this->request->getPost("selected_entry");
        // TODO: check if it's a subject or a module s or m (parse the first char of the string)

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


        if ($this->m_grade_model->save($grade)) {
            $apprentice = $this->getApprentice($userCourseId);
            return redirect()->to(base_url(
                'plafor/apprentice/view_apprentice/'. $apprentice['id'] . '/' .
                $userCourseId));
        }
        $data = $post;
        $data['errors'] = $this->m_grade_model->errors();
        $data['grade_id'] ??= 0;
        $data['apprentice'] = $this->getApprentice($userCourseId);
        $data['course_plan'] = model('CoursePlanModel')
            ->getCoursePlanIdByUserCourse($userCourseId);
        $data['subject_and_domains_list'] = $this
            ->getsubjectAndModulesList($userCourseId);
        $data['selected_entry'] = $data['subject'];
        $data['is_exam_made_in_school'] = $grade['is_school'];
        return $this->display_view("\Plafor/grade/save", $data);
        

    }

    /**
     * Inserts or modifies the grade of an apprentice.
     *
     * @param int $gradeId ID of the grade.
     *
     * @return string|RedirectResponse
     *
     */
    public function saveGrade(int $userCourseId,
        int $gradeId = 0): string|RedirectResponse
    {
        $UserCourseModel = $this->m_user_course_model;

        $apprenticeId = $UserCourseModel
            ->find($userCourseId)['fk_user'];

        if (!isCurrentUserTrainerOfApprentice($apprenticeId)
            && !isCurrentUserSelfApprentice($apprenticeId))
        {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }
        // TODO check if id grade is autorized
        // isCurrenUserSelfNote
        // or isCurrenUserTrainerOfApprentice

        if ($this->request->is('post')) {
            return $this->saveGradePost($userCourseId, $gradeId);
        }
        if ($this->request->is('get')) {
            return $this->saveGradeGet($userCourseId, $gradeId);
        }

        assert(false, 'GradeController saveGrade methods http unimplemented');
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
    public function deleteGrade(?int $action = null, int $grade_id = 0,
        bool $confirm = false): string|RedirectResponse
    {
        $grade = $this->m_grade_model->withDeleted()->find($grade_id);

        if(is_null($grade) || !isset($action))
            return redirect()->to("plafor/grade/save");

        $user_course = $this->m_user_course_model->find($grade["fk_user_course"]);
        $apprentice = $this->m_user_model->find($user_course["fk_user"]);

        if (!isCurrentUserTrainerOfApprentice($apprentice["id"]))
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        if (isset($grade["fk_teaching_subject"]))
            $subject = $this->m_teaching_subject_model->find($grade['fk_teaching_subject']);

        elseif (isset($grade["fk_teaching_module"]))
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
                    "type"  => lang("Grades.grade"),
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
    
                "cancel_btn_url" => url_to('updateGrade', $user_course['id'], $grade_id)
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
