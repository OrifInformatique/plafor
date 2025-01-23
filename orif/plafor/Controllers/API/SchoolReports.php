<?php

/**
 * API for school report resources.
 *
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */

namespace Plafor\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Response;

class SchoolReports extends ResourceController
{
    protected $format = 'json';

    /**
     * Gets all apprentices school report summaries.
     *
     * @return Response
     *
     */
    public function index(): Response
    {
        $school_reports_summaries =
        [
            "apprentices" => [],
            "trainers"    => []
        ];

        $apprentices = model("User_model")->getApprentices();

        foreach($apprentices as $apprentice)
        {
            $fk_trainer = model("TrainerApprenticeModel")
                ->where("fk_apprentice", $apprentice["id"])
                ->first()["fk_trainer"] ?? null;

            $apprentice_data =
            [
                "user_id"      => intval($apprentice["id"]),
                "username"     => $apprentice["username"],
                "fk_trainer"   => intval($fk_trainer),
                "user_courses" => []
            ];

            $user_courses = model("UserCourseModel")->where("fk_user", $apprentice["id"])->findAll();

            foreach($user_courses as $user_course)
            {
                $course_plan_official_name = model("CoursePlanModel")
                    ->find($user_course["fk_course_plan"])["official_name"];

                $user_course_data =
                [
                    "id"             => intval($user_course["id"]),
                    "official_name"  => $course_plan_official_name,
                    "global_average" => model("GradeModel")->getApprenticeAverage($user_course["id"])
                ];

                array_push($apprentice_data["user_courses"], $user_course_data);
            }

            array_push($school_reports_summaries["apprentices"], $apprentice_data);
        }

        $trainers = model("User_model")->getTrainers();

        foreach($trainers as $trainer)
        {
            $trainer_data =
            [
                "user_id"  => $trainer["id"],
                "username" => $trainer["username"],
            ];

            array_push($school_reports_summaries["trainers"], $trainer_data);
        }

        /*$school_reports_summaries =
        [
            "apprentices" =>
            [
                [
                    "user_id" => 1,
                    "username" => "Gabriel Da Costa Salgado",
                    "fk_trainer" => 1,
                    "user_courses" =>
                    [
                        [
                            "id" => 1,
                            "formation_number" => 88605,
                            "official_name" => "Opératrice en informatique / Opérateur en informatique avec CFC",
                            "global_average" => 5.0
                        ]
                    ]
                ],
                [
                    "user_id" => 2,
                    "username" => "Dylan Dervey",
                    "fk_trainer" => 2,
                    "user_courses" =>
                    [
                        [
                            "id" => 2,
                            "formation_number" => 88611,
                            "official_name" => "Informaticienne / Informaticien avec CFC, orientation développement d'applications",
                            "global_average" => 1.5
                        ],
                        [
                            "id" => 3,
                            "formation_number" => 88611,
                            "official_name" => "Informaticienne / Informaticien avec CFC, orientation explotation et infrastructure",
                            "global_average" => 5.5
                        ]
                    ]
                ],
                [
                    "user_id" => 3,
                    "username" => "Je suis un nom extrêmement long pour voir comment l'interface réagit...",
                    "fk_trainer" => 2,
                    "user_courses" =>
                    [
                        [
                            "id" => 4,
                            "formation_number" => 329868168,
                            "official_name" => "Formation",
                            "global_average" => 6
                        ]
                    ]
                ]
            ],

            "trainers" =>
            [
                [
                    "user_id" => 1,
                    "username" => "Je suis un nom de formateur extrêmement long pour voir comment l'interface réagit..."
                ],
                [
                    "user_id" => 2,
                    "username" => "Didier Viret"
                ],
                [
                    "user_id" => 3,
                    "username" => "Je suis un nom de formateur un peu long."
                ],
            ]
        ];*/

        return $this->response->setJSON($school_reports_summaries);
    }

    /**
     * Gets the report details from a specific user_course.
     *
     * @param ?int $id ID of the user course.
     *
     * @return Response
     *
     */
    public function show($id = null): Response
    {
        $user_course    = model("UserCourseModel")->find($id);
        $course_plan    = model("CoursePlanModel")->find($user_course["fk_course_plan"]);
        $apprentice     = model("User_model")->find($user_course["fk_user"]);
        $global_average = model("GradeModel")->getApprenticeAverage($user_course["id"]);

        if(!$user_course || !$course_plan || !$apprentice) return null;

        $apprentice_school_report =
        [
            "user_id"     => intval($apprentice["id"]),
            "username"    => $apprentice["username"],
            "user_course" =>
            [
                "id"               => intval($user_course["id"]),
                "official_name"    => $course_plan["official_name"],
                "date_begin"       => $user_course["date_begin"],
                "date_end"         => $user_course["date_end"],
                "global_average"   => floatval($global_average),
                "teaching_domains" => [],
                "yearly_reports"   => []
            ]
        ];

        $years = [];
        $number_of_years = date("Y", strtotime($user_course["date_end"])) - date("Y", strtotime($user_course["date_begin"]));
        $year = date("Y", strtotime($user_course["date_begin"]));

        for($i = 1; $i <= $number_of_years; $i++)
        {
            array_push($apprentice_school_report["user_course"]["yearly_reports"],
            [
                "year" => $i,
                "yearly_average" => null,
                "teaching_domains" => []
            ]);

            array_push($years,
            [
                "index" => $i - 1,
                "begin" => sprintf("%s-08-01", $year),
                "end"   => sprintf("%s-07-31", $year + 1)
            ]);

            $year += 1;
        }

        $teaching_domains_ids = model("TeachingDomainModel")->getTeachingDomainIdByUserCourse($user_course["id"]);

        foreach($teaching_domains_ids as $teaching_domain_id)
        {
            $teaching_domain = model("TeachingDomainModel")->find($teaching_domain_id);

            $teaching_domain_data =
            [
                "id"             => intval($teaching_domain_id),
                "title"          => $teaching_domain["title"],
                "weight"         => floatval($teaching_domain["domain_weight"]),
                "average"        => null,
                "is_eliminatory" => $teaching_domain["is_eliminatory"] === "1" ? true: false
            ];

            $subjects_ids = model("TeachingSubjectModel")->getTeachingSubjectIdByDomain($teaching_domain_id);

            if(!empty($subjects_ids))
            {
                $teaching_domain_data["subjects"] = [];

                foreach($subjects_ids as $subject_id)
                {
                    $subject = model("TeachingSubjectModel")->find($subject_id);
                    $subject_average = model("GradeModel")->getApprenticeSubjectAverage($teaching_domain_id, $subject_id);

                    $subject_data =
                    [
                        "id"      => intval($subject_id),
                        "name"    => $subject["name"],
                        "average" => $subject_average,
                        "grades"  => []
                    ];

                    $grades = model("GradeModel")->getApprenticeSubjectGrades($teaching_domain_id, $subject_id);

                    foreach($grades as $grade)
                    {
                        $grade_id = model("GradeModel")
                            ->select("id")
                            ->where("fk_user_course", $teaching_domain_id)
                            ->where("fk_teaching_subject", $subject_id)
                            ->find()[0]["id"];

                        $grade_data =
                        [
                            "id" => intval($grade_id),
                            "grade" => floatval($grade["grade"])
                        ];

                        foreach($years as $year)
                        {
                            if(strtotime($grade["date"]) >= strtotime($year["begin"])
                                && strtotime($grade["date"]) <= strtotime($year["end"]))
                            {
                                $yr_domains = &$apprentice_school_report["user_course"]["yearly_reports"][$year["index"]]["teaching_domains"];

                                if(!in_array($teaching_domain_data, $yr_domains))
                                {
                                    array_push($yr_domains, $teaching_domain_data);
                                }

                                $domain_index = array_search($teaching_domain_data, $yr_domains);

                                $yr_domain_subjects = &$yr_domains[$domain_index]["subjects"];

                                if(!in_array($subject_data, $yr_domain_subjects))
                                {
                                    array_push($yr_domain_subjects, $subject_data);
                                }

                                $subject_index = array_search($subject_data, $yr_domain_subjects);

                                array_push($yr_domain_subjects[$subject_index]["grades"], $grade_data);

                                $yr_domain_subjects[$subject_index]["average"] = model("GradeModel")
                                    ->getAverageFromArray($yr_domain_subjects[$subject_index]["grades"]);

                                $yr_domains[$domain_index]["average"] = array_reduce(
                                    $yr_domain_subjects, fn($sum, $subject) => $sum + $subject["average"], 0)
                                    / (count($yr_domain_subjects) ?: 1);

                                $yr_domains[$domain_index]["average"] = round($yr_domains[$domain_index]["average"] * 10) / 10;
                            }
                        }

                        array_push($subject_data["grades"], $grade_data);
                    }

                    array_push($teaching_domain_data["subjects"], $subject_data);
                }

                $teaching_domain_data["average"] = model("GradeModel")->getApprenticeDomainAverageNotModule($user_course["id"], $teaching_domain_id);
            }

            $modules = model("TeachingModuleModel")->getByTeachingDomainId($teaching_domain_id);

            if(!empty($modules))
            {
                $teaching_domain_data["modules"] = [];
                $teaching_domain_data["school_modules_average"] = 0;
                $teaching_domain_data["non_school_modules_average"] = 0;

                foreach($modules as $module)
                {
                    $module_grade = model("GradeModel")->getApprenticeModuleGrade($user_course["id"], $module["id"]);

                    $module_data =
                    [
                        "id"            => intval($module["id"]),
                        "module_number" => intval($module["module_number"]),
                        "name"          => $module["official_name"],
                        "grade"         => null,
                        "is_school"     => null,
                    ];

                    if($module_grade)
                    {
                        $module_data["grade"]     = floatval($module_grade["grade"]);
                        $module_data["is_school"] = $module_grade["is_school"] === "1" ? true : false;

                        foreach($years as $year)
                        {
                            if(strtotime($module_grade["date"]) >= strtotime($year["begin"])
                                && strtotime($module_grade["date"]) <= strtotime($year["end"]))
                            {
                                $yr_domains = &$apprentice_school_report["user_course"]["yearly_reports"][$year["index"]]["teaching_domains"];

                                if(!in_array($teaching_domain_data, $yr_domains))
                                {
                                    array_push($yr_domains, $teaching_domain_data);
                                }

                                $domain_index = array_search($teaching_domain_data, $yr_domains);

                                $yr_domain_modules = &$yr_domains[$domain_index]["modules"];

                                if(!in_array($module_data, $yr_domain_modules))
                                {
                                    array_push($yr_domain_modules, $module_data);
                                }
                            }

                            $school_weight = config('\Plafor\Config\PlaforConfig')->SCHOOL_WEIGHT;
                            $extern_weight = config('\Plafor\Config\PlaforConfig')->EXTERN_WEIGHT;

                            $yr_domains[$domain_index]["school_modules_average"] = array_reduce($yr_domain_modules,
                                fn($acc, $module) => $module["is_school"] ? $acc + $module["grade"] : null);

                            $yr_domains[$domain_index]["non_school_modules_average"] = array_reduce($yr_domain_modules,
                            fn($acc, $module) => !$module["is_school"] ? $acc + $module["grade"] : null);

                            $yr_domains[$domain_index]["average"] = ($yr_domains[$domain_index]["school_modules_average"] * $school_weight +
                                $yr_domains[$domain_index]["school_modules_average"] * $extern_weight) / ($school_weight + $extern_weight);

                            $yr_domains[$domain_index]["average"] = round($yr_domains[$domain_index]["average"] * 10) / 10;
                        }
                    }

                    array_push($teaching_domain_data["modules"], $module_data);
                }

                $teaching_domain_data["average"]                    = model("GradeModel")->getWeightedModuleAverage($user_course["id"]);
                $teaching_domain_data["school_modules_average"]     = model("GradeModel")->getApprenticeModuleAverage($user_course["id"], true);
                $teaching_domain_data["non_school_modules_average"] = model("GradeModel")->getApprenticeModuleAverage($user_course["id"], false);
            }

            /*d($teaching_domain_data);*/
            foreach($years as $year)
            {
                if(!empty($apprentice_school_report["user_course"]["yearly_reports"][$year["index"]]["teaching_domains"]))
                {
                    $domains_weight_sum = array_reduce($yr_domains, fn($sum, $domain) => $sum + $domain["weight"]);

                    $apprentice_school_report["user_course"]["yearly_reports"][$year["index"]]["yearly_average"] = array_reduce($yr_domains,
                        fn($sum, $domain) => $sum + $domain["average"] * $domain["weight"]) / $domains_weight_sum;

                        $apprentice_school_report["user_course"]["yearly_reports"][$year["index"]]["yearly_average"] =
                            round($apprentice_school_report["user_course"]["yearly_reports"][$year["index"]]["yearly_average"] * 10) / 10;
                }
            }

            array_push($apprentice_school_report["user_course"]["teaching_domains"], $teaching_domain_data);
        }

        /*$apprentice_school_report =
        [
            "user_id" => 1,
            "username" => "Gabriel Da Costa Salgado",
            "user_course" =>
            [
                "id" => 101,
                "formation_number" => 88605,
                "official_name" => "Opératrice en informatique / Opérateur en informatique avec CFC",
                "date_begin" => "2022-08-01",
                "date_end" => "2026-07-31",
                "global_average" => 5.0,
                "teaching_domains" =>
                [
                    [
                        "id" => 1,
                        "title" => "Compétences de base élargies",
                        "weight" => 0.6,
                        "average" => 5,
                        "is_eliminatory" => false,
                        "subjects" =>
                        [
                            [
                                "id" => 1,
                                "name" => "Mathématiques",
                                "average" => 2,
                                "grades" => [
                                    ["id" => 1, "grade" => 5],
                                    ["id" => 2, "grade" => 4],
                                ]
                            ],
                            [
                                "id" => 2,
                                "name" => "Anglais",
                                "average" => 3.5,
                                "grades" => [
                                    ["id" => 3, "grade" => 1],
                                    ["id" => 4, "grade" => 6],
                                ]
                            ]
                        ]
                    ],
                    [
                        "id" => 301,
                        "title" => "Informatique",
                        "weight" => 0.4,
                        "average" => 5.5,
                        "school_modules_average" => 6,
                        "non_school_modules_average" => 4.5,
                        "is_eliminatory" => true,
                        "modules" =>
                        [
                            [
                                "id" => 1,
                                "module_number" => 320,
                                "name" => "Programmer orienté objet",
                                "grade" => 4.5,
                                "is_school" => true
                            ],
                            [
                                "id" => 2,
                                "module_number" => 431,
                                "name" => "	Exécuter des mandats demandés autonome dans son propre environnement professionnel",
                                "grade" => 1,
                                "is_school" => true
                            ],
                            [
                                "id" => 3,
                                "module_number" => 187,
                                "name" => "	Mettre en service un poste de travail ICT avec le système d’exploitation",
                                "grade" => 1,
                                "is_school" => false
                            ]
                        ]
                    ]
                ],

                "yearly_reports" =>
                [
                    [
                        "year" => 1,
                        "yearly_average" => 5,
                        "teaching_domains" =>
                        [
                            [
                                "id" => 1,
                                "title" => "Compétences de base élargies",
                                "average" => 5,
                                "subjects" =>
                                [
                                    [
                                        "id" => 1,
                                        "name" => "Mathématiques",
                                        "average" => 5,
                                        "grades" =>
                                        [
                                            ["id" => 1, "grade" => 5]
                                        ]
                                    ],
                                ]
                            ],
                            [
                                "id" => 301,
                                "title" => "Informatique",
                                "average" => 1,
                                "modules" =>
                                [
                                    [
                                        "id" => 2,
                                        "module_number" => 431,
                                        "name" => "	Exécuter des mandats demandés autonome dans son propre environnement professionnel",
                                        "grade" => 1
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        "year" => 2,
                        "yearly_average" => 4.5,
                        "teaching_domains" =>
                        [
                            [
                                "id" => 1,
                                "title" => "Compétences de base élargies",
                                "average" => 5,
                                "subjects" =>
                                [
                                    [
                                        "id" => 1,
                                        "name" => "Mathématiques",
                                        "average" => 4,
                                        "grades" =>
                                        [
                                            ["id" => 2, "grade" => 4]
                                        ]
                                    ],
                                    [
                                        "id" => 2,
                                        "name" => "Anglais",
                                        "average" => 3.5,
                                        "grades" =>
                                        [
                                            ["id" => 3, "grade" => 1],
                                            ["id" => 4, "grade" => 6]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "id" => 301,
                                "title" => "Informatique",
                                "average" => 3,
                                "modules" =>
                                [
                                    [
                                        "id" => 1,
                                        "module_number" => 320,
                                        "name" => "Programmer orienté objet",
                                        "grade" => 4.5
                                    ],
                                    [
                                        "id" => 3,
                                        "module_number" => 187,
                                        "name" => "	Mettre en service un poste de travail ICT avec le système d’exploitation",
                                        "grade" => 1
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];*/

        return $this->response->setJSON($apprentice_school_report);
    }
}