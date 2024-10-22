<?php


function getSubjectsAndModulesList(int $userCourseId): array
{
    $list[lang('Grades.subjects')] = getSubjects($userCourseId);
    $list[lang('Grades.modules')] = getModules($userCourseId);
    return $list;
}

function getSubjects(int $userCourseId): array
{
    $domainModel = model('TeachingDomainModel');

    $domainIds = $domainModel
        ->getTeachingDomainIdByUserCourse($userCourseId);

    $subjectModel = model('TeachingSubjectModel');

    $subjectIdsPerDomain = array_map(fn($courseId)
        => $subjectModel->getTeachingSubjectIdByDomain($courseId), $domainIds);

    $subjectIds = array_reduce($subjectIdsPerDomain,
        fn($carry, $row) => [...$carry, ...$row], []);

    // [0] => [subjectId, name]
    $subjectIdsWithNames = array_map(fn($subjectId) =>
        [$subjectId, $subjectModel->select('name')->allowCallbacks(false)
        ->find($subjectId)['name'] ?? ' '], $subjectIds);

    $formatedSubjects = array_reduce($subjectIdsWithNames,
        fn($carry, $row) => [...$carry, 's' . $row[0] => $row[1]], []);

    asort($formatedSubjects);

    return $formatedSubjects;

}

function getModules(int $userCourseId): array
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

    $formatedModules = array_reduce($modules, fn($carry, $row) =>
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
        assert('Unimplemented domain or unknown label for domain in match');
        return null;
    }
    $domain = $getDomain($userCourseId, withDeleted: true);
    if (empty($domain)) return null;
    // get subjects from domain -> metod
    $subjectModel = model('TeachingSubjectModel');
    $subjects = $subjectModel->getTeachingSubjectIdByDomain($domain['id']);
    // get the first subject from subjects -> [0]
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
