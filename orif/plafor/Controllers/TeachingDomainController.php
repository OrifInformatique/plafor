<?php
/**
 * Controller who manage Domain, Modules and Subjects grades
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
     * // TODO: check saveTeachingSubject and do the same
     *
     * @param int $domain_id        => ID of the teaching domain (default = 0)
     * @param int $course_plan_id   => ID of the course plan (default = 0)
     * 
     * @return string|Response
     */
    public function saveTeachingDomain(int $domain_id = 0, int $course_plan_id = 0) : string|Response {

        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        // Redirect to the last URL if course plan ID doesn't exist
        if (empty($this->m_course_plan_model->find($course_plan_id))){
            return redirect()->to(previous_url()); // TODO: comment this line if DD (Dylan Dervey)
        }

        if(count($_POST) > 0) {
            $domain_name = $this->request->getPost("domain_name");
            $course_plan_id = $this->request->getPost("course_plan");
            $domain_weight = $this->request->getPost("domain_weight");
            $is_eliminatory = $this->request->getPost("is_domain_eliminatory");
            $course_plan_parent = $this->request->getPost("domain_parent_course_plan");

            $data_to_model = [
                "id"                        => $domain_id,
                "fk_teaching_domain_title"  => $domain_name,
                "fk_course_plan"            => $course_plan_id,
                "domain_weight"             => $domain_weight,
                "is_eliminatory"            => $is_eliminatory
            ];
            
            $this->m_teaching_domain_model->save($data_to_model);
            
            // Return to previous page if there is NO error
            if ($this->m_teaching_domain_model->errors() == null) {
                return redirect()->to("plafor/domain/view");
            }
        }


        $course_plan_parent = $this->m_course_plan_model->findAll();

        $data_to_view = [
            "title"                     => $domain_id == 0 ? lang('Grades.create_domain') : lang('Grades.update_domain'),
            "fk_teaching_domain_title"  => $domain_id,
            "fk_course_plan"            => $course_plan_id,
            "domain_weight"             => $domain_weight,
            "is_eliminatory"            => $is_eliminatory,
            "domain_parent_course_plan" => $course_plan_parent,
            "errors"                    => $this->m_teaching_domain_model->errors()
        ];

        // Return to the current view if subject_id is OVER 0, for update
        // OR return to the current view if there is ANY error with the model
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
     * @param int $subject_id   => ID of the teaching subject (default = 0)
     * @param int $domain_id    => ID of the teaching domain (default = 0)
     * 
     * @return string|Response
     */
    public function saveTeachingSubject(int $subject_id = 0, int $domain_id = 0) : string|Response {
        
        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }
        
        // Redirect to the last URL if domain ID doesn't exist
        if (empty($this->m_teaching_domain_model->find($domain_id))){
            return redirect()->to(previous_url()); // TODO: comment this line if DD (Dylan Dervey)
        }

        if(count($_POST) > 0) {

            $data_to_model = [
                "id"                    => $subject_id,
                "fk_teaching_domain"    => $this->request->getPost("subject_parent_domain"),
                "name"                  => $this->request->getPost("subject_name"),
                "subject_weight"        => $this->request->getPost("subject_weight"),
            ];
            
            $this->m_teaching_subject_model->save($data_to_model);
            
            // Return to previous page if there is NO error
            if ($this->m_teaching_subject_model->errors() == null) {

                $course_plan_id = $this->m_teaching_domain_model->find($domain_id)['fk_course_plan'];
                
                return redirect()->to("plafor/courseplan/view_course_plan/" . $course_plan_id);
            }
        }
        
        // Get a list with all Subject and Domain
        $data_from_model = $this->m_teaching_subject_model->find($subject_id);
        
        $data_to_view = [
            "title"                     => $subject_id == 0 ? lang('Grades.create_subject') : lang('Grades.update_subject'),
            "subject_id"                => $subject_id,
            "subject_parent_domain"     => $domain_id,
            "subject_name"              => $data_from_model["name"] ?? null,
            "subject_weight"            => $data_from_model["subject_weight"] ?? null,
            "domain_name"               => $this->m_teaching_domain_model->find($domain_id) ["title"], 
            "errors"                    => $this->m_teaching_subject_model->errors()
        ];
        
        // Return to the current view if subject_id is OVER 0, for update
        // OR return to the current view if there is ANY error with the model
        // OR empty $_POST
        return $this->display_view("\Plafor/subject/save", $data_to_view);
    }



    /**
     * Delete or reactivate a Teaching Subject
     *
     * @param int $subject_id   => ID of the subject
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
        if (!isCurrentUserTrainer()){
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        $teaching_module = [];
        // Get teaching modules of the domain
            foreach ($this->m_teaching_module_model->findAll() as $module){
            $teaching_modules [] = [
                "id"                    => $module["id"],
                "number_module"         => $module["module_number"],
                "name_module"           => $module["official_name"],
                "version_module"        => $module["version"],
            ];
        }
        
        $data_to_view["modules"] = $teaching_modules;

        if($with_deleted){
            $data_to_view["modules"] = array_merge($data_to_view["modules"], 
                $this->m_teaching_module_model->onlyDeleted()->findAll());
        }

        return $this->display_view("\Plafor/module/view", $data_to_view);
    }

    

    /**
     * Add/Update a teaching module
     * // TODO: check saveTeachingSubject and do the same
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