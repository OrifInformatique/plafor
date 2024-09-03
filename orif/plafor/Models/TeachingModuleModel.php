<?php

namespace Plafor\Models;

use CodeIgniter\Model;

class TeachingModuleModel extends Model
{
    // protected $DBGroup          = 'default';
    protected $table            = 'teaching_module';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['module_number', 'official_name',
        'version'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    protected $deletedField  = 'archive';

    // Validation
    protected $validationRules      = [
        'module_number' => 'is_natural_no_zero',
        'official_name' => 'string',
        'version' => 'is_natural_no_zero',
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
            'find' => array_key_exists(0, $data['data']) ?
            $this->afterFindFindAll($data['data']) :
            $this->afterFindFind($data['data']),
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
    // add teaching_domain
    protected function afterFindFind(array $data): array
    {
        if (array_key_exists('id', $data)) { 
            $teachingDomainModel = model('TeachingDomainModel');
            $data['teaching_domains'] = $teachingDomainModel
                ->join('teaching_domain_module',
                    'teaching_domain.id = fk_teaching_domain', 'left')
                ->where('fk_teaching_module = ', $data['id'])
                ->withDeleted()
                ->select('teaching_domain.id,'
                    . ' teaching_domain.fk_teaching_domain_title,'
                    . ' teaching_domain.fk_course_plan,'
                    . ' teaching_domain.domain_weight, is_eliminatory,'
                    . ' teaching_domain.archive')
                ->find();
        }
        return $data;
    }
}
