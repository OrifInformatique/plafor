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
use CodeIgniter\HTTP\ResponseInterface;
use Plafor\Models\TeachingDomainModel;
use Plafor\Models\TeachingSubjectModel;
use Plafor\Models\TeachingModuleModel;
use Plafor\Models\GradeModel;

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
use Plafor\Models\UserCourseModel;
use Plafor\Models\UserCourseStatusModel;
use Psr\Log\LoggerInterface;
use User\Models\User_model;
use User\Models\User_type_model;


class GradeController extends \App\Controllers\BaseController{

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
    }

        // // Access permissions
        // if ($_SESSION["user_access"] == config("\User\Config\UserConfig")->access_level_apprentice
        //     && $apprentice["id"] != $_SESSION["user_id"]) {
        //     return redirect()->to(base_url("plafor/apprentice/list_apprentice"));
        // }
    
        
    /**
     * Insert or modify the grade of an apprentice
     *
     * @param  int $grade_id        => ID of the grade to modify it
     *                              => Empty to insert a new grade
     * @param  int $apprentice_id   => ID of the apprentice
     * @param  int $subject_id      => ID of the subject
     *                              => Empty if it's a module
     * @param  int $module_id       => ID of the module
     *                              => Empty if it's a subject
     * @param  float $grade         => Grade between 0.0 and 6.0 (1 decimal)
     * @param  bool $is_school      => True if grade is done at school
     *                              => False if grade is NOT done at school 
     * @return string
     */
    public function SaveGrade(
        int $grade_id = 0, 
        int $apprentice_id, 
        int $subject_id = 0,
        int $module_id = 0,
        float $grade,
        bool $is_school = true
        ) : string {

        // Gets data related to the grade (for update)
        // @TODO

        // Access permissions
        // @TODO
        
        // Error if subject AND module have an ID over 0 or don't exist
        // @TODO

        // Error if grade is not in range
        // @TODO

        // Actions upon form submission
        if (count($_POST) > 0){
            $new_grade = [
                "fk_user_course"        => $apprentice_id,
                "fk_teaching_subject"   => $subject_id,
                "fk_teaching_module"    => $module_id,
                "date"                  => date("Y-m-d H:i:s"),
                "grade"                 => $grade,
                "is_school"             => $is_school,
            ];
        }

        // Insert grade in DB
        // @TODO

        // Update grade in DB
        // @TODO

        // Return to the previous view
        // @TODO
        return $this->display_view("");
    }

    
    /**
     * Delete(soft) a grade in the DB
     *
     * @param  int $grade_id    => ID of the grade to delete
     * @return string
     */
    public function DeleteGrade(int $grade_id) : string {

        // Access permissions
        // if ($_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_lvl_trainer){
        // if ($_SESSION["user_access"] >= config(UserConfig::class)->access_lvl_trainer){ // Test if this work
        if ($_SESSION["user_access"] >= config(ConfigUserConfig::class)->access_lvl_trainer){ // Test if this work
            
            // Gets data related to the grade
            $grade = $this->m_grade_model->find($grade_id);

            // Check if the grade exist in DB
            if (!is_null($grade)){
                $this->m_grade_model->delete($grade_id);

                // Return to the previous view
                return redirect()->to(base_url("/* @TODO */"));
            }

            // Missing grade
            return redirect()->to(base_url("/* @TODO */"));
        }

        // Missing permissions
        return $this->display_view("\User\errors\403error");
    }


    public function ShowGrade(int $grade_id) : string  {

    }


    public function ShowModuleAverageGrade(int $apprentice_id) : string {

    }


    public function ShowSubjectAverageGrade(int $subject_id, int $apprentice_id) : string {

    }


    public function ShowAllTeachingDomain() : string {

    }
    

    public function SaveTeachingDomain() : string {

    }









}


















