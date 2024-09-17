<?php

namespace Plafor\Models;

use CodeIgniter\Model;

class TeachingDomainModuleModel extends Model
{
    // protected $DBGroup          = 'default';
    protected $table            = 'teaching_domain_module';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['fk_teaching_domain', 'fk_teaching_module'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'fk_teaching_domain' => 
        'is_natural_no_zero|isDomainModuleLinkUnique[]',
        'fk_teaching_module' =>
        'is_natural_no_zero|isDomainModuleLinkUnique[]',
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
     * Processing data after a search query for a single record.
     *
     * This method is called after executing a search query for a single record
     * and allows processing the retrieved data.
     * It retrieves the associated teaching domain and module information and
     * adds it to the data.
     *
     * @param array $data Array containing the retrieved data.
     * @return array Array containing the processed data with teaching domain
     * and module information.
     */
    protected function afterFindFind(array $data): array
    {
        if (array_key_exists('fk_teaching_domain', $data)) { 
            $domainModel = model('TeachingDomainModel');
            $data['teachingDomain'] = $domainModel
                ->withDeleted()
                ->find($data['fk_teaching_domain']);
            unset($data['fk_teaching_domain']);
        }
        if (array_key_exists('fk_teaching_module', $data)) { 
            $moduleModel = model('TeachingModuleModel');
            $data['teachingModule'] = $moduleModel
                ->withDeleted()
                ->find($data['fk_teaching_module']);
            unset($data['fk_teaching_module']);
        }
        return $data;
    }

    /**
     * Checks if a teaching domain is already linked to a teaching module.
     *
     * Note: This method does not check for soft deleted links.
     *
     * @param int $domainId The ID of the teaching domain.
     * @param int $moduleId The ID of the teaching module.
     *
     * @return bool True if the domain is already linked to the module, false
     * otherwise.
     */
    public function existsLinkBetweenDomainAndModule(int $domainId, int
        $moduleId): bool
    {
        $linkRecord = $this->where('fk_teaching_domain = ', $domainId)
            ->where('fk_teaching_module = ', $moduleId)
            ->allowCallbacks(false)
            ->find();
        return !empty($linkRecord);
    }

}
