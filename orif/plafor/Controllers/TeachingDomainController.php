<?php
/**
 * Controller who manage Domain, Modules and Subjects grades
 * Required level connected
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use User\Config\UserConfig; // Test
use Config\UserConfig as ConfigUserConfig; // Test

class TeachingDomainController extends \App\Controllers\BaseController{

    // Class Constant
    const m_ERROR_MISSING_PERMISSIONS = "\User/errors/403error";

    /**
     * Method to initialize controller attributes
     */
    public function initController(\CodeIgniter\HTTP\RequestInterface $request,
                                    \CodeIgniter\HTTP\ResponseInterface $response,
                                    \Psr\Log\LoggerInterface $logger) : void {

        $this->access_level = "@";
        parent::initController($request, $response, $logger);

        // Loads required models
        $this->m_teaching_domain_model        = model("TeachingDomainModel");
        $this->m_teaching_subject_model       = model("TeachingSubjectModel");
        $this->m_teaching_module_model        = model("TeachingModuleModel");
        $this->m_teaching_domain_module_model = model("TeachingDomainModuleModel");
        $this->m_teaching_domain_title_model  = model("TeachingDomainTitleModel");
        $this->m_user_course_model            = model("UserCourseModel");
        $this->m_user_model                   = model("User_model");
        $this->m_course_plan_model            = model("CoursePlanModel");
        $this->m_grade_model                  = model("GradeModel");

        helper("AccessPermissions_helper");
    }



    /**
     * Return a view with all Domains titles
     *
     * @param  bool $with_archived   => false, witout the archived domain (Default)
     *                              => true, show the archived domain
     *
     * @return string|RedirectResponse
     * @return string|RedirectResponse
     */
    public function getAllDomainsTitle(bool $with_archived = false) : string|RedirectResponse {
    public function getAllDomainsTitle(bool $with_archived = false) : string|RedirectResponse {
        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        $with_archived = $this->request->getGet("wa") ?? false;

        $domain_title = [];
        // Get all Domain titles
        foreach ($this->m_teaching_domain_title_model->withDeleted($with_archived)->findAll() as $title){
            $domain_title [] = [
                "id"                    => $title["id"],
                "domain_title"          => $title["title"],
                "archive"               => $title["archive"],
            ];
        }

        $data_to_view["domains_title"] = $domain_title;

        return $this->display_view("\Plafor/domain/title/view", $data_to_view);
    }



    /**
     * Add/Update a teaching domain title
     *
     * @param  int $domain_title_id     => ID of the teaching domain (default = 0)
     *
     * @return string|RedirectResponse
     * @return string|RedirectResponse
     */
    public function saveTeachingDomainTitle(int $domain_title_id = 0) : string|RedirectResponse {
    public function saveTeachingDomainTitle(int $domain_title_id = 0) : string|RedirectResponse {

        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        // Redirect to the last URL if Domain title ID doesn't exist
        if (is_null($domain_title_id)){
            return redirect()->to(previous_url());
        }

        if(count($_POST) > 0) {

            $data_to_model = [
                "id"                        => $domain_title_id,
                "title"                     => $this->request->getPost("domain_title"),
            ];

            // dd($data_to_model);
            $this->m_teaching_domain_title_model->save($data_to_model);

            // Return to previous page if there is NO error
            if ($this->m_teaching_domain_model->errors() == null) {
                return redirect()->to("plafor/teachingdomain/getAllDomainsTitle");
            }
        }

        $data_from_model = $this->m_teaching_domain_model->find($domain_title_id);

        $data_to_view = [
            "title"                 => $domain_title_id == 0 ? lang("Grades.create_domain_title") : lang("Grades.update_domain_title"),
            "domain_title_id"       => $domain_title_id,
            "domain_title"          => $data_from_model["title"] ?? null,
            "errors"                => $this->m_teaching_domain_model->errors()
        ];

        // Return to the current view if subject_id is OVER 0, for update
        // OR return to the current view if there is ANY error with the model
        // OR empty $_POST
        return $this->display_view("\Plafor/domain/title/save", $data_to_view);
    }



    /**
     * Alterate a trainer_apprentice link depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int $domain_title_id ID of the domain.
     *
     * @param int $action Action to ally on the domain title.
     *      - 1 for deactivating (soft delete)
     *      - 2 for deleting (hard delete)
     *      - 3 for reactivating
     *
     * @return string|RedirectResponse
     *
     */
    public function deleteTeachingDomainTitle(int $action = null, int $domain_title_id = 0, bool $confirm = false): string|RedirectResponse
    {
        if(!isCurrentUserAdmin())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $domain_title = $this->m_teaching_domain_title_model->withDeleted()->find($domain_title_id);

        if(is_null($domain_title) || !isset($action))
            return redirect()->to(previous_url());

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
                if(!empty($this->m_teaching_domain->where("fk_teaching_domain_title", $domain_title_id)->findAll()))
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
     * Add/Update a teaching domain
     *
     * @param int $domain_id        => ID of the teaching domain (default = 0)
     * @param int $course_plan_id   => ID of the course plan (default = 0)
     *
     * @return string|RedirectResponse
     * @return string|RedirectResponse
     */
    public function saveTeachingDomain(int $course_plan_id = 0, int $domain_id = 0) : string|RedirectResponse {
    // TODO : Create new domain title and link with this entry (when creating a domain title inside saveTeachingDomain)
    public function saveTeachingDomain(int $course_plan_id = 0, int $domain_id = 0) : string|RedirectResponse {

        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        $course_plan = $this->m_course_plan_model->withDeleted()->find($course_plan_id);

        // Redirect to the last URL if course plan ID doesn"t exist
        if (empty($course_plan)){
            return redirect()->to(previous_url());
        }

        if(count($_POST) > 0) {
            $domain_name = !empty($this->request->getPost("new_domain_name")) ? 
                $this->request->getPost("new_domain_name") : $this->request->getPost("domain_name");

            $data_to_model = [
                "id"                        => $domain_id,
                "fk_course_plan"            => $course_plan_id,
                "fk_teaching_domain_title"  => $domain_name,
                "domain_weight"             => $this->request->getPost("domain_weight") ? $this->request->getPost("domain_weight") / 100 : null,
                "is_eliminatory"            => $this->request->getPost("is_domain_eliminatory") ?? false
            ];

            $this->m_teaching_domain_model->save($data_to_model);

            // Return to previous page if there is NO error
            if ($this->m_teaching_domain_model->errors() == null) {
                return redirect()->to("plafor/courseplan/view_course_plan/" . $course_plan_id);
            }
        }

        $parent_course_plan = [
            "id" => $course_plan_id,
            "official_name" => $course_plan["official_name"],
        ];

        $titles = [];
        foreach ($this->m_teaching_domain_title_model->findAll() as $domain_title) {
            $titles[$domain_title["id"]] = $domain_title["title"];
        }

        $data_from_model = $this->m_teaching_domain_model->find($domain_id);

        $data_to_view = [
            "title"                         => $domain_id == 0 ? lang("Grades.create_domain") : lang("Grades.update_domain"),
            "domain_id"                     => $domain_id,
            "parent_course_plan"            => $parent_course_plan,
            "domain_name"                   => $this->m_teaching_domain_model->find($domain_id)["title"] ?? null,
            "domain_names"                  => $titles,
            "domain_weight"                 => $data_from_model["domain_weight"] ?? null,
            "is_domain_eliminatory"         => $data_from_model["is_eliminatory"] ?? null,
            "is_domain_archived"            => !isset($data_from_model["archive"]) ? false : true,
            "is_domain_archived"            => !isset($data_from_model["archive"]) ? false : true,
            "errors"                        => $this->m_teaching_domain_model->errors()
        ];

        // Return to the current view if subject_id is OVER 0, for update
        // OR return to the current view if there is ANY error with the model
        // OR empty $_POST
        return $this->display_view("\Plafor/domain/save", $data_to_view);
    }



    /**
     * Alterate a teaching domain depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int $domain_id ID of the domain.
     *
     * @param int $action Action to apply on the teaching domain.
     *      - 1 for deactivating (soft delete)
     *      - 2 for deleting (hard delete)
     *      - 3 for reactivating
     *
     * @return string|RedirectResponse
     *
     */
    public function deleteTeachingDomain(int $action = null, int $domain_id = 0, bool $confirm = false): string|RedirectResponse
    {
        if(!isCurrentUserAdmin())
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
     * Add/Update a teaching subject
     *
     * @param int $subject_id   => ID of the teaching subject (default = 0)
     * @param int $domain_id    => ID of the teaching domain (default = 0)
     *
     * @return string|RedirectResponse
     * @return string|RedirectResponse
     */
    public function saveTeachingSubject(int $domain_id = 0, int $subject_id = 0) : string|RedirectResponse {
    public function saveTeachingSubject(int $domain_id = 0, int $subject_id = 0) : string|RedirectResponse {

        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        $teaching_domain = $this->m_teaching_domain_model->withDeleted()->find($domain_id);

        // Redirect to the last URL if domain ID doesn"t exist
        if (empty($teaching_domain)){
            return redirect()->to(previous_url());
        }

        $course_plan_id = $this->m_teaching_domain_model->find($domain_id)["fk_course_plan"];

        if(count($_POST) > 0) {

            $data_to_model = [
                "id"                    => $subject_id,
                "fk_teaching_domain"    => $domain_id,
                "name"                  => $this->request->getPost("subject_name"),
                "subject_weight"        => $this->request->getPost("subject_weight") ? $this->request->getPost("subject_weight") / 100 : null,
            ];

            $this->m_teaching_subject_model->save($data_to_model);

            // Return to previous page if there is NO error
            if ($this->m_teaching_subject_model->errors() == null) {

                return redirect()->to("plafor/courseplan/view_course_plan/" . $course_plan_id);
            }
        }

        // Get a list with all Subjects and Domains
        $data_from_model = $this->m_teaching_subject_model->find($subject_id);

        $data_to_view = [
            "title"                     => $subject_id == 0 ? lang("Grades.create_subject") : lang("Grades.update_subject"),
            "parent_domain"             => ["id" => $domain_id, "name" => $teaching_domain["title"]],
            "subject_id"                => $subject_id,
            "subject_name"              => $data_from_model["name"] ?? null,
            "subject_weight"            => $data_from_model["subject_weight"] ?? null,
            "is_subject_archived"       => !isset($data_from_model['archive']) ? false : true,
            "is_subject_archived"       => !isset($data_from_model['archive']) ? false : true,
            "parent_course_plan_id"     => $course_plan_id,
            "errors"                    => $this->m_teaching_subject_model->errors()
        ];

        // Return to the current view if subject_id is OVER 0, for update
        // OR return to the current view if there is ANY error with the model
        // OR empty $_POST
        return $this->display_view("\Plafor/subject/save", $data_to_view);
    }



    /**
     * Alterate a teaching subject depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int $subject_id ID of the subject.
     *
     * @param int $action Action to apply on the teaching subject.
     *      - 1 for deactivating (soft delete)
     *      - 2 for deleting (hard delete)
     *      - 3 for reactivating
     *
     * @return string|RedirectResponse
     *
     */
    public function deleteTeachingSubject(int $action = null, int $subject_id = 0, bool $confirm = false): string|RedirectResponse
    {
        if (!isCurrentUserAdmin())
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

        switch ($action)
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
     * Return all teaching module data to a view
     *
     * @param  bool $with_archived  => false, witout the archived domain (Default)
     *                              => true, show the archived domain
     *
     * @return string|RedirectResponse
     * @return string|RedirectResponse
     */
    public function getAllTeachingModule(bool $with_archived = false) : string|RedirectResponse {
    public function getAllTeachingModule(bool $with_archived = false) : string|RedirectResponse {

        // Access permissions
        if (!isCurrentUserTrainer()){
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        // Check box with_archived using a JS script (in view)
        $with_archived = $this->request->getGet("wa") ?? false;

        $teaching_module = [];
        // Get all teaching modules of the domain
        foreach ($this->m_teaching_module_model->withDeleted($with_archived)->orderBy("module_number", "ASC")->findAll() as $module){
            $teaching_modules [] = [
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
     * Add/Update a teaching module
     *
     * @param int $module_id    => ID of the teaching module (default = 0)
     *
     * @return string|RedirectResponse
     * @return string|RedirectResponse
     */
    public function saveTeachingModule(int $module_id = 0) : string|RedirectResponse {
    public function saveTeachingModule(int $module_id = 0) : string|RedirectResponse {

        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        if(count($_POST) > 0) {
            $data_to_model = [
                "id"                    => $module_id,
                "module_number"         => $this->request->getPost("module_number"),
                "official_name"         => $this->request->getPost("module_name"),
                "version"               => $this->request->getPost("module_version"),
            ];

            $this->m_teaching_module_model->save($data_to_model);

            // Return to previous page if there is NO error
            if ($this->m_teaching_module_model->errors() == null) {
                return redirect()->to("plafor/teachingdomain/getAllTeachingModule");
            }
        }

        // Get a list with all Modules and Domains
        $data_from_model = $this->m_teaching_module_model->withDeleted()->find($module_id);

        $data_to_view = [
            "title"                 => $module_id == 0 ? lang("Grades.create_module") : lang("Grades.update_module"),
            "module_id"             => $module_id,
            "module_number"         => $data_from_model["module_number"] ?? null,
            "module_name"           => $data_from_model["official_name"] ?? null,
            "module_version"        => $data_from_model["version"] ?? null,
            "errors"                => $this->m_teaching_module_model->errors()
        ];

        // Return to the current view if module_id is OVER 0, for update
        // OR Return to the current view if there is ANY error with the model
        // OR empty $_POST
        return $this->display_view("\Plafor/module/save", $data_to_view);
    }



    /**
     * Alterate a teaching module depending on $action.
     * For every action, a action confirmation is displayed.
     *
     * @param int $module_id ID of the module.
     *
     * @param int $action Action to apply on the teaching module.
     *      - 1 for deactivating (soft delete)
     *      - 2 for deleting (hard delete)
     *      - 3 for reactivating
     *
     * @return string|RedirectResponse
     *
     */
    public function deleteTeachingModule(int $action = null, int $module_id = 0, bool $confirm = false): string|RedirectResponse
    {
        if (!isCurrentUserAdmin())
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);

        $teaching_module = $this->m_teaching_module_model->withDeleted()->find($module_id);

        if (is_null($teaching_module) || !isset($action))
            return redirect()->to(previous_url());

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

        switch ($action)
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
         * Add/Update link between a Domain and a Module
         *
         * @param  int $domain_id   => ID of the domain to link with the module (default 0)
         *
         * @return string|RedirectResponse
         * @return string|RedirectResponse
         */
        public function saveTeachingModuleLink(int $domain_id = 0) : string|RedirectResponse {
        public function saveTeachingModuleLink(int $domain_id = 0) : string|RedirectResponse {

            // Access permissions
            if (!isCurrentUserAdmin()) {
                return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
            }

            // Redirect to the last URL if domain ID doesn"t exist
            if (empty($this->m_teaching_domain_model->withDeleted()->find($domain_id))){
                return redirect()->to(previous_url());
            }

            if(count($_POST) > 0) {

                // hard delete all links between modules and this domain
                $this->m_teaching_domain_module_model->where("fk_teaching_domain", $domain_id)->delete();
                
                // foreach module_id passed, create a link
                foreach ($_POST as $module_id) {

                    $data_to_model = [
                        "fk_teaching_domain"    => $domain_id,
                        "fk_teaching_module"    => $module_id,
                    ];

                    $this->m_teaching_domain_module_model->insert($data_to_model);
                }
                
                // Return to previous page if there is NO error
                if ($this->m_teaching_domain_module_model->errors() == null) {
                    return redirect()->to("plafor/courseplan/view_course_plan/" 
                        . $this->m_teaching_domain_model->find($domain_id)["fk_course_plan"]);
                }
            }

            // Get a list with all Modules
            $list_all_modules = [];
            foreach ($this->m_teaching_module_model->withDeleted()->findAll() as $module) {
                
                // Check if there is a link between the domain and the module in the DB 
                $is_linked = false;
                $link = [
                    "fk_teaching_domain" => $domain_id,
                    "fk_teaching_module" => $module["id"],
                ];

                if (!empty($this->m_teaching_domain_module_model->withDeleted()->where($link)->find())) { 
                    $is_linked = true;
                }

                $list_all_modules[] = [
                    "id"        => $module["id"],               // ID of the module. Required.
                    "number"    => $module["module_number"],    // Nunber of the module. Required.
                    "name"      => $module["official_name"],    // Name of the module. Required.
                    "is_linked" => $is_linked,                  // Defines whether the module is linked to the domain. Required.
                ];
            }

            $data_to_view = [
                "modules"               => $list_all_modules,
                "domain_id"             => $domain_id,
                "parent_course_plan_id" => $this->m_teaching_domain_model->find($domain_id)["fk_course_plan"],
                "errors"                => $this->m_teaching_module_model->errors()
            ];

            // Return to the current view if module_id is OVER 0, for update
            // OR Return to the current view if there is ANY error with the model
            // OR empty $_POST
            return $this->display_view("\Plafor/module/link", $data_to_view);
    }
}