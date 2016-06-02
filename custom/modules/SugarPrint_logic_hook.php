<?php
class SugarPrint_logic_hook
{
  function SugarPrint_logic_hook($event, $arguments)
  {    
   if ((!isset($_REQUEST["to_pdf"])) && (!isset($_REQUEST["sugar_body_only"])))
    {
    echo '<script type="text/javascript" src="modules/SugarPrint/js/SugarPrint.js"></script>';    
    } 
    else
    {
      if (isset($_REQUEST["to_pdf"]))
         if($_REQUEST["to_pdf"] == false)
            echo '<script type="text/javascript" src="modules/SugarPrint/js/SugarPrint.js"></script>';
    }            
  }  
                                                                  
}
?>