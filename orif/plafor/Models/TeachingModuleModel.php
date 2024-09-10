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
     * Enhances the result data by adding related teaching domains, including
     * soft deleted ones.
     * 
     * Retrieves a list of teaching domains associated with the current
     * teaching module, including their IDs, titles, course plans, weights,
     * eliminatory status, and archive status.
     * 
     * Note: This method also retrieves soft deleted teaching domains, in
     * addition to active ones.
     * 
     * @param array $data The result data from the find or first operation.
     * @return array The enhanced result data with additional teaching domain
     * information.
     */
    protected function afterFindFind(array $data): array
    {
        if (array_key_exists('id', $data)) { 
            $teachingDomainModel = model('TeachingDomainModel');
            $data['teaching_domains'] = $teachingDomainModel
                ->select('teaching_domain.id,'
                    . ' teaching_domain.fk_teaching_domain_title,'
                    . ' teaching_domain.fk_course_plan,'
                    . ' teaching_domain.domain_weight, is_eliminatory,'
                    . ' teaching_domain.archive')
                ->join('teaching_domain_module',
                    'teaching_domain.id = fk_teaching_domain', 'left')
                ->where('fk_teaching_module = ', $data['id'])
                ->withDeleted()
                ->find();
        }
        return $data;
    }

    /**
     * Retrieves the modules associated with a teaching domain.
     *
     * @param int $domainId The ID of the teaching domain.
     * @param bool|null $withDeleted Indicates whether deleted modules should
     * be included in the results. Defaults to true.
     *
     * @return array The list of modules associated with the teaching domain.
     */
    public function getByTeachingDomainId(int $domainId, ?bool
        $withDeleted = true): array
    {
        $modules = $this->select('teaching_module.id,
            teaching_module.module_number, teaching_module.official_name,'
            . ' teaching_module.version')
             ->join('teaching_domain_module', 'teaching_module.id ='
                 . ' teaching_domain_module.fk_teaching_module', 'left')
             ->where('teaching_domain_module.fk_teaching_domain = ', $domainId)
             ->withDeleted($withDeleted)
             ->find();
        return $modules;
    }

}
