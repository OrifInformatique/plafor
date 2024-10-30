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
     * @param int $userCourseId ID of the user_course
     * @param int $subjectId ID of the subject
     *
     * @return array
     *
     */
    public function getApprenticeSubjectGrades(int $userCourseId,
        int $subjectId): array
    {
        $data = $this
            ->select('grade.fk_user_course, grade.fk_teaching_subject, '
                . 'grade.date, grade.grade, grade.archive, '
                . 'teaching_subject.name')
            ->join('teaching_subject',
            'teaching_subject.id = fk_teaching_subject', 'left')
            ->join('user_course', 'user_course.id = fk_user_course ', 'left')
            ->where('fk_user_course = ', $userCourseId)
            ->where('fk_teaching_subject = ', $subjectId)
            ->allowCallbacks(false)
            ->find();
        return $data;
    }

    /**
     * Gets all grades from all modules, from a specific user_course
     * (apprentice).
     *
     * @param int $userCourseId ID of the user_course
     * @param bool $isSchool "true" to get only school modules grades
     *                        "false" to get only non school modules grades
     *                        NULL to get all modules grades
     *
     * @return array
     *
     */
    public function getApprenticeModulesGrades(int $userCourseId,
        ?bool $isSchool = null): array
    {
        $this->select('grade.fk_user_course, grade.fk_teaching_module, '
            . 'grade.date, grade.grade,' . 'grade.is_school, '
            . 'grade.archive, teaching_module.official_name')
            ->join('teaching_module',
            'teaching_module.id = fk_teaching_module', 'left')
            ->join('user_course', 'user_course.id = fk_user_course ', 'left')
            ->where('fk_user_course = ', $userCourseId)
            ->where('fk_teaching_module is not null')
            ->where('fk_teaching_subject is null')
            ->allowCallbacks(false);
        if (isset($isSchool)) $this->where('is_school = ', $isSchool);
        $data = $this->find();
        if (isset($isSchool)) {
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
     * @param int $userCourseId ID of the user_course
     * @param int $moduleId ID of the module
     *
     * @return array
     *
     */
    public function getApprenticeModuleGrade(int $userCourseId,
        int $moduleId): array
    {
        $data = $this->select('grade.fk_user_course, '
            . 'grade.fk_teaching_module, grade.date, grade.grade, '
            . 'grade.is_school, grade.archive, teaching_module.official_name')
            ->join('teaching_module',
            'teaching_module.id = fk_teaching_module', 'left')
            ->join('user_course', 'user_course.id = fk_user_course ', 'left')
            ->where('fk_user_course = ', $userCourseId)
            ->where('fk_teaching_module = ', $moduleId)
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
    private function getAverageFromArray(array $grades): ?float
    {
        $onlyGrades = array_map(fn($row) => $row['grade'], $grades);
        $sum = array_sum($onlyGrades);
        if (count($onlyGrades) === 0) return null;
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
     * @param int $userCourseId The ID of the user course.
     * @param int $subjectId The ID of the subject.
     * @param callable|null $roundMethod A callback function to round the
     * average grade. Defaults to rounding to one decimal point.
     * @return float The average grade of the apprentice for the subject.
     *
     * Example usage:
     * $data = $gradeModel->getApprenticeSubjectAverage($userCourseId,
     *     $subjectId, [$gradeModel, 'roundHalfPoint']);
     */
    public function getApprenticeSubjectAverage(int $userCourseId,
        int $subjectId, ?callable $roundMethod = null): ?float
    {
        $grades = $this
            ->getApprenticeSubjectGrades($userCourseId, $subjectId);
        if (count($grades) === 0) return null;
        $average = $this->getAverageFromArray($grades);
        $roundMethod = $roundMethod ?? [$this, 'roundOneDecimalPoint'];
        return $roundMethod($average);

    }

    /**
     * Calculates the average grade of an apprentice for a module.
     *
     * @param int $userCourseId The ID of the user course.
     * @param bool|null $isSchool Whether to consider school grades or not.
     * Defaults to null.
     * @param callable|null $roundMethod A callback function to round the
     * average grade. Defaults to rounding to one decimal point.
     * @return float The average grade of the apprentice for the module.
     *
     * Example usage:
     * $data = $gradeModel->getApprenticeModuleAverage($userCourseId,
     *     $isSchool, [$gradeModel, 'roundHalfPoint']);
     */
    public function getApprenticeModuleAverage(int $userCourseId,
        ?bool $isSchool = null, ?callable $roundMethod = null): ?float
    {
        $grades = $this->getApprenticeModulesGrades($userCourseId,
            $isSchool);
        $average = $this->getAverageFromArray($grades);
        if (is_null($average)) return null;
        $roundMethod = $roundMethod ?? [$this, 'roundOneDecimalPoint'];
        return $roundMethod($average);
    }


    /**
     * Calculates the weighted average grade module for a user's course.
     *
     * This method retrieves the grades for school and inter-enterprise
     * modules, calculates their respective averages, and then combines them
     * using the defined weights.
     *
     * @param int $userCourseId The ID of the user's course.
     * @param callable|null $roundMethod A callback function to round the
     * result. Defaults to rounding to one decimal point.
     *
     * @return float The weighted average grade.
     */
    public function getWeightedModuleAverage(int $userCourseId,
        ?callable $roundMethod = null): ?float
    {
        // Define the weights for school and inter-enterprise modules
        $schoolWeight = config('\Plafor\Config\PlaforConfig')->SCHOOL_WEIGHT;
        $externWeight = config('\Plafor\Config\PlaforConfig')->EXTERN_WEIGHT;

        // Retrieve school module grades for the user's course
        $schoolGrades = $this->getApprenticeModulesGrades($userCourseId, true);
        $schoolAverage = $this->getAverageFromArray($schoolGrades);
        // Retrieve inter-enterprise module grades for the user's course
        $externGrades = $this
            ->getApprenticeModulesGrades($userCourseId, false);
        $externAverage = $this->getAverageFromArray($externGrades);
        // Calculate the weighted average grade
        $schoolWeight = empty($schoolGrades) ? 0 : $schoolWeight;
        $externWeight = empty($externGrades) ? 0 : $externWeight;
        if (($schoolWeight + $externWeight) == 0) return null;
        $average = ($schoolWeight * $schoolAverage + $externWeight
            * $externAverage) / ($schoolWeight + $externWeight);
        // potenration between epsic module and interentreprise module
        $roundMethod = $roundMethod ?? [$this, 'roundOneDecimalPoint'];
        return $roundMethod($average);
    }


    /**
     * Calculates the average grade of an apprentice in a specific domain this
     * is not a module.
     *
     * @param int $userCourseId The ID of the user's course.
     * @param int $domainId The ID of the domain.
     * @param callable|null $roundMethod A callback function to round the
     * result. Defaults to null.
     *
     * @return float|null The average grade of the apprentice in the domain, or
     * null if no grade are available.
     */
    public function getApprenticeDomainAverageNotModule(int $userCourseId,
        int $domainId, ?callable $roundMethod = null): ?float
    {
        $subjectModel = model('TeachingSubjectModel');
        $subjectIds = $subjectModel
            ->getTeachingSubjectIdByDomain($domainId);
        $subjectAverages = array_map(fn($id) =>
            $this->getApprenticeSubjectAverage($userCourseId, $id),
            $subjectIds);
        $subjectAveragesWithoutNull = array_filter($subjectAverages,
            fn($average) => !is_null($average));
        if (count($subjectAveragesWithoutNull) === 0) return null;
        $averageDomain = array_sum($subjectAveragesWithoutNull) /
            count($subjectAveragesWithoutNull);
        $roundMethod = $roundMethod ?? [$this, 'roundOneDecimalPoint'];
        return $roundMethod($averageDomain);
    }

    /**
     * Calculates the overall average grade of an apprentice.
     *
     * This method takes into account both the module grade and the domain
     * grades, weighted according to their respective weights.
     *
     * @param int $userCourseId The ID of the user's course.
     *
     * @return float The overall average grade of the apprentice, rounded to
     * one decimal point.
     */
    public function getApprenticeAverage(int $userCourseId): ?float
    {
        // get module grade
        $moduleGrade = $this->getWeightedModuleAverage($userCourseId);

        // get other module
        $teachingDomainModel = model('TeachingDomainModel');

        $domainIds = $teachingDomainModel
            ->getTeachingDomainIdByUserCourse($userCourseId);

        // [0] => [id, weight]
        $domainIdsAndWeights = array_map(
            function($domainId) use ($teachingDomainModel)
            {
                $domainWeightRaw = $teachingDomainModel
                    ->select('domain_weight')
                    ->find($domainId);
                $domainWeight = $domainWeightRaw['domain_weight'] ?? 0;
                return [$domainId, $domainWeight];
            }, $domainIds);

        // [0] => [grade, weight]
        $domainGradesAndWeight = array_map(
            function($domainIdAndWeight) use ($userCourseId)
            {
                $grade = $this
                    ->getApprenticeDomainAverageNotModule($userCourseId,
                        $domainIdAndWeight[0]);
                return [$grade, $domainIdAndWeight[1]];
            }, $domainIdsAndWeights);

        $domainGradesAndWeightWithoutZeroGrade = array_filter(
            $domainGradesAndWeight, fn($row) => $row[0] != 0);

        $domainGradeSum = array_reduce($domainGradesAndWeightWithoutZeroGrade,
            fn($sum, $gradeAndWeight)
            => $sum + ($gradeAndWeight[0] ?? 0) * $gradeAndWeight[1], 0);

        $domainWeightsSum = array_reduce(
            $domainGradesAndWeightWithoutZeroGrade,
            fn($sum, $gradeAndWeight) => $sum +  $gradeAndWeight[1], 0);

        $ITWeight = $teachingDomainModel->getITDomainWeight($userCourseId);

        $maybeNullITWeight = $moduleGrade == 0 ? 0 : $ITWeight;

        $weightModuleGrade = $moduleGrade * $maybeNullITWeight;

        $sumWithModule = $domainGradeSum + $weightModuleGrade;

        $sumWeight = $domainWeightsSum + $maybeNullITWeight;

        if ($sumWeight == 0) return null;

        $weightedExistSum = $sumWithModule / $sumWeight;

        return $this->roundOneDecimalPoint($weightedExistSum);
    }


    /**
     * Retrieves the data required for the school report view
     * (school_report.php).
     *
     * This method collects various grades and data for a given user course ID,
     * including the overall average grade, module grades, TPI grade, CBE
     * grade, and ECG grade.
     *
     * @param int $userCourseId The ID of the user's course.
     *
     * @return array An array containing the school report data.
     */
    public function getSchoolReportData(int $userCourseId): array
    {
        $data['cfc_average'] = $this->getApprenticeAverage($userCourseId);
        $data['modules'] = $this->getModuleArrayForView($userCourseId);
        $data['tpi_grade'] = $this->getTpiGradeForView($userCourseId);
        $data['cbe'] = $this->getCbeGradeForView($userCourseId);
        $data['ecg'] = $this->getEcgGradeForView($userCourseId);
        return $data;
    }


    /**
     * Retrieves the module data for the school report view
     * (school_report.php).
     *
     * This method collects and formats the module grades and averages for both
     * school and non-school modules,
     * including their respective weightings and overall average.
     *
     * @param int $userCourseId The ID of the user's course.
     *
     * @return array An array containing the formatted module data.
     */
    public function getModuleArrayForView(int $userCourseId): array
    {
        // potenration between epsic module and interentreprise module
        $schoolWeight = config('\Plafor\Config\PlaforConfig')->SCHOOL_WEIGHT;
        $externWeight = config('\Plafor\Config\PlaforConfig')->EXTERN_WEIGHT;

        $schoolModules = $this
            ->getApprenticeModulesGradesForView($userCourseId, isSchool: true);
        $noSchoolModules = $this
            ->getApprenticeModulesGradesForView($userCourseId,
                isSchool: false);
        $modules['school']['modules'] = $schoolModules;
        $modules['school']['weighting'] = intval($schoolWeight * 100);
        $modules['school']['average'] = $this
            ->getApprenticeModuleAverage($userCourseId, isSchool: true);
        $modules['non-school']['modules'] = $noSchoolModules;
        $modules['non-school']['weighting'] = intval($externWeight * 100);
        $modules['non-school']['average'] = $this
            ->getApprenticeModuleAverage($userCourseId, isSchool: false);
        $teachingDomainModel = model('TeachingDomainModel');
        $modules['weighting'] = intval($teachingDomainModel
            ->getITDomainWeight($userCourseId) * 100);
        $modules['average'] = $this
            ->getWeightedModuleAverage($userCourseId);
        return $this->putDefaultDataForModuleView($modules);
    }

    /**
     * Formats the module data for the school report view by adding default
     * values for missing data.
     *
     * This method ensures that the module data array has the required
     * structure and values, even if some data is missing, by adding default
     * values for empty or non-existent keys.
     *
     * @param array $viewModules The module data array to be formatted.
     *
     * @return array The formatted module data array with default values for
     * missing data.
     */
    private function putDefaultDataForModuleView(array $viewModules): array
    {
        if (empty($viewModules['school']['modules'])) {
            // module not found
            $viewModules['school']['modules'][0]['name'] = ' ';
            // module not found
            $viewModules['school']['modules'][0]['number'] = ' ';
        }
        if (empty($viewModules['non-school']['modules'])) {
            // module not found
            $viewModules['non-school']['modules'][0]['name'] = ' ';
            // module not found
            $viewModules['non-school']['modules'][0]['number'] = ' ';
        }
        if (empty($viewModules['school']['weighting'])) {
            // weighting not found
            $viewModules['school']['weighting'] = ' ';
        }
        if (empty($viewModules['non-school']['weighting'])) {
            // weighting not found
            $viewModules['non-school']['weighting'] = ' ';
        }
        if (empty($viewModules['school']['modules'][0]['grade'])) {
            // grade not found
            $viewModules['school']['modules'][0]['grade']['id'] = ' ';
            // grade not found
            $viewModules['school']['modules'][0]['grade']['value'] = ' ';
        }
        if (empty($viewModules['non-school']['modules'][0]['grade'])) {
            // grade not found
            $viewModules['non-school']['modules'][0]['grade']['id'] = ' ';
            // grade not found
            $viewModules['non-school']['modules'][0]['grade']['value'] = ' ';
        }
        return $viewModules;
    }

    /**
     * Retrieves the module grades for an apprentice, filtered by school or
     * non-school modules.
     *
     * This method retrieves the module grades for a given user course ID, with
     * optional filtering by school or non-school modules.
     *
     * @param int $userCourseId The ID of the user's course.
     * @param bool|null $isSchool Whether to filter by school modules (true) or
     * non-school modules (false). Defaults to null.
     *
     * @return array An array of module grades, with each module containing its
     * number, name, and grade details.
     */
    public function getApprenticeModulesGradesForView(int $userCourseId,
        ?bool $isSchool = null): array
    {
        $this->select('teaching_module.module_number as number, '
            . ' teaching_module.official_name as name, grade.id, '
            . ' grade.grade as value')
            ->join('teaching_module',
            'teaching_module.id = fk_teaching_module', 'left')
            ->join('user_course', 'user_course.id = fk_user_course ', 'left')
            ->where('fk_user_course = ', $userCourseId)
            ->where('fk_teaching_module is not null')
            ->where('fk_teaching_subject is null')
            ->allowCallbacks(false);
        if (isset($isSchool)) $this->where('grade.is_school = ', $isSchool);
        $data = $this->find();
        $mapedData = array_map(function ($record) {
            $record['grade']['id'] = $record['id'];
            $record['grade']['value'] = $record['value'];
            unset($record['id'], $record['value']);
            return $record;
        }, $data);
        return $mapedData;
    }

    /**
     * Retrieves the TPI grade for an apprentice, formatted for the school
     * report view (school_report.php).
     *
     * This method retrieves the TPI domain for a given user course ID and
     * returns the corresponding grade in a format suitable for display in the
     * school report view.
     *
     * @param int $userCourseId The ID of the user's course.
     *
     * @return array|null An array containing the TPI grade ID and value, or
     * null if the TPI domain is not found.
     */
    public function getTpiGradeForView(int $userCourseId): ?array
    {
        $teachingDomainModel = model('TeachingDomainModel');
        $domain = $teachingDomainModel->getTpiDomain($userCourseId);
        if (is_null($domain)) return null;
        $grade = $this->getApprenticeDomainAverageNotModule($userCourseId,
            $domain['id']);
        $gradeId = $this->getGradeIdForDomain($userCourseId, $domain['id']);
        return [
            'id' => $gradeId,
            'value' => $grade
        ];
    }


    /**
     * Retrieves the grade ID for a given domain and user course.
     *
     * This method retrieves the grade ID associated with a specific domain and
     * user course ID.
     *
     * @param int $userCourseId The ID of the user's course.
     * @param int $domainId The ID of the domain.
     *
     * @return int|null The grade ID, or null if not found.
     */
    public function getGradeIdForDomain(int $userCourseId,
        int $domainId): ?int
    {
        $subjectModel = model('TeachingSubjectModel');
        [$subjectId] = $subjectModel->getTeachingSubjectIdByDomain($domainId);
        $id = $this
            ->select('id')
            ->where('fk_user_course', $userCourseId)
            ->where('fk_teaching_subject', $subjectId)
            ->allowCallbacks(false)
            ->first()['id'] ?? null;
        return $id;
    }


    /**
     * Retrieves the domain grade data for a given user course, formatted for
     * the school report view (school_report.php).
     *
     * This method retrieves the domain data, including subject grades,
     * weighting, and average, and formats it for display in the school report
     * view.
     *
     * @param int $userCourseId The ID of the user's course.
     * @param callable $getDomain A callback function to retrieve the domain
     * data.
     *
     * @return array|null An array containing the formatted domain grade data,
     * or null if the domain is not found.
     */
    private function getDomainGradeForView(int $userCourseId,
        callable $getDomain): ?array
    {
        $domain = $getDomain($userCourseId);
        if (is_null($domain)) return null;
        $teachingSubjectModel = model('TeachingSubjectModel');
        $subjectIds = $teachingSubjectModel
            ->getTeachingSubjectIdByDomain($domain['id']);
        $subjectsWithGrades = array_map(fn($subjectId) => $this
                ->getApprenticeSubjectGradesForView($userCourseId, $subjectId),
            $subjectIds);
        $data['subjects'] = $subjectsWithGrades;
        $data['weighting'] = intval($domain['domain_weight'] * 100);
        $data['average'] = $this->getApprenticeDomainAverageNotModule(
            $userCourseId, $domain['id']);
        return $this->putDefaultDataForDomainGradeView($data);
    }


    /**
     * Formats the domain grade data for the school report view
     * (school_report.php) by adding default values for missing grades.
     *
     * This method ensures that each subject has exactly 8 grades, adding
     * default values for any missing grades, to ensure proper display in the
     * school report view.
     *
     * @param array $data The domain grade data to be formatted.
     *
     * @return array The formatted domain grade data with default values for
     * missing grades.
     */
    private function putDefaultDataForDomainGradeView(array $data): array
    {
        if (empty($data['subjects'])) {
            $data['subjects'][0] =
                ['name' => ' ',
                'weighting' => ' ',
                'grades' => []
                ];
        }
        $data['subjects'] = array_map(function ($subject) {
            assert(count($subject['grades']) <= 8);
            while (count($subject['grades']) !== 8)
            {
                $subject['grades'][] = [
                    'id' => ' ',
                    'value' => ' ', # grade not found
                ];

            }
            return $subject;
        }, $data['subjects']);
        return $data;
    }

    /**
     * Retrieves the CBE grade data for a given user course, formatted for the
     * school report view (school_report.php).
     *
     * This method retrieves the CBE domain data and formats it for display in
     * the school report view.
     *
     * @param int $userCourseId The ID of the user's course.
     *
     * @return array|null An array containing the formatted CBE grade data, or
     * null if the CBE domain is not found.
     */
    public function getCbeGradeForView(int $userCourseId): ?array
    {
        $teachingDomainModel = model('TeachingDomainModel');
        return $this->getDomainGradeForView($userCourseId,
            [$teachingDomainModel, 'getCbeDomain']);
    }

    /**
     * Retrieves the grades for a given subject and user course, formatted for
     * the school report view (school_report.php).
     *
     * This method retrieves the subject data, including its name, weighting,
     * grades, and average, and formats it for display in the school report
     * view.
     *
     * @param int $userCourseId The ID of the user's course.
     * @param int $subjectId The ID of the subject.
     *
     * @return array An array containing the formatted subject data, including
     * its grades and average.
     */
    public function getApprenticeSubjectGradesForView(int $userCourseId,
        int $subjectId): array
    {
        $teachingSubjectModel = model('TeachingSubjectModel');
        $subject = $teachingSubjectModel
            ->select('name, subject_weight as weighting')
            ->allowCallbacks(false)
            ->find($subjectId);
        $subject['grades'] = $this
            ->select('id, grade as value')
            ->where('fk_teaching_subject', $subjectId)
            ->where('fk_user_course', $userCourseId)
            ->allowCallbacks(false)
            ->orderBy('date')
            ->findAll();
        $subject['weighting'] = intval($subject['weighting'] * 100);
        $subject['average'] = $this->getApprenticeSubjectAverage($userCourseId,
            $subjectId);
        return $subject;

    }

    /**
     * Retrieves the ECG grade data for a given user course, formatted for the
     * school report view (school_report.php).
     *
     * This method retrieves the ECG domain data and formats it for display in
     * the school report view.
     *
     * @param int $userCourseId The ID of the user's course.
     *
     * @return array|null An array containing the formatted ECG grade data, or
     * null if the ECG domain is not found.
     */
    public function getEcgGradeForView(int $userCourseId): ?array
    {
        $teachingDomainModel = model('TeachingDomainModel');
        return $this->getDomainGradeForView($userCourseId,
            [$teachingDomainModel, 'getEcgDomain']);
    }

}
