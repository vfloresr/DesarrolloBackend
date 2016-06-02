<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class List_Hook{


   
    function crea_tarea($bean, $event, $arguments)
    {
		

		if($bean->pdf_joomla != ''){
			$pdf =  preg_replace('#^https?://#', '', $bean->pdf_joomla);

			if(strpos($pdf, "http://") == FALSE)  $pdf = "http://".$pdf;

			$bean->pdf_joomla = "<a href='".$pdf."' target='_blank'><button style='background:red'>Ver PDF</button></a>";
		}else $bean->pdf_joomla = "N/A";
		if($bean->url != ''){

			$url =  preg_replace('#^https?://#', '', $bean->url);

			if(strpos($url, "http://") == FALSE)  $url = "http://".$url;

			$bean->url = "<a href='".$url."' target='_blank'><button >Ver URL</button></a>";
		}else $bean->url = "N/A";
	 		
		 		

	    
	}	




}
?>