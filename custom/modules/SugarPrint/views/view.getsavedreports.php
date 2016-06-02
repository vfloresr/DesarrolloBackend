<?php
 
class Viewgetsavedreports extends SugarView {

	function Viewgetsavedreports() {
 		parent::SugarView();
	}


	function display() 
  {
    global $current_user;   
    $Robj = BeanFactory::getBean('SugarPrint');     
    $cmodule="";      
    if(!empty($_REQUEST["cmodule"]))
    {
      $cmodule=$_REQUEST["cmodule"];   
      $R_list = $Robj->get_full_list("","sugarprint.report_module='".$cmodule."' and (sugarprint.privatereport=0 or sugarprint.assigned_user_id='".$current_user->id."')");      
    }    
    else
    {   
      $R_list = $Robj->get_full_list("","(sugarprint.privatereport=0 or sugarprint.assigned_user_id='".$current_user->id."')");          
    }   
    $list=Array();      
    foreach($R_list as $key =>$r ) {     
        $list[] = array('name'=>$r->name,'id'=>$r->id);    
    }    
    print json_encode($list);  
	}
}          
?>