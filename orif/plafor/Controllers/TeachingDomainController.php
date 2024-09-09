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
use Plafor\Models\CoursePlanModel;

use User\Config\UserConfig; // Test
use Config\UserConfig as ConfigUserConfig; // Test


// @TODO : Check what is used
use Plafor\Models\AcquisitionStatusModel;
use Plafor\Models\CommentModel;
use Plafor\Models\CompetenceDomainModel;
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
        $this->m_course_plan_model = model("CoursePlanModel");
        helper("AccessPermissions_helper");
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

        // Data form
        $domain_name = $this->request->getPost("domain_name"); //TODO check if it's right
        $course_plan_id = $this->request->getPost("course_plan"); //TODO check if it's right
        $domain_weight = $this->request->getPost("domain_weight");
        $is_eliminatory = $this->request->getPost("is_eliminatory");
        $course_plan_parent = $this->request->getPost("domain_parent_course_plan");

        // Post
        if(count($_POST) > 0) {
            $data_to_model = [
                "id"                        => $domain_id,
                "fk_teaching_domain_title"  => $domain_name,
                "fk_course_plan"            => $course_plan_id,
                "domain_weight"             => $domain_weight,
                "is_eliminatory"            => $is_eliminatory
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
            return $this->display_view("\Plafor/domain/save", $this->m_teaching_domain_model->find("id", $domain_id));
        }

        // TODO What is needed (ID, name) ??
        $course_plan_parent = $this->m_course_plan_model->findAll();

        $data_to_view = [
            "fk_teaching_domain_title"  => $domain_id,
            "fk_course_plan"            => $course_plan_id,
            "domain_weight"             => $domain_weight,
            "is_eliminatory"            => $is_eliminatory,
            "domain_parent_course_plan" => $course_plan_parent, // todo check how to implement
            "errors"                    => $this->m_teaching_domain_model->errors()
        ];

        // Return to the current view if there is ANY error with the model
        // OR empty $_POST
        return $this->display_view("\Plafor/domain/save", $data_to_view);
    }


        
    /**
     * Delete or reactivate a Teaching Domain
     *
     * @param int $domain_id    => ID of the domain
     * @param int $action       => 0 = display a confirmation (default)
     *                          => 1 = soft delete
     *                          => 3 = reactivate
     * 
     * @return string|Response
     */
    public function deleteTeachingDomain(int $domain_id, int $action = 0) : string|Response {
        // todo Delete Teaching Domain
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
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
        
        // Data form
        $teaching_domain = $this->request->getPost("teaching_domain");
        $name = $this->request->getPost("name");
        $subject_weight = $this->request->getPost("subject_weight");

        
        if(count($_POST) > 0) {
            $data_to_model = [
                "id"                    => $subject_id,
                "fk_teaching_domain"    => $teaching_domain,
                "name"                  => $name,
                "subject_weight"        => $subject_weight,
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
            return $this->display_view("\Plafor/subject/save", $this->m_teaching_subject_model->find("id", $subject_id));
        }
        
        // Return to the current view if there is ANY error with the model
        // OR empty $_POST
        $data_to_view = [
            "teaching_domain"   => $teaching_domain,
            "name"              => $name,
            "subject_weight"    => $subject_weight,
            "errors"            => $this->m_teaching_subject_model->errors()
        ];

        return $this->display_view("\Plafor/subject/save", $data_to_view);
    }



    /**
     * Delete or reactivate a Teaching Subject
     *
     * @param int $subject_id    => ID of the subject
     * @param int $action       => 0 = display a confirmation (default)
     *                          => 1 = soft delete
     *                          => 3 = reactivate
     * 
     * @return string|Response
     */
    public function deleteTeachingSubject(int $subject_id, int $action = 0) : string|Response {
        // todo Delete Teaching Subject
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
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

        $data_to_view["items"] = $this->m_teaching_module_model->findAll();

        if($with_deleted){
            $data_to_view["items"] = array_merge($data_to_view["items"], 
                $this->m_teaching_module_model->onlyDeleted()->findAll());
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

        // Get data form
        $teaching_domain = $this->request->getPost("teaching_domain");
        $module_number = $this->request->getPost("module_number");
        $official_name = $this->request->getPost("official_name");
        $version = $this->request->getPost("version");

        if(count($_POST) > 0) {
            $data_to_model = [
                "id"                    => $module_id,
                "module_number"         => $module_number,
                "official_name"         => $official_name,
                "version"               => $version,
            ];
            
            
            // todo find PK for teaching_domain_module with the 2 FK
            $data_link =[
                "id"                    => $link_id,
                "fk_teaching_domain"    => $teaching_domain,
                "fk_teaching_module"    => $module_id
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
            return $this->display_view("\Plafor/module/save", $this->m_teaching_module_model->find("id", $module_id));
        }

        // Return to the current view if there is ANY error with the model
        // OR empty $_POST
        $data_to_view = [
            "teaching_domain"   => $teaching_domain,
            "module_number"     => $module_number,
            "official_name"     => $official_name,
            "version"           => $version,
            "errors"            => $this->m_teaching_module_model->errors()
        ];

        return $this->display_view("\Plafor/module/save", $data_to_view);
    }



    /**
     * Delete or reactivate a Teaching Module
     *
     * @param int $module_id    => ID of the module
     * @param int $action       => 0 = display a confirmation (default)
     *                          => 1 = soft delete
     *                          => 3 = reactivate
     * 
     * @return string|Response
     */
    public function deleteTeachingModule(int $module_id, int $action = 0) : string|Response {
        // todo Delete Teaching Module
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
    }
}