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
            [
                "user_id" => 1,
                "username" => "Gabriel Da Costa Salgado",
                "user_courses" =>
                [
                    [
                        "id" => 1,
                        "course_plan_id" => 88605,
                        "official_name" => "Opératrice en informatique / Opérateur en informatique avec CFC",
                        "global_average" => 5.0
                    ]
                ]
            ],
            [
                "user_id" => 2,
                "username" => "Dylan Dervey",
                "user_courses" =>
                [
                    [
                        "id" => 2,
                        "course_plan_id" => 88611,
                        "official_name" => "Informaticienne / Informaticien avec CFC, orientation développement d'applications",
                        "global_average" => 1.5
                    ],
                    [
                        "id" => 3,
                        "course_plan_id" => 88611,
                        "official_name" => "Informaticienne / Informaticien avec CFC, orientation explotation et infrastructure",
                        "global_average" => 5.5
                    ]
                ]
            ],
            [
                "user_id" => 3,
                "username" => "Je suis un nom extrêmement long pour voir comment l'interface réagit...",
                "user_courses" =>
                [
                    [
                        "id" => 4,
                        "course_plan_id" => 329868168,
                        "official_name" => "Formation",
                        "global_average" => 6
                    ]
                ]
            ]
        ];

        return $this->response->setJSON($school_reports_summaries);
    }

    /**
     * Gets the latest school report details from a specific apprentice.
     * The course plan returned is the more recent user_course.
     * The user_courses with the "current" status are selected first.
     *
     * @param ?int $id ID of the user course.
     *
     * @return Response
     *
     */
    public function show($id = null): Response
    {
        $apprentice_school_report =
        [
            "user_id" => 1,
            "username" => "Gabriel Da Costa Salgado",
            "user_courses" =>
            [
                [
                    "id" => 101,
                    "course_plan_id" => 88605,
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
                            "is_eliminatory" => false,
                            "subjects" =>
                            [
                                [
                                    "id" => 1,
                                    "name" => "Mathématiques",
                                    "weight" => 0.75,
                                    "grades" => [
                                        ["id" => 1, "grade" => 5, "date" => "2023-01-31"],
                                        ["id" => 2, "grade" => 4, "date" => "2024-06-31"],
                                    ]
                                ],
                                [
                                    "id" => 2,
                                    "name" => "Anglais",
                                    "weight" => 0.25,
                                    "grades" => [
                                        ["id" => 3, "grade" => 1, "date" => "2024-01-15"],
                                        ["id" => 4, "grade" => 6, "date" => "2024-02-20"],
                                    ]
                                ]
                            ]
                        ],
                        [
                            "id" => 301,
                            "title" => "Informatique",
                            "weight" => 0.4,
                            "is_eliminatory" => true,
                            "modules" =>
                            [
                                [
                                    "id" => 1,
                                    "module_number" => 320,
                                    "name" => "Programmer orienté objet",
                                    "grade" => 4.5,
                                    "grade_date" => "2024-10-10",
                                    "is_school" => true
                                ],
                                [
                                    "id" => 2,
                                    "module_number" => 431,
                                    "name" => "	Exécuter des mandats demandés autonome dans son propre environnement professionnel",
                                    "grade" => 1,
                                    "grade_date" => "2023-03-10",
                                    "is_school" => true
                                ],
                                [
                                    "id" => 3,
                                    "module_number" => 187,
                                    "name" => "	Mettre en service un poste de travail ICT avec le système d’exploitation",
                                    "grade" => 1,
                                    "grade_date" => "2024-12-10",
                                    "is_school" => false
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $this->response->setJSON($apprentice_school_report);
    }
}