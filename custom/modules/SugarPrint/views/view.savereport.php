<?php
 
class Viewsavereport extends SugarView {

	function Viewsavereport() {
 		parent::SugarView();
	}


	function display() 
  {
    global $app_list_strings,$current_user, $mod_strings;  

    $r = BeanFactory::newBean('SugarPrint');

    if(!empty($_REQUEST["_idreport"]))
    {
      $r->retrieve($_REQUEST["_idreport"]);
    }
    else
    {
     $r->id = create_guid();
     $r->new_with_id = true;
    }
    $r->assigned_user_id=$current_user->id; 

    $r->filters = $_REQUEST["filters"];
    
    if(empty($_REQUEST["name"]))
       $r->name="Report";
    else
       $r->name=$_REQUEST["name"];

    if(empty($_REQUEST["privatereport"]))
       $r->privatereport=0;
    else
       $r->privatereport=$_REQUEST["privatereport"];
    
    if(empty($_REQUEST["print_logo"]))
       $r->print_logo=0;
    else
       $r->print_logo=$_REQUEST["print_logo"];

    if(empty($_REQUEST["format_pdf"]))
       $r->format_pdf="A4";
    else
       $r->format_pdf=$_REQUEST["format_pdf"];

    if(empty($_REQUEST["width_pdf"]))
       $r->width_pdf=0;
    else
       $r->width_pdf=$_REQUEST["width_pdf"];

    if(empty($_REQUEST["height_pdf"]))
       $r->height_pdf=0;
    else
       $r->height_pdf=$_REQUEST["height_pdf"];

    if(empty($_REQUEST["report_type"]))
       $r->report_type="reportpdf";
    else
       $r->report_type=$_REQUEST["report_type"];
   
   if(empty($_REQUEST["date_interval"]))
       $r->date_interval="m";
    else
       $r->date_interval=$_REQUEST["date_interval"];

    $r->report_module=$_REQUEST["report_module"];
    $r->sparkline=$_REQUEST["sparkline"];
    $r->calctype=$_REQUEST["calctype"];

    $r->select_list=$_REQUEST["select_list"];
    $r->save();
    
    echo  $mod_strings['LABREPORTSAVEDSUCC'];


	}
}          
?>
  

  