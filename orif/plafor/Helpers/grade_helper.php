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
        fn($carry, $row) => [...$carry, $row[0] => $row[1]], []);

    sort($formatedSubjects);

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
        [...$carry,
        $row['id'] => $row['module_number'] . ' ' .$row['official_name']], []);

    sort($formatedModules);
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
