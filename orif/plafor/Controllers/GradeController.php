<?php

// TODO : Order the functions by element and action (CRUD) in this order : Show/Read, Create/Save/Update, Delete
/**
 * Controller who manage modules and subjects grades
 * Required level connected
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Controllers;

use CodeIgniter\Debug\Toolbar\Collectors\Views;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Plafor\Models\TeachingDomainModel;
use Plafor\Models\TeachingSubjectModel;
use Plafor\Models\TeachingModuleModel;
use Plafor\Models\GradeModel;
use User\Models\User_model;
use Plafor\Models\UserCourseModel;



// @TODO : Check what is used
use Plafor\Models\AcquisitionStatusModel;
use Plafor\Models\CommentModel;
use Plafor\Models\CompetenceDomainModel;
use Plafor\Models\CoursePlanModel;
use Plafor\Models\ObjectiveModel;
use Plafor\Models\OperationalCompetenceModel;
use Plafor\Models\TrainerApprenticeModel;
use Plafor\Models\UserCourseStatusModel;
use Psr\Log\LoggerInterface;
use User\Models\User_type_model;


class GradeController extends \App\Controllers\BaseController{

    // Class Constant
    const m_ERROR_MISSING_PERMISSIONS = "\User/errors/403error";

    /**
     * Method to initialize controller attributes
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request,
                                    \CodeIgniter\HTTP\ResponseInterface $response,
                                    \Psr\Log\LoggerInterface $logger) : void {

        $this->access_level = "@";
        parent::initController($request, $response, $logger);

        // Loads required models
        $this->m_grade_model = model("GradeModel");
        $this->m_user_course_model = model("UserCourseModel");
        $this->m_user_model = model("User_model");
        $this->m_trainer_apprentice_model = model("TrainerApprenticeModel");
        helper("AccessPermissions_helper");
    }



    /**
     * Calculate the average of all grades
     *
     * @param  array $array => an array of module or an array of subject
     *
     * @return int
     */
    private function calculateAverageGrade(array $array) : int {
        $nbr_grade = count($array);
        $all_grade = array_column($array, "grade");
        $total_grade = array_sum($all_grade);

        return $total_grade / $nbr_grade;
    }



    /**
     * Insert/Modify the grade of an apprentice
     *
     * @param int $apprentice_id    => ID of the apprentice (default 0)
     * @param int $grade_id         => ID of the grade (default 0)
     * 
     * @return string|Response
     */
    public function saveGrade(int $apprentice_id = 0, int $grade_id = 0) : string|Response {

        $user_course_id = $this->request->getPost("user_course_id");

        // Access permissions
        if (!isCurrentUserTrainerOfApprentice($apprentice_id)){
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }
        elseif (!isCurrentUserSelfApprentice($apprentice_id)){
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }        
        
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
            
            $data_to_model = [
                "id"                    => $grade_id,
                "fk_user_course"        => $user_course_id,
                "fk_teaching_subject"   => $subject_id,
                "fk_teaching_module"    => $module_id,
                "date"                  => $this->request->getPost("exam_date"),
                "grade"                 => $this->request->getPost("grade"),
                "is_school"             => $this->request->getPost("is_exam_made_in_school"),
            ];
            
            // Insert or update grade in DB
            $this->m_grade_model->save($data_to_model);
            
            if ($this->m_grade_model->errors() == null) {
                return redirect()->to("plafor/grade/showAllGrade");
            }
        }
        
        $data_from_model = $this->m_grade_model->withDeleted()->find($grade_id);
        // dd($data_from_model);

        $data_to_view = [
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
        
        // Return to previous page if there is NO error
        // OR Return to the current view if there is ANY error with the model
        // OR empty $_POST
        return $this->display_view("\Plafor/grade/save", $data_to_view);
    }



    /**
     * Delete or reactivate a grade
     * @param int $grade_id     => ID of the grade, Required
     * @param int $action       => 0 = display a confirmation (default)
     *                          => 1 = soft delete
     *                          => 3 = reactivate
     *
     * @return void
     */
    public function deleteGrade(int $grade_id, int $action = 0) : string|Response {

        // Access permissions
        if (!isCurrentUserTrainerOfApprentice($course_plan_id)){
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        $grade = $this->m_grade_model->withDeleted()->find($grade_id);

        // Error if grade don't exist
        if(is_null($grade)){
            return redirect()->to("plafor/grade/save");
        }

        // Get the subject
        if($grade["fk_teaching_subject"] > 0){
            // TODO subject name
        }
        // OR the module
        else {
            // TODO module name
        }


        // TODO get Apprentice name


        // Action to perform
        switch($action){
            case 0: // Display confirmation
                // TODO : Edit the common view call to match new specifications
                $output = [

                    "entry" => [
                        "type"  => lang("plafor_lang.name_grade"),
                        "name"  => "",
                        "data"  => [
                            [
                                "name" => "", // TODO get Apprentice name
                                "value" => $grade["grade"]
                            ]
                        ]
                    ],
                    "cancel_btn_url" => base_url("plafor/grade/save/".$grade_id)
                ];

                // Enable the grade
                if($grade["archive"]){
                    $output["entry"]["message"] = lang("plafor_lang.enable_explanation_grade");
                    $output["primary_action"] =
                    [
                        "name" => lang("common_lang.btn_reactivate"),
                        "url"  => base_url(uri_string()."/3")
                    ];
                }

                // Disable the grade
                else{
                    $output["entry"]["message"] = lang("plafor_lang.disable_explanation_grade");
                    $output["primary_action"] =
                    [
                        "name" => lang("common_lang.btn_disable"),
                        "url"  => base_url(uri_string()."/1")
                    ];
                }

                return $this->display_view("", $output);

            case 1: // Soft delete
                $this->m_grade_model->delete($grade_id);
                break;

            case 3: // Reactivate grade and is links
                $this->m_grade_model->withDeleted()->update($grade_id, ["archive" => null]);
                break;
        }

        return redirect()->to("plafor/grade/school_report");
    }


      

    /**
     * Return the average grade of all modules
     *
     * @param  int $course_plan_id  => ID of the apprentice
     * @param  ?int $is_school      => true, average grades done in school
     *                              => false, average grades done outside school
     *                              => null, average grades of all modules
     *
     * @return string|Response
     */
    // TODO : Delete this function ; unused
    public function showModuleAverageGrade(int $apprentice_id, ?int $is_school = null) : string|Response {

        // Access permissions
        if (!isCurrentUserSelfApprentice($course_plan_id)){
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        // Get the average of all modules
        $average_grade = $this->m_grade_model->getApprenticeModuleAverage($course_plan_id, $is_school);

        // Data do send to the view
        $data_to_view = [
            "title"     => lang("plafor_lang.title_average_module_grade"),
            "average"   => $average_grade,
        ];

        // Return the view
        return $this->display_view("/plafor/grade/module_grade/", $data_to_view);
    }



    /**
     * Return the average grade of 1 subject
     *
     * @param  int $course_plan_id  => ID of the apprentice
     * @param  int $subject_id      => ID of the subject
     *
     * @return string|Response
     */
    // TODO : Delete this function ; unused
    public function showSubjectAverageGrade(int $subject_id, int $apprentice_id) : string|Response {

        // Access permissions
        if (!isCurrentUserSelfApprentice($course_plan_id)){
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        // Get grade from one subject (for the name)
        $subject = $this->m_grade_model->getApprenticeSubjectGrades($course_plan_id, $subject_id);

        // Get the average of all grades of one subject
        $average_grade = $this->m_grade_model->getApprenticeSubjectAverage($course_plan_id, $subject_id);

        // Data do send to the view
        $data_to_view = [
            "title"     => lang("plafor_lang.title_average_subject_grade", $subject["name"]),
            "average"   => $average_grade,
        ];

        // Return the view
        return $this->display_view("/plafor/grade/subject_grades", $data_to_view);
    }
}
