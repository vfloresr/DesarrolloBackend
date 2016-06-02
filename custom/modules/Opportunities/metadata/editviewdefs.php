<?php
$viewdefs ['Opportunities'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'custom/include/javascript/validaciones.js',
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
      'javascript' => '{$PROBABILITY_SCRIPT}',
      'useTabs' => true,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => false,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 'sales_stage',
          1 => 
          array (
            'name' => 'agente_c',
            'label' => 'LBL_AGENTE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'comment' => 'Date record created',
            'label' => 'LBL_DATE_ENTERED',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'comment' => 'Date record last modified',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
        3 => 
        array (
          0 => 'description',
          1 => 
          array (
            'name' => 'resumen_c',
            'studio' => 'visible',
            'label' => 'LBL_RESUMEN',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'numero_negocio_c',
            'label' => 'LBL_NUMERO_NEGOCIO',
          ),
          1 => 
          array (
            'name' => 'amount',
          ),
        ),
      ),
    ),
  ),
);
?>
