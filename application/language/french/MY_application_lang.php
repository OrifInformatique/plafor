<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * French translations of applcation's texts
 *
 * @author      Didier Viret
 * @link        https://github.com/OrifInformatique/ci_pack_base
 * @copyright   Copyright (c), Orif <http://www.orif.ch>
 */

// Application name
$lang['app_title']                      = 'Section informatique<br />Package de base';

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

// Page titles
$lang['title_course_plan_list']         = 'Liste des plans de formation';
$lang['title_course_plan_update']       = 'Modifier un plan de formation';
$lang['title_course_plan_new']          = 'Ajouter un plan de formation';
$lang['title_course_plan_delete']       = 'Supprimer un plan de formation';
$lang['title_competence_domain_list']   = 'Liste des domaines de compétence';
$lang['title_competence_domain_update'] = 'Modifier un domaine de compétence';
$lang['title_competence_domain_new']    = 'Ajouter un domaine de compétence';
$lang['title_competence_domain_delete'] = 'Supprimer un domaine de compétence';
$lang['title_operational_competence_list']
                                        = 'Liste des compétences opérationnelles';
$lang['title_operational_competence_update']
                                        = 'Modifier une compétence opérationnelle';
$lang['title_operational_competence_new']
                                        = 'Ajouter une compétence opérationnelle';
$lang['title_operational_competence_delete']
                                        = 'Supprimer une compétence opérationnelle';
$lang['title_objective_list']           = 'Liste des objectifs';
$lang['title_objective_update']         = 'Modifier un objectif';
$lang['title_objective_new']            = 'Ajouter un objectif';
$lang['title_objective_delete']         = 'Supprimer un objectif';

// Fields labels
$lang['field_course_plan_formation_number']
                                        = 'Numéro du plan de formation';
$lang['field_course_plan_official_name']
                                        = 'Nom du plan de formation';
$lang['field_course_plan_date_begin']   = 'Date de création du plan de formation';
$lang['field_competence_domain_course_plan']
                                        = 'Plan de formation lié au domaine de compétence';
$lang['field_competence_domain_symbol'] = 'Symbole du domaine de compétence';
$lang['field_competence_domain_name']   = 'Nom du domaine de compétence';
$lang['field_operational_competence_domain']
                                        = 'Domaine de compétence lié à la compétence opérationnelle';
$lang['field_operational_competence_name']
                                        = 'Nom de la compétence opérationnelle';
$lang['field_operational_competence_symbol']
                                        = 'Symbole de la compétence opérationnelle';
$lang['field_operational_competence_methodologic']
                                        = 'Compétence méthodologique';
$lang['field_operational_competence_social']
                                        = 'Compétence sociale';
$lang['field_operational_competence_personal']
                                        = 'Compétence personnelle';
$lang['field_objective_operational_competence']
                                        = 'Compétence opérationnelle liée à l\'objectif';
$lang['field_objective_symbol']         = 'Symbole de l\'objectif';
$lang['field_objective_taxonomy']       = 'Taxonomie de l\'objectif';
$lang['field_objective_name']           = 'Nom de l\'objectif';

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