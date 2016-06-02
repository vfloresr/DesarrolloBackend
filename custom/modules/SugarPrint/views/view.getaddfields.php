<?php
 
class Viewgetaddfields extends SugarView {

	function Viewgetaddfields() {
 		parent::SugarView();
	}


	function display() 
  {
     global $dictionary;
     require_once('modules/ModuleBuilder/views/view.modulefields.php');
     if(empty($_REQUEST["cmodule"]))
        exit();
     $viewmodfields = new ViewModulefields();
     $module_List=$moduleList;
     $objectName = BeanFactory::getObjectName($_REQUEST["cmodule"]);
     VardefManager::loadVardef($_REQUEST["cmodule"], $objectName, true);      
     $list=Array();      
     if (is_array($dictionary[$objectName]['fields']))
          {
            foreach($dictionary[$objectName]['fields'] as $def) {
              if ($viewmodfields->isValidStudioField($def))                        
                {      
                $list[] = array('name'=>$def['name'],'type'=>$def['type'],'label'=>addcslashes(translate($def['vname'],$_REQUEST["cmodule"]), '"'));    
                }     
              }                                                                
          } 
    print json_encode($list);  
  }
}   
?>