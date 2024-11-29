<?php
/**
 * Fichier de model pour course_plan
 *
 * @author      Orif (ViDi, HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Plafor\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;
use function Plafor\Controllers\getCompetenceDomains;
use function Plafor\Controllers\getCoursePlans;
use function Plafor\Controllers\getObjectives;
use function Plafor\Controllers\getOperationalCompetences;

class CoursePlanModel extends Model {
    protected $table = 'course_plan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['formation_number', 'official_name',
        'date_begin', 'archive'];
    protected $useSoftDeletes = true;
    protected $deletedField = 'archive';
    private $userCourseModel = null;
    private $competenceDomainModel = null;
    protected $validationRules;

    /** should be public but don't know if
     *  it will be used so stay public
     */
    public function __construct(ConnectionInterface &$db = null,
        ValidationInterface $validation = null)
    {
        $this->validationRules =
            [
                'id' => 'permit_empty',
                'formation_number'=> [
                    'label' => 'plafor_lang.field_course_plan_formation_number',
                    'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->FORMATION_NUMBER_MAX_LENGTH.']|numeric'."|checkFormPlanNumber[{id}]",
                ],
                'official_name'=> [
                    'label' => 'plafor_lang.field_course_plan_official_name',
                    'rules' => 'required|max_length['.config('\Plafor\Config\PlaforConfig')->OFFICIAL_NAME_MAX_LENGTH.']',
                ],
                'date_begin'=> [
                'label' => 'plafor_lang.field_course_plan_date_begin',
                'rules' => 'required',
            ]
        ];

        parent::__construct($db, $validation);
    }

    /**
     * @param $coursePlanId
     * @return array
     */
    public function getCompetenceDomains($coursePlanId,
        $with_archived = false)
    {
        $competenceDomainModel = model('CompetenceDomainModel');
        return $competenceDomainModel->where('fk_course_plan', $coursePlanId)
                                     ->withDeleted($with_archived)
                                     ->findAll();
    }

    /**
     * @param $coursePlanId
     * @return array
     */
    public function getUserCourses($coursePlanId) {
        $userCourseModel = model('UserCourseModel');
        return $userCourseModel->where('fk_course_plan', $coursePlanId)
                               ->withDeleted()->findAll();
    }
    /**
     * @param $userId //is the apprentice id
     * @return null|string|array // return jsonobjects list organized by course
     *                               Plan contained compdom and opcomp
     */
    public function getCoursePlanProgress($userId) {
        $competenceDomainsAssociated = [];
        $operational_competencesassociated = [];
        $coursePlanAssociated = [];
        if (!isset($userId)) {
            return null;
        }

        $coursePlans = [];
        $userCourseModel = model('UserCourseModel');
        $userCourses = $userCourseModel->where('fk_user', $userId)
                                       ->withDeleted()->findAll();
        foreach ($userCourses as $userCourse) {
            $fk_course_plan = $userCourse['fk_course_plan'];
            $coursePlans[$fk_course_plan] = $userCourseModel
                ->getCoursePlan($userCourse['fk_course_plan'], true);
            $coursePlans[$fk_course_plan]['fk_acquisition_status']
                = $userCourse['fk_status'];
        }

        foreach ($coursePlans as $coursePlan) {
            $indexedCompetenceDomains = [];
            $competenceDomainModel = model('CompetenceDomainModel');
            $competence_domains = $competenceDomainModel
                ->getCompetenceDomains(false, $coursePlan['id']);
            foreach ($competence_domains as $competence_domain) {
                $indexedCompetenceDomains[$competence_domain['id']]
                    = $competence_domain;
            }
            $coursePlan['competenceDomains'] = $indexedCompetenceDomains;

            foreach ($coursePlan['competenceDomains'] as $competence_domain) {
                # TODO Refectory to remove nested code
                $operational_competences = [];
                $indexedOperationalCompetences = [];
                $operational_competenceModel = model('OperationalCompetenceModel');
                $operational_competences = $operational_competenceModel->getOperationalCompetences(false, $competence_domain['id']);
                foreach ($operational_competences as $operational_competence) {
                    $indexedOperationalCompetences[$operational_competence['id']] = $operational_competence;
                }
                $competence_domain['operationalCompetences'] = $indexedOperationalCompetences;

                foreach ($competence_domain['operationalCompetences'] as $operational_competence) {
                    $userCourseModel->where(['fk_user' => $userId, 'fk_course_plan' => $coursePlan['id']])->first();
                    $intermediateArray = [];
                    $userCourse = $userCourseModel->where('fk_user', $userId)->where('fk_course_plan', $coursePlan['id'])->first();

                    $objectiveModel = model('ObjectiveModel');
                    foreach ($objectiveModel->getObjectives(false, $operational_competence['id']) as $objective) {
                        $objectiveModel = model('ObjectiveModel');
                        $objectiveModel->getAcquisitionStatus($objective['id'], $userCourse['id'])['fk_acquisition_level'];
                        $objectiveModel = model('ObjectiveModel');
                        $objective['fk_acquisition_level'] = $objectiveModel->getAcquisitionStatus($objective['id'], $userCourse['id'])['fk_acquisition_level'];
                        $intermediateArray[] = $objective;
                    }
                    $objectives = $intermediateArray;
                    $operational_competence['objectives'] = $objectives;
                    $competence_domain['operationalCompetences'][$operational_competence['id']] = $operational_competence;

                }
                $coursePlan['competenceDomains'][$competence_domain['id']] = $competence_domain;
            }
            $coursePlans[$coursePlan['id']] = $coursePlan;
            }

        return $coursePlans;
    }

    public function getCoursePlanIdByUserCourse(int $userCourseId,
        bool $withDeleted = true): ?int
    {
        $coursePlanId = $this
            ->select('course_plan.id')
            ->join('user_course', 'user_course.fk_course_plan =
                course_plan.id', 'left')
            ->where('user_course.id', $userCourseId)
            ->withDeleted($withDeleted)
            ->find()[0]['id'] ?? null;
        return $coursePlanId;
    }
}
?>
