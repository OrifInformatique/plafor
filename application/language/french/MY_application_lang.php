<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * French translations of applcation's texts
 *
 * @author      Didier Viret
 * @link        https://github.com/OrifInformatique/ci_pack_base
 * @copyright   Copyright (c), Orif <http://www.orif.ch>
 */

// Application name
$lang['app_title']                      = 'Plateforme <br />Suivie des formations';

// Page titles
$lang['page_prefix']                    = 'Pack base';

// Date and time formats
$lang['date_format_short']              = 'd.m.Y';
$lang['datetime_format_short']          = 'd.m.Y H:i';

// Buttons
$lang['btn_cancel']                     = 'Annuler';
$lang['btn_add_m']                      = 'Nouveau';
$lang['btn_add_f']                      = 'Nouvelle';
$lang['btn_save']                       = 'Enregistrer';
$lang['btn_update']                     = 'Modifier';  
$lang['btn_details']                    = 'Détails';
$lang['btn_disable']                    = 'Désactiver';
$lang['btn_reactivate']                 = 'Réactiver';
$lang['btn_delete']                     = 'Supprimer';
$lang['btn_hard_delete']                = 'Supprimer définitivement';

// Messages
$lang['msg_err_access_denied_header']   = 'Accès interdit';
$lang['msg_err_access_denied_message']  = 'Vous n\'êtes pas autorisé à accéder à cette fonction';

// Other texts
$lang['yes']                            = 'Oui';
$lang['no']                             = 'Non';

// User levels
$lang['title_administrator']            = 'Administrateur';
$lang['title_formator']                  = 'Formateur';
$lang['title_apprentice']               = 'Apprenti';

// User Course Status
$lang['title_in_progress']              = 'En cours';
$lang['title_successful']               = 'Réussi';
$lang['title_failed']                   = 'Échouée';
$lang['title_suspended']                = 'Suspendue';
$lang['title_abandoned']                = 'Abandonnée';

// Page titles
$lang['title_apprentice_list']          = 'Liste des apprentis';
$lang['title_apprentice_update']        = 'Modifier l\'apprenti';
$lang['title_apprentice_new']           = 'Ajouter un apprenti';
$lang['title_apprentice_delete']        = 'Supprimer un apprenti';
$lang['title_course_plan_list']         = 'Liste des plans de formation';
$lang['title_course_plan_update']       = 'Modifier le plan de formation';
$lang['title_course_plan_new']          = 'Ajouter un plan de formation';
$lang['title_course_plan_delete']       = 'Supprimer le plan de formation';
$lang['title_competence_domain_list']   = 'Liste des domaines de compétence';
$lang['title_competence_domain_update'] = 'Modifier le domaine de compétence';
$lang['title_competence_domain_new']    = 'Ajouter un domaine de compétence';
$lang['title_competence_domain_delete'] = 'Supprimer le domaine de compétence';
$lang['title_operational_competence_list']
                                        = 'Liste des compétences opérationnelles';
$lang['title_operational_competence_update']
                                        = 'Modifier la compétence opérationnelle';
$lang['title_operational_competence_new']
                                        = 'Ajouter une compétence opérationnelle';
$lang['title_operational_competence_delete']
                                        = 'Supprimer la compétence opérationnelle';
$lang['title_objective_list']           = 'Liste des objectifs';
$lang['title_objective_update']         = 'Modifier l\'objectif';
$lang['title_objective_new']            = 'Ajouter un objectif';
$lang['title_objective_delete']         = 'Supprimer l\'objectif';
$lang['title_user_course_list']         = 'Liste des formations liées';
$lang['title_user_course_update']       = 'Modifer la formation liée';
$lang['title_user_course_new']          = 'Ajouter une formation liée';
$lang['title_user_course_delete']       = 'Supprimer la formation liée';

// Details labels
$lang['details_apprentice']             = 'Détail de l\'apprenti';
$lang['details_course_plan']            = 'Détail du plan de formation';
$lang['details_competence_domain']      = 'Détail du domaine de compétence';
$lang['details_operational_competence'] = 'Détail de la compétence opérationnelle';
$lang['details_objective']              = 'Détail de l\'objectif';
$lang['details_user_course']            = 'Détail de la formation de l\'apprenti';


// Fields labels
$lang['field_apprentice_username']      = 'Nom de l\'apprenti';
$lang['field_apprentice_date_creation'] = 'Date de création de l\'apprenti';
$lang['field_followed_courses']         = 'Formation(s) suivie(s)';
$lang['field_linked_competence_domains']= 'Domaines de compétences liés';
$lang['field_linked_operational_competence']
                                        = 'Compétences opérationnelles liés';
$lang['field_course_plan_formation_number']
                                        = 'Numéro du plan de formation';
$lang['field_course_plans_formation_numbers']
                                        = 'Numéros des plans de formations';
$lang['field_course_plan_official_name']
                                        = 'Nom du plan de formation';
$lang['field_course_plans_official_names']
                                        = 'Noms des plans de formation';
$lang['field_course_plans_official_names']
                                        = 'Noms des plans de formation';
$lang['field_course_plan_date_begin']   = 'Date de création du plan de formation';
$lang['field_course_plans_dates_begin'] = 'Dates de création des plans de formation';
$lang['field_competence_domain_course_plan']
                                        = 'Plan de formation lié au domaine de compétence';
$lang['field_competence_domain_symbol'] = 'Symbole du domaine de compétence';
$lang['field_competence_domains_symbols']
                                        = 'Symbole des domaines de compétences';
$lang['field_competence_domain_name']   = 'Nom du domaine de compétence';
$lang['field_competence_domains_names'] = 'Noms des domaines de compétences';
$lang['field_operational_competence_domain']
                                        = 'Domaine de compétence lié à la compétence opérationnelle';
$lang['field_operational_competence_name']
                                        = 'Nom de la compétence opérationnelle';
$lang['field_operational_competences_names']
                                        = 'Noms des compétences opérationnelles';
$lang['field_operational_competence_symbol']
                                        = 'Symbole de la compétence opérationnelle';
$lang['field_operational_competences_symbols']
                                        = 'Symboles des compétences opérationnelles';
$lang['field_operational_competence_methodologic']
                                        = 'Compétence méthodologique';
$lang['field_operational_competence_social']
                                        = 'Compétence sociale';
$lang['field_operational_competence_personal']
                                        = 'Compétence personnelle';
$lang['field_objective_operational_competence']
                                        = 'Compétence opérationnelle liée à l\'objectif';
$lang['field_objective_symbol']         = 'Symboles de l\' objectif';
$lang['field_objectives_symbols']       = 'Symbole des objectif';
$lang['field_objective_taxonomy']       = 'Taxonomie de l\'objectif';
$lang['field_objectives_taxonomies']    = 'Taxonomie des objectifs';
$lang['field_objective_name']           = 'Nom de l\'objectif';
$lang['field_objectives_names']         = 'Nom des objectifs';
$lang['field_user_course_date_begin']   = 'Date du début de la formation';
$lang['field_user_course_date_end']     = 'Date de fin de la formation';
$lang['field_user_course_course_plan']  = 'Formation';
$lang['field_user_course_status']       = 'Statut de la formation';
$lang['field_id']                       = 'Identifiant';

// Admin texts
$lang['admin_apprentices']              = 'Apprentis';
$lang['admin_course_plans']             = 'Plans de formations';
$lang['admin_competence_domains']       = 'Domaines de compétences';
$lang['admin_objectives']               = 'Objectifs';
$lang['admin_operational_competences']  = 'Compétences opérationnelles';

// Error messages
$lang['msg_err_course_plan_not_exist']  = 'Le plan de formation sélectionné n\'existe pas';
$lang['msg_err_course_plan_not_unique'] = 'Ce plan de formation est déjà utilisé, merci d\'en choisir un autre';

// Other texts
$lang['course_plan']                    = 'Plan de formation';
$lang['course_plan_delete']             = 'Supprimer ce plan de formation';
$lang['course_plan_delete_explanation'] = 'Toutes les informations concernant ce plan de formation seront supprimées.';
$lang['competence_domain']              = 'Domaine de compétence';
$lang['competence_domain_delete']       = 'Supprimer ce domaine de compétence';
$lang['competence_domain_delete_explanation']
                                        = 'Toutes les informations concernant ce domaine de compétence seront supprimées.';
$lang['operational_competence']         = 'Compétence opérationnelle';
$lang['operational_competence_delete']  = 'Supprimer cette compétence opérationnelle';
$lang['operational_competence_delete_explanation']
                                        = 'Toutes les informations concernant cette compétence opérationnelle seront supprimées.';
$lang['objective']                      = 'Objectif';
$lang['objective_delete']               = 'Supprimer cet objectif';
$lang['objective_delete_explanation']   = 'Toutes les informations concernant cet objectif seront supprimées.';
$lang['user_course']                    = 'Formation liée';
$lang['user_course_delete']             = 'Supprimer cette formation liée';
$lang['user_course_delete_explanation'] = 'Toutes les informations concernant cette formation liée seront supprimées.';
$lang['apprentice']                     = 'Apprenti';
$lang['course_status']                  = 'Status des formations';
$lang['status']                         = 'Statut de la formation';
