<?php


/**
 * Retrieves the list of subjects and modules for a usercourse. The array
 * is formatted for grade save view
 *
 * @param int $userCourseId ID of the user's course
 * @param string|null $selectedDomain Selected domain (tpi, cbe, ecg,
 * modules)
 * @return array List of subjects and modules
 */
function getSubjectsAndModulesList(int $userCourseId,
    ?string $selectedDomain = null): array
{
    $defaultList = getSubjectsAndModulesListAll($userCourseId,
        willBeFiltered: true);

    if (is_null($selectedDomain)) return $defaultList;
    if ($selectedDomain === 'modules') {
        return getModulesInArrayKey($userCourseId);
    }
    $domainModel = model('TeachingDomainModel');
    // TODO magic string find better way
    $getDomain = match ($selectedDomain) {
        'tpi' => [$domainModel, 'getTpiDomain'],
        'cbe' => [$domainModel, 'getCbeDomain'],
        'ecg' => [$domainModel, 'getEcgDomain'],
        default => null,
    };
    if (is_null($getDomain)) return $defaultList;
    $domain = $getDomain($userCourseId, withDeleted: true);
    if (empty($domain)) return $defaultList;
    return getSubjectsByDomainId($domain['id'], $userCourseId);
}

function getSubjectsByDomainId(int $domainId, int $userCourseId): array
{
    $subjectModel = model('TeachingSubjectModel');
    $subjectIds = $subjectModel->getTeachingSubjectIdByDomain($domainId);
    $filteredSubjectId = array_filter($subjectIds, fn($row) =>
        !has8GradesOrMore($userCourseId, $row));

    $list[lang('Grades.subjects')] = getSubjects($filteredSubjectId);
    return $list;
}

function getSubjectsOrModulesListByGradeId(int $gradeId): array
{
    assert($gradeId > 0);
    $gradeModel = model('GradeModel');
    $grade = $gradeModel->select('fk_teaching_module, '
        . 'fk_teaching_subject, fk_user_course')
        ->allowCallbacks(false)
        ->withDeleted()
        ->find($gradeId);

    assert(!empty($grade));
    if (isset($grade['fk_teaching_module'])) {
        return getModulesInArrayKey($grade['fk_user_course']);
    }
    assert(!is_null($grade['fk_teaching_subject']));
    $subjectModel = model('TeachingSubjectModel');
    $subject = $subjectModel
        ->select('fk_teaching_domain')
        ->allowCallbacks(false)
        ->find($grade['fk_teaching_subject']);

    assert(!empty($subject));
    return getSubjectsByDomainId($subject['fk_teaching_domain'],
        $grade['fk_user_course']);
}

function getModulesInArrayKey(int $userCourseId): array
{
    $list[lang('Grades.modules')] = getModules($userCourseId);
    return $list;
}

/**
 * Retrieves the list of all subjects and modules for a user course. The array
 * is formatted for grade save view.
 *
 * @param int $userCourseId ID of the user's course
 * @param bool $willBeFiltered Whether the list will be filtered
 *
 * @return array List of all subjects and modules
 */
function getSubjectsAndModulesListAll(int $userCourseId,
    ?bool $willBeFiltered = false): array
{
    $list[lang('Grades.subjects')] = getSubjectsAll($userCourseId,
        $willBeFiltered);

    $list[lang('Grades.modules')] = getModules($userCourseId,
        $willBeFiltered);

    return $list;
}



/**
 * Retrieves the list of all subjects for a user course. The array is formatted
 * for grade save view.
 *
 * @param int $userCourseId ID of the user's course
 * @param bool $willBeFiltered Whether the list will be filtered
 *
 * @return array List of all subjects
 */
function getSubjectsAll(int $userCourseId, bool $willBeFiltered = false): array
{
    $domainModel = model('TeachingDomainModel');
    $domainIds = $domainModel
        ->getTeachingDomainIdByUserCourse($userCourseId);

    $subjectModel = model('TeachingSubjectModel');

    $subjectIdsPerDomain = array_map(fn($courseId)
        => $subjectModel->getTeachingSubjectIdByDomain($courseId), $domainIds);

    $subjectIds = array_reduce($subjectIdsPerDomain,
        fn($carry, $row) => [...$carry, ...$row], []);

    if ($willBeFiltered) {
        $filteredSubject = array_filter($subjectIds, fn($row) =>
            !has8GradesOrMore($userCourseId, $row));
    } else {
        $filteredSubject = $subjectIds;
    }

    return getSubjects($filteredSubject);
}

/**
 * Retrieves the list of subjects for a given list of subject IDs. The array is
 * formatted for grade save view.
 *
 * @param array $subjectIds List of subject IDs
 *
 * @return array List of subjects
 */
function getSubjects(array $subjectIds): array
{
    $subjectModel = model('TeachingSubjectModel');
    // [0] => [subjectId, name]
    $subjectIdsWithNames = array_map(fn($subjectId) =>
        [$subjectId, $subjectModel->select('name')->allowCallbacks(false)
        ->find($subjectId)['name'] ?? ' '], $subjectIds);

    $formatedSubjects = array_reduce($subjectIdsWithNames,
        fn($carry, $row) => [...$carry, 's' . $row[0] => $row[1]], []);

    asort($formatedSubjects);
    return $formatedSubjects;
}

/**
 * Checks if a user has a grade for a given module.
 *
 * @param int $userCourseId ID of the user's course
 * @param int $moduleId ID of the module
 *
 * @return bool Whether the user has a grade for the module
 */
function hasGrade(int $userCourseId, int $moduleId): bool
{
    $gradeModel = model('GradeModel');
    $grade = $gradeModel->select('id')
          ->where('fk_user_course', $userCourseId)
          ->where('fk_teaching_module', $moduleId)
          ->allowCallbacks(false)
          ->first();
      return !empty($grade);
}

/**
 * Checks if a user has 8 or more grades for a given subject.
 *
 * @param int $userCourseId ID of the user's course
 * @param int $subjectId ID of the subject
 *
 * @return bool Whether the user has 8 or more grades for the subject
 */
function has8GradesOrMore(int $userCourseId, int $subjectId): bool
{
    $gradeModel = model('GradeModel');
    $grade = $gradeModel->select('id')
          ->where('fk_user_course', $userCourseId)
          ->where('fk_teaching_subject', $subjectId)
          ->allowCallbacks(false)
          ->findAll();

    return count($grade) >= 8;
}

/**
 * Retrieves the list of modules for a user course. The array is formatted for
 * grade save view.
 *
 * @param int $userCourseId ID of the user's course
 * @param bool $willBeFiltered Whether the list will be filtered
 *
 * @return array List of modules
 */
function getModules(int $userCourseId, bool $willBeFiltered = false): array
{
    $domainModel = model('TeachingDomainModel');

    $domainIds = $domainModel
        ->getTeachingDomainIdByUserCourse($userCourseId);

    $moduleModel = model('TeachingModuleModel');

    $modulesPerDomain = array_map(
        fn($domainId) => $moduleModel->getByTeachingDomainId($domainId),
        $domainIds);

    $modules = array_reduce($modulesPerDomain,
        fn($carry, $row) => [...$carry, ...$row], []);

    if ($willBeFiltered) {
        $filteredModules = array_filter($modules,
            fn($row) => !hasGrade($userCourseId, $row['id']));
    } else {
        $filteredModules = $modules;
    }

    $formatedModules = array_reduce($filteredModules, fn($carry, $row) =>
        [...$carry, 'm' . $row['id'] => $row['module_number'] . ' '
        .$row['official_name']], []);

    asort($formatedModules);
    return $formatedModules;
}

/**
 * Retrieves the apprentice's id and username for a course.
 *
 * @param int $userCourseId ID of the user's course
 * @return array Apprentice's information
 */
function getApprentice(int $userCourseId): ?array
{
    $userCourseModel = model('UserCourseModel');
    $raw = $userCourseModel->select('fk_user')->find($userCourseId);
    $userId = $raw['fk_user'] ?? null;
    if (is_null($userId)) return null;
    $userModel = model('User_model');
    $user = $userModel->select('id, username')->find($userId);
    return $user;
}

/**
 * Retrieves the selected entry for a given subject.
 *
 * @param int $userCourseId ID of the user's course
 * @param string $selectedDomain Selected domain (tpi, cbe, ecg, modules)
 *
 * @return string|null Selected entry
 */
function getSelectedEntryForSubject(int $userCourseId,
    string $selectedDomain): ?string
{
    $domainModel = model('TeachingDomainModel');
    // match selectedDomain
    // TODO magic string find better way
    $getDomain = match ($selectedDomain) {
        'tpi' => [$domainModel, 'getTpiDomain'],
        'cbe' => [$domainModel, 'getCbeDomain'],
        'ecg' => [$domainModel, 'getEcgDomain'],
        default => null,
    };
    if (is_null($getDomain)) {
        assert(false,
            'Unimplemented domain or unknown label for domain in match');
        return null;
    }
    $domain = $getDomain($userCourseId, withDeleted: true);
    if (empty($domain)) return null;
    $subjectModel = model('TeachingSubjectModel');
    $subjects = $subjectModel->getTeachingSubjectIdByDomain($domain['id']);
    $formatedId = 's' . $subjects[0];
    return $formatedId;
}


/**
 * Retrieves the selected entry for modules.
 *
 * @param int $userCourseId ID of the user's course
 *
 * @return string|null Selected entry
 */
function getSelectedEntryForModules(int $userCourseId): ?string
{
    $domainModel = model('TeachingDomainModel');
    $domain = $domainModel->getITDomain($userCourseId, withDeleted: true);
    if (empty($domain)) return null;
    $moduleModel = model('TeachingModuleModel');
    $modules = $moduleModel->getByTeachingDomainId($domain['id']);
    $formatedId = 'm' . $modules[0]['id'];
    return $formatedId;
}

/**
 * Retrieves the selected entry for a given domain.
 *
 * @param int $userCourseId ID of the user's course
 * @param string $selectedDomain Selected domain (tpi, cbe, ecg, modules)
 *
 * @return string|null Selected entry
 */
function getSelectedEntry(int $userCourseId, string $selectedDomain): ?string
{
    if ($selectedDomain === 'modules') {
        return getSelectedEntryForModules($userCourseId);
    }
    return getSelectedEntryForSubject($userCourseId, $selectedDomain);
}

/**
 * Checks if a grade is in a given course.
 *
 * @param int $userCourseId ID of the user's course
 * @param int $gradeId ID of the grade
 *
 * @return bool Whether the grade is in the course
 */
function isGradeInCourse(int $userCourseId, int $gradeId): bool
{
    if ($gradeId === 0) return true;
    $gradeModel = model('GradeModel');
    $grade = $gradeModel
        ->select('fk_user_course')
        ->allowCallbacks(false)
        ->withDeleted()
        ->find($gradeId);
    assert(!empty($gradeId), 'grade not found');
    if (empty($grade)) return false;
    $courseIdOfGrade = $grade['fk_user_course'];
    $isAuthorised = $courseIdOfGrade == $userCourseId;
    return $isAuthorised;
}

/**
 * Retrieves the course plan name for a given user course.
 *
 * @param int $userCourseId ID of the user's course
 *
 * @return string|null Course plan name
 */
function getCoursePlanName(int $userCourseId): ?string
{
    $coursePlanModel = model('CoursePlanModel');
    $coursePlanId = $coursePlanModel
        ->getCoursePlanIdByUserCourse($userCourseId);
    $coursePlan = $coursePlanModel
        ->select('official_name')
        ->withDeleted()
        ->find($coursePlanId);
    assert(!empty($coursePlan));
    return $coursePlan['official_name'] ?? null;

}

/**
 * Adds a subject to a list of subjects.
 *
 * @param int $subjectId ID of the subject
 * @param array $subjects List of subjects
 *
 * @return array List of subjects with the added subject
 */
function addSubject(int $subjectId, array $subjects): array
{
    $subjectModel = model('TeachingSubjectModel');
    $formatedSubjectId = 's'. $subjectId;
    $subject = $subjectModel
        ->select('name')
        ->allowCallbacks(false)
        ->find($subjectId);
    assert(!empty($subject));
    $subjectName = $subject['name'] ?? ' ';
    $subjects[lang('Grades.subjects')][$formatedSubjectId]
        = $subjectName;
    return $subjects;

}

/**
 * Adds a module to a list of modules.
 *
 * @param int $moduleId ID of the module
 * @param array $modules List of modules
 *
 * @return array List of modules with the added module
 */
function addModule(int $moduleId, array $modules): array
{
    $moduleModel = model('TeachingModuleModel');
    $formatedModuleId = 'm'. $moduleId;
    $module = $moduleModel
        ->select('official_name, module_number')
        ->allowCallbacks(false)
        ->withDeleted()
        ->find($moduleId);
    assert(!empty($module));
    $formatedModuleId = 'm' . $moduleId;
    $modules[lang('Grades.modules')][$formatedModuleId] =
        "$module[module_number] $module[official_name]";
    return $modules;
}

/**
 * Adds a grade to a list of grades.
 *
 * @param int $gradeId ID of the grade
 * @param array $formatedList List of grades
 *
 * @return array List of grades with the added grade
 */
function addHimself(int $gradeId, array $formatedList): array
{
    if ($gradeId == 0) return $formatedList;
    $gradeModel = model('GradeModel');
    $grade = $gradeModel
        ->select('fk_teaching_module, fk_teaching_subject')
        ->allowCallbacks(false)
        ->withDeleted()
        ->find($gradeId);
    assert(!empty($grade));
    if (empty($grade)) return $formatedList;
    assert(!is_null($grade['fk_teaching_module'])
        || !is_null($grade['fk_teaching_subject']));
    if (is_null($grade['fk_teaching_module'])) {
        return addSubject($grade['fk_teaching_subject'], $formatedList);
    } else {
        return addModule($grade['fk_teaching_module'], $formatedList);
    }
}

/**
 * Rounds a number to the nearest half point (e.g. 4.25 -> 4.5).
 *
 * @param float $number The number to round.
 * @return float The rounded number.
 */
function roundHalfPoint(float $number): float
{
    return mround($number, 0.5);
}

/**
 * Rounds a number to one decimal point (e.g. 4.25 -> 4.3).
 *
 * @param float $number The number to round.
 * @return float The rounded number.
 */
function roundOneDecimalPoint(float $number): float
{
    return mround($number, 0.1);
}

/**
 * This is clone of the MROUND function in Microsoft Excel
 * mround returns a number rounded to the desired multiple.
 * @param float $number The value to round.
 * @param float $multiple. The multiple to which you want to round number.
 */
function mround(float $number, float $multiple): float
{
    assert($multiple > 0);
    // floating-point precision issues. Result is more precise if multiple is >
    // 1
    if ($multiple < 1) {
        $multiple = 1 / $multiple;
        return round($number * $multiple) / $multiple;
    }
    return round($number / $multiple) * $multiple;
}

/**
 * Retrieves the rounding function for a given model and ID.
 *
 * @param CodeIgniter\Model $model The model to use
 * @param int $id The ID to use to retrieve the rounding function
 *
 * @return callable The rounding function
 */
function getRoundFunction(CodeIgniter\Model $model, int $id): callable
{
    $data = $model->select('round_multiple')
                ->allowCallbacks(false)
                ->find($id);

    assert(!empty($data));
    assert(!empty($data['round_multiple']));
    $multiple = $data['round_multiple'];
    return function(float $number) use ($multiple): float
    {
        return mround($number, $multiple);
    };
}

/**
 * Retrieves the rounding function for a given subject.
 *
 * @param int $subjectId ID of the subject
 *
 * @return callable Rounding function
 */
function getSubjectRoundFunction(int $subjectId): callable
{
    $subjectModel = model('TeachingSubjectModel');
    return getRoundFunction($subjectModel, $subjectId);
}

/**
 * Retrieves the rounding function for a given domain.
 *
 * @param int $domainId ID of the domain
 *
 * @return callable Rounding function
 */
function getDomainRoundFunction(int $domainId): callable
{
    $domainModel = model('TeachingDomainModel');
    return getRoundFunction($domainModel, $domainId);
}

