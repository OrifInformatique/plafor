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
    protected $allowedFields    = ['domain_weight', 'is_eliminatory'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    protected $deletedField  = 'archive';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = ['getDomains'];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function getDomains(array $data) {
        $data['data'] = $this
            ->select('teaching_domain.id, title, official_name as '
            . 'course_plan_name, domain_weight, is_eliminatory, '
            . 'teaching_domain.archive')
            ->join('teaching_domain_title',
                'teaching_domain.id = fk_teaching_domain_title', 'left')
            ->join('course_plan', 'course_plan.id = fk_course_plan', 'left')
            ->allowCallbacks(false)->find($data['id']);
        $data['returnData'] = true;
        return $data;
    }
}
