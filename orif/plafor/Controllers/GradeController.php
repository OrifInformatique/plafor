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
         // '@' accessible for logged in users
        $this->access_level = "@";

        parent::initController($request, $response, $logger);

        $this->m_grade_model = model("GradeModel");
        $this->m_teaching_module_model = model("TeachingModuleModel");
        $this->m_teaching_subject_model = model("TeachingSubjectModel");
        $this->m_trainer_apprentice_model = model("TrainerApprenticeModel");
        $this->m_user_course_model = model("UserCourseModel");
        $this->m_user_model = model("User_model");

        helper("AccessPermissions_helper");
    }

    /**
     * Retrieves the list of subjects and modules for a usercourse. The array
     * is formatted for grade save view
     *
     * @param int $userCourseId ID of the user's course
     * @param string|null $selectedDomain Selected domain (tpi, cbe, ecg,
     * modules)
     * @return array List of subjects and modules
     */
    private function getsubjectAndModulesList(int $userCourseId,
        ?string $selectedDomain=null): array
    {
        helper('grade_helper');
        return getSubjectsAndModulesList($userCourseId, $selectedDomain);

    }

    private function getSubjectsOrModulesListByGradeId(int $gradeId): array
    {
        helper('grade_helper');
        return getSubjectsOrModulesListByGradeId($gradeId);
    }

    /**
     * Retrieves the apprentice's id and username for a course.
     *
     * @param int $userCourseId ID of the user's course
     * @return array Apprentice's information
     */
    private function getApprentice(int $userCourseId): array
    {
        helper('grade_helper');
        return getApprentice($userCourseId);
    }

    /**
     * Formats the grade data for the update view/form.
     *
     * @param int $gradeId ID of the grade
     * @return array Formatted grade data
     */
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

    /**
     * Retrieves the selected entry for a usercourse and a selected domain.
     * return subject id prefixed by s or module id prefixed by m
     *
     * @param int $userCourseId ID of the user's course
     * @param string|null $selectedDomain Selected domain (tpi, cbe, ecg,
     * modules)
     * @return string|null Selected entry
     */
    private function getSelectedEntry(int $userCourseId,
        ?string $selectedDomain = null): ?string
    {

        helper('grade_helper');
        return getSelectedEntry($userCourseId, $selectedDomain);
    }

    /**
     * Adds the current subject or the module to the list of subjects and
     * modules.
     *
     * @param int $gradeId ID of the grade
     * @param array $formatedList Formatted list of subjects and modules
     * @return array List of subjects and modules
     */
    private function addHimself(int $gradeId, array $formatedList): array
    {
        helper('grade_helper');
        return addHimself($gradeId, $formatedList);

    }

    /**
     * Show the view for the grade save page.
     *
     * @param int $userCourseId ID of the user's course
     * @param int $gradeId ID of the grade
     * @param string|null $selectedDomain Selected domain (tpi, cbe, ecg,
     * modules)
     * @return string View for the grade save page
     */
    private function saveGradeGet(int $userCourseId, int $gradeId,
        ?string $selectedDomain = null): string
    {
        $isUpdate = $gradeId !== 0;
        if ($isUpdate) {
            $data = $this->formatGradeForFormUpdate($gradeId);
            $subjectAndModulesList = $this
                ->getSubjectsOrModulesListByGradeId($gradeId);
        } else {
            $subjectAndModulesList = $this
                ->getsubjectAndModulesList($userCourseId, $selectedDomain);
        }
        $data['grade_id'] = $gradeId;
        $data['user_course_id'] = $userCourseId;
        $data["errors"] = $this->m_grade_model->errors();
        $data['apprentice'] = $this->getApprentice($userCourseId);
        $data['course_plan'] = $this->getCoursePlanName($userCourseId);

        $data['subject_and_domains_list'] = $this->addHimself($gradeId,
            $subjectAndModulesList);

        $data['title'] = lang(
            $isUpdate ? 'Grades.update_grade' : 'Grades.add_grade');

        if (!is_null($selectedDomain)) {
            // begin is not yet necessary
            $data['selected_entry'] = $this->getSelectedEntry($userCourseId,
                $selectedDomain);

            if (is_null($data['selected_entry'])) {
                unset($data['selected_entry']);
            }
            // end is not yet necessary
            $data['is_exam_made_in_school'] = $selectedDomain !== 'modules';
        }
        return $this->display_view("\Plafor/grade/save", $data);
    }

    /**
     * Saves the grade data and returns a redirect response or return the form
     * if error
     *
     * @param int $userCourseId ID of the user's course
     * @param int $gradeId ID of the grade
     * @return RedirectResponse|string Redirect response or view for the grade
     * save page
     */
    private function saveGradePost(int $userCourseId,
        int $gradeId): RedirectResponse | string
    {
        $post = $this->request->getPost();
        if ($gradeId !== 0) $grade['id'] = $gradeId;
        $grade['fk_user_course'] = $userCourseId;
        $grade['fk_teaching_subject'] = $post['subject'][0] === 's' ?
            intval(substr($post['subject'], 1)) : null;

        $grade['fk_teaching_module'] = $post['subject'][0] === 'm' ?
            intval(substr($post['subject'], 1)) : null;

        $grade['date'] = $post['exam_date'] ?? null;
        $grade['grade'] = $post['grade'];
        $grade['is_school'] = $post['is_exam_made_at_school'] ?? null;
        $grade['is_school'] = $grade['is_school'] === '1' ? 1 : 0;
        if ($this->m_grade_model->save($grade)) {
            $apprentice = $this->getApprentice($userCourseId);
            return redirect()->to(base_url(
                'plafor/apprentice/view_apprentice/'. $apprentice['id'] . '/' .
                $userCourseId));
        }
        $data = $post;
        $data['errors'] = $this->m_grade_model->errors();
        $data['grade_id'] ??= $gradeId;
        $data['apprentice'] = $this->getApprentice($userCourseId);
        $data['course_plan'] = $this->getCoursePlanName($userCourseId);
        $data['subject_and_domains_list'] = $this
            ->getsubjectAndModulesList($userCourseId);

        $data['selected_entry'] = $data['subject'];
        $data['is_exam_made_in_school'] = $grade['is_school'];
        return $this->display_view("\Plafor/grade/save", $data);
    }

    /**
     * Retrieves the course plan name for a user and a course.
     *
     * @param int $userCourseId ID of the user's course
     * @return string|null Course plan name
     */
    private function getCoursePlanName(int $userCourseId): ?string
    {
        helper('grade_helper');
        return getCoursePlanName($userCourseId);
    }

    /**
     * Checks if a grade is in a course.
     *
     * @param int $userCourseId ID of the user's course
     * @param int $gradeId ID of the grade
     * @return bool True if the grade is in the course, false otherwise
     */
    private function isGradeInCourse(int $userCourseId, int $gradeId): bool
    {
        helper('grade_helper');
        return isGradeInCourse($userCourseId, $gradeId);
    }

    /**
     * Inserts or modifies the grade of an apprentice.
     *
     * @param int $userCourseId ID of the user's course
     * @param int $gradeId ID of the grade (optional, default 0)
     * @param string|null $selectedDomain Selected domain (tpi, cbe, ecg,
     * modules) (optional)
     * @return string|RedirectResponse View for the grade save page or redirect
     * response
     */
    public function saveGrade(int $userCourseId, int $gradeId = 0, ?string
        $selectedDomain = null): string|RedirectResponse
    {
        $courseModel = $this->m_user_course_model;
        $apprenticeId = $courseModel->find($userCourseId)['fk_user'];
        $isTrainerOfUserOrIsHimself =
            (isCurrentUserTrainerOfApprentice($apprenticeId) ||
            isCurrentUserSelfApprentice($apprenticeId));

        $isAuthorised = $isTrainerOfUserOrIsHimself &&
            $this->isGradeInCourse($userCourseId, $gradeId);
        if (!$isAuthorised)
        {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }
        if ($this->request->is('post')) {
            return $this->saveGradePost($userCourseId, $gradeId);
        }
        if ($this->request->is('get')) {
            return $this->saveGradeGet($userCourseId, $gradeId,
                $selectedDomain);
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
     *      (optional)
     * @param int $grade_id ID of the grade.
     *
     * @param bool $confirm Defines whether the action has been confirmed.
     * (optional, default false) View for the action confirmation or redirect
     * response
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

        $user_course = $this->m_user_course_model
                            ->find($grade["fk_user_course"]);
        $apprentice = $this->m_user_model->find($user_course["fk_user"]);

        $user_course_id = $user_course['id'];
        $apprentice_id = $apprentice['id'];
        $is_trainer_of_user_or_is_himself =
            (isCurrentUserTrainerOfApprentice($apprentice_id) ||
            isCurrentUserSelfApprentice($apprentice_id));

        $is_authorised = $is_trainer_of_user_or_is_himself &&
            $this->isGradeInCourse($user_course_id, $grade_id);
        if (!$is_authorised) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

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
