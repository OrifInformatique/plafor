<?php


function getSubjectsAndModulesList(int $userCourseId,
    ?string $selectedDomain = null): array
{
    $defaultList = getSubjectsAndModulesListAll($userCourseId);
    if (is_null($selectedDomain)) {
        return $defaultList;
    }
    if ($selectedDomain === 'modules') {
        $list[lang('Grades.modules')] = getModules($userCourseId,
            willBeFiltered: true);

        return $list;
    }
    $domainModel = model('TeachingDomainModel');
    $getDomain = match ($selectedDomain) {
        'tpi' => [$domainModel, 'getTpiDomain'],
        'cbe' => [$domainModel, 'getCbeDomain'],
        'ecg' => [$domainModel, 'getEcgDomain'],
        default => null,
    };
    if (is_null($getDomain)) return $defaultList;
    $domain = $getDomain($userCourseId, withDeleted: true);
    if (empty($domain)) return $defaultList;
    $subjectModel = model('TeachingSubjectModel');
    $subjectIds = $subjectModel->getTeachingSubjectIdByDomain($domain['id']);
    $filteredSubjectId = array_filter($subjectIds, fn($row) =>
        !has8GradesOrMore($userCourseId, $row));

    return getSubjects($filteredSubjectId);
}

function getSubjectsAndModulesListAll(int $userCourseId): array
{
    $list[lang('Grades.subjects')] = getSubjectsAll($userCourseId);
    $list[lang('Grades.modules')] = getModules($userCourseId);
    return $list;
}



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

// selectedDomain: tpi, cbe, ecg, modules
function getSelectedEntryForSubject(int $userCourseId,
    string $selectedDomain): ?string
{
    $domainModel = model('TeachingDomainModel');
    // match selectedDomain
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

// selectedDomain: tpi, cbe, ecg, modules
function getSelectedEntry(int $userCourseId, string $selectedDomain): ?string
{
    if ($selectedDomain === 'modules') {
        return getSelectedEntryForModules($userCourseId);
    }
    return getSelectedEntryForSubject($userCourseId, $selectedDomain);
}

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
