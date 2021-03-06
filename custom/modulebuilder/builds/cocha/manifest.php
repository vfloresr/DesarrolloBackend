<?php
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 ********************************************************************************/


// THIS CONTENT IS GENERATED BY MBPackage.php
$manifest = array (
  0 => 
  array (
    'acceptable_sugar_versions' => 
    array (
      0 => '',
    ),
  ),
  1 => 
  array (
    'acceptable_sugar_flavors' => 
    array (
      0 => 'CE',
      1 => 'PRO',
      2 => 'ENT',
    ),
  ),
  'readme' => '',
  'key' => 'crm',
  'author' => 'Victor Flores',
  'description' => '',
  'icon' => '',
  'is_uninstallable' => true,
  'name' => 'cocha',
  'published_date' => '2015-11-11 21:22:41',
  'type' => 'module',
  'version' => 1447276961,
  'remove_tables' => 'prompt',
);


$installdefs = array (
  'id' => 'cocha',
  'beans' => 
  array (
    0 => 
    array (
      'module' => 'crm_solicitudes',
      'class' => 'crm_solicitudes',
      'path' => 'modules/crm_solicitudes/crm_solicitudes.php',
      'tab' => true,
    ),
    1 => 
    array (
      'module' => 'crm_negocios',
      'class' => 'crm_negocios',
      'path' => 'modules/crm_negocios/crm_negocios.php',
      'tab' => true,
    ),
    2 => 
    array (
      'module' => 'crm_negocio_detalle',
      'class' => 'crm_negocio_detalle',
      'path' => 'modules/crm_negocio_detalle/crm_negocio_detalle.php',
      'tab' => true,
    ),
    3 => 
    array (
      'module' => 'crm_pasajeros',
      'class' => 'crm_pasajeros',
      'path' => 'modules/crm_pasajeros/crm_pasajeros.php',
      'tab' => true,
    ),
  ),
  'layoutdefs' => 
  array (
    0 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/layoutdefs/crm_negocios_crm_negocio_detalle_crm_negocios.php',
      'to_module' => 'crm_negocios',
    ),
    1 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/layoutdefs/crm_negocios_crm_pasajeros_crm_negocios.php',
      'to_module' => 'crm_negocios',
    ),
    2 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/layoutdefs/crm_negocios_opportunities_crm_negocios.php',
      'to_module' => 'crm_negocios',
    ),
    3 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/layoutdefs/crm_negocios_tasks_crm_negocios.php',
      'to_module' => 'crm_negocios',
    ),
  ),
  'relationships' => 
  array (
    0 => 
    array (
      'meta_data' => '<basepath>/SugarModules/relationships/relationships/crm_negocios_crm_negocio_detalleMetaData.php',
    ),
    1 => 
    array (
      'meta_data' => '<basepath>/SugarModules/relationships/relationships/crm_negocios_crm_pasajerosMetaData.php',
    ),
    2 => 
    array (
      'meta_data' => '<basepath>/SugarModules/relationships/relationships/crm_negocios_opportunitiesMetaData.php',
    ),
    3 => 
    array (
      'meta_data' => '<basepath>/SugarModules/relationships/relationships/crm_negocios_tasksMetaData.php',
    ),
  ),
  'image_dir' => '<basepath>/icons',
  'copy' => 
  array (
    0 => 
    array (
      'from' => '<basepath>/SugarModules/modules/crm_solicitudes',
      'to' => 'modules/crm_solicitudes',
    ),
    1 => 
    array (
      'from' => '<basepath>/SugarModules/modules/crm_negocios',
      'to' => 'modules/crm_negocios',
    ),
    2 => 
    array (
      'from' => '<basepath>/SugarModules/modules/crm_negocio_detalle',
      'to' => 'modules/crm_negocio_detalle',
    ),
    3 => 
    array (
      'from' => '<basepath>/SugarModules/modules/crm_pasajeros',
      'to' => 'modules/crm_pasajeros',
    ),
  ),
  'language' => 
  array (
    0 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocio_detalle.php',
      'to_module' => 'crm_negocio_detalle',
      'language' => 'en_us',
    ),
    1 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocio_detalle.php',
      'to_module' => 'crm_negocio_detalle',
      'language' => 'es_es',
    ),
    2 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocio_detalle.php',
      'to_module' => 'crm_negocio_detalle',
      'language' => 'ru_ru',
    ),
    3 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocios.php',
      'to_module' => 'crm_negocios',
      'language' => 'en_us',
    ),
    4 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocios.php',
      'to_module' => 'crm_negocios',
      'language' => 'es_es',
    ),
    5 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocios.php',
      'to_module' => 'crm_negocios',
      'language' => 'ru_ru',
    ),
    6 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_pasajeros.php',
      'to_module' => 'crm_pasajeros',
      'language' => 'en_us',
    ),
    7 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_pasajeros.php',
      'to_module' => 'crm_pasajeros',
      'language' => 'es_es',
    ),
    8 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_pasajeros.php',
      'to_module' => 'crm_pasajeros',
      'language' => 'ru_ru',
    ),
    9 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocios.php',
      'to_module' => 'crm_negocios',
      'language' => 'en_us',
    ),
    10 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocios.php',
      'to_module' => 'crm_negocios',
      'language' => 'es_es',
    ),
    11 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocios.php',
      'to_module' => 'crm_negocios',
      'language' => 'ru_ru',
    ),
    12 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/Opportunities.php',
      'to_module' => 'Opportunities',
      'language' => 'en_us',
    ),
    13 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/Opportunities.php',
      'to_module' => 'Opportunities',
      'language' => 'es_es',
    ),
    14 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/Opportunities.php',
      'to_module' => 'Opportunities',
      'language' => 'ru_ru',
    ),
    15 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocios.php',
      'to_module' => 'crm_negocios',
      'language' => 'en_us',
    ),
    16 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocios.php',
      'to_module' => 'crm_negocios',
      'language' => 'es_es',
    ),
    17 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocios.php',
      'to_module' => 'crm_negocios',
      'language' => 'ru_ru',
    ),
    18 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/Tasks.php',
      'to_module' => 'Tasks',
      'language' => 'en_us',
    ),
    19 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/Tasks.php',
      'to_module' => 'Tasks',
      'language' => 'es_es',
    ),
    20 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/Tasks.php',
      'to_module' => 'Tasks',
      'language' => 'ru_ru',
    ),
    21 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocios.php',
      'to_module' => 'crm_negocios',
      'language' => 'en_us',
    ),
    22 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocios.php',
      'to_module' => 'crm_negocios',
      'language' => 'es_es',
    ),
    23 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/language/crm_negocios.php',
      'to_module' => 'crm_negocios',
      'language' => 'ru_ru',
    ),
    24 => 
    array (
      'from' => '<basepath>/SugarModules/language/application/es_es.lang.php',
      'to_module' => 'application',
      'language' => 'es_es',
    ),
    25 => 
    array (
      'from' => '<basepath>/SugarModules/language/application/en_us.lang.php',
      'to_module' => 'application',
      'language' => 'en_us',
    ),
  ),
  'vardefs' => 
  array (
    0 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/vardefs/crm_negocios_crm_negocio_detalle_crm_negocio_detalle.php',
      'to_module' => 'crm_negocio_detalle',
    ),
    1 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/vardefs/crm_negocios_crm_negocio_detalle_crm_negocios.php',
      'to_module' => 'crm_negocios',
    ),
    2 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/vardefs/crm_negocios_crm_pasajeros_crm_pasajeros.php',
      'to_module' => 'crm_pasajeros',
    ),
    3 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/vardefs/crm_negocios_crm_pasajeros_crm_negocios.php',
      'to_module' => 'crm_negocios',
    ),
    4 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/vardefs/crm_negocios_opportunities_Opportunities.php',
      'to_module' => 'Opportunities',
    ),
    5 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/vardefs/crm_negocios_opportunities_crm_negocios.php',
      'to_module' => 'crm_negocios',
    ),
    6 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/vardefs/crm_negocios_tasks_Tasks.php',
      'to_module' => 'Tasks',
    ),
    7 => 
    array (
      'from' => '<basepath>/SugarModules/relationships/vardefs/crm_negocios_tasks_crm_negocios.php',
      'to_module' => 'crm_negocios',
    ),
  ),
  'layoutfields' => 
  array (
    0 => 
    array (
      'additional_fields' => 
      array (
      ),
    ),
    1 => 
    array (
      'additional_fields' => 
      array (
      ),
    ),
    2 => 
    array (
      'additional_fields' => 
      array (
        'Opportunities' => 'crm_negocios_opportunities_name',
      ),
    ),
    3 => 
    array (
      'additional_fields' => 
      array (
        'Tasks' => 'crm_negocios_tasks_name',
      ),
    ),
  ),
);