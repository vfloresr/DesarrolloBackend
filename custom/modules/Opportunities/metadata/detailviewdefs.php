<?php
$viewdefs ['Opportunities'] = 
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
      'useTabs' => true,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => true,
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
          0 => 
          array (
            'name' => 'name',
            'customCode' => '{$fields.name.value}<a href='.$sugar_config['site_frontend'].'/modulos/solicitudes_web/acceso_backend.php?id_registro={$fields.id.value}&id_solicitud={$fields.crm_solicitud_id_c.value}&user_name=admin target="_blank" onclick=modal_email_solicitudes_web(this)><input type="button"  value="Enviar Cotización" Class="btn btn-danger"></a>',
          ),
          1 => 'sales_stage',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
          1 => 
          array (
            'name' => 'leads_opportunities_1_name',
            'label' => 'LBL_LEADS_OPPORTUNITIES_1_FROM_LEADS_TITLE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
          ),
          1 => 
          array (
            'name' => 'date_assigned_c',
            'label' => 'LBL_DATE_ASSIGNED',
          ),
        ),
        3 => 
        array (
          0 => 'lead_source',
          1 => 
          array (
            'name' => 'agente_c',
            'label' => 'LBL_AGENTE',
          ),
        ),
        4 => 
        array (
          0 => 'opportunity_type',
          1 => 
          array (
            'name' => 'date_modified',
            'label' => 'LBL_DATE_MODIFIED',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'resumen_c',
            'studio' => 'visible',
            'label' => 'LBL_RESUMEN',
          ),
          1 => 
          array (
            'name' => 'description',
            'nl2br' => true,
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'numero_negocio_c',
            'label' => 'LBL_NUMERO_NEGOCIO',
          ),
          1 => 
          array (
            'name' => 'amount',
            'label' => '{$MOD.LBL_AMOUNT} ({$CURRENCY})',
          ),
        ),
      ),
    ),
  ),
);
?>
