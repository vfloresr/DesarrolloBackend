<?php
$module_name = 'mo_Accesos';
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
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL2' => 
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
          1 => 'assigned_user_name',
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'oportunidades_web',
            'label' => 'LBL_OPORTUNIDADES_WEB',
          ),
          1 => 
          array (
            'name' => 'oportunidades_fugados',
            'label' => 'LBL_OPORTUNIDADES_FUGADOS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'oportunidades_cross',
            'label' => 'LBL_OPORTUNIDADES_CROSS',
          ),
          1 => 
          array (
            'name' => 'oportunidades_recompra',
            'label' => 'LBL_OPORTUNIDADES_RECOMPRA',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'solicitud_boton',
            'label' => 'LBL_SOLICITUD_BOTON',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'comment' => 'Date record last modified',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'tarea_cartera',
            'label' => 'LBL_TAREA_CARTERA',
          ),
          1 => 
          array (
            'name' => 'tarea_proximos',
            'label' => 'LBL_TAREA_PROXIMOS',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'tarea_retorno',
            'label' => 'LBL_TAREA_RETORNO',
          ),
          1 => 
          array (
            'name' => 'tarea_cumpleanios',
            'label' => 'LBL_TAREA_CUMPLEANIOS',
          ),
        ),
      ),
    ),
  ),
);
?>
