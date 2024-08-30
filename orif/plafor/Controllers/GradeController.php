<?php
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

use User\Config\UserConfig; // Test
use Config\UserConfig as ConfigUserConfig; // Test


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
        $this->m_teaching_domain_model = model("TeachingDomainModel");
        $this->m_teaching_subject_model = model("TeachingSubjectModel");
        $this->m_teaching_module_model = model("TeachingModuleModel");
        $this->m_grade_model = model("GradeModel");
        $this->m_user_course_model = model("UserCourseModel");
        $this->m_user_model = model("User_model");
        $this->m_trainer_apprentice_model = model("TrainerApprenticeModel");

    }



    /**
     * Helper function for Access permissions
     * Check if the user is a Trainer or higher
     * Check if the user is an Apprentice and is not in is personnal page
     * 
     * @return bool
     */
    private function isSelfApprentice($apprentice_id) : bool {

        // if ($this->isTrainer()){
        //     return true;
        // }

        // if ($_SESSION["user_access"] == config("\User\Config\UserConfig")->access_level_apprentice){
           
            // $user_course = $this->m_user_course_model->find($apprentice_id);
            // $apprentice = $this->m_user_model->find($user_course["fk_user"]);
            
        //     // if ($apprentice["id"] == $_SESSION["user_id"]) {
        //     if ($apprentice_id == $_SESSION["user_id"]) {
        //         return true;
        //     }
        // }
        // return false;

        return $this->isTrainer() 
            || ($_SESSION["user_access"] == config("\User\Config\UserConfig")->access_level_apprentice 
            && $apprentice_id == $_SESSION["user_id"]);
    }


    
    /**
     * Helper function for Access permissions
     * Check if the user is a Apprentice or higher
     *
     * @return bool
     */
    private function isApprentice() : bool {
        return $_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_level_apprentice;
    }
    


    /**
     * Helper function for Access permissions
     * Check if the user is a Trainer or higher
     *
     * @return bool
     */
    private function isTrainer() : bool {
        // return $_SESSION["user_access"] < config("\User\Config\UserConfig")->access_lvl_trainer
        // return $_SESSION["user_access"] < config(UserConfig::class)->access_lvl_trainer; // Test if this work
        return $_SESSION["user_access"] >= config(ConfigUserConfig::class)->access_lvl_trainer; // Test if this work        }
    }
    


    /**
     * Helper function for Access permissions
     * Check if the user is an Admin
     *
     * @return bool
     */
    private function isAdmin() : bool {
        return $_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_lvl_admin;
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
     * Helper function who check if the date is in format: Y-m-d H:i:s
     *  for save in the DB
     *
     * @param  string $date
     * 
     * @return bool
     */
    private function isDateValidToSave(string $date) : bool {
        $format = "Y-m-d H:i:s";
        $dateTime = \DateTime::createFromFormat($format, $date);

        return $dateTime && $dateTime->format($format) === $date;
    }
    
        

    /**
     * Insert/Modify the grade of an apprentice
     * 
     * @param  int $apprentice_id   => ID of the apprentice, default 0
     * @param  int $trainer_id      => ID of the trainer, default 0
     * @param  ?int $grade_id       => ID of the grade, can be null
     * 
     * @return string|Response
     */    
    public function saveGrade(
        int $apprentice_id = 0, 
        int $trainer_id = 0, 
        ?int $grade_id = null
        ) 
        : string|Response 
        {

        // Access permissions
        if (!$this->isSelfApprentice($apprentice_id)){
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        } 
        if (!$this->m_trainer_apprentice_model->isTrainerLinkedToApprentice($apprentice_id, $trainer_id)){
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        // Gets data related to the grade (for update)
        if (!is_null($grade_id)){
            $old_grade = $this->m_grade_model->find("id", $grade_id);
            
            return $this->display_view("/* @TODO */", $old_grade);
        }

        // Error if subject AND module have an id over 0
        $subject_id = $this->request->getPost("subject");
        $module_id = $this->request->getPost("module");
        if ($subject_id > 0 && $module_id > 0){
            return redirect()->to(base_url("/* @TODO */"));
        }

        // Error if subject don't exist in DB
        if ($subject_id > 0){
            if (!$this->m_teaching_subject_model->find($subject_id)){
                return redirect()->to(base_url("/* @TODO */"));
            }
        }
        // Error if module don't exist in DB
        else{
            if (!$this->m_teaching_module_model->find($module_id)){
                return redirect()->to(base_url("/* @TODO */"));
            }
        }

        // Error if grade is not in range
        $grade = $this->request->getPost("grade");
        if ($grade < 0 || $grade > 6) {
            return redirect()->to(base_url("/* @TODO */"));
        }

        // Error if date is in wrong format
        $date = $this->request->getPost("date");
        if (!$this->isDateValidToSave($date)){
            return redirect()->to(base_url("/* @TODO */"));
        }

        // Actions upon form submission
        if (count($_POST) > 0){
            $new_grade = [
                "id"                    => $grade_id,
                "fk_user_course"        => $apprentice_id,
                "fk_teaching_subject"   => $subject_id,
                "fk_teaching_module"    => $module_id,
                "date"                  => $date,
                "grade"                 => $grade,
                "is_school"             => $this->request->getPost("is_school"),
            ];
        }

        // Insert or update grade in DB
        $this->m_grade_model->save($new_grade);

        // Return to the previous view
        return $this->display_view("/* @TODO */");
    }

    
    
    /**
     * Delete
     *
     * @return void
     */
    public function deleteGrade(
        int $apprentice_id, 
        int $trainer_id, 
        int $grade_id, 
        int $action = 0
        ) 
        : string|Response 
        {
        
        // Access permissions
        if (!$this->m_trainer_apprentice_model->isTrainerLinkedToApprentice($apprentice_id, $trainer_id)){
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        $grade = $this->m_grade_model->withDeleted()->find($grade_id);

        // Error if grade don't exist
        if(is_null($grade)){
            return redirect()->to(base_url("/* @TODO */"));
        }
        
        // Get the subject 
        if($grade["fk_teaching_subject"] > 0){

        }
        // OR the module
        else {

        }
        
        // Action to perform
        switch($action){
            case 0: // Display confirmation
                $output = [

                    "entry" => [
                        "type"  => lang("plafor_lang.name_grade"),
                        "name"  => "",
                        "data"  => [
                            [
                                "name" => "N/A",
                                "value" => $grade["grade"]
                            ]
                        ]
                    ],

                    "cancel_btn_url" => base_url("/* @TODO */".$grade_id)
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

                return $this->display_view("/* @TODO */", $output);
            
            case 1: // Soft delete
                $this->m_grade_model->delete($grade_id);
                break;
            
            case 3: // Reactivate grade and is links
                $this->m_grade_model->withDeleted()->update($grade_id, ["archive" => null]);
                break;
        }

        return redirect()->to(base_url("/* @TODO */"));
    }



    public function showAllGrade() : string|Response  {
        // @TODO
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
    }

    

    /**
     * Show the average grade of all modules
     *
     * @param  int $apprentice_id   => ID of the apprentice
     * @param  ?int $is_school      => true, average grades done in school
     *                              => false, average grades done outside school
     *                              => null, average grades of all modules
     * 
     * @return string|Response
     */
    public function showModuleAverageGrade(int $apprentice_id, ?int $is_school = null) : string|Response {
        
        // Access permissions
        if (!$this->isSelfApprentice($apprentice_id)){
            return $this->display_view();
        } 

        

        // Get the average of all modules
        $average_grade = $this->m_grade_model->getApprenticeModuleAverage($apprentice_id, $is_school);
        
        // Data do send to the view
        $data = [
            "title"     => lang("plafor_lang.title_average_module_grade"),
            "average"   => $average_grade,
        ];

        // Return the view
        return $this->display_view("/* @TODO */", $data);
    }

    

    /**
     * Show the average grade of 1 subject
     *
     * @param  int $subject_id
     * @param  int $apprentice_id
     * @return string|Response
     */
    public function showSubjectAverageGrade(int $subject_id, int $apprentice_id) : string|Response {
        
        // Access permissions
        if (!$this->isSelfApprentice($apprentice_id)){
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        } 

        // Get grade from one subject
        $all_subject = $this->m_grade_model->"/* getAllGradeFromSubject($subject_id) */";
        // Error if empty array
        if (is_null($all_subject)){
            return $this->display_view("/* @TODO */");
        }

        // Get the name of the selected subject
        $subject_name = $this->m_grade_model->find();

        // Calculate the average of all grades of one subject
        $average_grade = calculateAverageGrade($all_subject);

        // Data do send to the view
        $data = [
            "title"     => lang("plafor_lang.title_average_subject_grade", $subject_name),
            "average"   => $average_grade,
        ];

        // Return the view
        return $this->display_view("/* @TODO */", $data);
    }



    public function showAllTeachingDomain(bool $with_deleted = false) : string|Response {

        // Access permissions
        if ($_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_level_apprentice){

            $data["list_title"] = lang("plafor_lang.list_title_domain"); 

            $data["columns"] = ["title"             => lang("plafor_lang.name_domain"),
                                "course_plan"       => lang("plafor_lang.parent_course_plan_domain"),
                                "domain_weight"     => lang("plafor_lang.weight_domain"),
                                "is_eliminatory"    => lang("plafor_lang.eliminatory_domain"),
            ]; 

            $data["items"] = [
                // @TODO
            ];

            if($with_deleted){
                $data["items"] = array_merge($data["items"], [
                // @TODO
                ]);
            }

            return $this->display_view("Common\items_list", $data);
        }

        // Missing permissions
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
    }
    

    public function saveTeachingDomain(
        string $domain_title,
        int $course_plan_id,
        float $domain_weight,
        bool $is_eliminatory,
        bool $archive
        ) : string {

        // Access permissions
        if ($_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_level_apprentice){

        }

        // Missing permissions
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
    }


    public function showAllTeachingSubject(bool $with_deleted = false) : string|Response {
        // Access permissions
        if ($_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_level_apprentice){

            $data["list_title"] = lang("plafor_lang.list_title_subject"); 

            $data["columns"] = ["name"              => lang("plafor_lang.name_subject"),
                                "title"             => lang("plafor_lang.parent_domain_subject"),
                                "subject_weight"    => lang("plafor_lang.weight_subject"),
            ]; 

            $data["items"] = [
                // @TODO
            ];

            if($with_deleted){
                $data["items"] = array_merge($data["items"], [
                // @TODO
                ]);
            }

            return $this->display_view("Common\items_list", $data);
        }

        // Missing permissions
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
    }
    

    public function saveTeachingSubject() : string|Response {
        
        // Access permissions
        if ($_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_level_apprentice){

        }

        // Missing permissions
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
    }


    public function showAllTeachingModule(bool $with_deleted = false) : string|Response {
        // Access permissions
        if ($_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_level_apprentice){

            $data["list_title"] = lang("plafor_lang.list_title_module"); 

            $data["columns"] = ["module_number"     => lang("plafor_lang.number_module"),
                                "official_name"     => lang("plafor_lang.name_module"),
                                "version"           => lang("plafor_lang.version_module"),
                                "title"             => lang("plafor_lang.parent_domain_module"),
            ]; 

            $data["items"] = [
                // @TODO
            ];

            if($with_deleted){
                $data["items"] = array_merge($data["items"], [
                // @TODO
                ]);
            }

            return $this->display_view("Common\items_list", $data);
        }

        // Missing permissions
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
    }
    

    public function saveTeachingModule() : string|Response {
        
        // Access permissions
        if ($_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_level_apprentice){

        }

        // Missing permissions
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
    }



}


















