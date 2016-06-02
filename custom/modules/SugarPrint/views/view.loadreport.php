<?php
 
class Viewloadreport extends SugarView {

	function Viewloadreport() {
 		parent::SugarView();
	}


	function display() 
  {
    global $current_user;
    if(empty($_REQUEST["_idreport"]))
      exit();

    $r = new SugarPrint();
   
    $r->retrieve($_REQUEST["_idreport"]);

    print json_encode(array(
      'name'=>$r->name,
      'id'=>$r->id,
      'assigned_user_id'=>$r->assigned_user_id,
      'name'=>$r->name,
      'privatereport'=>$r->privatereport,
      'print_logo'=>$r->print_logo,
      'format_pdf'=>$r->format_pdf,
      'width_pdf'=>$r->width_pdf,
      'height_pdf'=>$r->height_pdf,
      'report_type'=> $r->report_type,
      'report_module'=>$r->report_module,
      'date_interval'=>$r->date_interval,
      'sparkline'=>$r->sparkline,
      'calctype'=>$r->calctype,
      'select_list'=> html_entity_decode($r->select_list,ENT_COMPAT),
      'filters'=> html_entity_decode($r->filters)
    )                        
    );  

	}
}          
?>