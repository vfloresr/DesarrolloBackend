<?php

 function nombre_ejecutivo ($id){
	 global $db, $sugar_config;
   $ejecutivo = '';
   $query_ejecutivo = "SELECT concat(first_name,' ', last_name) as ejecutivo 
     FROM crm_produccion.users where id = '".$id."'";
  
  $resul_ejecutivo = $db->Query($query_ejecutivo);
  
   if($row = $db->fetchByAssoc($resul_ejecutivo)){
	 $nombre_ejecutivo  = ucwords($row['ejecutivo']);
	  $ejecutivo.= '<TABLE border="0" cellpadding="0" cellspacing="0" width="60%" style="margin-right:145px;" align="center" >
                                       <tbody class="mcnTextBlockOuter">
                                          <tr>
                                             <td valign="top" class="mcnTextBlockInner">
                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnTextContentContainer">
                                                   <tbody>
                                                     <tr>
													   <td valign="top">
														 <br>
															<span style="font-size:17px"><strong><span style="color:#696969">Buen d&iacute;a '.$nombre_ejecutivo.' </span></strong></span><br>
															  <br>
															   <span style="font-size:16px"><span style="color:#696969">Te mandamos un resumen con las nuevas oportunidades por trabajar en tu CRM.<br>
															   &#161;&Eacute;xito en tu gesti&oacute;n de clientes!</span></span><br>
															   &nbsp;
														  </td>
													  </tr>                                                    
												  </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>';
	  } 
 
  return $ejecutivo;
 }
 
    
 function mostrar_cumpleanio($id){
global $db, $sugar_config;
  $cumpleanios = '';
    // Este query muestra el nombre de los cumpleañeros del dia
 /* $query_cumpleanio = "SELECT id,
				      last_name,
				   SUBSTRING(birthdate FROM 6 FOR 10) as dia_cumple
				   FROM contacts 
				   WHERE birthdate is not null
				   AND SUBSTRING(birthdate FROM 6 FOR 10) = Date_format(now(),'%m-%d')
				   AND SUBSTRING(birthdate FROM 6 FOR 10) =  Date_format(DATE_SUB(now(), INTERVAL 1 DAY), '%m-%d')
				   AND assigned_user_id = '".$id."' ORDER BY 1,2";*/
				   
	 $query_cumpleanio="SELECT 
						id,
				        last_name,
                        DATE_FORMAT(birthdate,'%d-%m-%Y') as fecha_cumpleanios,
						SUBSTRING(birthdate FROM 6 FOR 10) as dia_cumple
				   FROM crm_produccion.contacts 
				   	   
				   WHERE birthdate is not null
           AND SUBSTRING(birthdate FROM 6 FOR 10) = Date_format(now(),'%m-%d')
           AND assigned_user_id = '".$id."' ORDER BY 1,2"; 			   
				   
				   
  $resul_cumpleanio = $db->Query($query_cumpleanio);
  $row = mysqli_num_rows($resul_cumpleanio);	
  if($row > 0){
	  $cont = 0;
	  $cumpleanios.='<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
                                       <tbody class="mcnImageBlockOuter">
                                          <tr>
                                             <td valign="top" style="padding:0px" class="mcnImageBlockInner">
                                                <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                                                   <tbody>
                                                      <tr>
                                                         <td class="mcnImageContent" valign="top" style="padding-right: 0px; padding-left: 0px; padding-top: 0; padding-bottom: 0; text-align:center;">
                                                            <img align="center" alt="" src="https://gallery.mailchimp.com/cf8e3f784ee65fee4bea0e12f/images/e5550c1e-49ad-491c-a750-310d4dd97b99.jpg" width="600" style="max-width:600px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage">
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                   <table border="0" cellpadding="0" cellspacing="0" width="60%" style="margin-right:145px;" align="center">
                                       <tbody class="mcnTextBlockOuter">
                                          <tr>
                                             <td valign="top" class="mcnTextBlockInner">
                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnTextContentContainer">
                                                   <tbody>
                                                      <tr>
                                                          <td valign="top" class="mcnTextContent" style="padding-top:9px; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;">
                                                            <strong><span style="font-size:18px"><span style="color: #006699;line-height: 20.8px;">Clientes</span></span></strong>
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>  
	                                     <table cellpadding="0" cellspacing="0" width="600" id="templateColumns" align="center" >
                                                   <TR style="font-size: 12px; color: #FFF;">
                                                      <TD>
													   <table border="0" cellpadding="0"  cellspacing="0" width="100%" align="center" class="templateColumnContainer">    
													     <th width="10%"  align="center"   bgcolor="#adadad" style="font-size:12px;">#</th>
														 <th width="50%"  align="center"   bgcolor="#adadad" style="font-size:12px;">&nbsp;&nbsp;Nombre</th>
														  <th width="40%"  align="center"  bgcolor="#adadad" style="font-size:12px;">&nbsp;&nbsp;&nbsp;Fecha de Cumplea&ntilde;os</th>
														</table>
													  </TD>
                                                      </TR>
                                                          
                                                            <tr>
															<td align="center" valign="top" width="100%" class="templateColumnContainer">   
															 <table  border="1" bordercolor="#e0e0e0" cellpadding="0" cellspacing="0" width="100%" class="table table-striped">';
														 
												while ($row = $db->fetchByAssoc($resul_cumpleanio)) {
													    $cont = $cont + 1;
														$nombre_cumpleanios = ucwords($row['last_name']);
														$fecha_cumpleanios  = $row['fecha_cumpleanios'];
													
														$cumpleanios.='<TR bgcolor="#f9f9f9" style="color: #151515">';
														$cumpleanios.='<TD  width="10%"  align="center" style="font-size:12px;">'.$cont.'</TD>';
														$cumpleanios.='<TD  width="50%"  align="center" style="font-size:12px;">'.$nombre_cumpleanios.'</TD>';
														$cumpleanios.='<TD  width="40%"  align="center" style="font-size:12px;">'.$fecha_cumpleanios.'</TD>';
														$cumpleanios.='</TR>';  
													 }	 
                                                
                                                 $cumpleanios.='</table>
															   </td>
														     </tr>
														 </table>';
										
													
  }
 
  return $cumpleanios;
  }
  
  function oportunidades_venta_cross($id){
	global $db, $sugar_config;
  
     // Este Query me Muestra la cantidad de crosselling que tiene asignado el ejecutivo
    $query_cross = "SELECT
					 n.id AS numero_negocio,
					 n.destino AS destino,
                     co.last_name AS nombre_comprador,
					 DATE_FORMAT(n.fecha_salida,'%d-%m-%Y') AS fecha_salida,
					 
                    (CASE WHEN ((dn.op_hotel_c = 1) AND (dn.op_asistencia_c = 1)) THEN 'Hotel - Asistencia' ELSE (CASE WHEN (dn.op_hotel_c = 1) 
                    THEN 'Hotel' ELSE (CASE WHEN (dn.op_asistencia_c = 1) THEN 'Asistencia' ELSE '' END) END) END) AS oportunidad_new
            		FROM
						crm_produccion.opportunities o
							JOIN crm_produccion.opportunities_cstm c ON c.id_c = o.id
							JOIN crm_produccion.crm_negocios_opportunities_c rn ON rn.crm_negocios_opportunitiesopportunities_idb = c.id_c
							JOIN crm_produccion.crm_negocios n ON n.id = rn.crm_negocios_opportunitiescrm_negocios_ida
							JOIN crm_produccion.crm_negocios_crm_pasajeros_c rp ON rp.crm_negocios_crm_pasajeroscrm_negocios_ida = n.id 
							JOIN crm_produccion.crm_pasajeros p ON p.id = rp.crm_negocios_crm_pasajeroscrm_pasajeros_idb AND p.tipo_pax LIKE '%Comprador%'
							JOIN crm_produccion.contacts co ON co.id = p.contact_id_c
							LEFT JOIN crm_produccion.crm_negocios_cstm dn ON ((dn.id_c = n.id)) 
							WHERE o.opportunity_type in ('crosselling') 
							 AND date(o.date_entered) >= curdate()
            	 			 AND  o.sales_stage = 'creado' 
							 AND  o.deleted = 0 
							 AND  n.deleted = 0 
							 AND  p.deleted = 0 
							 AND co.deleted = 0 
							 AND rp.deleted = 0   AND  o.assigned_user_id ='".$id."' ";
		 
		 $res_db= $db->Query($query_cross);	
        //validar que cuando el rowcount > 0 muestre la imagen
     	 $row = mysqli_num_rows($res_db);					  
							  
		  $oportunidad_cross = ''; $cont_cross=0;
		  if ($row > 0){
          $oportunidad_cross.='<table border="0" cellpadding="0" cellspacing="0" width="60%" style="margin-right:145px;" align="center">
                                       <tbody class="mcnTextBlockOuter">
                                          <tr>
                                             <td valign="top" class="mcnTextBlockInner">
                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnTextContentContainer">
                                                   <tbody>
                                                      <tr>
                                                         <td valign="top" class="mcnTextContent" style="padding-top:9px; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;">
                                                            <strong><span style="font-size:18px"><span style="color: #006699;line-height: 20.8px;">Nuevos cross selling</span></span></strong><br>
                                                            <span style="color: #006699;">Revisa tus nuevas oportunidades de cross selling</span>
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table> 
		  		  
		                             <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateColumns" align="center">
                                                            <TR style="font-size: 12px; color: #FFF;">
                                                              <TD>
															   <table border="1" cellpadding="0" cellspacing="0" width="100%" bordercolor="#e0e0e0" align="center" class="templateColumnContainer">
															   
																 <th width="15%"  align="center"  bgcolor="#adadad" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;N.Negocio</th>
																 <th width="25%"  align="center"  bgcolor="#adadad" style="font-size:12px;">&nbsp;&nbsp;&nbsp;Destino</th>
																 <th width="25%"  align="center"  bgcolor="#adadad" style="font-size:12px;">Comprador</th>
																 <th width="15%"  align="center"  bgcolor="#adadad" style="font-size:12px;">Fecha Salida</th>
																 <th width="20%"  align="left"    bgcolor="#adadad" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;Oportunidad</th>
																</table>
															   </TD>
                                                            </tr>
                                                          
                                                            <tr>
															<td align="center" valign="top" width="100%" class="templateColumnContainer">   
															 <table  border="1" bordercolor="#e0e0e0" cellpadding="0" cellspacing="0" width="100%" class="table table-striped">';
                                                           while ($row = $db->fetchByAssoc($res_db)) {
														   $cont_cross = $cont_cross + 1;	
														   $crosselling_num = $row['numero_negocio'];
														   $destino         = $row['destino'];
														   $nombre_cross 	= ucwords($row['nombre_comprador']);
														   $fecha_salida 	= $row['fecha_salida'];
														   $oportunidad	    = $row['oportunidad_new'];
														  
														$oportunidad_cross.='<TR bgcolor="#f9f9f9" style="color: #151515">';
														
														$oportunidad_cross.='<TD width="15%"  align="center" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;'.$crosselling_num.'</TD>';
														$oportunidad_cross.='<TD width="25%"  align="center" style="font-size:12px;">'.$destino.'</TD>';
														$oportunidad_cross.='<TD width="25%"  align="center" style="font-size:12px;rowspan="2">&nbsp;&nbsp;&nbsp;'.$nombre_cross.'</TD>';
														$oportunidad_cross.='<TD width="15%"  align="center" style="font-size:12px;">'.$fecha_salida.'</TD>';
														$oportunidad_cross.='<TD width="20%"  align="left" style="font-size:12px;">'.$oportunidad.'</TD>';
														
														$oportunidad_cross.='</TR>';  							
														}
                                                          $oportunidad_cross.='</table>
																		      </td>
																		    </tr>
																 </table>';
		  }													  
    return $oportunidad_cross;			  
						  
  }	

  

    function oportunidades_venta_recompra($id){
	global $db, $sugar_config;
  
     // Este Query muestra la cantidad de Recompra que tiene asignado el ejecutivo
		$query_recompra="SELECT
							 n.id AS numero_negocio,
							 n.destino AS destino,
							 co.last_name AS nombre_comprador,
							 n.monto AS monto_venta,
							 DATE_FORMAT( n.fecha_emision_real,'%d-%m-%Y')  AS fecha_compra
							FROM
							 crm_produccion.opportunities o
								JOIN crm_produccion.opportunities_cstm c ON c.id_c = o.id
								JOIN crm_produccion.crm_negocios_opportunities_c rn ON rn.crm_negocios_opportunitiesopportunities_idb = c.id_c
								JOIN crm_produccion.crm_negocios n ON n.id = rn.crm_negocios_opportunitiescrm_negocios_ida
								JOIN crm_produccion.crm_negocios_crm_pasajeros_c rp ON rp.crm_negocios_crm_pasajeroscrm_negocios_ida = n.id 
								JOIN crm_produccion.crm_pasajeros p ON p.id = rp.crm_negocios_crm_pasajeroscrm_pasajeros_idb AND p.tipo_pax LIKE '%Comprador%'
							    JOIN crm_produccion.contacts co ON co.id = p.contact_id_c
                                LEFT JOIN crm_produccion.crm_negocios_cstm dn ON ((dn.id_c = n.id)) 
							WHERE o.opportunity_type in ('recompra')
                             AND   date(o.date_entered) >= curdate()							
							 AND  o.sales_stage = 'creado' 
							 AND  o.deleted = 0 
							 AND  n.deleted = 0 
							 AND  p.deleted = 0 
							 AND co.deleted = 0 
							 AND rp.deleted = 0   AND  o.assigned_user_id = '".$id."' order by o.date_modified desc;";
		 
		 $res_db= $db->Query($query_recompra);	
        //validar que cuando el rowcount > 0 muestre la imagen
     	 $row = mysqli_num_rows($res_db);					  
							  
		  $oportunidad_recompra = '';
		  if ($row > 0){
          $oportunidad_recompra.='<table border="0" cellpadding="0" cellspacing="0" width="60%" style="margin-right:145px;" align="center">
                                       <tbody class="mcnTextBlockOuter">
                                          <tr>
                                             <td valign="top" class="mcnTextBlockInner">
                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnTextContentContainer">
                                                   <tbody>
                                                       <tr>
                                                         <td valign="top" class="mcnTextContent" style="padding-top:9px; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;">
                                                            <strong><span style="font-size:18px"><span style="color: #006699;line-height: 20.8px;">Nuevos Recompra</span></span></strong><br>
                                                            <span style="color: #006699;">Clientes que compraron contigo hace un año </span>
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table> 
		                                 <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateColumns" align="center">
                                                             <TR style="font-size: 12px; color: #FFF;">
                                                              <TD>
															   <table border="1" cellpadding="0" cellspacing="0" width="100%" bordercolor="#e0e0e0" align="center" class="templateColumnContainer">
															   
																 <th width="15%"  align="center"  bgcolor="#adadad" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;N.Negocio</th>
																 <th width="25%"  align="center"  bgcolor="#adadad" style="font-size:12px;">&nbsp;&nbsp;&nbsp;Destino</th>
																 <th width="25%"  align="center"  bgcolor="#adadad" style="font-size:12px;">Comprador</th>
																 <th width="15%"  align="center"  bgcolor="#adadad" style="font-size:12px;">Monto</th>
																 <th width="20%"  align="left"    bgcolor="#adadad" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;Fecha de Compra</th>
																</table>
															   </TD>
                                                            </tr>
                                                          
                                                            <tr>
															<td align="center" valign="top" width="100%" class="templateColumnContainer">   
															 <table  border="1" bordercolor="#e0e0e0" cellpadding="0" cellspacing="0" width="100%" class="table table-striped">';
															    
                                                           while ($row = $db->fetchByAssoc($res_db)) {
																
														    $recompra_num     =  $row['numero_negocio'];
															$destino          =  $row['destino'];
															$nombre_recompra  =  ucwords($row['nombre_comprador']);
														    $monto            =  $row['monto_venta'];
															$fecha_compra     =  $row['fecha_compra'];
														  
														$oportunidad_recompra.='<TR bgcolor="#f9f9f9" style="color: #151515">';
														$oportunidad_recompra.='<TD  width="15%"  align="center" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;'.$recompra_num.'</TD>';
														$oportunidad_recompra.='<TD  width="25%"  align="center" style="font-size:12px;">'.$destino.'</TD>';
														$oportunidad_recompra.='<TD  width="25%"  align="left" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nombre_recompra.'</TD>';
														$oportunidad_recompra.='<TD  width="15%"  align="left" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($monto, 2, '.', '').'</TD>';
														$oportunidad_recompra.='<TD  width="20%"  align="left" style="font-size:12px;">&nbsp;&nbsp;'.$fecha_compra.'</TD>';
														
														$oportunidad_recompra.='</TR>';  							
														}                              
                                                          $oportunidad_recompra.='</table>
																		         </td>
																		      </tr>
																   </table>';
		  }													  
    return $oportunidad_recompra;			  
						  
  }	
	

 function oportunidades_venta_fugados($id){
	global $db, $sugar_config;
  
     // Este Query muestra la cantidad de fugados que tiene asignado el ejecutivo
    $query_fugados = "SELECT
							 n.id AS numero_negocio,
							 n.destino AS destino,
							 co.last_name AS nombre_comprador,
							 n.monto AS monto_venta,
						     DATE_FORMAT( n.fecha_emision_real,'%d-%m-%Y')  AS fecha_compra
							FROM
							 crm_produccion.opportunities o
								JOIN crm_produccion.opportunities_cstm c ON c.id_c = o.id
								JOIN crm_produccion.crm_negocios_opportunities_c rn ON rn.crm_negocios_opportunitiesopportunities_idb = c.id_c
								JOIN crm_produccion.crm_negocios n ON n.id = rn.crm_negocios_opportunitiescrm_negocios_ida
								JOIN crm_produccion.crm_negocios_crm_pasajeros_c rp ON rp.crm_negocios_crm_pasajeroscrm_negocios_ida = n.id 
								JOIN crm_produccion.crm_pasajeros p ON p.id = rp.crm_negocios_crm_pasajeroscrm_pasajeros_idb AND p.tipo_pax LIKE '%Comprador%'
							  JOIN crm_produccion.contacts co ON co.id = p.contact_id_c
							LEFT JOIN crm_produccion.crm_negocios_cstm dn ON ((dn.id_c = n.id)) 
							WHERE o.opportunity_type in ('fugados') 
							 AND   date(o.date_entered) = curdate()
            	 			 AND  o.sales_stage = 'creado' 
							 AND  o.deleted = 0 
							 AND  n.deleted = 0 
							 AND  p.deleted = 0 
							 AND co.deleted = 0 
							 AND rp.deleted = 0   AND   o.assigned_user_id = '".$id."' order by o.date_modified desc; ";
		 
		 $res_db= $db->Query($query_fugados);	
        //validar que cuando el rowcount > 0 muestre la imagen
     	 $row = mysqli_num_rows($res_db);					  
							  
		  $oportunidad_fugados = '';
		  if ($row > 0){
          $oportunidad_fugados.='<table border="0" cellpadding="0" cellspacing="0" width="60%" style="margin-right:145px;" align="center">
                                       <tbody class="mcnTextBlockOuter">
                                          <tr>
                                             <td valign="top" class="mcnTextBlockInner">
                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnTextContentContainer">
                                                   <tbody>
                                                      <tr>
                                                         <td valign="top" class="mcnTextContent" style="padding-top:9px; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;">
                                                            <strong><span style="font-size:18px"><span style="color: #006699;line-height: 20.8px;">Fugados</span></span></strong><br>
                                                            <span style="color: #006699;">Clientes que compraron contigo hace m&aacute;s de un a &ntilde;o</span>
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>  
												
		                          <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateColumns" align="center">
                                                          <TR style="font-size: 12px; color: #FFF;">
                                                              <TD>
															   <table border="1" cellpadding="0" cellspacing="0" width="100%" bordercolor="#e0e0e0" align="center" class="templateColumnContainer">
															   
																 <th width="15%"  align="center"  bgcolor="#adadad" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;N.Negocio</th>
																 <th width="25%"  align="center"  bgcolor="#adadad" style="font-size:12px;">&nbsp;&nbsp;&nbsp;Destino</th>
																 <th width="25%"  align="center"  bgcolor="#adadad" style="font-size:12px;">Comprador</th>
																 <th width="15%"  align="center"  bgcolor="#adadad" style="font-size:12px;">Monto</th>
																 <th width="20%"  align="left"    bgcolor="#adadad" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;Fecha Salida</th>
																</table>
															   </TD>
                                                            </tr>
                                                          
                                                            <tr>
															<td align="center" valign="top" width="100%" class="templateColumnContainer">   
															<table  border="1" bordercolor="#e0e0e0" cellpadding="0" cellspacing="0" width="100%" class="table table-striped">';
                                                     while ($row = $db->fetchByAssoc($res_db)) {
													
													        $fugados_num     =  $row['numero_negocio'];
															$destino          =  $row['destino'];
															$nombre_recompra  =  ucwords($row['nombre_comprador']);
														    $monto            =  $row['monto_venta'];
															$fecha_compra     =  $row['fecha_compra'];	  
													
													    $oportunidad_fugados.='<TR bgcolor="#f9f9f9" style="color: #151515">';
														$oportunidad_fugados.='<TD  width="15%"  align="center" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;'.$fugados_num.'</TD>';
														$oportunidad_fugados.='<TD  width="25%"  align="center" style="font-size:12px;">'.$destino.'</TD>';
														$oportunidad_fugados.='<TD  width="25%"  align="left" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nombre_recompra.'</TD>';
														$oportunidad_fugados.='<TD  width="15%"  align="left" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($monto, 2, '.', '').'</TD>';
														$oportunidad_fugados.='<TD  width="20%"  align="left" style="font-size:12px;">&nbsp;&nbsp;'.$fecha_compra.'</TD>';
														
														$oportunidad_fugados.='</TR>';  							
														}                              
                                                        $oportunidad_fugados.='</table>
																		         </td>
																		      </tr>
																   </table>';
																								
													
												
		  }													  
    return $oportunidad_fugados;			  
						  
  }	

  
  function mostrar_imgagen_oportunidades($id){
	    global $db, $sugar_config;
	  
	    $query_cross = "SELECT
							 n.id AS numero_negocio,
							 co.phone_fax AS rut_comprador,
							 co.last_name AS nombre_comprador,
							 o.sales_stage,
							 o.assigned_user_id,
							 opportunity_type,
                             o.date_entered
							FROM
							 crm_produccion.opportunities o
								JOIN crm_produccion.opportunities_cstm c ON c.id_c = o.id
								JOIN crm_produccion.crm_negocios_opportunities_c rn ON rn.crm_negocios_opportunitiesopportunities_idb = c.id_c
								JOIN crm_produccion.crm_negocios n ON n.id = rn.crm_negocios_opportunitiescrm_negocios_ida
								JOIN crm_produccion.crm_negocios_crm_pasajeros_c rp ON rp.crm_negocios_crm_pasajeroscrm_negocios_ida = n.id 
								JOIN crm_produccion.crm_pasajeros p ON p.id = rp.crm_negocios_crm_pasajeroscrm_pasajeros_idb AND p.tipo_pax LIKE '%Comprador%'
							    JOIN crm_produccion.contacts co ON co.id = p.contact_id_c
							WHERE o.opportunity_type in ('crosselling') 
							 AND date(o.date_entered) >= curdate()-1 
            	 			 AND  o.sales_stage = 'creado' 
							 AND  o.deleted = 0 
							 AND  n.deleted = 0 
							 AND  p.deleted = 0 
							 AND co.deleted = 0 
							 AND rp.deleted = 0   AND  o.assigned_user_id ='".$id."' ";
		 
		 $res_db= $db->Query($query_cross);	
         $row_cross = mysqli_num_rows($res_db);	  
	  
	  
	      $query_recompra = "SELECT
							 n.id AS numero_negocio,
							 co.phone_fax AS rut_comprador,
							 co.last_name AS nombre_comprador,
							 o.sales_stage,
							 o.assigned_user_id,
							 opportunity_type,
                             o.date_entered
							FROM
							    crm_produccion.opportunities o
								JOIN crm_produccion.opportunities_cstm c ON c.id_c = o.id
								JOIN crm_produccion.crm_negocios_opportunities_c rn ON rn.crm_negocios_opportunitiesopportunities_idb = c.id_c
								JOIN crm_produccion.crm_negocios n ON n.id = rn.crm_negocios_opportunitiescrm_negocios_ida
								JOIN crm_produccion.crm_negocios_crm_pasajeros_c rp ON rp.crm_negocios_crm_pasajeroscrm_negocios_ida = n.id 
								JOIN crm_produccion.crm_pasajeros p ON p.id = rp.crm_negocios_crm_pasajeroscrm_pasajeros_idb AND p.tipo_pax LIKE '%Comprador%'
							    JOIN crm_produccion.contacts co ON co.id = p.contact_id_c
							WHERE o.opportunity_type in ('recompra') 
							AND date(o.date_entered) >= curdate()-1 
            	 			 AND  o.sales_stage = 'creado' 
							 AND  o.deleted = 0 
							 AND  n.deleted = 0 
							 AND  p.deleted = 0 
							 AND co.deleted = 0 
							 AND rp.deleted = 0   AND   o.assigned_user_id = '".$id."'";
		 
		 $res_db= $db->Query($query_recompra);	
         $row_recompra = mysqli_num_rows($res_db);
	  
	      $query_fugados = "SELECT
							 n.id AS numero_negocio,
							 co.phone_fax AS rut_comprador,
							 co.last_name AS nombre_comprador,
							 o.sales_stage,
							 o.assigned_user_id,
							 opportunity_type,
                             o.date_entered
							FROM
							 crm_produccion.opportunities o
								JOIN crm_produccion.opportunities_cstm c ON c.id_c = o.id
								JOIN crm_produccion.crm_negocios_opportunities_c rn ON rn.crm_negocios_opportunitiesopportunities_idb = c.id_c
								JOIN crm_produccion.crm_negocios n ON n.id = rn.crm_negocios_opportunitiescrm_negocios_ida
								JOIN crm_produccion.crm_negocios_crm_pasajeros_c rp ON rp.crm_negocios_crm_pasajeroscrm_negocios_ida = n.id 
								JOIN crm_produccion.crm_pasajeros p ON p.id = rp.crm_negocios_crm_pasajeroscrm_pasajeros_idb AND p.tipo_pax LIKE '%Comprador%'
							    JOIN crm_produccion.contacts co ON co.id = p.contact_id_c
							WHERE o.opportunity_type in ('fugados') 
							 AND date(o.date_entered) >= curdate()-1 
            	 			 AND  o.sales_stage = 'creado' 
							 AND  o.deleted = 0 
							 AND  n.deleted = 0 
							 AND  p.deleted = 0 
							 AND co.deleted = 0 
							 AND rp.deleted = 0   AND   o.assigned_user_id = '".$id."' ";
		 
		 $res_db= $db->Query($query_fugados);	
         $row_fugados = mysqli_num_rows($res_db);
	  
	  
	   if (($row_cross > 0) OR ($row_recompra > 0) OR ($row_fugados)){
		   
		$img_oportunidades.='<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
                                       <tbody class="mcnImageBlockOuter">
                                          <tr>
                                             <td valign="top" style="padding:9px" class="mcnImageBlockInner">
                                                <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                                                   <tbody>
                                                      <tr>
                                                         <td class="mcnImageContent" valign="top" style="padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;">
                                                            <img align="center" alt="" src="https://gallery.mailchimp.com/cf8e3f784ee65fee4bea0e12f/images/5f482d13-1c37-4176-9346-c305d7ec5830.jpg" width="564" style="max-width:600px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage">
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>';  
		   
		   
		   
	   }
	   return $img_oportunidades;
	  
	  
  }
  
  
  
  function mostrar_imagen_tareas($id){
	  global $db, $sugar_config;
	  
	  	$query_tareas_prox_viajes = "SELECT
								neg.id as numero_negocio,
								co.phone_fax AS rut_comprador,
								co.last_name AS nombre_comprador
							  FROM crm_produccion.tasks ta
								JOIN crm_produccion.users u ON u.id = ta.assigned_user_id
								JOIN crm_produccion.tasks_cstm ta_c ON ta_c.id_c = ta.id
								JOIN crm_produccion.crm_negocios_tasks_c rn ON rn.crm_negocios_taskstasks_idb = ta_c.id_c AND rn.deleted =0
								JOIN crm_produccion.crm_negocios neg ON neg.id = rn.crm_negocios_taskscrm_negocios_ida AND neg.deleted =0
								JOIN crm_produccion.crm_negocios_crm_pasajeros_c rp ON((rp.crm_negocios_crm_pasajeroscrm_negocios_ida = neg.id)) 
								JOIN crm_produccion.crm_pasajeros p ON(((p.id = rp.crm_negocios_crm_pasajeroscrm_pasajeros_idb) 
								AND (p.tipo_pax like '%Comprador%'))) 
								JOIN crm_produccion.contacts co ON((co.id = p.contact_id_c))  
								WHERE ta_c.tipo_c in ('Proximos') 
								AND date(ta.date_entered) >= curdate()-1 
							    AND  ta.status = 'Not Started'  AND ta.assigned_user_id ='".$id."'";

	     $resul_db_tarea= $db->Query($query_tareas_prox_viajes);
		 $row_prox_viajes = mysqli_num_rows($resul_db_tarea);
	  
	    $query_tareas_retorno = "SELECT
								neg.id as numero_negocio,
								co.phone_fax AS rut_comprador,
								co.last_name AS nombre_comprador
							  FROM crm_produccion.tasks ta
								JOIN crm_produccion.users u ON u.id = ta.assigned_user_id
								JOIN crm_produccion.tasks_cstm ta_c ON ta_c.id_c = ta.id
								JOIN crm_produccion.crm_negocios_tasks_c rn ON rn.crm_negocios_taskstasks_idb = ta_c.id_c AND rn.deleted =0
								JOIN crm_produccion.crm_negocios neg ON neg.id = rn.crm_negocios_taskscrm_negocios_ida AND neg.deleted =0
								JOIN crm_produccion.crm_negocios_crm_pasajeros_c rp ON((rp.crm_negocios_crm_pasajeroscrm_negocios_ida = neg.id)) 
								JOIN crm_produccion.crm_pasajeros p ON(((p.id = rp.crm_negocios_crm_pasajeroscrm_pasajeros_idb) 
								AND (p.tipo_pax like '%Comprador%'))) 
								JOIN crm_produccion.contacts co ON((co.id = p.contact_id_c))  
								WHERE ta_c.tipo_c in ('Retornos') 
								AND date(ta.date_entered) >= curdate()-1  
							    AND  ta.status = 'Not Started'  AND ta.assigned_user_id = '".$id."'";

	     $resul_db_retorno= $db->Query($query_tareas_retorno);
		 $row_retorno = mysqli_num_rows($resul_db_retorno);
		 
		 if (($row_prox_viajes > 0) OR ($row_retorno > 0)){
			 
			 
			$img_tareas.='<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnImageBlock" style="min-width:100%;">
                                       <tbody class="mcnImageBlockOuter">
                                          <tr>
                                             <td valign="top" style="padding:9px" class="mcnImageBlockInner">
                                                <table align="left" width="100%" border="0" cellpadding="0" cellspacing="0" class="mcnImageContentContainer" style="min-width:100%;">
                                                   <tbody>
                                                      <tr>
                                                         <td class="mcnImageContent" valign="top" style="padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;">
                                                            <img align="center" alt="" src="https://gallery.mailchimp.com/cf8e3f784ee65fee4bea0e12f/images/242d8917-a05e-42e2-9e31-fef779112094.jpg" width="564" style="max-width:600px; padding-bottom: 0; display: inline !important; vertical-align: bottom;" class="mcnImage">
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>'; 
			 
			 
		 }
		 
	    return $img_tareas;
	  
  }
  
	
 function tareas_prox_viajes($id){
	global $db, $sugar_config;
	// Este Query me Muestra la cantidad de Proximos Viajes que tiene el ejecutivo Asignado
	
	$query_tareas_prox_viajes = "  SELECT
									DATEDIFF(neg.fecha_salida,now()) as dias_viaje,
									neg.id as numero_negocio,
									neg.destino as destino,
									co.last_name AS nombre_comprador,
									DATE_FORMAT(neg.fecha_salida,'%d-%m-%Y')  AS fecha_salida
                      FROM crm_produccion.tasks ta
						JOIN crm_produccion.users u ON u.id = ta.assigned_user_id
						JOIN crm_produccion.tasks_cstm ta_c ON ta_c.id_c = ta.id
						JOIN crm_produccion.crm_negocios_tasks_c rn ON crm_produccion.rn.crm_negocios_taskstasks_idb = ta_c.id_c AND rn.deleted =0
						JOIN crm_produccion.crm_negocios neg ON neg.id = rn.crm_negocios_taskscrm_negocios_ida AND neg.deleted =0
						JOIN crm_produccion.crm_negocios_crm_pasajeros_c rp ON((rp.crm_negocios_crm_pasajeroscrm_negocios_ida = neg.id)) 
						JOIN crm_produccion.crm_pasajeros p ON(((p.id = rp.crm_negocios_crm_pasajeroscrm_pasajeros_idb) 
						AND (p.tipo_pax like '%Comprador%'))) 
						JOIN crm_produccion.contacts co ON((co.id = p.contact_id_c))  
                    WHERE ta_c.tipo_c in ('Proximos') 
					     and (neg.fecha_salida >= curdate()) AND (neg.fecha_salida <= (curdate() + interval 7 day))
                          AND  ta.status = 'Not Started'  AND ta.assigned_user_id ='".$id."' order by dias_viaje";

	     $resul_db_tarea= $db->Query($query_tareas_prox_viajes);
		 $row = mysqli_num_rows($resul_db_tarea);
		 
		 $oportunidad_prox_viajes = '';
		  if ($row > 0){
          $oportunidad_prox_viajes.='<table border="0" cellpadding="0" cellspacing="0" width="60%" style="margin-right:145px;" align="center">
                                       <tbody class="mcnTextBlockOuter">
                                          <tr>
                                             <td valign="top" class="mcnTextBlockInner">
                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnTextContentContainer">
                                                   <tbody>
                                                       <tr>
                                                         <td valign="top" class="mcnTextContent" style="padding-top:9px; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;">
                                                            <strong><span style="font-size:18px"><span style="color: #006699;line-height: 20.8px;">Pr&oacute;ximos Viajes </span></span></strong><br>
                                                            <span style="color: #006699;">Revisa tus viajes de los pr&oacute;ximos 7 d&iacute;as</span>
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                 </table>
                                               </td>
                                            </tr>
                                         </tbody>
                                      </table>
									
		                               <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateColumns" align="center">
                                                             <TR style="font-size: 12px; color: #FFF;">
                                                               <TD>
															    <table border="1" cellpadding="0"  cellspacing="0" width="100%" bordercolor="#e0e0e0" align="center" class="templateColumnContainer">
																   <th width="15%"  align="center"  style="font-size:12px;" bgcolor="#adadad">&nbsp;&nbsp;&nbsp;&nbsp;Dias para el Viaje</th>
																   <th width="20%"  align="center"  style="font-size:12px;" bgcolor="#adadad">&nbsp;&nbsp;&nbsp;N.Negocio</th>
																   <th width="25%"  align="center"  style="font-size:12px;" bgcolor="#adadad">&nbsp;&nbsp;&nbsp;Destino</th>
																   <th width="22%"  align="center"  style="font-size:12px;" bgcolor="#adadad">Comprador</th>
																   <th width="18%"  align="left"    style="font-size:12px;" bgcolor="#adadad">&nbsp;&nbsp;&nbsp;&nbsp;Fecha Salida</th>
																</table>
															   </TD>
                                                            </TR>
                                                          
                                                          
														
														  
														  
                                         				  <tr>
															<td align="center" valign="top" width="100%" class="templateColumnContainer">   
															  <table  border="1" bordercolor="#e0e0e0" cellpadding="0" cellspacing="0" width="100%" class="table table-striped">';
                                                     while ($row = $db->fetchByAssoc($resul_db_tarea)) {
															$dias_viaje         = $row['dias_viaje'];
															$prox_viajes_num    = $row['numero_negocio'];
															$destino            =  $row['destino'];
															$nombre_prox_viajes = ucwords($row['nombre_comprador']);
															$fecha_salida       =  $row['fecha_salida'];	  
													
													    $oportunidad_prox_viajes.='<TR bgcolor="#f9f9f9" style="color: #151515">';
														$oportunidad_prox_viajes.='<TD  width="15%"  align="center" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;'.$dias_viaje.'</TD>';
														$oportunidad_prox_viajes.='<TD  width="20%"  align="center" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;'.$prox_viajes_num.'</TD>';
														$oportunidad_prox_viajes.='<TD  width="25%"  align="left" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$destino.'</TD>';
														$oportunidad_prox_viajes.='<TD  width="22%"  align="center" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nombre_prox_viajes.'</TD>';
														$oportunidad_prox_viajes.='<TD  width="18%"  align="center" style="font-size:12px;">&nbsp;'.$fecha_salida.'</TD>';
														
														$oportunidad_prox_viajes.='</TR>';  							
														}                              
                                                        $oportunidad_prox_viajes.='</table>
																		         </td>
																		      </tr>
																   </table>';
												
		     }else{
				 
				 $oportunidad_prox_viajes = '';
				 
			 }													  
       return $oportunidad_prox_viajes;	
		 
	}

 function tareas_retorno($id){
	global $db, $sugar_config;
	// Este Query me Muestra la cantidad de Retornos que tiene el ejecutivo Asignado
	
	$query_tareas_retorno = "SELECT
							  neg.id as numero_negocio,
							  co.last_name AS nombre_comprador,
							  neg.destino as destino,
							  neg.monto as monto,
							  DATE_FORMAT(neg.fecha_destino,'%d-%m-%Y')  AS fecha_salida
                      FROM crm_produccion.tasks ta
                      JOIN crm_produccion.users u ON u.id = ta.assigned_user_id
                      JOIN crm_produccion.tasks_cstm ta_c ON ta_c.id_c = ta.id
                      JOIN crm_produccion.crm_negocios_tasks_c rn ON rn.crm_negocios_taskstasks_idb = ta_c.id_c AND rn.deleted =0
                      JOIN crm_produccion.crm_negocios neg ON neg.id = rn.crm_negocios_taskscrm_negocios_ida AND neg.deleted =0
                      JOIN crm_produccion.crm_negocios_crm_pasajeros_c rp ON((rp.crm_negocios_crm_pasajeroscrm_negocios_ida = neg.id)) 
                      JOIN crm_produccion.crm_pasajeros p ON(((p.id = rp.crm_negocios_crm_pasajeroscrm_pasajeros_idb) 
                      AND (p.tipo_pax like '%Comprador%'))) 
                      JOIN crm_produccion.contacts co ON((co.id = p.contact_id_c))  
                      WHERE ta_c.tipo_c in ('Retornos') 
                      AND date(neg.fecha_destino) = curdate()
                       AND  ta.status = 'Not Started'  AND ta.assigned_user_id = '".$id."' order by neg.fecha_destino desc";

	     $resul_db_tarea= $db->Query($query_tareas_retorno);
		 $row = mysqli_num_rows($resul_db_tarea);
		 
		 $oportunidad_tareas_retorno = '';
		  if ($row > 0){
          $oportunidad_tareas_retorno.='<table border="0" cellpadding="0" cellspacing="0" width="60%" style="margin-right:145px;" align="center">
                                         <tbody class="mcnTextBlockOuter">
                                          <tr>
                                             <td valign="top" class="mcnTextBlockInner">
                                                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;" class="mcnTextContentContainer">
                                                   <tbody>
                                                       <tr>
                                                         <td valign="top" class="mcnTextContent" style="padding-top:9px; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;">
                                                            <strong><span style="font-size:18px"><span style="color: #006699;line-height: 20.8px;">Retornos</span></span></strong><br>
                                                            <span style="color: #006699;">Contacta a tus clientes que vuelven hoy</span>
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                               </td>
                                            </tr>
                                         </tbody>
                                       </table>
		                                 <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateColumns" align="center">
                                                            <TR style="font-size: 12px; color: #FFF;">
                                                               <TD>
															    <table border="1" cellpadding="0"  cellspacing="0" width="100%" bordercolor="#e0e0e0" align="center" class="templateColumnContainer">
																   <th width="15%"  align="center"  style="font-size:12px;" bgcolor="#adadad">&nbsp;&nbsp;&nbsp;&nbsp;N.Negocio</th>
																   <th width="25%"  align="center"  style="font-size:12px;" bgcolor="#adadad">&nbsp;&nbsp;&nbsp;Destino</th>
																   <th width="25%"  align="center"  style="font-size:12px;" bgcolor="#adadad">Comprador</th>
																   <th width="15%"  align="center"  style="font-size:12px;" bgcolor="#adadad">Monto</th>
																   <th width="20%"  align="left"    style="font-size:12px;" bgcolor="#adadad">&nbsp;&nbsp;&nbsp;&nbsp;Fecha Retorno</th>
																</table>
															   </TD>
                                                            </TR>
                                                          	
															<tr>
															<td align="center" valign="top" width="100%" class="templateColumnContainer">   
															 <table  border="1" bordercolor="#e0e0e0" cellpadding="0" cellspacing="0" width="100%" class="table table-striped">';
                                                     while ($row = $db->fetchByAssoc($resul_db_tarea)) {
													
 													        $retorno_num     =  $row['numero_negocio'];
															$destino          =  $row['destino'];
															$nombre_retorno  =  ucwords($row['nombre_comprador']);
														    $monto            =  $row['monto'];
															$fecha_compra     =  $row['fecha_salida'];	
														  
											            $oportunidad_tareas_retorno.='<TR bgcolor="#f9f9f9" style="color: #151515">';
														$oportunidad_tareas_retorno.='<TD  width="15%"  align="center" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;'.$retorno_num.'</TD>';
														$oportunidad_tareas_retorno.='<TD  width="25%"  align="center" style="font-size:12px;">'.$destino.'</TD>';
														$oportunidad_tareas_retorno.='<TD  width="25%"  align="left" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nombre_retorno.'</TD>';
														$oportunidad_tareas_retorno.='<TD  width="15%"  align="left" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($monto, 2, '.', '').'</TD>';
														$oportunidad_tareas_retorno.='<TD  width="20%"  align="left" style="font-size:12px;">&nbsp;&nbsp;'.$fecha_compra.'</TD>';
														
														$oportunidad_tareas_retorno.='</TR>';  							
														}                              
                                                        $oportunidad_tareas_retorno.='</table>
																		         </td>
																		      </tr>
																   </table>';
																				
											
		     }else{
				 $oportunidad_tareas_retorno = '';
				 
			 }													  
       return $oportunidad_tareas_retorno;	
		 
	}
	
	
 function mostrar_boton_acceder($id){
	 
	global $db, $sugar_config; 
	 
	 $query_btn = "SELECT id,
					user_name,
					photo,
					first_name,
					last_name
				FROM users WHERE photo is not null
				AND id = '".$id."' ";
			  
	$resul_db = $db->Query($query_btn);
	$row = mysqli_num_rows($resul_db);		

  $boton='';
  if($row > 0){
	  
	if($row = $db->fetchByAssoc($resul_db)){
		$btn_id = $row['photo'];

	$boton.='<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnButtonBlock" style="min-width:100%;" align="center">
                                       <tbody class="mcnButtonBlockOuter">
                                          <tr>
                                             <td style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top" align="center" class="mcnButtonBlockInner">
                                                <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate !important;border-radius: 3px;background-color: #DC143C;">
                                                   <tbody>
                                                      <tr>
                                                         <td align="center" valign="middle" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 10px;">
                                                            <a class="mcnButton " title="Acceder al CRM" href="http://crm.cochadigital.com/login_api.php?user='.$btn_id.'" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Acceder al CRM</a>
                                                         </td>
                                                      </tr>
                                                   </tbody>
                                                </table>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>';
	  
	  	          }   
	    
       }
	 
	 return $boton;
 }

