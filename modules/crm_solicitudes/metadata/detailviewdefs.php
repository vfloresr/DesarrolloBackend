<?php
$module_name = 'crm_solicitudes';
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
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'adultos',
            'label' => 'LBL_ADULTOS',
          ),
          1 => 
          array (
            'name' => 'ninos',
            'label' => 'LBL_NINOS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'destino',
            'label' => 'LBL_DESTINO',
          ),
          1 => 
          array (
            'name' => 'edades',
            'label' => 'LBL_EDADES',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'agente',
            'label' => 'LBL_AGENTE',
          ),
          1 => 
          array (
            'name' => 'canal',
            'label' => 'LBL_CANAL',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'analytics',
            'label' => 'LBL_ANALYTICS',
          ),
          1 => 
          array (
            'name' => 'agente_check',
            'label' => 'LBL_AGENTE_CHECK',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'categoria_id',
            'label' => 'LBL_CATEGORIA_ID',
          ),
          1 => 
          array (
            'name' => 'categoria_nombre',
            'label' => 'LBL_CATEGORIA_NOMBRE',
          ),
        ),
        6 => 
        array (
          0 => 'description',
          1 => 
          array (
            'name' => 'comentarios_check',
            'label' => 'LBL_COMENTARIOS_CHECK',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'fecha_flexible',
            'label' => 'LBL_FECHA_FLEXIBLE',
          ),
          1 => 
          array (
            'name' => 'fecha_viaje',
            'label' => 'LBL_FECHA_VIAJE',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'hotel',
            'label' => 'LBL_HOTEL',
          ),
          1 => 
          array (
            'name' => 'hotel_habitacion',
            'label' => 'LBL_HOTEL_HABITACION',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'pdf_joomla',
            'label' => 'LBL_PDF_JOOMLA',
          ),
          1 => 
          array (
            'name' => 'url',
            'label' => 'LBL_URL',
          ),
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'pdf_producto',
            'label' => 'LBL_PDF_PRODUCTO',
          ),
          1 => 
          array (
            'name' => 'numero_producto',
            'label' => 'LBL_NUMERO_PRODUCTO',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'hotel_tarifas',
            'label' => 'LBL_HOTEL_TARIFAS',
          ),
          1 => 
          array (
            'name' => 'precio',
            'label' => 'LBL_PRECIO',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'producto_id',
            'label' => 'LBL_PRODUCTO_ID',
          ),
          1 => 
          array (
            'name' => 'url_producto',
            'label' => 'LBL_URL_PRODUCTO',
          ),
        ),
        13 => 
        array (
          0 => '',
          1 => '',
        ),
      ),
    ),
  ),
);
?>
