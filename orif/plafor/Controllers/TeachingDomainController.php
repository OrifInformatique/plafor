<?php
/**
 * Controller who manage Domain, Modules and Subjects grades
 * Required level connected
 * @author      Orif (ViDi, ThJo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Plafor\Controllers;

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

        helper("AccessPermissions_helper");
    }



    /**
     * Return a view with all Domains titles
     *
     * @param  bool $with_archived   => false, witout the archived domain (Default)
     *                              => true, show the archived domain
     *
     * @return string|Response
     */
    public function getAllDomainsTitle(bool $with_archived = false) : string|Response {
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
     * @return string|Response
     */
    public function saveTeachingDomainTitle(int $domain_title_id = 0) : string|Response {

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
     * Delete or reactivate a Teaching Domain title
     *
     * @param int $domain_title_id  => ID of the domain
     * @param int $action           => 0 = display a confirmation (default)
     *                              => 1 = soft delete
     *                              => 3 = reactivate
     *
     * @return string
     */
    public function deleteTeachingDomainTitle(int $domain_title_id = 0, int $action = 0) : string|Response {

        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        $domain_title = $this->m_teaching_domain_title_model->withDeleted()->find($domain_title_id);

        // Redirect to the last URL if subject ID doesn"t exist
        if (is_null($domain_title)){
            return redirect()->to(previous_url());
        }

        switch ($action){
            // Displays confirmation
            case 0:
                $output = [
                    "entry" => [
                        "type"    => lang("Grades.domain_title"),
                        "name"    => $domain_title["title"]
                    ],
                    "cancel_btn_url" => base_url("plafor/teachingdomain/getAllDomainsTitle")
                ];

                if($domain_title["archive"]) {

                    $output["type"] = "reactivate";
                    $output["entry"]["message"] = lang("Grades.domain_title_enable_explanation");
                }
                else {

                    $output["type"] = "disable";
                    $output["entry"]["message"] = lang("Grades.domain_title_disable_explanation");
                }

                return $this->display_view("\Common/manage_entry", $output);

            // Deactivates (soft delete) competence domain
            case 1:
                $this->m_teaching_domain_title_model->delete($domain_title_id);
                break;

            // Reactivates competence domain
            case 3:
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
     * @return string|Response
     */
    public function saveTeachingDomain(int $course_plan_id = 0, int $domain_id = 0) : string|Response {

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

            // TODO : Vérifier que soit $domain_name (dropdown option) ou soit $new_domain_name (input text) soit renseingé. Renvoyer une erreur à la vue si non.
            $domain_name = $this->request->getPost("new_domain_name") ?? $this->request->getPost("domain_name");
            d($domain_name);

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
            "errors"                        => $this->m_teaching_domain_model->errors()
        ];

        // Return to the current view if subject_id is OVER 0, for update
        // OR return to the current view if there is ANY error with the model
        // OR empty $_POST
        return $this->display_view("\Plafor/domain/save", $data_to_view);
    }



    /**
     * Delete or reactivate a Teaching Domain
     // BUG: Multiple Common list in the same page (checkbox: see disable)
     *
     * @param int $domain_id    => ID of the domain
     * @param int $action       => 0 = display a confirmation (default)
     *                          => 1 = soft delete
     *                          => 3 = reactivate
     *
     * @return string|Response
     */
    public function deleteTeachingDomain(int $domain_id, int $action = 0) : string|Response {

        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        $teaching_domain = $this->m_teaching_domain_model->withDeleted()->find($domain_id);

        // Redirect to the last URL if domain ID doesn"t exist
        if (is_null($teaching_domain)){
            return redirect()->to(previous_url());
        }

        switch ($action){
            // Displays confirmation
            case 0:
                $output = [
                    "entry" => [
                        "type"    => lang("Grades.domain"),
                        "name"    => $teaching_domain["title"]
                    ],
                    "cancel_btn_url" => base_url("plafor/courseplan/view_course_plan/" . $domain_id)
                ];

                if($teaching_domain["archive"]) {

                    $output["type"] = "reactivate";
                    $output["entry"]["message"] = lang("Grades.domain_enable_explanation");
                }
                else {

                    $output["type"] = "disable";
                    $output["entry"]["message"] = lang("Grades.domain_disable_explanation");
                }

                return $this->display_view("\Common/manage_entry", $output);

            // Deactivates (soft delete) competence domain
            case 1:
                $this->m_teaching_domain_model->delete($domain_id);
                break;

            // Reactivates competence domain
            case 3:
                $this->m_teaching_domain_model->withDeleted()->update($domain_id, ["archive" => null]);
                break;
        }

        return redirect()->to(base_url("plafor/courseplan/view_course_plan/" . $teaching_domain["fk_course_plan"]));
    }



    /**
     * Add/Update a teaching subject
     *
     * @param int $subject_id   => ID of the teaching subject (default = 0)
     * @param int $domain_id    => ID of the teaching domain (default = 0)
     *
     * @return string|Response
     */
    public function saveTeachingSubject(int $domain_id = 0, int $subject_id = 0) : string|Response {

        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        // Redirect to the last URL if domain ID doesn"t exist
        if (empty($this->m_teaching_domain_model->withDeleted()->find($domain_id))){
            return redirect()->to(previous_url());
        }

        if(count($_POST) > 0) {

            $data_to_model = [
                "id"                    => $subject_id,
                "fk_teaching_domain"    => $this->request->getPost("subject_parent_domain"),
                "name"                  => $this->request->getPost("subject_name"),
                "subject_weight"        => $this->request->getPost("subject_weight"),
            ];

            $this->m_teaching_subject_model->save($data_to_model);

            // Return to previous page if there is NO error
            if ($this->m_teaching_subject_model->errors() == null) {

                $course_plan_id = $this->m_teaching_domain_model->find($domain_id)["fk_course_plan"];

                return redirect()->to("plafor/courseplan/view_course_plan/" . $course_plan_id);
            }
        }

        // Get a list with all Subjects and Domains
        $data_from_model = $this->m_teaching_subject_model->find($subject_id);

        $data_to_view = [
            "title"                     => $subject_id == 0 ? lang("Grades.create_subject") : lang("Grades.update_subject"),
            "subject_id"                => $subject_id,
            "subject_parent_domain"     => $domain_id,
            "subject_name"              => $data_from_model["name"] ?? null,
            "subject_weight"            => $data_from_model["subject_weight"] ?? null,
            "domain_name"               => $this->m_teaching_domain_model->find($domain_id) ["title"],
            "errors"                    => $this->m_teaching_subject_model->errors()
        ];

        // Return to the current view if subject_id is OVER 0, for update
        // OR return to the current view if there is ANY error with the model
        // OR empty $_POST
        return $this->display_view("\Plafor/subject/save", $data_to_view);
    }



    /**
     * Delete or reactivate a Teaching Subject
     *
     * @param int $subject_id   => ID of the subject
     * @param int $action       => 0 = display a confirmation (default)
     *                          => 1 = soft delete
     *                          => 3 = reactivate
     *
     * @return string|Response
     */
    public function deleteTeachingSubject(int $subject_id, int $action = 0) : string|Response {

        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        $teaching_subject = $this->m_teaching_subject_model->withDeleted()->find($subject_id);

        // Redirect to the last URL if subject ID doesn"t exist
        if (is_null($teaching_subject)){
            return redirect()->to(previous_url());
        }

        switch ($action){
            // Displays confirmation
            case 0:
                $output = [
                    "entry" => [
                        "type"    => lang("Grades.subject"),
                        "name"    => $teaching_subject["name"]
                    ],
                    "cancel_btn_url" => base_url("plafor/courseplan/view_course_plan/" . $teaching_subject["fk_course_plan"] . $subject_id) // TODO: URL
                ];

                if($teaching_subject["archive"]) {

                    $output["type"] = "reactivate";
                    $output["entry"]["message"] = lang("Grades.subject_enable_explanation");
                }
                else {

                    $output["type"] = "disable";
                    $output["entry"]["message"] = lang("Grades.subject_disable_explanation");
                }

                return $this->display_view("\Common/manage_entry", $output);

            // Deactivates (soft delete) competence domain
            case 1:
                $this->m_teaching_subject_model->delete($subject_id);
                break;

            // Reactivates competence domain
            case 3:
                $this->m_teaching_subject_model->withDeleted()->update($subject_id, ["archive" => null]);
                break;
        }

        return redirect()->to(base_url("plafor/courseplan/view_course_plan/" . $teaching_subject["fk_course_plan"]));
    }



    /**
     * Return all teaching module data to a view
     *
     * @param  bool $with_archived  => false, witout the archived domain (Default)
     *                              => true, show the archived domain
     *
     * @return string|Response
     */
    public function getAllTeachingModule(bool $with_archived = false) : string|Response {

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
                "id"                    => $module["id"],
                "number_module"         => $module["module_number"],
                "name_module"           => $module["official_name"],
                "version_module"        => $module["version"],
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
     * @return string|Response
     */
    public function saveTeachingModule(int $module_id = 0) : string|Response {

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
     * Delete or reactivate a Teaching Module
     *
     * @param int $module_id    => ID of the module
     * @param int $action       => 0 = display a confirmation (default)
     *                          => 1 = soft delete
     *                          => 3 = reactivate
     *
     * @return string|Response
     */
    public function deleteTeachingModule(int $module_id, int $action = 0) : string|Response {

        // Access permissions
        if (!isCurrentUserAdmin()) {
            return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
        }

        $teaching_module = $this->m_teaching_module_model->withDeleted()->find($module_id);

        // Redirect to the last URL if subject ID doesn"t exist
        if (is_null($teaching_module)){
            return redirect()->to(previous_url());
        }

        switch ($action){
            // Displays confirmation
            case 0:
                $output = [
                    "entry" => [
                        "type"    => lang("Grades.module"),
                        "name"    => $teaching_module["module_number"] . " - " . $teaching_module["official_name"]
                    ],
                    "cancel_btn_url" => base_url("plafor/teachingdomain/getAllTeachingModule")
                ];

                if($teaching_module["archive"]) {

                    $output["type"] = "reactivate";
                    $output["entry"]["message"] = lang("Grades.module_enable_explanation");
                }
                else {

                    $output["type"] = "disable";
                    $output["entry"]["message"] = lang("Grades.module_disable_explanation");
                }

                return $this->display_view("\Common/manage_entry", $output);

            // Deactivates (soft delete) competence domain
            case 1:
                $this->m_teaching_module_model->delete($module_id);
                break;

            // Reactivates competence domain
            case 3:
                $this->m_teaching_module_model->withDeleted()->update($module_id, ["archive" => null]);
                break;
            }

            return redirect()->to(base_url("plafor/teachingdomain/getAllTeachingModule"));
        }



        /**
         * Add/Update link between a Domain and a Module
         *
         * // TODO: link between 1 domain and 1 or more modules (array of module ID)
         * @param  int $domain_id   => ID of the domain to link with the module (default 0)
         *
         * @return string|Response
         */
        public function saveTeachingModuleLink(int $domain_id = 0) : string|Response {

            // Access permissions
            if (!isCurrentUserAdmin()) {
                return $this->display_view(self::m_ERROR_MISSING_PERMISSIONS);
            }

            // Redirect to the last URL if domain ID doesn"t exist
            if (empty($this->m_teaching_domain_model->withDeleted()->find($domain_id))){
                return redirect()->to(previous_url());
            }

            if(count($_POST) > 0) {

                // Foreach module ID in the list given check if it exist, for an Update or Add a new entry
                foreach ($this->request->getPost("list_module_id") as $module_id) {
                    $link_id = 0;

                    $link_id = $this->m_teaching_domain_module_model
                    ->where([
                        "fk_teaching_domain" => $domain_id,
                        "fk_teaching_module" => $module_id
                        ])
                    ->find();

                    $data_to_model = [
                        "id"                    => $link_id,
                        "fk_teaching_domain"    => $domain_id,
                        "fk_teaching_module"    => $module_id,
                    ];

                    $this->m_teaching_domain_module_model->save($data_to_model);

                    // Déclarer un tableau contenant les données à ajouter
                    // Déclarer un tableau contenant les ids des liens à supprimer

                    // Prendre tous les modules
                    // Boucler sur chaque module
                        // Prendre les data de la checkbox corresondant au module
                        // If data exist
                        // If liaison non exisante
                        // Ajouer au tableau de création les données à créer
                        // (Rien à faire si case coché et liaison déjà existante)

                        // Else (if data don"t exist)
                        // If liaison existante
                        // Ajouter l"id de la liaison au tableau de suppression
                        // (Rien à faire si case décochée et liaison non existante)

                        // Créer toutes les entrées, en utlisant le tableau de création
                    // Supprimer toutes les entrées, en utilisant le tableau de suppression
                }

                // Return to previous page if there is NO error

                $course_plan_id = $this->m_course_plan_model->select("fk_course_plan")->find($domain_id);

                if ($this->m_teaching_domain_module_model->errors() == null) {
                    return redirect()->to("plafor/teachingdomain/view_course_plan/" . $course_plan_id); // TODO: URL
                    // return redirect()->to(previous_url()); /* ?????? c'est assassin ? */

                }
            }


            // TODO: Get the link ID between the domain and a module (can be NULL)
            $domain_links = $this->m_teaching_domain_module_model->where("fk_teaching_domain", $domain_id)->findAll();

            // Get a list with all Modules
            //$data_from_model = $this->m_teaching_module_model->withDeleted()->find($module_id); // TODO: are link deleted ??

            $data_to_view = [
            //    // TODO: return array : module_number, module_name, module_id, is_module_linked
            //    "title"                 => lang("Grades.link_domain_module"),
            //    "module_number"         => $data_from_model["module_number"],
            //    "module_name"           => $data_from_model["official_name"],
                "errors"                => $this->m_teaching_module_model->errors()
            ];

            // Return to the current view if module_id is OVER 0, for update
            // OR Return to the current view if there is ANY error with the model
            // OR empty $_POST
            return $this->display_view("\Plafor/module/link", $data_to_view);
    }
}