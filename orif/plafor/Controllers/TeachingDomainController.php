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


class TeachingDomainController extends \App\Controllers\BaseController{

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
        $this->m_user_course_model = model("UserCourseModel");
        $this->m_user_model = model("User_model");
        $this->m_trainer_apprentice_model = model("TrainerApprenticeModel");

    }


    
    /**
     * Return all teaching domains data to a view
     *
     * @param  bool $with_deleted   => false, witout the archived teaching domain (Default)
     *                              => true, with the archived teaching domain
     * 
     * @return string|Response
     */
    public function getAllTeachingDomain(bool $with_deleted = false) : string|Response {

        // Access permissions
        if (!isCurrentUserApprentice()){
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        // Title
        $data_to_view["list_title"] = lang("/* TODO title */");

        $data_to_view["column"] = [
            // TODO check if using fk ?
            "fk_teaching_domain_title"  => lang(""),
            "fk_course_plan"            => lang(""),
            "domaine_weight"            => lang(""),
            "is_eliminatory"            => lang("")
        ];

        // $data_to_view["items"] = $this->m_teaching_domain_model->/*TODO get all teaching domain*/

        if($with_deleted){
            $data_to_view["items"] = array_merge($data_to_view["items"], [
            // @TODO get all teaching domain with archive
            ]);
        }

        return $this->display_view("\Plafor/domain/view", $data_to_view);
    }


        
    /**
     * Add/Update a teaching domain
     *
     * @param int $domain_id    => ID of the teaching domain (default = 0)
     * 
     * @return string|Response
     */
    public function saveTeachingDomain(int $domain_id = 0) : string|Response {

        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        if(count($_POST) > 0) {
            $data_to_model = [
                "id" => $domain_id,
                //TODO
            ];

            // Insert or update grade in DB
            $this->m_teaching_domain_model->save($data_to_model);

            // Return to previous page if there is NO error
            if ($this->m_teaching_domain_model->errors()==null) {
                return redirect()->to("plafor/domain/view");
            }
        }

        // Return to the current view if domain_id is OVER 0, for update
        elseif ($domain_id > 0) {
            return $this->display_view("plafor/domain/save", $this->m_teaching_domain_model->find("id", $domain_id));
        }

        // Return to the current view if there is ANY error with the model
        // OR empty $_POST
        return $this->display_view("\Plafor/domain/save", $data_to_view);
    }

    

    /**
     * Return all teaching subjects data to a view
     *
     * @param  bool $with_deleted   => false, witout the archived domain (Default)
     *                              => true, show the archived domain
     * 
     * @return string|Response
     */
    public function getAllTeachingSubject(bool $with_deleted = false) : string|Response {

        // Access permissions
        if (!isCurrentUserApprentice()){
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        // Title
        $data_to_view["list_title"] = lang("/* TODO title */");

        $data_to_view["column"] = [
            // TODO check if using fk ?
            "fk_teaching_domain"    => lang(""),
            "name"                  => lang(""),
            "subject_weight"        => lang("")
        ];

        // $data_to_view["items"] = $this->m_teaching_subject_model->/*TODO get all teaching domain*/

        if($with_deleted){
            $data_to_view["items"] = array_merge($data_to_view["items"], [
            // @TODO get all teaching domain with archive
            ]);
        }

        return $this->display_view("\Plafor/subject/view", $data_to_view);
    }

    

    /**
     * Add/Update a teaching subject
     *
     * @param int $subject_id    => ID of the teaching subject (default = 0)
     * 
     * @return string|Response
     */
    public function saveTeachingSubject(int $subject_id = 0) : string|Response {

        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        if(count($_POST) > 0) {
            $data_to_model = [
                "id" => $subject_id,
                //TODO
            ];

            // Insert or update grade in DB
            $this->m_teaching_subject_model->save($data_to_model);

            // Return to previous page if there is NO error
            if ($this->m_teaching_subject_model->errors()==null) {
                return redirect()->to("plafor/subject/view");
            }
        }

        // Return to the current view if subject_id is OVER 0, for update
        elseif ($subject_id > 0) {
            return $this->display_view("plafor/subject/save", $this->m_teaching_subject_model->find("id", $subject_id));
        }

        // Return to the current view if there is ANY error with the model
        // OR empty $_POST
        return $this->display_view("\Plafor/subject/save", $data_to_view);
    }


    
    /**
     * Return all teaching module data to a view
     *
     * @param  bool $with_deleted   => false, witout the archived domain (Default)
     *                              => true, show the archived domain
     * 
     * @return string|Response
     */
    public function getAllTeachingModule(bool $with_deleted = false) : string|Response {

        // Access permissions
        if (!isCurrentUserApprentice()){
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        $data_to_view["list_title"] = lang("/* TODO title */");

        $data_to_view["column"] = [
            // TODO check if using fk + how to get it
            "fk_teaching_domain"    => lang(""),
            "module_number"         => lang(""),
            "official_name"         => lang(""),
            "version"               => lang("")
        ];

        // $data_to_view["items"] = $this->m_teaching_module_model->/*TODO get all teaching domain*/

        if($with_deleted){
            $data_to_view["items"] = array_merge($data_to_view["items"], [
            // @TODO get all teaching domain with archive
            ]);
        }

        return $this->display_view("\Plafor/module/view", $data_to_view);
    }

    

    /**
     * Add/Update a teaching module
     *
     * @param int $module_id    => ID of the teaching module (default = 0)
     * 
     * @return string|Response
     */
    public function saveTeachingModule(int $module_id = 0) : string|Response {

        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        if(count($_POST) > 0) {
            $data_to_model = [
                "id" => $module_id,
                //TODO
            ];

            // Insert or update grade in DB
            $this->m_teaching_module_model->save($data_to_model);

            // Return to previous page if there is NO error
            if ($this->m_teaching_module_model->errors()==null) {
                return redirect()->to("plafor/module/view");
            }
        }

        // Return to the current view if module_id is OVER 0, for update
        elseif ($module_id > 0) {
            return $this->display_view("plafor/module/save", $this->m_teaching_module_model->find("id", $module_id));
        }

        // Return to the current view if there is ANY error with the model
        // OR empty $_POST
        return $this->display_view("\Plafor/module/save", $data_to_view);
    }
}