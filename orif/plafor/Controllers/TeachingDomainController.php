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


class DomainController extends \App\Controllers\BaseController{

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

    
    public function showAllTeachingDomain(bool $with_deleted = false) : string|Response {

        // Access permissions
        if ($_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_level_apprentice){

            $data["items"] = [
                // @TODO
            ];

            if($with_deleted){
                $data["items"] = array_merge($data["items"], [
                // @TODO
                ]);
            }

            return $this->display_view("\Plafor/domain/view", $data);
        }

        // Missing permissions
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

    }


    public function saveTeachingDomain(
        /* string $domain_title,
        int $course_plan_id,
        float $domain_weight,
        bool $is_eliminatory,
        bool $archive */
        int $id_domain = 0
        ) : string|Response {

        // Access permissions
        if ($_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_level_apprentice){

            if(empty($_POST))
            {
                $data =
                [
                    'id' => $id_domain
                ];
                return $this->display_view('\Plafor/domain/save', $data);
            }

            return redirect()->to('plafor/grade/showAllTeachingDomain');
        }

        // Missing permissions
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

    }


    public function showAllTeachingSubject(bool $with_deleted = false) : string|Response {
        // Access permissions
        if ($_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_level_apprentice){

            $data["items"] = [
                // @TODO
            ];

            if($with_deleted){
                $data["items"] = array_merge($data["items"], [
                // @TODO
                ]);
            }

            return $this->display_view("\Plafor/subject/view", $data);
        }

        // Missing permissions
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

    }


    public function saveTeachingSubject(int $id_subject = 0) : string|Response {

        // Access permissions
        if ($_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_level_apprentice){

            if(empty($_POST))
            {
                $data =
                [
                    'id' => $id_subject
                ];
                return $this->display_view('\Plafor/subject/save', $data);
            }

            return redirect()->to('plafor/grade/showAllTeachingSubject');
        }

        // Missing permissions
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

    }


    public function showAllTeachingModule(bool $with_deleted = false) : string|Response {
        // Access permissions
        if ($_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_level_apprentice){

            $data["items"] = [
                // @TODO
            ];

            if($with_deleted){
                $data["items"] = array_merge($data["items"], [
                // @TODO
                ]);
            }

            return $this->display_view("\Plafor/module/view", $data);
        }

        // Missing permissions
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

    }


    public function saveTeachingModule(int $id_module = 0) : string|Response {

        // Access permissions
        if ($_SESSION["user_access"] >= config("\User\Config\UserConfig")->access_level_apprentice){

            if(empty($_POST))
            {
                $data =
                [
                    'id' => $id_module
                ];
                return $this->display_view('\Plafor/module/save', $data);
            }

            return redirect()->to('plafor/grade/showAllTeachingModule');
        }

        // Missing permissions
        return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

    }
}