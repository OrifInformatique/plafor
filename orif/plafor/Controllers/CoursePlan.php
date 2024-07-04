<?php
/**
 * Controller pour la gestion des plan de formation non associés à un apprenti
 * Required level connected
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Controllers;

use CodeIgniter\I18n\Time;
use Plafor\Models\AcquisitionStatusModel;
use Plafor\Models\CommentModel;
use Plafor\Models\CompetenceDomainModel;
use Plafor\Models\CoursePlanModel;
use Plafor\Models\ObjectiveModel;
use Plafor\Models\OperationalCompetenceModel;
use Plafor\Models\TrainerApprenticeModel;
use Plafor\Models\UserCourseModel;
use Plafor\Models\UserCourseStatusModel;
use User\Models\User_model;
use User\Models\User_type_model;

class CoursePlan extends \App\Controllers\BaseController
{
    /**
     * Method to initialize controller attributes
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger) {
        $this->access_level="@";
        parent::initController($request, $response, $logger);

        // Loads required models
        $this->acquisition_status_model = model('AcquisitionStatusModel');
        $this->comment_model = model('CommentModel');
        $this->comp_domain_model = model('CompetenceDomainModel');
        $this->course_plan_model = model('CoursePlanModel');
        $this->objective_model = model('ObjectiveModel');
        $this->operational_comp_model = model('OperationalCompetenceModel');
        $this->user_course_model = model('UserCourseModel');
        $this->user_course_status_model = model('UserCourseStatusModel');
        $this->trainer_apprentice_model = model('TrainerApprenticeModel');
        $this->user_model = model('User_model');
        $this->user_type_model = model('User_type_model');
    }

    /**
     * Adds or modifies a course plan
     *
     * @param integer $course_plan_id : ID of the course plan to modify, leave blank to create a new one
     * @return void
     */
    public function save_course_plan($course_plan_id = 0) {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            // Gets data of the course plan if it exists
            $course_plan = $this->course_plan_model->withDeleted()->find($course_plan_id);
            $lastDatas = array();

            // Actions upon form submission
            if (count($_POST) > 0) {
                // Data to insert or update
                $new_course_plan = array(
                    'formation_number'  => $this->request->getPost('formation_number'),
                    'official_name'     => $this->request->getPost('official_name'),
                    'date_begin'        => $this->request->getPost('date_begin'),
                );

                // Query to perform
                if (!is_null($course_plan)) {
                    // Course plan already exists - updates it
                    $this->course_plan_model->update($course_plan_id, $new_course_plan);
                } else {
                    // No course plan found in database - inserts a new one
                    $this->course_plan_model->insert($new_course_plan);
                }

                // Error handling
                if ($this->course_plan_model->errors() == null) {
                    // No error - redirects to list of course plans
                    return redirect()->to(base_url('/plafor/courseplan/list_course_plan'));
                } else {
                    // Error - autofills form with pre-submitted values
                    $lastDatas = array(
                        'formation_number'  => $this->request->getPost('formation_number'),
                        'official_name'     => $this->request->getPost('official_name'),
                        'date_begin'        => $this->request->getPost('date_begin')
                    );
                }
            }

            // Data to send to the view
            $formTitle = !is_null($course_plan) ? 'update' : 'new';
            $output = array(
                'title'         => (lang('plafor_lang.title_course_plan_' . $formTitle)),
                'course_plan'   => !empty($lastDatas) ? $lastDatas : $course_plan,
                'errors'        => $this->course_plan_model->errors(),
            );
            return $this->display_view('\Plafor\course_plan\save', $output);
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }

    /**
     * Deletes a course plan depending on $action
     *
     * @param integer $course_plan_id : ID of the course_plan to affect
     * @param integer $action         : Action to apply on the course plan:
     *      - 0 for displaying the confirmation
     *      - 1 for deactivating (soft delete)
     *      - 3 for reactivating
     * @return void
     */
    public function delete_course_plan($course_plan_id = 0, $action = 0) {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            $competenceDomainIds = [];
            $objectiveIds = [];

            // Gets data of the course plan if it exists
            $course_plan = $this->course_plan_model->withDeleted()->find($course_plan_id);

            if (is_null($course_plan)) {
                return redirect()->to('/plafor/courseplan/list_course_plan');
            }

            // Action to perform
            switch ($action) {
                case 0: // Displays confirmation
                    $output = array(
                        'course_plan' => $course_plan
                    );
                    return $this->display_view('\Plafor\course_plan\delete', $output);
                case 1: // Deactivates (soft delete) course plan
                    $this->course_plan_model->delete($course_plan_id); // This model uses soft deletes by default

                    return redirect()->to('/plafor/courseplan/list_course_plan');
                case 3: // Reactivates course plan and its linked objectives
                    $this->course_plan_model->withDeleted()->update($course_plan_id, ['archive' => null]);

                    return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
                default:
                    // No action
                    return redirect()->to('/plafor/courseplan/list_course_plan');
            }
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }

    /**
     * Adds or modifies a competence domain
     *
     * @param integer $course_plan_id       : ID of the course plan
     * @param integer $competence_domain_id : ID of the competence domain to modify, leave blank to create a new one
     * @return void
     */
    public function save_competence_domain($course_plan_id = 0, $competence_domain_id = 0) {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            // Gets data of the course plan and the competence domain if they exist
            $course_plan = $this->course_plan_model->withDeleted()->find($course_plan_id);
            $competence_domain = $this->comp_domain_model->withDeleted()->find($competence_domain_id);

            // Redirection
            if (is_null($course_plan) ||
                !is_null($competence_domain) && $competence_domain['fk_course_plan'] != $course_plan_id) {
                return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
            }

            // Actions upon form submission
            if (count($_POST) > 0) {
                $new_competence_domain = array(
                    'fk_course_plan'    => $this->request->getPost('course_plan'),
                    'symbol'            => $this->request->getPost('symbol'),
                    'name'              => $this->request->getPost('name')
                );

                // Query to perform
                if (!is_null($competence_domain)) {
                    // Competence domain already exists - updates it
                    $this->comp_domain_model->update($competence_domain_id, $new_competence_domain);
                } else {
                    // No competence domain found in database - creates a new one
                    $this->comp_domain_model->insert($new_competence_domain);
                }
                // Error handling
                if ($this->comp_domain_model->errors() == null) {
                    // No error - redirects to course plan
                    return redirect()->to(base_url('plafor/courseplan/view_course_plan/' . ($new_competence_domain['fk_course_plan']??'')));
                }
            }

            // Data to send to the view
            $course_plans = null;
            foreach ($this->course_plan_model->findColumn('official_name') as $courseplanOfficialName)
                $course_plans[$this->course_plan_model->where('official_name', $courseplanOfficialName)->first()['id']] = $courseplanOfficialName;

            $output = array(
                'title'                 => lang('plafor_lang.title_competence_domain_'.(!is_null($competence_domain) ? 'update' : 'new')),
                'competence_domain_id'  => $competence_domain_id,
                'competence_domain'     => $competence_domain,
                'course_plans'          => $course_plans,
                'fk_course_plan_id'     => $course_plan_id,
                'errors'                => $this->comp_domain_model->errors(),
            );
            return $this->display_view('\Plafor\competence_domain/save', $output);
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }

    /**
     * Deletes a competence domain depending on $action
     *
     * @param integer $competence_domain_id : ID of the competence domain to affect
     * @param integer $action               : Action to apply on the course plan:
     *      - 0 for displaying the confirmation
     *      - 1 for deactivating (soft delete)
     *      - 3 for reactivating
     * @return void
     */
    public function delete_competence_domain($competence_domain_id = 0, $action = 0) {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            // Gets data of the competence domain if it exists
            $competence_domain = $this->comp_domain_model->withDeleted()->find($competence_domain_id);

            // Redirection
            if (is_null($competence_domain)) {
                return redirect()->to('plafor/courseplan/list_course_plan');
            }

            // Action to perform
            switch ($action) {
                case 0: // Displays confirmation
                    $output = array(
                        'competence_domain' => $competence_domain
                    );

                    return $this->display_view('\Plafor/competence_domain/delete', $output);
                case 1: // Deactivates (soft delete) competence domain
                    $this->comp_domain_model->delete($competence_domain_id);
                    break;
                case 3: // Reactivates competence domain
                    $this->comp_domain_model->withDeleted()->update($competence_domain_id, ['archive' => null]);
                    break;
                default: // Do nothing
                    break;
            }
            return redirect()->to(base_url('plafor/courseplan/view_course_plan/' . $competence_domain['fk_course_plan']));
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }

    /**
     * Adds or modifies an operational competence
     *
     * @param integer $operational_competence_id : ID of the operational competence to modify,
     *                                             leave blank to create a new one
     * @param integer $competence_domain_id      : ID of the competence domain
     * @return void
     */
    public function save_operational_competence($comp_domain_id = 0, $operational_comp_id = 0) {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            // Gets data of the competence domain and the operational competence if they exist
            $comp_domain = $this->comp_domain_model->withDeleted()->find($comp_domain_id);
            $operational_comp = $this->operational_comp_model->withDeleted()->find($operational_comp_id);

            // Redirection
            if (is_null($comp_domain) ||
                !is_null($operational_comp) && $operational_comp['fk_competence_domain'] != $comp_domain_id) {
                return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
            }

            // Actions upon form submission
            if (count($_POST) > 0) {
                $new_operational_comp = array(
                    'symbol'                => $this->request->getPost('symbol'),
                    'name'                  => $this->request->getPost('name'),
                    'methodologic'          => $this->request->getPost('methodologic'),
                    'social'                => $this->request->getPost('social'),
                    'personal'              => $this->request->getPost('personal'),
                    'fk_competence_domain'  => $this->request->getPost('competence_domain')
                );

                // Query to perform
                if (!is_null($operational_comp)) {
                    // Operational competence already exists - updates it
                    $this->operational_comp_model->update($operational_comp_id, $new_operational_comp);
                } else {
                    // No operational competence was found in database - creates a new one
                    $this->operational_comp_model->insert($new_operational_comp);
                }
                // Error handling
                if ($this->operational_comp_model->errors() == null) {
                    // No error - redirects to the competence domain
                    return redirect()->to(base_url('plafor/courseplan/view_competence_domain/' . $new_operational_comp['fk_competence_domain']));
                }
            }

            // Data to send to the view
            $competenceDomains = [];
            foreach ($this->comp_domain_model->withDeleted()->findAll() as $competenceDomain) {
                $competenceDomains[$this->comp_domain_model->withDeleted()->where('id', $competenceDomain['id'])->first()['id']] = $competenceDomain['name'];
            }

            $output = array(
                'title'                     => lang('plafor_lang.title_operational_competence_' . ((bool)$operational_comp_id ? 'update' : 'new')),
                'operational_competence'    => $operational_comp,
                'competence_domains'        => $competenceDomains,
                'competence_domain'         => $comp_domain,
                'errors'                    => $this->operational_comp_model->errors(),
            );

            return $this->display_view('\Plafor\operational_competence/save', $output);
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }

    /**
     * Deletes an operational competence depending on $action
     *
     * @param integer $operational_competence_id : ID of the operational competence to affect
     * @param integer $action                    : Action to apply on the course plan:
     *      - 0 for displaying the confirmation
     *      - 1 for deactivating (soft delete)
     *      - 3 for reactivating
     * @return void
     */
    public function delete_operational_competence($operational_comp_id = 0, $action = 0) {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            // Gets data of the operational competence if it exists
            $operational_comp = $this->operational_comp_model->withDeleted()->find($operational_comp_id);

            // Redirection
            if (is_null($operational_comp)) {
                return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
            }

            // Action to perform
            switch ($action) {
                case 0: // Displays confirmation
                    $output = array(
                        'operational_competence' => $operational_comp
                    );
                    return $this->display_view('\Plafor\operational_competence/delete', $output);
                case 1: // Deactivates (soft delete) operational competence
                    $this->operational_comp_model->delete($operational_comp_id, FALSE);
                    break;
                case 3: // Reactivates operational competence
                    $this->operational_comp_model->withDeleted()->update($operational_comp_id, ['archive' => null]);
                    break;
                default: // Do nothing
                    break;
            }
            return redirect()->to(base_url('plafor/courseplan/view_competence_domain/'.$operational_comp['fk_competence_domain']));
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }

    /**
     * Deletes a user's course depending on $action
     *
     * @param integer $user_course_id : ID of the user_course to affect
     * @param integer $action         : Action to apply on the course plan:
     *      - 0 for displaying the confirmation
     *      - 1 for deleting (hard delete)
     * @return void
     */
    public function delete_user_course($user_course_id = 0, $action = 0) {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            // Gets data of the user's course if it exists
            $user_course = $this->user_course_model->find($user_course_id);

            // Redirection
            if (is_null($user_course)) {
                /**
                 * @todo Create method and view for list of users' courses
                 */
                // return redirect()->to(base_url('plafor/courseplan/list_user_course'));
                return redirect()->to(base_url('plafor/apprentice/list_apprentice')); // Temporary redirection
            }

            // Gets data for the confirmation view
            $course_plan = $this->course_plan_model->find($user_course['fk_course_plan']);
            $apprentice = $this->user_model->find($user_course['fk_user']);
            $status = $this->user_course_status_model->find($user_course['fk_status']);

            // Action to perform
            switch ($action) {
                case 0: // Displays confirmation
                    $output = array(
                        'user_course' => $user_course,
                        'course_plan' => $course_plan,
                        'apprentice' => $apprentice,
                        'status' => $status,
                        'title' => lang('plafor_lang.title_user_course_delete')
                    );
                    return $this->display_view('Plafor\user_course/delete', $output);
                case 1: // Deletes user's course and the corresponding comments and acquisition status
                    // Deletes comments
                    foreach ($this->acquisition_status_model->where('fk_user_course', $user_course_id)->find() as $acquisition_status) {
                        $this->comment_model->where('fk_acquisition_status', $acquisition_status['id'])->delete();
                    };
                    // Deletes acquisition status
                    $this->acquisition_status_model->where('fk_user_course', $user_course_id)->delete();
                    // Deletes user's course
                    $this->user_course_model->delete($user_course_id);
                    break;
                default: // Do nothing
                    break;
            }
            return redirect()->to(base_url('plafor/apprentice/list_apprentice'));
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }

    /**
     * Adds or modifies an objective
     *
     * @param integer $objective_id              : ID of the objective to modify, leave blank to create a new one
     * @param integer $operational_competence_id : ID of the operational competence
     * @return void
     */
    public function save_objective($objective_id = 0, $operational_comp_id = 0) {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            // Get datas of given objective and operational competence
            $objective = $this->objective_model->withDeleted()->find($objective_id);
            if (!is_null($objective)) {
                // Given objective has to be modified. Get the operational competence corresponding to it.
                $operational_comp = $this->operational_comp_model->withDeleted()->find($objective['fk_operational_competence']);
            } else {
                // No objective is given, add a new objective for the operational competence given in second parameter
                $operational_comp = $this->operational_comp_model->withDeleted()->find($operational_comp_id);
            }

            // If no objective and no operational competence is given, redirect to courseplan list
            if (is_null($operational_comp)) {
                return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
            }

            // Actions upon form submission
            if (count($_POST) > 0) {
                $new_objective = array(
                    'symbol'                    => $this->request->getPost('symbol'),
                    'taxonomy'                  => $this->request->getPost('taxonomy'),
                    'name'                      => $this->request->getPost('name'),
                    'fk_operational_competence' => $this->request->getPost('operational_competence')
                );

                // Query to perform
                if (!is_null($objective)) {
                    // Objective already exists - updates it
                    $this->objective_model->update($objective_id, $new_objective);
                } else {
                    // No objective found in database - inserts a new one
                    $objective_id = $this->objective_model->insert($new_objective);
                    // When we add objective we have to update all students' acquisition status when operational
                    if ($this->objective_model->errors() == null) {
                        $userCourses = $this->course_plan_model->getUserCourses(
                            $this->operational_comp_model->getCompetenceDomain(
                                $this->objective_model->getOperationalCompetence(
                                    $new_objective['fk_operational_competence']
                                )['fk_competence_domain']
                            )['fk_course_plan']
                        );
                        foreach ($userCourses as $userCourse) {
                            $this->acquisition_status_model->insert(['fk_objective'=>$objective_id,'fk_user_course'=>$userCourse['id'],'fk_acquisition_level'=>1]);
                        }
                    } else {
                        $objective_id = 0;
                    }
                }
                // Error handling
                if ($this->objective_model->errors() == null) {
                    // No error - redirects to the operational competence
                    return redirect()->to(base_url('plafor/courseplan/view_operational_competence/' . $operational_comp_id));
                }
            }

            // Data to send to the view
            $operationalCompetences = [];
            foreach ($this->operational_comp_model->findAll() as $operationalCompetence) {
                $operationalCompetences[$operationalCompetence['id']] = $operationalCompetence['name'];
            }
            $output = array(
                'title'                     => lang('plafor_lang.title_objective_' . (is_null($objective) ? 'new' : 'update')),
                'objective'                 => $objective,
                'operational_competences'   => $operationalCompetences,
                'operational_competence_id' => $operational_comp['id'],
                'errors'                    => $this->objective_model->errors(),
            );

            return $this->display_view('\Plafor\objective/save', $output);
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }

    /**
     * Deletes an objective depending on $action
     *
     * @param integer $objective_id : ID of the objective to affect
     * @param integer $action       : Action to apply on the course plan:
     *      - 0 for displaying the confirmation
     *      - 1 for deactivating (soft delete)
     *      - 2 for deleting (hard delete)
     *      - 3 for reactivating
     * @return void
     */
    public function delete_objective($objective_id = 0, $action = 0) {
        // Access permissions
        if ($_SESSION['user_access'] >= config('\User\Config\UserConfig')->access_lvl_admin) {
            // Gets data of the objective if it exists
            $objective = $this->objective_model->withDeleted()->find($objective_id);

            // Redirection
            if (is_null($objective)) {
                return redirect()->to('plafor/courseplan/list_course_plan');
            }

            // Action to perform
            switch ($action) {
                case 0: // Displays confirmation
                    $output = array(
                        'objective' => $objective
                    );
                    return $this->display_view('\Plafor\objective/delete', $output);
                case 1: // Deactivates (soft delete) objective
                    $this->objective_model->delete($objective_id, FALSE);
                    break;
                case 2: // Deletes (hard delete) objective
                    break; // Hard delete dysfunctional - temporarily disabled
                    /**
                     * @todo - Delete acquisition status first
                     *       - Add delete button in the confirmation view
                     */
                    $this->objective_model->delete($objective_id, TRUE);
                    break;
                case 3: // Reactivates objective
                    $this->objective_model->withDeleted()->update($objective_id, ['archive' => null]);
                    break;
                default: // Do nothing
                    break;
            }
            return redirect()->to(base_url('plafor/courseplan/view_operational_competence/'.$objective['fk_operational_competence']));
        } else {
            return $this->display_view('\User\errors\403error');
        }
    }

    /**
     * Displays the list of course plans
     *
     * @param int $id_apprentice : ID of the apprentice
     * @param boolean $with_archived : Whether or not to include archived course plans
     * @return void
     */
    public function list_course_plan($id_apprentice = 0, $with_archived = false) {
        // Checks if the soft deleted course plans should be displayed
        $with_archived = $this->request->getGet('wa') ?? false;

        // Gets data of the user if it exists and is an apprentice
        $user_type_id = $this->user_type_model->
            where('access_level', config('\User\Config\UserConfig')->access_level_apprentice)->first()['id'];
        $apprentice = $this->user_model->where('fk_user_type', $user_type_id)->withDeleted()->find($id_apprentice);

        // Gets data of the course plans depending on whether an apprentice is selected or not
        if (is_null($apprentice)) {
            // Apprentice is selected
            $course_plans = $this->course_plan_model->withDeleted($with_archived)->findAll();
        } else {
            // No apprentice is selected
            $userCourses = $this->user_course_model->getWhere(['fk_user'=>$id_apprentice])->getResult();
            $coursesId = array();

            foreach ($userCourses as $userCourse){
                $coursesId[] = $userCourse->fk_course_plan;
            }

            // $course_plans = $this->course_plan_model->get_many($coursesId);
            $course_plans = $this->course_plan_model->whereIn('id',count($coursesId)==0?[null]:$coursesId)->findAll();
        }

        // Data to send to the view
        $output = array(
            'title'         => lang('plafor_lang.title_list_course_plan'),
            'course_plans'  => $course_plans,
            'with_archived' => $with_archived
        );

        if (is_numeric($id_apprentice)) {
            $output[] = ['course_plans' => $course_plans];
        }

        return $this->display_view(['Plafor\course_plan\list'], $output);
    }

    /**
     * Shows details of the selected course plan
     *
     * @param int (SQL PRIMARY KEY) $course_plan_id : ID of the selected course plan
     * @return void
     */
    public function view_course_plan($course_plan_id = 0) {
        $with_archived = $this->request->getGet('wa') ?? false;

        // Gets data of the course plan if it exists
        $course_plan = $this->course_plan_model->withDeleted()->find($course_plan_id);

        // Redirection
        if (is_null($course_plan)) {
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
        }

        $competence_domains = $this->course_plan_model->getCompetenceDomains($course_plan_id, $with_archived);

        // Format date
        $date_begin = Time::createFromFormat('Y-m-d', $course_plan['date_begin']);
        $course_plan['date_begin'] = $date_begin->toLocalizedString('dd.MM.Y');

        // Data to send to the view
        $output = array(
            'title'                 => lang('plafor_lang.title_view_course_plan'),
            'course_plan'           => $course_plan,
            'competence_domains'    => $competence_domains
        );

        return $this->display_view('\Plafor\course_plan\view',$output);
    }

    /**
     * Shows details of the selected competence domain
     *
     * @param int (SQL PRIMARY KEY) $competence_domain_id : ID of the selected competence domain
     * @return void
     */
    public function view_competence_domain($comp_domain_id = 0) {
        // Gets data of the competence domain if it exists
        $comp_domain = $this->comp_domain_model->withDeleted()->find($comp_domain_id);

        // Redirection
        if (is_null($comp_domain)) {
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
        }

        $with_archived = $this->request->getGet('wa') ?? false;
        $course_plan = $this->comp_domain_model->getCoursePlan($comp_domain['fk_course_plan'], true);

        // Format date
        $date_begin = Time::createFromFormat('Y-m-d', $course_plan['date_begin']);
        $course_plan['date_begin'] = $date_begin->toLocalizedString('dd.MM.Y');

        // Data to send to the view
        $output = array(
            'title'             => lang('plafor_lang.title_view_competence_domain'),
            'course_plan'       => $course_plan,
            'competence_domain' => $comp_domain,
            'with_archived'     => $with_archived,
        );

        return $this->display_view('\Plafor/competence_domain/view',$output);
    }

    /**
     * Shows details of the selected operational competence
     *
     * @param int $operational_competence_id : ID of the selected operational competence
     * @return void
     */
    public function view_operational_competence($operational_comp_id = 0) {
        // Gets data of the operational competence if it exists
        $operational_comp = $this->operational_comp_model->withDeleted(true)->find($operational_comp_id);

        // Redirection
        if (is_null($operational_comp)) {
            return redirect()->to(base_url('plafor/courseplan/list_course_plan/'));
        }

        $with_archived = $this->request->getGet('wa') ?? false;
        $comp_domain = null;
        $course_plan = null;

        try {
            $comp_domain = $this->operational_comp_model->getCompetenceDomain($operational_comp['fk_competence_domain']);
            $course_plan = $this->comp_domain_model->getCoursePlan($comp_domain['fk_course_plan']);
        } catch (Exception $exception) {
            // ?
        }

        $objectives = $this->operational_comp_model->getObjectives($operational_comp['id'], $with_archived);

        // Data to send to the view
        $output = array(
            'title'                     => lang('plafor_lang.title_view_operational_competence'),
            'operational_competence'    => $operational_comp,
            'competence_domain'         => $comp_domain,
            'course_plan'               => $course_plan,
            'objectives'                => $objectives
        );

        return $this->display_view('\Plafor/operational_competence/view',$output);
    }

    /**
     * Shows details of the selected objective
     * @param int $objective_id : ID of the selected objective
     * @return void
     */
    public function view_objective($objective_id = 0) {
        // Gets data of the objective if it exists
        $objective = $this->objective_model->withDeleted()->find($objective_id);

        // Redirection
        if (is_null($objective)) {
            return redirect()->to(base_url('plafor/courseplan/list_course_plan'));
        }

        // Gets data of operational competence and competence domain
        $operational_comp = $this->objective_model->getOperationalCompetence($objective['fk_operational_competence'], true);
        $comp_domain = $this->operational_comp_model->getCompetenceDomain($operational_comp['fk_competence_domain']);
        $course_plan = null;

        if (!is_null($comp_domain)) {
            $course_plan = $this->comp_domain_model->getCoursePlan($comp_domain['fk_course_plan']);
        }

        // Data to send to the view
        $output = array(
            'title'                     => lang('plafor_lang.title_view_objective'),
            'objective'                 => $objective,
            'operational_competence'    => $operational_comp,
            'competence_domain'         => $comp_domain,
            'course_plan'               => $course_plan
        );

        return $this->display_view('Plafor\objective/view',$output);
    }
}
