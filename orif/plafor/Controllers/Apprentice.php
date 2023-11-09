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
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        $this->access_level=config('\User\Config\UserConfig')->access_level_apprentice;
        parent::initController($request, $response, $logger);
        $this->validation=Services::validation();
    }

    /**
     * Default method, redirect to a homepage depending on the type of user
     */
    public function index() {
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            // Session is set, redirect depending on the type of user
            if($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
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

    
    public function list_apprentice($withDeleted=0)
    {
        $trainer_id = $this->request->getGet('trainer_id');
        if ($trainer_id==null && $this->session->get('user_access')==config('\User\Config\UserConfig')->access_lvl_trainer){
            $trainer_id=$this->session->get('user_id');
        }
        $trainersList = array();
        $trainersList[0] = lang('common_lang.all_m');
        $apprentice_level = User_type_model::getInstance()->where('access_level', config("\User\Config\UserConfig")->access_level_apprentice)->find();

        foreach(User_model::getTrainers() as $trainer)
            {
                $trainersList[$trainer['id']] = $trainer['username'];
            }
        
        if($trainer_id == null or $trainer_id == 0){
            $apprentices = User_model::getApprentices($withDeleted);

            $coursesList=[];
            foreach (CoursePlanModel::getInstance()->withDeleted(true)->findall() as $courseplan)
                $coursesList[$courseplan['id']]=$courseplan;
            $courses = UserCourseModel::getInstance()->withDeleted(true)->findall();
        }else{
            $apprentices=[];
            if (count(TrainerApprenticeModel::getInstance()->where('fk_trainer', $trainer_id)->findall()))
                $apprentices = User_Model::getInstance()->whereIn('id', array_column(TrainerApprenticeModel::getInstance()->where('fk_trainer', $trainer_id)->findall(), 'fk_apprentice'))->findall();
            $coursesList=[];
            foreach (CoursePlanModel::getInstance()->withDeleted(true)->findall() as $courseplan)
                $coursesList[$courseplan['id']]=$courseplan;
            $courses = UserCourseModel::getInstance()->withDeleted(true)->findall();
            }
        
        
        $output = array(
            'title' => lang('plafor_lang.title_list_apprentice'),
            'trainer_id' => $trainer_id,
            'trainers' => $trainersList,
            'apprentices' => $apprentices,
            'coursesList' => $coursesList,
            'courses' => $courses,
            'with_archived' => $withDeleted
        );

        $this->display_view(['Plafor\apprentice/list'], $output);
    }

    public function view_apprentice($apprentice_id = null)
    {
        $apprentice = User_model::getInstance()->find($apprentice_id);
        
        if(is_null($apprentice) || $apprentice['fk_user_type'] != User_type_model::getInstance()->where('name',lang('plafor_lang.title_apprentice'))->first()['id']){
            return redirect()->to(base_url("/plafor/apprentice/list_apprentice"));
        }
        $user_courses=[];
        foreach (UserCourseModel::getInstance()->where('fk_user',$apprentice_id)->findall() as $usercourse) {
            $date_begin = Time::createFromFormat('Y-m-d', $usercourse['date_begin']);
            $date_end = Time::createFromFormat('Y-m-d', $usercourse['date_end']);
            $usercourse['date_begin'] = $date_begin->toLocalizedString('dd.MM.Y');
            $usercourse['date_end']!=='0000-00-00'? $usercourse['date_end'] = $date_end->toLocalizedString('dd.MM.Y'):null;
            $user_courses[$usercourse['id']] = $usercourse;
        }

        $user_course_status=[];
        foreach (UserCourseStatusModel::getInstance()->withDeleted(true)->findAll() as $usercoursetatus)
        $user_course_status[$usercoursetatus['id']] = $usercoursetatus;

        $course_plans=[];
        foreach (CoursePlanModel::getInstance()->withDeleted(true)->findall() as $courseplan)
        $course_plans[$courseplan['id']] = $courseplan;

        $trainers = [];
        foreach (User_model::getInstance()->where('fk_user_type',User_type_model::getInstance()->where('name',lang('plafor_lang.title_trainer'))->first()['id'])->withDeleted(true)->findall() as $trainer)
            $trainers[$trainer['id']]= $trainer;

        $links = [];
        foreach (TrainerApprenticeModel::getInstance()->where('fk_apprentice',$apprentice_id)->findAll() as $link)
            $links[$link['id']]=$link;
        
        $output = array(
            'title' => lang('plafor_lang.title_view_apprentice'),
            'apprentice' => $apprentice,
            'trainers' => $trainers,
            'links' => $links,
            'user_courses' => $user_courses,
            'user_course_status' => $user_course_status,
            'course_plans' => $course_plans
        );
        $this->display_view('Plafor\apprentice/view',$output);
    }

    /**
     * Form to create a link between a apprentice and a course plan
     *
     * @param int (SQL PRIMARY KEY) $id_user_course
     */
    public function save_user_course($id_apprentice = null,$id_user_course = 0){
        if ($this->session->get('user_access')>=config('\User\Config\UserConfig')->access_lvl_trainer) {
            $apprentice = User_model::getInstance()->find($id_apprentice);
            $user_course = UserCourseModel::getInstance()->find($id_user_course);

            if ($id_apprentice == null || $apprentice['fk_user_type'] != User_type_model::getInstance()->where('name', lang('plafor_lang.title_apprentice'))->first()['id']) {
                return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
                exit();
            }

            if (count($_POST) > 0) {
                $fk_course_plan = $this->request->getPost('course_plan');
                $user_course = array(
                    'fk_user' => $id_apprentice,
                    'fk_course_plan' => $fk_course_plan,
                    'fk_status' => $this->request->getPost('status'),
                    'date_begin' => $this->request->getPost('date_begin'),
                    'date_end' => $this->request->getPost('date_end'),
                );
                if ($id_user_course > 0) {
                    //update
                    //when we update the userCourse see if the courseplan is changed
                    UserCourseModel::getInstance()->update($id_user_course, $user_course);
                } else if (UserCourseModel::getInstance()->where('fk_user', $id_apprentice)->where('fk_course_plan', $fk_course_plan)->first() == null) {
                    //insert
                    $id_user_course = UserCourseModel::getInstance()->insert($user_course);

                    $course_plan = UserCourseModel::getCoursePlan($user_course['fk_course_plan']);
                    $competenceDomainIds = [];

                    foreach (CoursePlanModel::getCompetenceDomains($course_plan['id']) as $competence_domain) {
                        $competenceDomainIds[] = $competence_domain['id'];
                    }

                    $operational_competences = [];
                    // si il n'y a pas de compétence operationnelles associées
                    try {
                        $operational_competences = OperationalCompetenceModel::getInstance()->withDeleted()->whereIn('fk_competence_domain', $competenceDomainIds)->findAll();
                    } catch (\Exception $e) {
                    };
                    $objectiveIds = array();
                    foreach ($operational_competences as $operational_competence) {
                        foreach (OperationalCompetenceModel::getObjectives($operational_competence['id']) as $objective) {
                            $objectiveIds[] = $objective['id'];
                        }
                    }
                    foreach ($objectiveIds as $objectiveId) {
                        $acquisition_status = array(
                            'fk_objective' => $objectiveId,
                            'fk_user_course' => $id_user_course,
                            'fk_acquisition_level' => 1
                        );

                        AcquisitionStatusModel::getInstance()->insert($acquisition_status);
                    }
                }
                if (UserCourseModel::getInstance()->errors() == null) {
                    //if ok
                    return redirect()->to(base_url('plafor/apprentice/view_apprentice/' . $id_apprentice));
                    exit();
                }
            }
            $course_plans = [];
            foreach (CoursePlanModel::getInstance()->findAll() as $courseplan)
                $course_plans[$courseplan['id']] = $courseplan['official_name'];
            $status = [];
            foreach (UserCourseStatusModel::getInstance()->findAll() as $usercoursestatus) {
                $status[$usercoursestatus['id']] = $usercoursestatus['name'];
            }
            $output = array(
                'title' => lang('plafor_lang.title_user_course_plan_link'),
                'course_plans' => $course_plans,
                'user_course' => $user_course,
                'status' => $status,
                'apprentice' => $apprentice,
                'errors' => UserCourseModel::getInstance()->errors()
            );

            return $this->display_view('Plafor\user_course/save', $output);
        }
        else{
            return $this->display_view('\User\errors\403error');
        }
    }
    /**@todo the user doesn't modify the trainer but add one on update
    /**
     * Create a link between a apprentice and a trainer, or change the trainer
     * linked on the selected trainer_apprentice SQL entry
     *
     * @param int $id_apprentice = ID of the apprentice to add the link to or change the link of
     * @param int $id_link = ID of the link to modify. If 0, adds a new link
     * @return void
     */
    public function save_apprentice_link($id_apprentice = null, $id_link = null){

        $apprentice = User_model::getInstance()->find($id_apprentice);

        if($_SESSION['user_access'] < config('\User\Config\UserConfig')->access_lvl_trainer
            || $apprentice == null
            || $apprentice['fk_user_type'] != User_type_model::getInstance()->
            where('name',lang('plafor_lang.title_apprentice'))->first()['id']){
            return redirect()->to(base_url());
        }

        if(count($_POST) > 0){
            $apprentice_link = array(
                'fk_trainer' => $this->request->getPost('trainer'),
                'fk_apprentice' => $this->request->getPost('apprentice'),
            );
            $old_link = TrainerApprenticeModel::getInstance()->where('fk_trainer',$apprentice_link['fk_trainer'])->where('fk_apprentice',$apprentice_link['fk_apprentice'])->first();
            if ($id_link != null) {
                if (!is_null($old_link)) {
                    // Delete the old link instead of deleting the one being changed
                    // It's easier that way
                    TrainerApprenticeModel::getInstance()->delete($id_link);
                } else {
                    TrainerApprenticeModel::getInstance()->update($id_link,$apprentice_link);
                }
            } elseif (is_null($old_link)) {
                // Don't insert a new link that is the same as an old one
                TrainerApprenticeModel::getInstance()->insert($apprentice_link);
            }

            if(TrainerApprenticeModel::getInstance()->errors()==null){
                //ok
                // This is used to prevent an apprentice from being linked to the same person twice
                return redirect()->to(base_url("plafor/apprentice/view_apprentice/{$id_apprentice}"));
            }
        }
        // It seems that the MY_model dropdown method can't return a filtered result
        // so here we get every users that are trainer, then we create a array
        // with the matching constitution

        $trainersRaw = User_model::getTrainers();

        $trainers = array();

        foreach ($trainersRaw as $trainer){
            $trainers[$trainer['id']] = $trainer['username'];
        }

        $link = $id_link==null?null:TrainerApprenticeModel::getInstance()->find($id_link);

        $output = array(
            'title'=>lang('plafor_lang.title_save_apprentice_link'),
            'apprentice' => $apprentice,
            'trainers' => $trainers,
            'link' => $link,
            'errors'=>TrainerApprenticeModel::getInstance()->errors()
        );

        $this->display_view('Plafor\apprentice/link',$output);
    }
    /**
     * Deletes a trainer_apprentice link depending on $action
     *
     * @param integer $link_id = ID of the trainer_apprentice_link to affect
     * @param integer $action = Action to apply on the trainer_apprentice link :
     *  - 0 for displaying the confirmation
     *  - 1 for deleting (hard delete)
     * @return void
     */
    public function delete_apprentice_link($link_id, $action = 0)
    {

        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_trainer) {
            $link = TrainerApprenticeModel::getInstance()->find($link_id);
            $apprentice = TrainerApprenticeModel::getApprentice($link['fk_apprentice']);
            $trainer = TrainerApprenticeModel::getTrainer($link['fk_trainer']);
            if (is_null($link)) {
                return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
            }

            switch ($action) {
                case 0: // Display confirmation
                    $output = array(
                        'link' => $link,
                        'apprentice' => $apprentice,
                        'trainer' => $trainer,
                        'title' => lang('plafor_lang.title_apprentice_link_delete')
                    );
                    $this->display_view('\Plafor\apprentice/delete', $output);
                    break;
                case 1: // Delete apprentice link
                    TrainerApprenticeModel::getInstance()->delete($link_id, TRUE);
                    return redirect()->to(base_url('plafor/apprentice/list_apprentice/' . $apprentice['id']));
                default: // Do nothing
                    return redirect()->to(base_url('plafor/apprentice/list_apprentice/' . $apprentice['id']));
            }
        }
        else{
            return $this->display_view('\User\errors\403error');
        }
    }
    /**
     * Show details of the selected acquisition status
     *
     * @param int $acquisition_status_id = ID of the acquisition status to view
     * @return void
     */
    public function view_acquisition_status($acquisition_status_id = null){
        $acquisition_status = AcquisitionStatusModel::getInstance()->find($acquisition_status_id);
        $objective=AcquisitionStatusModel::getObjective($acquisition_status['fk_objective']);
        $acquisition_level=AcquisitionStatusModel::getAcquisitionLevel($acquisition_status['fk_acquisition_level']);
        if($acquisition_status == null){
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }

        $comments = CommentModel::getInstance()->where('fk_acquisition_status',$acquisition_status_id)->findAll();
        $trainers = User_model::getTrainers();
        $output = array(
            'title' => lang('plafor_lang.title_acquisition_status_view'),
            'acquisition_status' => $acquisition_status,
            'trainers' => $trainers,
            'comments' => $comments,
            'objective' => $objective,
            'acquisition_level' => $acquisition_level,
        );

        return $this->display_view('Plafor\acquisition_status/view',$output);
    }
    /**
     * Changes an acquisition status for an apprentice
     *
     * @param int $acquisition_status_id = ID of the acquisition status to change
     * @return Response|ResponseInterface
     */
    public function save_acquisition_status($acquisition_status_id = 0) {
        $acquisitionStatus = AcquisitionStatusModel::getInstance()->find($acquisition_status_id);

        if($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_level_apprentice) {
            // No need to check with $user_course outside of an apprentice
            $userCourse = UserCourseModel::getInstance()->find($acquisitionStatus['fk_user_course']);
            if ($userCourse['fk_user'] != $_SESSION['user_id']) {
                return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
            }
        }

        if (is_null($acquisitionStatus)) {
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }
        $acquisitionLevels=[];
        foreach (AcquisitionLevelModel::getInstance()->findAll() as $acquisitionLevel)
            $acquisitionLevels[$acquisitionLevel['id']]=$acquisitionLevel['name'];

        // Check if data was sent

        if (!empty($_POST)) {
            $acquisitionLevel = $this->request->getPost('field_acquisition_level');
            $acquisitionStatus=AcquisitionStatusModel::getInstance()->find($acquisition_status_id);
            $acquisitionStatus['fk_acquisition_level'] = $acquisitionLevel;
            //check if opcomp and compdom is avtive

            $objective=ObjectiveModel::getInstance()->find($acquisitionStatus['fk_objective']);
            $opeationalCompetence=ObjectiveModel::getOperationalCompetence($objective['fk_operational_competence']);
            //verify if op comp is disabled
            if ($opeationalCompetence==null||$opeationalCompetence['archive']!=null){
                return $this->response->setContentType('application/json')->setStatusCode(409)->setBody(json_encode(['error'=>lang('plafor_lang.associated_op_comp_disabled')]));
            }
            //if not verify if competence domain is active
            else{
                $competenceDomain=OperationalCompetenceModel::getCompetenceDomain($opeationalCompetence['fk_competence_domain']);
                if ($competenceDomain==null|$competenceDomain['archive']!=null){
                    return $this->response->setContentType('application/json')->setStatusCode(409)->setBody(json_encode(['error'=>lang('plafor_lang.associated_comp_dom_disabled')]));
                }
            }
            AcquisitionStatusModel::getInstance()->update($acquisition_status_id, $acquisitionStatus);
            if (AcquisitionStatusModel::getInstance()->errors()==null) {

                //if ok
                return $this->response->setStatusCode(200,'OK');
            }
        }

        $output = [
            'title'=>lang('plafor_lang.title_acquisition_status_save'),
            'acquisition_levels' => $acquisitionLevels,
            'acquisition_level' => $acquisitionStatus['fk_acquisition_level'],
            'id' => $acquisition_status_id,
            'errors'=> AcquisitionStatusModel::getInstance()->errors()
        ];

        return $this->display_view('Plafor\acquisition_status/save', $output);
    }
    public function add_comment($acquisition_status_id = null, $comment_id = null){
        $acquisition_status = AcquisitionStatusModel::getInstance()->find($acquisition_status_id);

        if($acquisition_status == null || $_SESSION['user_access'] < config('\User\Config\UserConfig')->access_lvl_trainer){
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }

        if (count($_POST) > 0) {
            $comment = array(
                'fk_trainer' => $_SESSION['user_id'],
                'fk_acquisition_status' => $acquisition_status_id,
                'comment' => $this->request->getPost('comment'),
                'date_creation' => date('Y-m-d H:i:s'),
            );
            if($comment_id == null)
                CommentModel::getInstance()->insert($comment);
            else
                CommentModel::getInstance()->update($comment_id, $comment);

            if (CommentModel::getInstance()->errors()==null) {
                //if ok

                return redirect()->to(base_url('plafor/apprentice/view_acquisition_status/'.$acquisition_status['id']));
            }
        
        }

        $comment = CommentModel::getInstance()->find($comment_id);

        $output = array(
            'title'=>lang('plafor_lang.title_comment_save'),
            'acquisition_status' => $acquisition_status,
            'comment_id' => $comment_id,
            'commentValue' => ($comment['comment']??''),
            'errors'    => CommentModel::getInstance()->errors()
        );

        return $this->display_view('\Plafor\comment/save',$output);
    }

    public function delete_comment($comment_id = 0, $acquisition_status_id = 0) {
        CommentModel::getInstance()->delete($comment_id);
        return redirect()->to(base_url('plafor/apprentice/view_acquisition_status/'.$acquisition_status_id));
    }



    /**
     * @param null $userId the id of user
     * If admin
     */
    public function getCoursePlanProgress($userId=null,$coursePlanId=null){
        if ($userId==null && $this->session->get('user_id')==null)
            return;
        //if user is admin
        if($this->session->get('user_access')>=config('\User\UserConfig')->access_lvl_admin){
            return $this->response->setContentType('application/json')->setBody(json_encode($coursePlanId!=null?[(CoursePlanModel::getInstance()->getCoursePlanProgress($userId))[$coursePlanId]]:CoursePlanModel::getInstance()->getCoursePlanProgress($userId),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
        //in the case of a trainer see only his apprentices
        elseif ($this->session->get('user_access')>=config('\User\UserConfig')->access_lvl_trainer&&in_array($userId,TrainerApprenticeModel::getApprenticeIdsFromTrainer($this->session->get('user_id')))){
            return $this->response->setContentType('application/json')->setBody(json_encode($coursePlanId!=null?[(CoursePlanModel::getInstance()->getCoursePlanProgress($userId))[$coursePlanId]]:CoursePlanModel::getInstance()->getCoursePlanProgress($userId),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        }
        else{
            $response=null;
            //In the case of a student let him only see his coursePlanProgress else return 403
            $userId!=$this->session->get('user_id')?$response=$this->response->setStatusCode(403):$response=$this->response->setContentType('application/json')->setBody(json_encode($coursePlanId!=null?[(CoursePlanModel::getInstance()->getCoursePlanProgress($userId))[$coursePlanId]]:CoursePlanModel::getInstance()->getCoursePlanProgress($userId),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

            return $response;
        }



    }
    /**
     * Show a user course's details
     *
     * @param int $id_user_course = ID of the user course to view
     * @return void
     */
    public function view_user_course($id_user_course = null){
        $objectives = null;
        $acquisition_levels = null;
        $user_course = UserCourseModel::getInstance()->find($id_user_course);
        if($user_course == null){
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }

        $apprentice = User_model::getInstance()->find($user_course['fk_user']);
        if($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_level_apprentice && $apprentice['id'] != $_SESSION['user_id']) {
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }
        $user_course_status = UserCourseModel::getUserCourseStatus($user_course['fk_status']);
        $course_plan = UserCourseModel::getCoursePlan($user_course['fk_course_plan']);
        $trainers_apprentice = TrainerApprenticeModel::getInstance()->where('fk_apprentice',$apprentice['id'])->findAll();
        if($user_course == null){
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }
        //if url parameters contains filter operationalCompetenceId
        if ($this->request->getGet('operationalCompetenceId')!=null){
            $objectives=[];
            $acquisition_status=[];
            foreach(CoursePlanModel::getCompetenceDomains(UserCourseModel::getInstance()->find($id_user_course)['fk_course_plan'])as $competenceDomain) {
                foreach (CompetenceDomainModel::getOperationalCompetences($competenceDomain['id']) as $operationalCompetence) {
                    if ($operationalCompetence['id'] == $this->request->getGet('operationalCompetenceId')) {
                        foreach (OperationalCompetenceModel::getObjectives($operationalCompetence['id']) as $objective){
                            $objectives[$objective['id']]=$objective;
                        }
                    }
                }
            }
            foreach (UserCourseModel::getAcquisitionStatus($id_user_course) as $acquisition_statuse){
                foreach ($objectives as $objective){
                    if ($acquisition_statuse['fk_objective'] ==$objective['id']){
                        $acquisition_status[]=$acquisition_statuse;
                    }
                }
            }
        }
        else {
            $acquisition_status = UserCourseModel::getAcquisitionStatus($id_user_course);

            foreach ($acquisition_status as $acquisitionstatus) {
                $objectives[$acquisitionstatus['fk_objective']] = AcquisitionStatusModel::getObjective($acquisitionstatus['fk_objective']);
            }
        }
        foreach (AcquisitionLevelModel::getInstance()->findAll() as $acquisitionLevel) {
            $acquisition_levels[$acquisitionLevel['id']] = $acquisitionLevel;
        }
        $output = array(
            'title'=>lang('plafor_lang.title_user_course_view'),
            'user_course' => $user_course,
            'apprentice' => $apprentice,
            'user_course_status' => $user_course_status,
            'course_plan' => $course_plan,
            'trainers_apprentice' => $trainers_apprentice,
            'acquisition_status' => $acquisition_status,
            'acquisition_levels' => $acquisition_levels,
            'objectives'=>$objectives
        );

        $this->display_view('\Plafor\user_course/view',$output);
    }
    /**
     * Delete or deactivate a user depending on $action
     *
     * @param integer $user_id = ID of the user to affect
     * @param integer $action = Action to apply on the user:
     *  - 0 for displaying the confirmation
     *  - 1 for deactivating (soft delete)
     *  - 2 for deleting (hard delete)
     * @return void
     */
    public function delete_user($user_id, $action = 0)
    {
        if ($_SESSION['user_access'] == config('\User\Config\UserConfig')->access_lvl_admin) {
            $user = User_model::getInstance()->withDeleted()->find($user_id);
            if (is_null($user)) {
                return redirect()->to(base_url('/user/admin/list_user'));
            }

            switch ($action) {
                case 0: // Display confirmation
                    $output = array(
                        'user' => $user,
                        'title' => lang('user_lang.title_user_delete')
                    );
                    $this->display_view('\User\admin\delete_user', $output);
                    break;
                case 1: // Deactivate (soft delete) user
                    if ($_SESSION['user_id'] != $user['id']) {
                        User_model::getInstance()->delete($user_id, FALSE);
                    }
                    return redirect()->to('/user/admin/list_user');
                case 2: // Delete user
                    if ($_SESSION['user_id'] != $user['id']) {
                        //here we have to delete associated infos
                        foreach(TrainerApprenticeModel::getInstance()->where('fk_apprentice',$user['id'])->orWhere('fk_trainer',$user['id'])->findAll() as $trainerApprentice)
                            $trainerApprentice==null?:TrainerApprenticeModel::getInstance()->delete($trainerApprentice['id']);
                        if (count(UserCourseModel::getUser($user['id']))>0){
                            foreach(UserCourseModel::getInstance()->where('fk_user',$user['id'])->findAll() as $userCourse){
                                foreach(UserCourseModel::getAcquisitionStatus($userCourse['id']) as $acquisitionStatus){

                                    foreach (CommentModel::getInstance()->where('fk_acquisition_status',$acquisitionStatus['id']) as $comment){
                                        $comment==null?:CommentModel::getInstance()->delete($comment['id'],true);
                                    }
                                    AcquisitionStatusModel::getInstance()->delete($acquisitionStatus['id'],true);
                                }
                                UserCourseModel::getInstance()->delete($userCourse['id'],true);
                            }

                        }
                        User_model::getInstance()->delete($user_id, TRUE);
                    }
                    return redirect()->to('/user/admin/list_user');
                default: // Do nothing
                    return redirect()->to('/user/admin/list_user');
            }
        }
    }
}