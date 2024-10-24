<?php

namespace Plafor\Models;

use CodeIgniter\Model;

class TeachingDomainModel extends Model
{
    // protected $DBGroup          = 'default';
    protected $table            = 'teaching_domain';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['fk_teaching_domain_title',
        'fk_course_plan', 'domain_weight', 'is_eliminatory'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    protected $deletedField  = 'archive';

    // Validation
    protected $validationRules      = [
        'fk_teaching_domain_title'     => 'is_natural_no_zero',
        'fk_course_plan'        => 'is_natural_no_zero',
        'domain_weight'     => 'decimal',
        'is_eliminatory' => 'is_boolean_or_binary_value',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = ['afterFind'];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    /**
     * Post-processing hook for find operations.
     *
     * @param array $data Contains the result from the find operation, along
     * with additional metadata.
     *   - $data['data']: The result data from the find operation.
     *   - $data['method']: The name of the find method that was called (e.g.
     *   'findAll', 'find', 'first').
     *
     * @return array $data The edited result data.
     *
     * This method applies additional processing to the result data based on
     * the type of find operation:
     * - findAll and find without an ID in the parameter call afterFindFindAll.
     * - first and find with an ID in the parameter call afterFindFind.
     */
    protected function afterFind(array $data): array
    {
        if (is_null($data['data'])) return $data;
        $data['data'] = match ($data['method']) {
            'first' => $this->afterFindFind($data['data']),
            'find' => array_key_exists(0, $data['data']) ?
            $this->afterFindFindAll($data['data']) :
            $this->afterFindFind($data['data']),
            'findAll' => $this->afterFindFindAll($data['data']),
            default => $data['data']
        };
        return $data;
    }

    /**
     * Post-processing hook for findAll operations.
     *
     * Applies the afterFindFind method to each element of the result set
     * returned by findAll.
     *
     * @param array $data The result set from the findAll operation.
     * @return array The result set with each element processed by
     * afterFindFind.
     */
    protected function afterFindFindAll(array $data): array
    {
        return array_map(fn($row) => $this->afterFindFind($row), $data);
    }

    /**
     * Post-processing hook for find and first operations.
     *
     * Enhances the result data by adding related information:
     * - Teaching domain title (if fk_teaching_domain_title is present)
     * - Course plan name (if fk_course_plan is present)
     *
     * These enhancements are achieved through joins with the
     * teaching_domain_title and course_plan tables.
     *
     * @param array $data The result data from the find or first operation.
     * @return array The enhanced result data with additional information.
     */
    protected function afterFindFind(array $data): array
    {
        if (array_key_exists('fk_teaching_domain_title', $data)) {
            $data['title'] = $this->select('teaching_domain_title.title')
                                  ->join('teaching_domain_title',
             'teaching_domain_title.id = fk_teaching_domain_title', 'left')
             ->allowCallbacks(false)->withDeleted()
             ->find($data['id'])['title'];
        }
        if (array_key_exists('fk_course_plan', $data)) {
            $data['course_plan_name'] = $this
                ->select('course_plan.official_name')
             ->join('course_plan', 'course_plan.id = fk_course_plan', 'left')
             ->allowCallbacks(false)->withDeleted()
             ->find($data['id'])['official_name'];
        }
        return $data;
    }

    public function getTeachingDomainIdByUserCourse(int $userCourseId,
        bool $withDeleted = true): array
    {
        $userCourseModel = model('CoursePlanModel');
        $coursePlanId = $userCourseModel
            ->getCoursePlanIdByUserCourse($userCourseId, $withDeleted);
        $domainIdsRaw = $this
            ->select('teaching_domain.id')
            ->where('fk_course_plan = ', $coursePlanId)
            ->withDeleted($withDeleted)
            ->findAll();
        $domainIds = array_map(fn($record) => $record['id'], $domainIdsRaw);
        return $domainIds;
    }

    private function getDomainByUserCourseAndName(int $userCourseId,
        string $domainName, bool $withDeleted = true): ?array
    {
        $domainIds = $this->getTeachingDomainIdByUserCourse($userCourseId,
            $withDeleted);
        if (empty($domainIds)) return null;
        $domains = array_map(fn($id) => $this->withDeleted($withDeleted)
            ->find($id), $domainIds);
        $domainsFilted = array_filter($domains,
            fn($domain) => $domain['title'] === $domainName
        );
        $domain = $domainsFilted[array_key_last($domainsFilted)] ?? null;
        if (is_null($domain)) return null;
        return $domain;
    }

    public function getITDomainWeight(int $userCourseId,
        bool $withDeleted = true): ?float
    {
        $ITDomain = $this->getITDomain($userCourseId, $withDeleted);
        if (is_null($ITDomain)) return null;
        $ITWeight = $ITDomain['domain_weight'];
        return $ITWeight;
    }

    public function getITDomain(int $userCourseId,
        bool $withDeleted = true): ?array
    {
        // TODO find a better way
        // magic string
        $domainNameForModuleWeight = 'Informatique';
        $ITDomain = $this->getDomainByUserCourseAndName($userCourseId,
            $domainNameForModuleWeight, $withDeleted);
        return $ITDomain;

    }

    public function getTpiDomain(int $userCourseId,
        bool $withDeleted = true): ?array
    {
        // TODO find a better way
        // magic string
        $tpiDomainName = 'Travail pratique individuel';
        $domain = $this->getDomainByUserCourseAndName($userCourseId,
            $tpiDomainName, $withDeleted);
        return $domain;
    }

    public function getCbeDomain(int $userCourseId,
        bool $withDeleted = true): ?array
    {
        // TODO find a better way
        // magic string
        $domainName = 'Compétences de base élargies';
        $domain = $this->getDomainByUserCourseAndName($userCourseId,
            $domainName, $withDeleted);
        return $domain;
    }

    public function getEcgDomain(int $userCourseId,
        bool $withDeleted = true): ?array
    {
        // TODO find a better way
        // magic string
        $domainName = 'Culture générale';
        $domain = $this->getDomainByUserCourseAndName($userCourseId,
            $domainName, $withDeleted);
        return $domain;
    }


}
