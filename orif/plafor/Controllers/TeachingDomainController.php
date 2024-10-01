<?php

/**
 * Controller who manage domains, modules and subjects.
 *
 * Access level required : logged.
 *
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 *
 */

namespace Plafor\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class TeachingDomainController extends \App\Controllers\BaseController
{
    // Class Constant
    const m_ERROR_MISSING_PERMISSIONS = "\User/errors/403error";

    /**
     * Initializes controller attributes.
     *
     * @param RequestInterface $request
     *
     * @param ResponseInterface $response
     *
     * @param LoggerInterface $logger
     *
     * @return void
     *
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        $this->access_level = "@";

        parent::initController($request, $response, $logger);

        $this->m_course_plan_model            = model("CoursePlanModel");
        $this->m_grade_model                  = model("GradeModel");
        $this->m_teaching_domain_model        = model("TeachingDomainModel");
        $this->m_teaching_domain_module_model = model("TeachingDomainModuleModel");
        $this->m_teaching_domain_title_model  = model("TeachingDomainTitleModel");
        $this->m_teaching_module_model        = model("TeachingModuleModel");
        $this->m_teaching_subject_model       = model("TeachingSubjectModel");
        $this->m_user_course_model            = model("UserCourseModel");
        $this->m_user_model                   = model("User_model");

        helper("AccessPermissions_helper");
    }



    /**
     * Shows the list of teaching domains titles.
     *
     * @return string|RedirectResponse
     *
     */
    public function getAllDomainsTitle(): string|RedirectResponse
    {
        if (!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $with_archived = $this->request->getGet("wa") ?? false;

        $domain_titles = [];

        foreach ($this->m_teaching_domain_title_model->withDeleted($with_archived)->findAll() as $title)
        {
            $domain_titles[] =
            [
                "id"                    => $title["id"],
                "domain_title"          => $title["title"],
                "archive"               => $title["archive"],
            ];
        }

        $data_to_view["domains_title"] = $domain_titles;

        return $this->display_view("\Plafor/domain/title/view", $data_to_view);
    }



    /**
     * Adds or updates a teaching domain title.
     *
     * @param int $domain_title_id  ID of the teaching domain.
     *
     * @return string|RedirectResponse
     *
     */
    public function saveTeachingDomainTitle(int $domain_title_id = 0): string|RedirectResponse
    {
        if (!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $domain_title = $this->m_teaching_domain_title_model->find($domain_title_id);

        if(count($_POST) > 0)
        {
            $data_to_model =
            [
                "id"                        => $domain_title_id,
                "title"                     => $this->request->getPost("domain_title"),
            ];

            $this->m_teaching_domain_title_model->save($data_to_model);

            if (empty($this->m_teaching_domain_model->errors()))
                return redirect()->to("plafor/teachingdomain/getAllDomainsTitle");
        }

        $data_to_view =
        [
            "title"                 => is_null($domain_title) ? lang("Grades.create_domain_title") : lang("Grades.update_domain_title"),
            "domain_title_id"       => $domain_title_id,
            "domain_title"          => $this->request->getPost("domain_title") ?? $domain_title["title"] ?? null,
            "errors"                => $this->m_teaching_domain_model->errors()
        ];

        return $this->display_view("\Plafor/domain/title/save", $data_to_view);
    }



    /**
     * Alterate a trainer_apprentice link depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int|null $action Action to ally on the domain title.
     *      - 1 for deactivating (soft delete)
     *      - 2 for deleting (hard delete)
     *      - 3 for reactivating
     *
     * @param int $domain_title_id ID of the domain.
     *
     * @param bool $confirm Defines whether the action has been confirmed.
     *
     * @return string|RedirectResponse
     *
     */
    public function deleteTeachingDomainTitle(int|null $action = null, int $domain_title_id = 0, bool $confirm = false): string|RedirectResponse
    {
        if(!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $domain_title = $this->m_teaching_domain_title_model->withDeleted()->find($domain_title_id);

        if(is_null($domain_title) || !isset($action))
            return redirect()->to(base_url("plafor/teachingdomain/getAllDomainsTitle"));

        if(!$confirm)
        {
            $output =
            [
                "entry" =>
                [
                    "type"    => lang("Grades.domain_title"),
                    "name"    => $domain_title["title"]
                ],
                "cancel_btn_url" => base_url("plafor/teachingdomain/getAllDomainsTitle")
            ];
        }

        switch($action)
        {
            // Deactivates the domain title
            case 1:
                if(!$confirm)
                {
                    $output["type"] = "disable";
                    $output["entry"]["message"] = lang("Grades.domain_title_disable_explanation");

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->m_teaching_domain_title_model->delete($domain_title_id);
                break;

            // Deletes the domain title
            case 2:
                // Prevents the hard deletion of the domain title if there are domains linked to it.
                if(!empty($this->m_teaching_domain_model->where("fk_teaching_domain_title", $domain_title_id)->findAll()))
                    return redirect()->to(base_url("plafor/teachingdomain/getAllDomainsTitle"));

                if(!$confirm)
                {
                    $output['type'] = 'delete';
                    $output['entry']['message'] = lang("Grades.domain_title_delete_explanation");

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->m_teaching_domain_title_model->delete($domain_title_id, true);
                break;

            // Reactivates the domain title
            case 3:
                if(!$confirm)
                {
                    $output["type"] = "reactivate";
                    $output["entry"]["message"] = lang("Grades.domain_title_enable_explanation");

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->m_teaching_domain_title_model->withDeleted()->update($domain_title_id, ["archive" => null]);
                break;
        }

        return redirect()->to(base_url("plafor/teachingdomain/getAllDomainsTitle"));
    }



    /**
     * Adds or updates a teaching domain.
     *
     * @param int $course_plan_id ID of the course plan.
     *
     * @param int $domain_id ID of the teaching domain.
     *
     * @return string|RedirectResponse
     *
     */
    public function saveTeachingDomain(int $course_plan_id = 0, int $domain_id = 0): string|RedirectResponse
    {
        if (!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $course_plan = $this->m_course_plan_model->withDeleted()->find($course_plan_id);

        if (is_null($course_plan))
            return redirect()->to("plafor/courseplan/view_course_plan/".$course_plan_id);

        if(count($_POST) > 0)
        {
            // TODO : Vérifier que soit $domain_name (dropdown option) ou soit $new_domain_name (input text) soit renseingé. Renvoyer une erreur à la vue si non.
            $domain_name = $this->request->getPost("new_domain_name") ?? $this->request->getPost("domain_name");

            $data_to_model =
            [
                "id"                        => $domain_id,
                "fk_course_plan"            => $course_plan_id,
                "fk_teaching_domain_title"  => $domain_name_id,
                "domain_weight"             => $this->request->getPost("domain_weight") ? $this->request->getPost("domain_weight") / 100 : null,
                "is_eliminatory"            => $this->request->getPost("is_domain_eliminatory") ?? false
            ];

            $this->m_teaching_domain_model->save($data_to_model);

            if (empty($this->m_teaching_domain_model->errors()))
                return redirect()->to("plafor/courseplan/view_course_plan/".$course_plan_id);
        }

        $parent_course_plan =
        [
            "id" => $course_plan_id,
            "official_name" => $course_plan["official_name"],
        ];

        $titles = [];

        foreach ($this->m_teaching_domain_title_model->withDeleted()->findAll() as $domain_title)
            $titles[$domain_title["id"]] = $domain_title["title"];

        $data_from_model = $this->m_teaching_domain_model->find($domain_id);

        $data_to_view =
        [
            "title"                         => $domain_id == 0 ? lang("Grades.create_domain") : lang("Grades.update_domain"),
            "domain_id"                     => $domain_id,
            "parent_course_plan"            => $parent_course_plan,
            "domain_name"                   => $this->m_teaching_domain_model->find($domain_id)["fk_teaching_domain_title"] ?? null,
            "domain_names"                  => $titles,
            "domain_weight"                 => isset($data_from_model["domain_weight"]) ? $data_from_model["domain_weight"] * 100 : null,
            "is_domain_eliminatory"         => $data_from_model["is_eliminatory"] ?? null,
            "is_domain_archived"            => !isset($data_from_model["archive"]) ? false : true,
            "is_domain_archived"            => !isset($data_from_model["archive"]) ? false : true,
            "errors"                        => $this->m_teaching_domain_model->errors()
        ];

        return $this->display_view("\Plafor/domain/save", $data_to_view);
    }



    /**
     * Alterate a teaching domain depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int|null $action Action to apply on the teaching domain.
     *      - 1 for deactivating (soft delete)
     *      - 2 for deleting (hard delete)
     *      - 3 for reactivating
     *
     * @param int $domain_id ID of the domain.
     *
     * @param bool $confirm Defines whether the action has been confirmed.
     *
     * @return string|RedirectResponse
     *
     */
    public function deleteTeachingDomain(int|null $action = null, int $domain_id = 0, bool $confirm = false): string|RedirectResponse
    {
        if(!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $teaching_domain = $this->m_teaching_domain_model->withDeleted()->find($domain_id);

        if(is_null($teaching_domain) || !isset($action))
            return redirect()->to(previous_url());

        if(!$confirm)
        {
            $output =
            [
                "entry" =>
                [
                    "type"    => lang("Grades.domain"),
                    "name"    => $teaching_domain["title"]
                ],
                "cancel_btn_url" => base_url("plafor/teachingdomain/saveTeachingDomain/".
                    $teaching_domain["fk_course_plan"].'/'.$domain_id)
            ];
        }

        $linked_subjects = $this->m_teaching_subject_model->where("fk_teaching_domain", $domain_id)->withDeleted()->findAll();
        $linked_modules  = $this->m_teaching_domain_module_model->where("fk_teaching_domain", $domain_id)->withDeleted()->findAll();

        switch($action)
        {
            // Deactivates the teaching domain
            case 1:
                if(!$confirm)
                {
                    $output["type"] = "disable";
                    $output["entry"]["message"] = lang("Grades.domain_disable_explanation");

                    return $this->display_view('\Common/manage_entry', $output);
                }

                // Disable all subjects linked to the domain
                foreach($linked_subjects as $linked_subject)
                    $this->m_teaching_subject_model->delete($linked_subject["id"]);

                // Disable links between a domain and modules
                foreach($linked_modules as $linked_module)
                    $this->m_teaching_domain_module_model->delete($linked_module["id"]);

                $this->m_teaching_domain_model->delete($domain_id);
                break;

            // Deletes the teaching domain
            case 2:
                // Prevent the hard deletion of the domain if there are subject or modules linked to it
                if(!empty($linked_subjects) || !empty($linked_modules))
                {
                    return redirect()->to(base_url("plafor/teachingdomain/saveTeachingDomain/".
                        $teaching_domain["fk_course_plan"].'/'.$domain_id));
                }

                if(!$confirm)
                {
                    $output["type"] = "delete";
                    $output["entry"]["message"] = lang("Grades.domain_delete_explanation");

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->m_teaching_domain_model->delete($domain_id, true);
                break;

            // Reactivates the teaching domain
            case 3:
                if(!$confirm)
                {
                    $output["type"] = "reactivate";
                    $output["entry"]["message"] = lang("Grades.domain_enable_explanation");

                    return $this->display_view('\Common/manage_entry', $output);
                }

                // Reactivate all subjects linked to the domain
                foreach($linked_subjects as $linked_subject)
                    $this->m_teaching_subject_model->update($linked_subject["id"], ["archive" => null]);

                // Reactivate links between a domain and modules
                foreach($linked_modules as $linked_module)
                    $this->m_teaching_domain_module_model->update($linked_module["id"], ["archive" => null]);

                $this->m_teaching_domain_model->withDeleted()->update($domain_id, ["archive" => null]);
                break;
        }

        return redirect()->to(base_url("plafor/courseplan/view_course_plan/".$teaching_domain["fk_course_plan"]));
    }



    /**
     * Adds or updates a teaching subject.
     *
     * @param int $domain_id ID of the teaching domain.
     *
     * @param int $subject_id ID of the teaching subject.
     *
     * @return string|RedirectResponse
     *
     */
    public function saveTeachingSubject(int $domain_id = 0, int $subject_id = 0) : string|RedirectResponse
    {
        if (!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $teaching_domain = $this->m_teaching_domain_model->withDeleted()->find($domain_id);

        if (empty($teaching_domain))
            return redirect()->to(previous_url());

        $course_plan_id = $this->m_teaching_domain_model->find($domain_id)["fk_course_plan"];

        if(count($_POST) > 0)
        {
            $data_to_model =
            [
                "id"                    => $subject_id,
                "fk_teaching_domain"    => $domain_id,
                "name"                  => $this->request->getPost("subject_name"),
                "subject_weight"        => $this->request->getPost("subject_weight") ? $this->request->getPost("subject_weight") / 100 : null,
            ];

            $this->m_teaching_subject_model->save($data_to_model);

            if (empty($this->m_teaching_subject_model->errors()))
                return redirect()->to("plafor/courseplan/view_course_plan/".$course_plan_id);
        }

        $data_from_model = $this->m_teaching_subject_model->find($subject_id);

        $data_to_view =
        [
            "title"                     => $subject_id == 0 ? lang("Grades.create_subject") : lang("Grades.update_subject"),
            "parent_domain"             => ["id" => $domain_id, "name" => $teaching_domain["title"]],
            "subject_id"                => $subject_id,
            "subject_name"              => $data_from_model["name"] ?? null,
            "subject_weight"            => isset($data_from_model["subject_weight"]) ? $data_from_model["subject_weight"] * 100 : null,
            "is_subject_archived"       => !isset($data_from_model['archive']) ? false : true,
            "is_subject_archived"       => !isset($data_from_model['archive']) ? false : true,
            "parent_course_plan_id"     => $course_plan_id,
            "errors"                    => $this->m_teaching_subject_model->errors()
        ];

        return $this->display_view("\Plafor/subject/save", $data_to_view);
    }



    /**
     * Alterate a teaching subject depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int|null $action Action to apply on the teaching subject.
     *      - 1 for deactivating (soft delete)
     *      - 2 for deleting (hard delete)
     *      - 3 for reactivating
     *
     * @param int $subject_id ID of the subject.
     *
     * @param bool $confirm Defines whether the action has been confirmed.
     *
     * @return string|RedirectResponse
     *
     */
    public function deleteTeachingSubject(int|null $action = null, int $subject_id = 0, bool $confirm = false): string|RedirectResponse
    {
        if (!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $teaching_subject = $this->m_teaching_subject_model->withDeleted()->find($subject_id);

        if (is_null($teaching_subject) || !isset($action))
            return redirect()->to(previous_url());

        if(!$confirm)
        {
            $output =
            [
                "entry" =>
                [
                    "type"    => lang("Grades.subject"),
                    "name"    => $teaching_subject["name"]
                ],
                "cancel_btn_url" => base_url("plafor/courseplan/view_course_plan/".
                    $teaching_subject["teaching_domain"]["fk_course_plan"])
            ];
        }

        switch($action)
        {
            // Deactivates the subject
            case 1:
                if(!$confirm)
                {
                    $output["type"] = "disable";
                    $output["entry"]["message"] = lang("Grades.subject_disable_explanation");

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->m_teaching_subject_model->delete($subject_id);
                break;

            // Deletes the subject
            case 2:
                // Prevent the hard deletion of the subject if there are grades linked to it
                if(!empty($this->m_grade_model->where("fk_teaching_module", $module_id)->findAll()))
                {
                    return redirect()->to(base_url("plafor/courseplan/view_course_plan/".
                        $teaching_subject["teaching_domain"]["fk_course_plan"]));
                }

                if(!$confirm)
                {
                    $output["type"] = "delete";
                    $output["entry"]["message"] = lang("Grades.subject_delete_explanation");

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->m_teaching_subject_model->delete($subject_id, true);
                break;

            // Reactivates the subject
            case 3:
                if(!$confirm)
                {
                    $output["type"] = "reactivate";
                    $output["entry"]["message"] = lang("Grades.subject_enable_explanation");

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->m_teaching_subject_model->withDeleted()->update($subject_id, ["archive" => null]);
                break;
        }

        return redirect()->to(base_url("plafor/courseplan/view_course_plan/".
            $teaching_subject["teaching_domain"]["fk_course_plan"]));
    }



    /**
     * Shows the list of all modules.
     *
     * @return string|RedirectResponse
     *
     */
    public function getAllTeachingModule(): string|RedirectResponse
    {
        if (!hasCurrentUserTrainerAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $with_archived = $this->request->getGet("wa") ?? false;

        $teaching_module = [];
        // Get all teaching modules of the domain
        foreach ($this->m_teaching_module_model->withDeleted($with_archived)->orderBy("module_number", "ASC")->findAll() as $module)
        {
            $teaching_modules [] =
            [
                "id"             => $module["id"],
                "number_module"  => $module["module_number"],
                "name_module"    => $module["official_name"],
                "version_module" => $module["version"],
                "archive"        => $module["archive"]
            ];
        }

        $data_to_view["modules"] = $teaching_modules;

        return $this->display_view("\Plafor/module/view", $data_to_view);
    }



    /**
     * Adds or updates a teaching module.
     *
     * @param int $module_id ID of the teaching module.
     *
     * @return string|RedirectResponse
     *
     */
    public function saveTeachingModule(int $module_id = 0): string|RedirectResponse
    {
        if (!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        if(count($_POST) > 0)
        {
            $data_to_model =
            [
                "id"                    => $module_id,
                "module_number"         => $this->request->getPost("module_number"),
                "official_name"         => $this->request->getPost("module_name"),
                "version"               => $this->request->getPost("module_version"),
            ];

            $this->m_teaching_module_model->save($data_to_model);

            if (empty($this->m_teaching_module_model->errors()))
                return redirect()->to("plafor/teachingdomain/getAllTeachingModule");
        }

        $data_from_model = $this->m_teaching_module_model->withDeleted()->find($module_id);

        $data_to_view =
        [
            "title"                 => $module_id == 0 ? lang("Grades.create_module") : lang("Grades.update_module"),
            "module_id"             => $module_id,
            "module_number"         => $data_from_model["module_number"] ?? null,
            "module_name"           => $data_from_model["official_name"] ?? null,
            "module_version"        => $data_from_model["version"] ?? null,
            "errors"                => $this->m_teaching_module_model->errors()
        ];

        return $this->display_view("\Plafor/module/save", $data_to_view);
    }



    /**
     * Alterate a teaching module depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int $action Action to apply on the teaching module.
     *      - 1 for deactivating (soft delete)
     *      - 2 for deleting (hard delete)
     *      - 3 for reactivating
     *
     * @param int $module_id ID of the module.
     *
     * @param bool $confirm Defines whether the action has been confirmed.
     *
     * @return string|RedirectResponse
     *
     */
    public function deleteTeachingModule(int|null $action = null, int $module_id = 0, bool $confirm = false): string|RedirectResponse
    {
        if (!hasCurrentUserAdminAccess())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $teaching_module = $this->m_teaching_module_model->withDeleted()->find($module_id);

        if (is_null($teaching_module) || !isset($action))
            return redirect()->to(base_url("plafor/teachingdomain/getAllTeachingModule"));

        if(!$confirm)
        {
            $output =
            [
                "entry" =>
                [
                    "type"    => lang("Grades.module"),
                    "name"    => $teaching_module["module_number"] . " - " . $teaching_module["official_name"]
                ],
                "cancel_btn_url" => base_url("plafor/teachingdomain/getAllTeachingModule")
            ];
        }

        switch($action)
        {
            // Deactivates the module
            case 1:
                if(!$confirm)
                {
                    $output["type"] = "disable";
                    $output["entry"]["message"] = lang("Grades.module_disable_explanation");

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->m_teaching_module_model->delete($module_id);
                break;

            // Deletes the module
            case 2:
                $linked_grades  = $this->m_grade_model->where("fk_teaching_module", $module_id)->findAll();
                $linked_domains = $this->m_teaching_domain_module_model->where("fk_teaching_module", $module_id)->findAll();

                // Prevent the hard deletion of the module if there are grades or domains linked to it
                if(!empty($linked_grades) || !empty($linked_domains))
                    return redirect()->to(base_url("plafor/teachingdomain/getAllTeachingModule"));

                if(!$confirm)
                {
                    $output["type"] = "delete";
                    $output["entry"]["message"] = lang("Grades.module_delete_explanation");

                    return $this->display_view('\Common/manage_entry', $output);
                }

            // Reactivates the module
            case 3:
                if(!$confirm)
                {
                    $output["type"] = "reactivate";
                    $output["entry"]["message"] = lang("Grades.module_enable_explanation");

                    return $this->display_view('\Common/manage_entry', $output);
                }

                $this->m_teaching_module_model->withDeleted()->update($module_id, ["archive" => null]);
                break;
            }

            return redirect()->to(base_url("plafor/teachingdomain/getAllTeachingModule"));
        }



        /**
         * Adds or updates links between a domain and modules
         *
         * @param int $domain_id ID of the domain.
         *
         * @return string|RedirectResponse
         *
         */
        public function saveTeachingModuleLink(int $domain_id = 0): string|RedirectResponse
        {
            if (!hasCurrentUserAdminAccess())
                return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

            if (empty($this->m_teaching_domain_model->withDeleted()->find($domain_id)))
                return redirect()->to(previous_url());

            if(count($_POST) > 0)
            {
                // hard delete all links between modules and this domain
                $this->m_teaching_domain_module_model->withDeleted()->where("fk_teaching_domain", $domain_id)->delete();
                
                unset($_POST["submitted"]);

                // foreach module_id passed, create a link
                foreach ($_POST as $module_id)
                {
                    $data_to_model =
                    [
                        "fk_teaching_domain"    => $domain_id,
                        "fk_teaching_module"    => $module_id,
                    ];

                    $this->m_teaching_domain_module_model->insert($data_to_model);
                }
                
                // Return to previous page if there is NO error
                if (empty($this->m_teaching_domain_module_model->errors()))
                    return redirect()->to("plafor/courseplan/view_course_plan/".
                        $this->m_teaching_domain_model->find($domain_id)["fk_course_plan"]);
            }

            // Get a list with all modules
            $list_all_modules = [];
            foreach ($this->m_teaching_module_model->withDeleted()->findAll() as $module) {
                
                // Check if there is a link between the domain and the module
                $is_linked = false;
                $link = [
                    "fk_teaching_domain" => $domain_id,
                    "fk_teaching_module" => $module["id"],
                ];

                if (!empty($this->m_teaching_domain_module_model->withDeleted()->where($link)->find())) 
                    $is_linked = true;

                $list_all_modules[] = [
                    "id"        => $module["id"],
                    "number"    => $module["module_number"],
                    "name"      => $module["official_name"],
                    "is_linked" => $is_linked,
                ];
            }

            $data_to_view = [
                "modules"               => $list_all_modules,
                "domain_id"             => $domain_id,
                "parent_course_plan_id" => $this->m_teaching_domain_model->find($domain_id)["fk_course_plan"],
                "errors"                => $this->m_teaching_module_model->errors()
            ];

            return $this->display_view("\Plafor/module/link", $data_to_view);
    }
}