<?php
$viewdefs ['Users'] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
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
      'form' => 
      array (
        'headerTpl' => 'modules/Users/tpls/DetailViewHeader.tpl',
        'footerTpl' => 'modules/Users/tpls/DetailViewFooter.tpl',
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_USER_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'LBL_USER_INFORMATION' => 
      array (
        0 => 
        array (
          0 => 'user_name',
          1 => 
          array (
            'name' => 'first_name',
            'label' => 'LBL_FIRST_NAME',
          ),
        ),
        1 => 
        array (
          0 => 'status',
          1 => 
          array (
            'name' => 'last_name',
            'label' => 'LBL_LAST_NAME',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'UserType',
            'customCode' => '{$USER_TYPE_READONLY}',
          ),
          1 => 
          array (
            'name' => 'sucursal_c',
            'studio' => 'visible',
            'label' => 'LBL_SUCURSAL',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'favorito_c',
            'label' => 'LBL_FAVORITO',
          ),
          1 => 'phone_work',
        ),
        4 => 
        array (
          0 => 'department',
          1 => 'title',
        ),
        5 => 
        array (
          0 => 'reports_to_name',
          1 => 
          array (
            'name' => 'sucursal_cocha_c',
            'studio' => 'visible',
            'label' => 'LBL_SUCURSAL_COCHA',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'is_group',
            'studio' => 
            array (
              'listview' => false,
              'searchview' => false,
              'formula' => false,
            ),
            'label' => 'LBL_GROUP_USER',
          ),
          1 => 
          array (
            'name' => 'sugar_login',
            'studio' => 
            array (
              'listview' => false,
              'searchview' => false,
              'formula' => false,
            ),
            'label' => 'LBL_SUGAR_LOGIN',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'authenticate_id',
            'studio' => 
            array (
              'listview' => false,
              'searchview' => false,
              'related' => false,
            ),
            'label' => 'LBL_AUTHENTICATE_ID',
          ),
          1 => 
          array (
            'name' => 'external_auth_only',
            'studio' => 
            array (
              'listview' => false,
              'searchview' => false,
              'related' => false,
            ),
            'label' => 'LBL_EXT_AUTHENTICATE',
          ),
        ),
      ),
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'crosselling_ejecutiva_c',
            'label' => 'LBL_CROSSELLING_EJECUTIVA',
          ),
          1 => 
          array (
            'name' => 'crosselling_sucursal_c',
            'label' => 'LBL_CROSSELLING_SUCURSAL',
          ),
        ),
      ),
    ),
  ),
);
?>
