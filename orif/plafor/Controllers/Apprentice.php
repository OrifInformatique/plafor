<?php
/**
 * Controller pour la gestion des apprentis
 * Required level apprentice
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Controllers;

use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use CodeIgniter\Validation\Validation;
use Exception;
use Plafor\Models\AcquisitionLevelModel;
use Plafor\Models\AcquisitionStatusModel;
use Plafor\Models\CommentModel;
use Plafor\Models\CompetenceDomainModel;
use Plafor\Models\CoursePlanModel;
use Plafor\Models\ObjectiveModel;
use Plafor\Models\OperationalCompetenceModel;
use Plafor\Models\UserCourseModel;
use Plafor\Models\UserCourseStatusModel;
use Plafor\Models\TrainerApprenticeModel;

use User\Models\User_type_model;
use User\Models\User_model;

class Apprentice extends \App\Controllers\BaseController
{
    private Validation $validation;

    /**
     * Method to initialize controller attributes
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger) {
        $this->access_level = "*";
        parent::initController($request, $response, $logger);
        $this->validation = Services::validation();

        // Loads required models
        $this->acquisition_lvl_model = model('AcquisitionLevelModel');
        $this->acquisition_status_model = model('AcquisitionStatusModel');
        $this->comment_model = model('CommentModel');
        $this->comp_domain_model = model('CompetenceDomainModel');
        $this->course_plan_model = model('CoursePlanModel');
        $this->objective_model = model('ObjectiveModel');
        $this->operational_comp_model = model('OperationalCompetenceModel');
        $this->user_course_model = model('UserCourseModel');
        $this->user_course_status_model = model('UserCourseStatusModel');
        $this->trainer_apprentice_model = model('TrainerApprenticeModel');
        $this->user_type_model = model('User_type_model');
        $this->user_model = model('User_model');
    }

    /**
     * Default method to redirect to a homepage depending on the type of user
     * 
     * @return void
     */
    public function index() {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            // Session is set, redirect depending on the type of user
            if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
                // User is administrator
                return redirect()->to(base_url('user/admin/list_user'));
            } elseif ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_trainer) {
                // User is trainer
                return redirect()->to(base_url('plafor/apprentice/list_apprentice?trainer_id='.$_SESSION['user_id']));
            } else {
                // User is apprentice
                return redirect()->to(base_url('plafor/apprentice/view_apprentice/'.$_SESSION['user_id']));
            }
        } else {
            // No session is set, redirect to login page
            return redirect()->to(base_url('user/auth/login'));
        }
    }

    /**
     * Displays the list of apprentices
     * 
     * @param $withDeleted : Whether or not to show deleted apprentices
     * @return void
     */
    public function list_apprentice($withDeleted = 0) {
        // Gets trainer information if they are connected
        $trainer_id = $this->request->getGet('trainer_id');

        if ($trainer_id == null && $this->session->get('user_access')==config('\User\Config\UserConfig')->access_lvl_trainer) {
            $trainer_id = $this->session->get('user_id');
        }

        // Gets username of all trainers for the dropdown menu
        $trainersList = array();
        $trainersList[0] = lang('common_lang.all_m');
        $apprentice_level = $this->user_type_model->where('access_level', config("\User\Config\UserConfig")->access_level_apprentice)->find();

        foreach ($this->user_model->getTrainers() as $trainer) {
            $trainersList[$trainer['id']] = $trainer['username'];
        }

        // Gets data of apprentices, depending on the logged-in user
        if ($trainer_id == null || $trainer_id == 0) {
            // User is not a trainer - lists all apprentices
            $apprentices = $this->user_model->getApprentices($withDeleted);

            $coursesList=[];
            foreach ($this->course_plan_model->withDeleted(true)->findAll() as $courseplan)
                $coursesList[$courseplan['id']]=$courseplan;
            $courses = $this->user_course_model->withDeleted(true)->findAll();
        } else {
            // User is a trainer - lists their linked apprentices
            $apprentices=[];
            if (count($this->trainer_apprentice_model->where('fk_trainer', $trainer_id)->findAll()))
                $apprentices = $this->user_model->whereIn('id', array_column($this->trainer_apprentice_model->where('fk_trainer', $trainer_id)->findAll(), 'fk_apprentice'))->findAll();
            $coursesList=[];
            foreach ($this->course_plan_model->withDeleted(true)->findAll() as $courseplan)
                $coursesList[$courseplan['id']]=$courseplan;
            $courses = $this->user_course_model->withDeleted(true)->findAll();
        }

        // Data to send to the view
        $output = array(
            'title'         => lang('plafor_lang.title_list_apprentice'),
            'trainer_id'    => $trainer_id,
            'trainers'      => $trainersList,
            'apprentices'   => $apprentices,
            'coursesList'   => $coursesList,
            'courses'       => $courses,
            'with_archived' => $withDeleted
        );

        return $this->display_view('\Plafor\apprentice\list', $output);
    }

    /**
     * Displays the view for a given apprentice
     * 
     * @param int $apprentice_id : ID of the apprentice
     * @return void
     */
    public function view_apprentice($apprentice_id = 0) {
        // Gets data of the user if it exists and is an apprentice
        $user_type_id = $this->user_type_model->
            where('access_level', config('\User\Config\UserConfig')->access_level_apprentice)->first()['id'];
        $apprentice = $this->user_model->where('fk_user_type', $user_type_id)->withDeleted()->find($apprentice_id);

        // Redirection
        if(is_null($apprentice)) {
            return redirect()->to(base_url("/plafor/apprentice/list_apprentice"));
        }

        // Preparing data for the view
        // User's courses
        $user_courses = [];
        foreach ($this->user_course_model->where('fk_user',$apprentice_id)->findAll() as $usercourse) {
            $date_begin = Time::createFromFormat('Y-m-d', $usercourse['date_begin']);
            $date_end = Time::createFromFormat('Y-m-d', $usercourse['date_end']);
            $usercourse['date_begin'] = $date_begin->toLocalizedString('dd.MM.Y');
            $usercourse['date_end']!=='0000-00-00'? $usercourse['date_end'] = $date_end->toLocalizedString('dd.MM.Y'):null;
            $user_courses[$usercourse['id']] = $usercourse;
        }
        // Status of the user's courses
        $user_course_status = [];
        foreach ($this->user_course_status_model->withDeleted(true)->findAll() as $usercoursetatus)
        $user_course_status[$usercoursetatus['id']] = $usercoursetatus;
        // Course plans
        $course_plans = [];
        foreach ($this->course_plan_model->withDeleted(true)->findAll() as $courseplan)
        $course_plans[$courseplan['id']] = $courseplan;
        // Trainers
        $trainers = [];
        foreach ($this->user_model->where('fk_user_type',$this->user_type_model->where('name',lang('plafor_lang.title_trainer'))->first()['id'])->withDeleted(true)->findAll() as $trainer)
            $trainers[$trainer['id']]= $trainer;
        // Apprentice-trainer links
        $links = [];
        foreach ($this->trainer_apprentice_model->where('fk_apprentice',$apprentice_id)->findAll() as $link)
            $links[$link['id']]=$link;

        // Data to send to the view
        $output = array(
            'title'                 => lang('plafor_lang.title_view_apprentice'),
            'apprentice'            => $apprentice,
            'trainers'              => $trainers,
            'links'                 => $links,
            'user_courses'          => $user_courses,
            'user_course_status'    => $user_course_status,
            'course_plans'          => $course_plans
        );
        return $this->display_view('Plafor\apprentice/view',$output);
    }


    /**
     * Display the list of user courses linked to one given user
     *
     * @param int $id_apprentice ID of the concerned apprentice
     * 
     * @return void
     * 
     */
    public function list_user_courses($id_apprentice = null) 
    {
        if(is_numeric($id_apprentice))
            $user = $this->user_model->where('id', $id_apprentice)->first();

        if(!isset($user) || empty($user))
            return redirect()->to('plafor/apprentice/list_apprentice');

        $user_courses = $this->user_course_model->where('fk_user', $id_apprentice)->findAll();

        // Get the course_plan informations for each user_course
        foreach($user_courses as &$user_course)
        {
            $user_course['course_plan'] = $this->course_plan_model->withDeleted()->find($user_course['fk_course_plan']);
            $user_course['status']      = $this->user_course_status_model->getUserCourseStatusName($user_course['fk_status']);

            if($user_course['date_end'] === '0000-00-00')
                $user_course['date_end'] = null;
        }

        $output = array(
            'title'         => sprintf(lang('plafor_lang.title_user_course_plan_list'), $user['username']),
            'user_courses'  => $user_courses,
            'id_apprentice' => $id_apprentice
        );

        return $this->display_view(['Plafor\user_course\list'], $output);
    }


    /**
     * Displays a form to create a link between an apprentice and a course plan
     *
     * @param int $id_apprentice   ID of the apprentice
     * @param int $id_user_course  ID of the user's course
     * 
     * @return void
     * 
     */
    public function save_user_course($id_apprentice = 0, $id_user_course = 0)
    {
        // Access permissions
        if($this->session->get('user_access')>=config('\User\Config\UserConfig')->access_lvl_trainer) 
        {
            $user_type_id = $this->user_type_model
                ->where('access_level', config('\User\Config\UserConfig')->access_level_apprentice)
                ->first()['id'];

            $apprentice = $this->user_model
                ->where('fk_user_type', $user_type_id)
                ->find($id_apprentice);

            $user_course = $this->user_course_model->find($id_user_course);

            if(is_null($apprentice))
                return redirect()->to(base_url('plafor/apprentice/list_apprentice'));

            if(count($_POST) > 0)
            {
                $fk_course_plan = $this->request->getPost('course_plan');
                $new_user_course = array(
                    'fk_user'           => $id_apprentice,
                    'fk_course_plan'    => $fk_course_plan,
                    'fk_status'         => $this->request->getPost('status'),
                    'date_begin'        => $this->request->getPost('date_begin'),
                    'date_end'          => $this->request->getPost('date_end'),
                );

                if (!is_null($user_course)) {
                    // User's course already exists - updates it
                    $this->user_course_model->update($id_user_course, $new_user_course);
                }
                else 
                {
                    $user_has_course = $this->user_course_model->where(['fk_user' => $id_apprentice, 'fk_course_plan' => $fk_course_plan])->findAll() ? true : false;

                    // If the apprentice already follows the course plan submitted, prevent the creation of the entry.
                    if(!$user_has_course)
                    {
                        // No user's course was found in database - inserts a new one
                        $id_user_course = $this->user_course_model->insert($new_user_course);
                            
                        $course_plan = $this->user_course_model->getCoursePlan($new_user_course['fk_course_plan']);
                        $competenceDomainIds = [];
                        
                        foreach ($this->course_plan_model->getCompetenceDomains($course_plan['id']) as $competence_domain) 
                            $competenceDomainIds[] = $competence_domain['id'];
                        
                        $operational_competences = [];
                        // No operational competence associated
                        try 
                        {
                            $operational_competences = $this->operational_comp_model->withDeleted()->whereIn('fk_competence_domain', $competenceDomainIds)->findAll();
                        } 
                        
                        catch (\Exception $e) {};

                        // Adds an acquisition status of level 1 for each objective
                        $objectiveIds = array();
                        foreach ($operational_competences as $operational_competence) 
                        {
                            foreach ($this->operational_comp_model->getObjectives($operational_competence['id']) as $objective) 
                                $objectiveIds[] = $objective['id'];
                        }

                        foreach ($objectiveIds as $objectiveId) 
                        {
                            $acquisition_status = array(
                                'fk_objective'          => $objectiveId,
                                'fk_user_course'        => $id_user_course,
                                'fk_acquisition_level'  => 1
                            );
                            
                            $this->acquisition_status_model->insert($acquisition_status);
                        }
                    }
                }

                if($this->user_course_model->errors() == null) 
                    return redirect()->to(base_url('plafor/apprentice/list_user_courses/' . $id_apprentice));
            }

            // Preparing data for the view
            $course_plans = [];
            $status = [];

            if ($id_user_course == 0) {
                // New user course can only refer to an active course plan and not to a soft deleted one
                foreach ($this->course_plan_model->findAll() as $courseplan)
                    $course_plans[$courseplan['id']] = $courseplan['official_name'];
            } else {
                // Existing user course can refer to an active or a soft deleted course plan
                foreach ($this->course_plan_model->withDeleted()->findAll() as $courseplan)
                    $course_plans[$courseplan['id']] = $courseplan['official_name'];
            }

            foreach ($this->user_course_status_model->findAll() as $usercoursestatus)
                $status[$usercoursestatus['id']] = $usercoursestatus['name'];

            // Data to send to the view
            $output = array(
                'title'         => lang('plafor_lang.title_add_user_course'),
                'course_plans'  => $course_plans,
                'user_course'   => $user_course,
                'status'        => $status,
                'apprentice'    => $apprentice,
                'errors'        => $this->user_course_model->errors()
            );

            return $this->display_view('Plafor\user_course/save', $output);
        } 
        
        else
            return $this->display_view('\User\errors\403error');
    }

    /**
     * @todo the user doesn't modify the trainer but add one on update
     * 
     * Creates a link between an apprentice and a trainer, or changes the trainer
     * linked on the selected trainer_apprentice SQL entry
     *
     * @param int $id_apprentice : ID of the apprentice to add the link to or change the link of
     * @param int $id_link       : ID of the link to modify. If 0, adds a new link
     * @return void
     */
    public function save_apprentice_link($id_apprentice = 0, $id_link = 0) {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_trainer) {
            // Gets data of the apprentice and the link if they exist
            $user_type_id = $this->user_type_model->
                where('access_level', config('\User\Config\UserConfig')->access_level_apprentice)->first()['id'];
            $apprentice = $this->user_model->where('fk_user_type', $user_type_id)->find($id_apprentice);
            $link = $this->trainer_apprentice_model->find($id_link);

            // Redirection
            if (is_null($apprentice) || !is_null($link) && $link['fk_apprentice'] != $id_apprentice) {
                return redirect()->to(base_url());
            }

            // Actions upon form submission
            if (count($_POST) > 0) {
                // Prepares data for insert of update query
                $new_link = array(
                    'fk_trainer' => $this->request->getPost('trainer'),
                    'fk_apprentice' => $this->request->getPost('apprentice'),
                );

                // Query to perform
                if (is_null($link)) {
                    // There is no existing link - inserts a new one
                    $this->trainer_apprentice_model->insert($new_link);
                } else {
                    // A link already exists - updates it
                    $this->trainer_apprentice_model->update($id_link,$new_link);
                }
                // Error handling
                if ($this->trainer_apprentice_model->errors()==null) {
                    // No error - returns to apprentice page
                    return redirect()->to(base_url("plafor/apprentice/view_apprentice/{$id_apprentice}"));
                }
            }

            // Gets data of trainers for the dropdown menu BUT ignore the trainers who are 
            // already linked to the selected apprentice
            $trainersRaw = $this->user_model->getTrainers();
            $trainers = array();
            $linked_apprentices = array();

            foreach ($trainersRaw as $trainer){
                $linked_apprentices = $this->trainer_apprentice_model->getApprenticeIdsFromTrainer($trainer['id']);
                if (is_null($linked_apprentices) || !in_array($id_apprentice, $linked_apprentices)){
                    $trainers[$trainer['id']] = $trainer['username'];
                }
            }

            // Data to send to the view
            $output = array(
                'title'         => lang('plafor_lang.title_save_apprentice_link'),
                'apprentice'    => $apprentice,
                'trainers'      => $trainers,
                'link'          => $link,
                'errors'        => $this->trainer_apprentice_model->errors()
            );

            return $this->display_view('Plafor\apprentice/link',$output);
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }

    /**
     * Deletes a trainer_apprentice link depending on $action
     *
     * @param integer $link_id : ID of the trainer_apprentice_link to affect
     * @param integer $action  : Action to apply on the trainer_apprentice link :
     *  - 0 for displaying the confirmation
     *  - 1 for deleting (hard delete)
     * @return void
     */
    public function delete_apprentice_link($link_id = 0, $action = 0) {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_trainer) {
            // Gets data related to the link
            $link = $this->trainer_apprentice_model->find($link_id);

            if (is_null($link)) {
                return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
            }

            $apprentice = $this->trainer_apprentice_model->getApprentice($link['fk_apprentice']);
            $trainer = $this->trainer_apprentice_model->getTrainer($link['fk_trainer']);

            // Action to perform
            switch ($action) {
                case 0: // Displays confirmation
                    $output = array(
                        'link'          => $link,
                        'apprentice'    => $apprentice,
                        'trainer'       => $trainer,
                    );
                    return $this->display_view('\Plafor\apprentice/delete', $output);
                case 1: // Deletes apprentice link
                    $this->trainer_apprentice_model->delete($link_id, TRUE);
                    break;
                default: // Do nothing
                    break;
            }
            return redirect()->to(base_url('plafor/apprentice/list_apprentice/' . $apprentice['id']));
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }

    /**
     * Shows details of the selected acquisition status
     *
     * @param int $acquisition_status_id : ID of the acquisition status to view
     * @return void
     */
    public function view_acquisition_status($acquisition_status_id = 0) {
        // Gets data of to the acquisition status if it exists
        $acquisition_status = $this->acquisition_status_model->find($acquisition_status_id);

        // Redirection
        if (is_null($acquisition_status)) {
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }

        // Preparing data for the view
        $objective = $this->acquisition_status_model->getObjective($acquisition_status['fk_objective']);
        $acquisition_level = $this->acquisition_status_model->getAcquisitionLevel($acquisition_status['fk_acquisition_level']);
        $user_course = $this->user_course_model->find($acquisition_status['fk_user_course']);
        $apprentice = $this->user_model->find($user_course['fk_user']);

        // Access permissions
        if ($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_level_apprentice
            && $apprentice['id'] != $_SESSION['user_id']) {
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }

        $comments = $this->comment_model->where('fk_acquisition_status',$acquisition_status_id)->findAll();
        $trainers = $this->user_model->getTrainers();

        // Data to send to the view
        $output = array(
            'title'                 => lang('plafor_lang.title_view_acquisition_status'),
            'acquisition_status'    => $acquisition_status,
            'trainers'              => $trainers,
            'comments'              => $comments,
            'objective'             => $objective,
            'acquisition_level'     => $acquisition_level,
        );

        return $this->display_view('Plafor\acquisition_status/view',$output);
    }

    /**
     * Changes an acquisition status for an apprentice
     *
     * @param int $acquisition_status_id : ID of the acquisition status to change
     * @return Response|ResponseInterface
     */
    public function save_acquisition_status($acquisition_status_id = 0) {
        // Gets data of the acquisition status if it exists
        $acquisitionStatus = $this->acquisition_status_model->find($acquisition_status_id);

        // Access permissions
        if ($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_level_apprentice) {
            // No need to check with $user_course outside of an apprentice
            $userCourse = $this->user_course_model->find($acquisitionStatus['fk_user_course']);
            if ($userCourse['fk_user'] != $_SESSION['user_id']) {
                return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
            }
        }

        // Redirection
        if (is_null($acquisitionStatus)) {
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }

        // Gets acquisition levels for the dropdown menu
        $acquisitionLevels = [];
        foreach ($this->acquisition_lvl_model->findAll() as $acquisitionLevel) {
            $acquisitionLevels[$acquisitionLevel['id']]=$acquisitionLevel['name'];
        }

        // Actions upon form submission
        if (!empty($_POST)) {
            $acquisitionLevel = $this->request->getPost('field_acquisition_level');
            $acquisitionStatus = $this->acquisition_status_model->find($acquisition_status_id);
            $acquisitionStatus['fk_acquisition_level'] = $acquisitionLevel;

            // Checks if operational competence and competence domain are active
            $objective = $this->objective_model->find($acquisitionStatus['fk_objective']);
            $opeationalCompetence = $this->objective_model->getOperationalCompetence($objective['fk_operational_competence']);

            // Verifies if operational competence is disabled
            if ($opeationalCompetence==null||$opeationalCompetence['archive']!=null) {
                // Disabled
                return $this->response->setContentType('application/json')->setStatusCode(409)->setBody(json_encode(['error'=>lang('plafor_lang.associated_op_comp_disabled')]));
            } else {
                // Enabled - verifies if competence domain is active
                $competenceDomain=$this->operational_comp_model->getCompetenceDomain($opeationalCompetence['fk_competence_domain']);
                if ($competenceDomain==null|$competenceDomain['archive']!=null) {
                    return $this->response->setContentType('application/json')->setStatusCode(409)->setBody(json_encode(['error'=>lang('plafor_lang.associated_comp_dom_disabled')]));
                }
            }
            $this->acquisition_status_model->update($acquisition_status_id, $acquisitionStatus);
            // Error handling
            if ($this->acquisition_status_model->errors()==null) {
                // No error
                $this->response->setStatusCode(200,'OK');
            }
        }

        // Data to send to the view
        $output = [
            'title'                 => lang('plafor_lang.title_acquisition_status_save'),
            'acquisition_levels'    => $acquisitionLevels,
            'acquisition_level'     => $acquisitionStatus['fk_acquisition_level'],
            'id'                    => $acquisition_status_id,
            'errors'                => $this->acquisition_status_model->errors()
        ];

        return $this->display_view('Plafor\acquisition_status/save', $output);
    }

    /**
     * Adds or modifies a comment for an acquisition status
     * 
     * @param int $acquisition_status_id : ID of the acquisition status
     * @param int $comment_id            : ID of the comment
     * @return void
     */
    public function add_comment($acquisition_status_id = 0, $comment_id = 0) {
        // Gets data related to the comment
        $acquisition_status = $this->acquisition_status_model->find($acquisition_status_id);
        $comment = $this->comment_model->find($comment_id);

        // Access permissions
        if (is_null($acquisition_status)
            || is_null($comment) && $comment_id != 0
            || $_SESSION['user_access'] < config('\User\Config\UserConfig')->access_lvl_trainer) {
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }

        // Actions upon form submission
        if (count($_POST) > 0) {
            $new_comment = array(
                'fk_trainer'            => $_SESSION['user_id'],
                'fk_acquisition_status' => $acquisition_status_id,
                'comment'               => $this->request->getPost('comment'),
                'date_creation'         => date('Y-m-d H:i:s'),
            );
            
            // Checks action to perform
            if (is_null($comment)) {
                // Comment doesn't already exist - inserts it
                $this->comment_model->insert($new_comment);
            } else {
                // Comment already exists - updates it
                $this->comment_model->update($comment_id, $new_comment);
            }

            // Checks for errors
            if ($this->comment_model->errors()==null) {
                // No error - return to acquisition status page
                return redirect()->to(base_url('plafor/apprentice/view_acquisition_status/'.$acquisition_status['id']));
            }
        }

        // Data to send to the view
        $output = array(
            'title'                 => lang('plafor_lang.title_comment_save'),
            'acquisition_status'    => $acquisition_status,
            'comment_id'            => $comment_id,
            'commentValue'          => ($comment['comment']??''),
            'errors'                => $this->comment_model->errors()
        );

        return $this->display_view('\Plafor\comment/save',$output);
    }

    /**
     * Deletes a comment from an acquisition status
     * 
     * @param int $comment_id : ID of the comment to delete
     * @return void
     */
    public function delete_comment($comment_id = 0) {
        // Gets comment array from database
        $comment = $this->comment_model->find($comment_id);

        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_trainer) {
            // Checks if comment exists in database
            if (!is_null($comment)) {
                // Deletes comment
                $this->comment_model->delete($comment_id);
                return redirect()->to(base_url('plafor/apprentice/view_acquisition_status/'.$comment['fk_acquisition_status']));
            }
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }
        return $this->display_view('\User\errors\403error');
    }

    /**
     * Gets the course plan progress for a given user
     * 
     * @param null $userId       : ID of the user
     * @param null $coursePlanId : ID of the course plan
     * @return Response|ResponseInterface
     */
    public function getCoursePlanProgress($userId = null,$coursePlanId = null) {
        if ($userId==null && $this->session->get('user_id')==null)
            return;
        // if user is admin
        if ($this->session->get('user_access')>=config('\User\UserConfig')->access_lvl_admin) {
            return $this->response->setContentType('application/json')->setBody(json_encode($coursePlanId!=null?[($this->course_plan_model->getCoursePlanProgress($userId))[$coursePlanId]]:$this->course_plan_model->getCoursePlanProgress($userId),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
        // in the case of a trainer see only his apprentices
        elseif ($this->session->get('user_access')>=config('\User\UserConfig')->access_lvl_trainer&&in_array($userId,$this->trainer_apprentice_model->getApprenticeIdsFromTrainer($this->session->get('user_id')))){
            return $this->response->setContentType('application/json')->setBody(json_encode($coursePlanId!=null?[($this->course_plan_model->getCoursePlanProgress($userId))[$coursePlanId]]:$this->course_plan_model->getCoursePlanProgress($userId),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        } else {
            $response=null;
            // In the case of a student let him only see his coursePlanProgress else return 403
            $userId!=$this->session->get('user_id')?$response=$this->response->setStatusCode(403):$response=$this->response->setContentType('application/json')->setBody(json_encode($coursePlanId!=null?[($this->course_plan_model->getCoursePlanProgress($userId))[$coursePlanId]]:$this->course_plan_model->getCoursePlanProgress($userId),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

            return $response;
        }
    }

    /**
     * Shows the details of a user's course
     *
     * @param int $id_user_course : ID of the user course to view
     * @return void
     */
    public function view_user_course($id_user_course = 0) {
        $objectives = null;
        $acquisition_levels = null;
        // Gets data of user's course if it exists
        $user_course = $this->user_course_model->find($id_user_course);

        // Redirection
        if ($user_course == null) {
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }

        $apprentice = $this->user_model->find($user_course['fk_user']);

        // Access permissions - apprentices can only see their own courses
        if ($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_level_apprentice
            && $apprentice['id'] != $_SESSION['user_id']) {
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }

        // Preparing data for the view
        $user_course_status = $this->user_course_model->getUserCourseStatus($user_course['fk_status']);
        $course_plan = $this->user_course_model->getCoursePlan($user_course['fk_course_plan'], true);
        $trainers_apprentice = $this->trainer_apprentice_model->where('fk_apprentice',$apprentice['id'])->findAll();


        // If url parameters contains filter operationalCompetenceId
        if ($this->request->getGet('operationalCompetenceId')!=null){
            $objectives = [];
            $acquisition_status = [];
            foreach ($this->course_plan_model->getCompetenceDomains($this->user_course_model->find($id_user_course)['fk_course_plan']) as $competenceDomain) {
                foreach ($this->comp_domain_model->getOperationalCompetences($competenceDomain['id']) as $operationalCompetence) {
                    if ($operationalCompetence['id'] == $this->request->getGet('operationalCompetenceId')) {
                        foreach ($this->operational_comp_model->getObjectives($operationalCompetence['id']) as $objective) {
                            $objectives[$objective['id']] = $objective;
                        }
                    }
                }
            }
            foreach ($this->user_course_model->getAcquisitionStatus($id_user_course) as $acquisition_statuse){
                foreach ($objectives as $objective){
                    if ($acquisition_statuse['fk_objective'] ==$objective['id']){
                        $acquisition_status[]=$acquisition_statuse;
                    }
                }
            }
        } else {
            $acquisition_status = $this->user_course_model->getAcquisitionStatus($id_user_course);

            foreach ($acquisition_status as $acquisitionstatus) {
                $objectives[$acquisitionstatus['fk_objective']] = $this->acquisition_status_model->getObjective($acquisitionstatus['fk_objective']);
            }
        }
        foreach ($this->acquisition_lvl_model->findAll() as $acquisitionLevel) {
            $acquisition_levels[$acquisitionLevel['id']] = $acquisitionLevel;
        }

        // Data to send to the view
        $output = array(
            'title'                 => lang('plafor_lang.title_view_user_course'),
            'user_course'           => $user_course,
            'apprentice'            => $apprentice,
            'user_course_status'    => $user_course_status,
            'course_plan'           => $course_plan,
            'trainers_apprentice'   => $trainers_apprentice,
            'acquisition_status'    => $acquisition_status,
            'acquisition_levels'    => $acquisition_levels,
            'objectives'            => $objectives
        );

        return $this->display_view('\Plafor\user_course/view',$output);
    }

    /**
     * Deletes or deactivates a user depending on $action
     *
     * @param integer $user_id : ID of the user to affect
     * @param integer $action  : Action to apply on the user:
     *      - 0 for displaying the confirmation
     *      - 1 for deactivating (soft delete)
     *      - 2 for deleting (hard delete)
     * @return void
     */
    public function delete_user($user_id = 0, $action = 0) {
        // Access permissions
        if ($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_lvl_admin) {
            // Gets data of the user if it exists
            $user = $this->user_model->withDeleted()->find($user_id);

            // Redirection
            if (is_null($user)) {
                return redirect()->to(base_url('/user/admin/list_user'));
            }

            // Action to perform
            switch ($action) {
                case 0: // Displays confirmation
                    $output = array(
                        'user' => $user,
                        'title' => lang('user_lang.title_user_delete')
                    );
                    return $this->display_view('\User\admin\delete_user', $output);
                case 1: // Deactivates (soft delete) user
                    if ($_SESSION['user_id'] != $user['id']) {
                        $this->user_model->delete($user_id, FALSE);
                    }
                    break;
                case 2: // Deletes user
                    if ($_SESSION['user_id'] != $user['id']) {
                        // Deletes associated information
                        foreach($this->trainer_apprentice_model->where('fk_apprentice',$user['id'])->orWhere('fk_trainer',$user['id'])->findAll() as $trainerApprentice)
                            $trainerApprentice==null?:$this->trainer_apprentice_model->delete($trainerApprentice['id']);
                        if (count($this->user_course_model->getUser($user['id']))>0){
                            foreach($this->user_course_model->where('fk_user',$user['id'])->findAll() as $userCourse){
                                foreach($this->user_course_model->getAcquisitionStatus($userCourse['id']) as $acquisitionStatus){

                                    foreach ($this->comment_model->where('fk_acquisition_status',$acquisitionStatus['id']) as $comment){
                                        $comment==null?:$this->comment_model->delete($comment['id'],true);
                                    }
                                    $this->acquisition_status_model->delete($acquisitionStatus['id'],true);
                                }
                                $this->user_course_model->delete($userCourse['id'],true);
                            }
                        }
                        $this->user_model->delete($user_id, TRUE);
                    }
                    break;
                default: // Do nothing
                    break;
            }
            return redirect()->to('/user/admin/list_user');
        }
        return $this->display_view('\User\errors\403error');
    }
}
