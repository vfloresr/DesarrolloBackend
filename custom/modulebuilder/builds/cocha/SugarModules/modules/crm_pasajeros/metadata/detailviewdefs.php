<?php
$module_name = 'crm_pasajeros';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
          1 => 
          array (
            'name' => 'tipo_pax',
            'studio' => 'visible',
            'label' => 'LBL_TIPO_PAX',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'crm_negocios_crm_pasajeros_name',
          ),
          1 => 
          array (
            'name' => 'contacto',
            'studio' => 'visible',
            'label' => 'LBL_CONTACTO',
          ),
        ),
        2 => 
        array (
          0 => 'date_entered',
          1 => 'assigned_user_name',
        ),
        3 => 
        array (
          0 => 'description',
        ),
      ),
    ),
  ),
);
?>
