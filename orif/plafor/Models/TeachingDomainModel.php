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

         // TODO custom rule to accept 0 1 false true
        'is_eliminatory' => 'required',
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


    protected function afterFind(array $data): array
    {
        if (is_null($data['data'])) return $data;
        $data['data'] = match ($data['method']) {
            'first' => $this->afterFindFind($data['data']),
            'find' => array_key_exists('id', $data) ?
            $this->afterFindFind($data['data']) :
            $this->afterFindFindAll($data['data']),
            'findAll' => $this->afterFindFindAll($data['data']),
            default => $data['data']
        };
        return $data;
    }

    // this call when findAll is used
    protected function afterFindFindAll(array $data): array
    {
        return array_map(fn($row) => $this->afterFindFind($row), $data);
    }

    // this call when find or first is used
    protected function afterFindFind(array $data): array
    {
        if (array_key_exists('fk_teaching_domain_title', $data)) { 
            $data['title'] = $this->select('title')
                                  ->join('teaching_domain_title',
             'teaching_domain_title.id = fk_teaching_domain_title', 'left')
             ->allowCallbacks(false)->withDeleted()
             ->find($data['id'])['title'];
        }
        if (array_key_exists('fk_course_plan', $data)) { 
            $data['course_plan_name'] = $this->select('official_name')
             ->join('course_plan', 'course_plan.id = fk_course_plan', 'left')
             ->allowCallbacks(false)->withDeleted()
             ->find($data['id'])['official_name'];
        }
        return $data;
    }

}
