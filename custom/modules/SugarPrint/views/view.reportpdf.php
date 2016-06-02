<?php     

require_once('custom/modules/SugarPrint/views/SugarPrintCls.php');
                
class Viewreportpdf extends SugarView {

	function display() 
  {
   $sp = new SugarPrintCls();  
   $sp->main();  
  }
}
?>       