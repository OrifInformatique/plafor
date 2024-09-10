<?php

namespace Plafor\Models;

use CodeIgniter\Model;

class GradeModel extends Model
{
    // protected $DBGroup          = 'default';
    protected $table            = 'grade';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['fk_user_course', 'fk_teaching_subject',
        'fk_teaching_module', 'date', 'grade', 'is_school'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    protected $deletedField  = 'archive';

    // Validation
    protected $validationRules      = [
        'fk_user_course' => 'is_natural_no_zero',
        'fk_teaching_subject' => 'required_without[fk_teaching_module]|'
            . 'is_module_xor_subject[]',
        'fk_teaching_module' => 'required_without[fk_teaching_subject]|'
            . 'is_module_xor_subject[]',
        'date' => 'valid_date',
        'grade' => 'greater_than_equal_to[0]|less_than_equal_to[6]',
        'is_school' => 'required|is_boolean_or_binary_value'
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
     * - Teaching subject name (if fk_teaching_subject is present)
     * - Teaching module name (if fk_teaching_module is present)
     * - User ID (if fk_user_course is present)
     * 
     * @param array $data The result data from the find or first operation.
     * @return array The enhanced result data with additional information.
     */
    protected function afterFindFind(array $data): array
    {
        if (isset($data['fk_teaching_subject'])) { 
            $teachingSubjectModel = model('TeachingSubjectModel');
            $data['teaching_subject_name'] = $teachingSubjectModel
                ->select('teaching_subject.name')
                ->withDeleted()
                ->find($data['fk_teaching_subject'])['name'];
        } else {
            unset($data['fk_teaching_subject']);
        }
        if (isset($data['fk_teaching_module'])) { 
            $teachingModuleModel = model('TeachingModuleModel');
            $data['teaching_module_name'] = $teachingModuleModel
                ->select('teaching_module.official_name')
                ->withDeleted()
                ->find($data['fk_teaching_module'])['official_name'];
        } else {
            unset($data['fk_teaching_module']);
        }
        if (isset($data['fk_user_course'])) { 
            $userCouseModel = model('UserCourseModel');
            $data['user_id'] = $userCouseModel
                ->withDeleted()
                ->find($data['fk_user_course'])['fk_user'];
        }
        return $data;
    }

    /**
     * Gets all grades from a specific subject, from a specific user_course
     * (apprentice).
     *
     * @param int $id_user_course ID of the user_course
     * @param int $id_subject ID of the subject
     * 
     * @return array
     *
     */
    public function getApprenticeSubjectGrades(int $id_user_course,
        int $id_subject): array
    {
        $data = $this
            ->select('grade.fk_user_course, grade.fk_teaching_subject, '
                . 'grade.date, grade.grade, grade.archive, '
                . 'teaching_subject.name')
            ->join('teaching_subject',
            'teaching_subject.id = fk_teaching_subject', 'left')
            ->join('user_course', 'user_course.id = fk_user_course ', 'left')
            ->where('fk_user_course = ', $id_user_course)
            ->where('fk_teaching_subject = ', $id_subject)
            ->allowCallbacks(false)
            ->find();
        return $data;
    }

    /**
     * Gets all grades from all modules, from a specific user_course
     * (apprentice).
     *
     * @param int $id_user_course ID of the user_course
     * @param bool $is_school "true" to get only school modules grades
     *                        "false" to get only non school modules grades
     *                        NULL to get all modules grades
     * 
     * @return array
     *
     */
    public function getApprenticeModulesGrades(int $id_user_course,
        ?bool $is_school = null): array
    {
        $this->select('grade.fk_user_course, grade.fk_teaching_module, '
            . 'grade.date, grade.grade,' . 'grade.is_school, '
            . 'grade.archive, teaching_module.official_name')
            ->join('teaching_module',
            'teaching_module.id = fk_teaching_module', 'left')
            ->join('user_course', 'user_course.id = fk_user_course ', 'left')
            ->where('fk_user_course = ', $id_user_course)
            ->where('fk_teaching_module is not null')
            ->where('fk_teaching_subject is null')
            ->allowCallbacks(false);
        if (isset($is_school)) $this->where('is_school = ', $is_school);
        $data = $this->find();
        if (isset($is_school)) {
            $data = array_map(function($row) {
                unset($row['is_school']);
                return $row;
            }, $data);
        }
        return $data;
    }

    /**
     * Gets a grade from a specific module, from a specific user_course
     * (apprentice).
     *
     * @param int $id_user_course ID of the user_course
     * @param int $id_module ID of the module
     * 
     * @return array
     *
     */
    public function getApprenticeModuleGrade(int $id_user_course,
        int $id_module): array
    {
        $data = $this->select('grade.fk_user_course, '
            . 'grade.fk_teaching_module, grade.date, grade.grade, '
            . 'grade.is_school, grade.archive, teaching_module.official_name')
            ->join('teaching_module',
            'teaching_module.id = fk_teaching_module', 'left')
            ->join('user_course', 'user_course.id = fk_user_course ', 'left')
            ->where('fk_user_course = ', $id_user_course)
            ->where('fk_teaching_module = ', $id_module)
            ->allowCallbacks(false)
            ->first();
        return $data;
    }

    /**
     * Calculates the average of an array of grades returned by the
     * getApprenticeSubjectGrades or getApprenticeModulesGrades methods.
     * 
     * @param array $grades The array of grades returned by
     * getApprenticeSubjectGrades or getApprenticeModulesGrades, where each
     * grade is an associative array containing the grade data.
     * @return float The average grade.
     */
    private function getAverageFromArray(array $grades): float
    {
        $onlyGrades = array_map(fn($row) => $row['grade'], $grades);
        $sum = array_sum($onlyGrades);
        $average = $sum / count($onlyGrades);
        return $average;
    }

    /**
     * Rounds a number to the nearest half point (e.g. 4.25 -> 4.5).
     * 
     * @param float $number The number to round.
     * @return float The rounded number.
     */
    private function roundHalfPoint(float $number): float
    {
        return round($number * 2) / 2;
    }

    /**
     * Rounds a number to one decimal point (e.g. 4.25 -> 4.3).
     * 
     * @param float $number The number to round.
     * @return float The rounded number.
     */
    private function roundOneDecimalPoint(float $number): float
    {
        return round($number * 10) / 10;
    }

    /**
     * Calculates the average grade of an apprentice for a specific subject.
     * 
     * @param int $id_user_course The ID of the user course.
     * @param int $id_subject The ID of the subject.
     * @param callable|null $round_method A callback function to round the
     * average grade. Defaults to rounding to one decimal point.
     * @return float The average grade of the apprentice for the subject.
     * 
     * Example usage:
     * $data = $gradeModel->getApprenticeSubjectAverage($id_user_course,
     *     $id_subject, [$gradeModel, 'roundHalfPoint']);
     */
    public function getApprenticeSubjectAverage(int $id_user_course,
        int $id_subject, ?callable $round_method = null): float
    {
        $grades = $this
            ->getApprenticeSubjectGrades($id_user_course, $id_subject);
        $average = $this->getAverageFromArray($grades);
        $round_method = $round_method ?? [$this, 'roundOneDecimalPoint'];
        return $round_method($average);

    }

    /**
     * Calculates the average grade of an apprentice for a module.
     * 
     * @param int $id_user_course The ID of the user course.
     * @param bool|null $is_school Whether to consider school grades or not.
     * Defaults to null.
     * @param callable|null $round_method A callback function to round the
     * average grade. Defaults to rounding to one decimal point.
     * @return float The average grade of the apprentice for the module.
     * 
     * Example usage:
     * $data = $gradeModel->getApprenticeModuleAverage($id_user_course,
     *     $is_school, [$gradeModel, 'roundHalfPoint']);
     */
    public function getApprenticeModuleAverage(int $id_user_course,
        ?bool $is_school = null, ?callable $round_method = null): float
    {
        $grades = $this->getApprenticeModulesGrades($id_user_course,
            $is_school);
        $average = $this->getAverageFromArray($grades);
        $round_method = $round_method ?? [$this, 'roundOneDecimalPoint'];
        return $round_method($average);
    }
}
