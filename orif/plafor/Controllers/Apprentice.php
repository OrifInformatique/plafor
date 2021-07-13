<?php


namespace Plafor\Controllers;


use CodeIgniter\Config\Services;
use CodeIgniter\Validation\Validation;
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
     * Show details of the selected course plan
     *
     * @param int (SQL PRIMARY KEY) $course_plan_id
     *
     */
    public function view_course_plan($course_plan_id = null)
    {

        $course_plan = CoursePlanModel::getInstance()->withDeleted(true)->find($course_plan_id);
        $competence_domains=CoursePlanModel::getCompetenceDomains($course_plan_id);
        if($course_plan == null){
            return redirect()->to(base_url('plafor/admin/list_course_plan'));
        }

        $output = array(
            'title'=>lang('plafor_lang.title_course_plan_view'),
            'course_plan' => $course_plan,
            'competence_domains'=>$competence_domains
        );

        $this->display_view('\Plafor\course_plan\view',$output);
    }
    
    public function list_apprentice($withDeleted=0)
    {
        $trainer_id = $this->request->getGet('trainer_id');
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
            foreach (CoursePlanModel::getInstance()->findall() as $courseplan)
                $coursesList[$courseplan['id']]=$courseplan;
            $courses = UserCourseModel::getInstance()->findall();
        }else{
                $apprentices = User_Model::getInstance()->whereIn('id', array_column(TrainerApprenticeModel::getInstance()->where('fk_trainer', $trainer_id)->findall(), 'fk_apprentice'))->findall();
                $coursesList=[];
                foreach (CoursePlanModel::getInstance()->findall() as $courseplan)
                    $coursesList[$courseplan['id']]=$courseplan;
                $courses = UserCourseModel::getInstance()->findall();
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

        $this->display_view(['Plafor\templates/admin_menu','Plafor\apprentice/list'], $output);
    }

    public function view_apprentice($apprentice_id = null)
    {
        $apprentice = User_model::getInstance()->find($apprentice_id);
        
        if(is_null($apprentice) || $apprentice['fk_user_type'] != User_type_model::getInstance()->where('name',lang('user_lang.title_apprentice'))->first()['id']){
            return redirect()->to(base_url("/plafor/apprentice/list_apprentice"));
        }
        $user_courses=[];
        foreach (UserCourseModel::getInstance()->where('fk_user',$apprentice_id)->findall() as $usercourse)
        $user_courses[$usercourse['id']] = $usercourse ;

        $user_course_status=[];
        foreach (UserCourseStatusModel::getInstance()->findAll() as $usercoursetatus)
        $user_course_status[$usercoursetatus['id']] = $usercoursetatus;

        $course_plans=[];
        foreach (CoursePlanModel::getInstance()->findall() as $courseplan)
        $course_plans[$courseplan['id']] = $courseplan;

        $trainers = [];
        foreach (User_model::getInstance()->where('fk_user_type',User_type_model::getInstance()->where('name',lang('user_lang.title_trainer'))->first()['id'])->findall() as $trainer)
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
     * Show details of the selected competence domain
     *
     * @param int (SQL PRIMARY KEY) $competence_domain_id
     *
     */
    public function view_competence_domain($competence_domain_id = null)
    {
        $competence_domain = CompetenceDomainModel::getInstance()->withDeleted()->find($competence_domain_id);

        if($competence_domain == null){
            return redirect()->to(base_url('admin/list_competence_domain'));
        }

        $output = array(
            'title' =>lang('plafor_lang.title_view_competence_domain'),
            'course_plan' =>CompetenceDomainModel::getCoursePlan($competence_domain['fk_course_plan'],true)
        ,
            'competence_domain' => $competence_domain,
        );

        return $this->display_view('\Plafor/competence_domain/view',$output);
    }
    /**
     * Form to create a link between a apprentice and a course plan
     *
     * @param int (SQL PRIMARY KEY) $id_user_course
     */
    public function save_user_course($id_apprentice = null,$id_user_course = 0){

        $apprentice = User_model::getInstance()->find($id_apprentice);
        $user_course = UserCourseModel::getInstance()->find($id_user_course);

        if($id_apprentice == null || $apprentice['fk_user_type'] != User_type_model::getInstance()->where('name',lang('user_lang.title_apprentice'))->first()['id']){
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
            exit();
        }

        if(count($_POST) > 0){
            $rules = array(
                    'course_plan'=>[
                    'label' => 'user_lang.course_plan',
                    'rules' => 'required|numeric',
                ],
                    'status'=>[
                    'label' => 'user_lang.status',
                    'rules' => 'required|numeric',
                ],
                    'date_begin'=>[
                    'label' => 'user_lang.field_user_course_date_begin',
                    'rules' => 'required',
                ]
                /*
                array(
                    'field' => 'date_end',
                    'label' => 'lang:field_user_course_date_end',
                    'rules' => 'required',
                ),
                */
            );
            $this->validation->setRules($rules);

            if($this->validation->withRequest($this->request)->run()){
                $fk_course_plan = $this->request->getPost('course_plan');
                $user_course = array(
                    'fk_user' => $id_apprentice,
                    'fk_course_plan' => $fk_course_plan,
                    'fk_status' => $this->request->getPost('status'),
                    'date_begin' => $this->request->getPost('date_begin'),
                    'date_end' => $this->request->getPost('date_end'),
                );

                if($id_user_course > 0){
                    echo UserCourseModel::getInstance()->update($id_user_course, $user_course);
                }else if(UserCourseModel::getInstance()->where('fk_user', $id_apprentice)->where('fk_course_plan', $fk_course_plan)->first()==null) {
                    $id_user_course = UserCourseModel::getInstance()->insert($user_course);

                    $course_plan = UserCourseModel::getCoursePlan($user_course['fk_course_plan']);
                    $competenceDomainIds=[];
                    foreach (CoursePlanModel::getCompetenceDomains($course_plan['id']) as $competence_domain){
                        $competenceDomainIds[] = $competence_domain['id'];
                    }

                    $operational_competences=[];
                    // si il n'y a pas de compétence operationnelles associées
                    try {
                        $operational_competences = OperationalCompetenceModel::getInstance()->withDeleted()->whereIn('fk_competence_domain', $competenceDomainIds)->findAll();
                    }catch (\Exception $e){};
                    $objectiveIds = array();
                    foreach ($operational_competences as $operational_competence){
                        foreach (OperationalCompetenceModel::getObjectives($operational_competence['id']) as $objective){
                            $objectiveIds[] = $objective['id'];
                        }
                    }
                    foreach ($objectiveIds as $objectiveId){
                        $acquisition_status = array(
                            'fk_objective' => $objectiveId,
                            'fk_user_course' => $id_user_course,
                            'fk_acquisition_level' => 1
                        );

                        AcquisitionStatusModel::getInstance()->insert($acquisition_status);
                    }
                }
                return redirect()->to(base_url('plafor/apprentice/view_apprentice/'.$id_apprentice));
                exit();
            }
        }
        $course_plans=[];
        foreach (CoursePlanModel::getInstance()->findAll() as $courseplan)
            $course_plans[$courseplan['id']]=$courseplan['official_name'];
        $status=[];
        foreach (UserCourseStatusModel::getInstance()->findAll() as $usercoursestatus) {
            $status[$usercoursestatus['id']]=$usercoursestatus['name'];
        }
        $output = array(
            'title' => lang('plafor_lang.user_course_title_course_plan_link'),
            'course_plans' => $course_plans,
            'user_course'   => $user_course,
            'status' => $status,
            'apprentice' => $apprentice
        );

        return $this->display_view('Plafor\user_course/save',$output);
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

        if($_SESSION['user_access'] < config('\User\Config\UserConfig')->access_lvl_admin
            || $apprentice == null
            || $apprentice['fk_user_type'] != User_type_model::getInstance()->
            where('name',lang('user_lang.title_apprentice'))->first()['id']){
            return redirect()->to(base_url());
        }

        if(count($_POST) > 0){
            $rules = array(
                    'trainer'=>[
                    'label' => 'user_lang.field_trainer_link',
                    'rules' => 'required|numeric'
                    ]

            );

            $this->validation->setRules($rules);

            if($this->validation->withRequest($this->request)->run()){
                $apprentice_link = array(
                    'fk_trainer' => $this->request->getPost('trainer'),
                    'fk_apprentice' => $this->request->getPost('apprentice'),
                );
                // This is used to prevent an apprentice from being linked to the same person twice
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
        );

        $this->display_view('Plafor\apprentice/link',$output);
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
     * @return void
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

            $this->validation->setRules(['field_acquisition_level'=>[
                'label'=>'user_lang.field_acquisition_level',
                'rules'=>'required|in_list['.implode(',', array_keys($acquisitionLevels)).']'
            ]]);

            if ($this->validation->withRequest($this->request)->run()) {
                $acquisitionStatus = [
                    'fk_acquisition_level' => $acquisitionLevel
                ];
                AcquisitionStatusModel::getInstance()->update($acquisition_status_id, $acquisitionStatus);

                return redirect()->to(base_url('plafor/apprentice/view_acquisition_status/'.$acquisition_status_id));
            }
        }

        $output = [
            'title'=>lang('plafor_lang.title_acquisition_status_save'),
            'acquisition_levels' => $acquisitionLevels,
            'acquisition_level' => $acquisitionStatus['fk_acquisition_level'],
            'id' => $acquisition_status_id
        ];

        return $this->display_view('Plafor\acquisition_status/save', $output);
    }
    public function add_comment($acquisition_status_id = null, $comment_id = null){
        $acquisition_status = AcquisitionStatusModel::getInstance()->find($acquisition_status_id);

        if($acquisition_status == null || $_SESSION['user_access'] < config('\User\Config\UserConfig')->access_lvl_trainer){
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }

        if (count($_POST) > 0) {
            $rules = array(
                    'comment'=>[
                    'label' => 'user_lang.field_comment',
                    'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->SQL_TEXT_MAX_LENGTH.']',
                        ]
            );
            $this->validation->setRules($rules);
            if ($this->validation->withRequest($this->request)->run()) {
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

                return redirect()->to(base_url('plafor/apprentice/view_acquisition_status/'.$acquisition_status['id']));
            }
        
        }

        $comment = CommentModel::getInstance()->find($comment_id);

        $output = array(
            'title'=>lang('plafor_lang.title_comment_save'),
            'acquisition_status' => $acquisition_status,
            'comment_id' => $comment_id,
            'commentValue' => ($comment['comment']??'')
        );

        return $this->display_view('\Plafor\comment/save',$output);
    }

    public function delete_comment($comment_id = 0, $acquisition_status_id = 0) {
        CommentModel::getInstance()->delete($comment_id);
        return redirect()->to(base_url('plafor/apprentice/view_acquisition_status/'.$acquisition_status_id));
    }
    /**
     * Show details of the selected operational competence
     *
     * @param int $operational_competence_id = ID of the operational competence to view
     * @return void
     */
    public function view_operational_competence($operational_competence_id = null)
    {
        $operational_competence = OperationalCompetenceModel::getInstance()->withDeleted(true)->find($operational_competence_id);

        if($operational_competence == null){
            return redirect()->to(base_url('plafor/admin/list_operational_competence'));
        }

        $competence_domain = OperationalCompetenceModel::getCompetenceDomain($operational_competence['fk_competence_domain']);
        $course_plan = CompetenceDomainModel::getCoursePlan($competence_domain['fk_course_plan']);
        $objectives=OperationalCompetenceModel::getObjectives($operational_competence['id']);
        $output = array(
            'title'=>lang('plafor_lang.title_view_operational_competence'),
            'operational_competence' => $operational_competence,
            'competence_domain' => $competence_domain,
            'course_plan' => $course_plan,
            'objectives' => $objectives
        );

        return $this->display_view('\Plafor/operational_competence/view',$output);
    }
    /**
     * Show details of the selected objective
     * @param int $objective_id = ID of the objective to view
     * @return void
     */
    public function view_objective($objective_id = null)
    {
        $objective = ObjectiveModel::getInstance()->withDeleted()->find($objective_id);

        if($objective == null){
            return redirect()->to(base_url('plafor/admin/list_objective'));
        }


        $operational_competence = ObjectiveModel::getOperationalCompetence($objective['fk_operational_competence'],true);

        $competence_domain = OperationalCompetenceModel::getCompetenceDomain($operational_competence['fk_competence_domain']);
        $course_plan = CompetenceDomainModel::getCoursePlan($competence_domain['fk_course_plan']);

        $output = array(
            'title' => lang('plafor_lang.title_view_objective'),
            'objective' => $objective,
            'operational_competence' => $operational_competence,
            'competence_domain' => $competence_domain,
            'course_plan' => $course_plan
        );

        $this->display_view('Plafor\objective/view',$output);
    }
    /**
     * Show a user course's details
     *
     * @param int $id_user_course = ID of the user course to view
     * @return void
     */
    public function view_user_course($id_user_course = null){
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
        $acquisition_status = UserCourseModel::getAcquisitionStatus($id_user_course);
        if($user_course == null){
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        }
        $objectives=[];
        foreach ($acquisition_status as $acquisitionstatus){
            $objectives[$acquisitionstatus['fk_objective']]=AcquisitionStatusModel::getObjective($acquisitionstatus['fk_objective']);
        }
        $acquisition_levels=[];
        foreach (AcquisitionLevelModel::getInstance()->findAll() as $acquisitionLevel){
            $acquisition_levels[$acquisitionLevel['id']]=$acquisitionLevel;
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
    /*public function tester(){
        $file=fopen(WRITEPATH.'plafor.json','r+');
        $filedatas=fopen(WRITEPATH.'filedatas.txt','a+');
        $decodedfile=json_decode(fread($file,filesize(WRITEPATH.'plafor.json')));
        fclose($file);

        $datasName=[];
        for($i=2;$i<count($decodedfile);$i++){
            $temptab=[];
            foreach ($decodedfile[$i]->data as $row){
                $temptab[]=(array)$row;
            }
            $datasName[$decodedfile[$i]->name]=$temptab;

        }
        d($datasName);
        foreach ($datasName['user_type'] as $aclvl) {
            $datastoappend=print_r($aclvl,true);
            echo var_export($aclvl,true);
            fwrite($filedatas,var_export($aclvl,true).";\n");
        }
        fclose($filedatas);
    }
    */
}