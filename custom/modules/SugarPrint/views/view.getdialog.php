<?php
 
class Viewgetdialog extends SugarView {

	function Viewgetdialog() {
 		parent::SugarView();
	}


	function display() 
  {

    global $app_list_strings, $mod_strings, $current_language, $dictionary, $sugar_config;  
  

    require_once('include/Sugar_Smarty.php');
    $sugar_smarty   = new Sugar_Smarty();

    if(!empty($_REQUEST["printlogo"]))
     $printlogo=$_REQUEST["printlogo"];
    else
     $printlogo='no';
    $sugar_smarty->assign("printlogo",$printlogo);
    if(!empty($_REQUEST["customwidth"]))
     $customwidth=$_REQUEST["customwidth"];
    else
     $customwidth='';
    $sugar_smarty->assign("customwidth",$customwidth);
    if(!empty($_REQUEST["customheight"]))
     $customheight=$_REQUEST["customheight"];
    else
     $customheight='';
    $sugar_smarty->assign("customheight",$customheight);
    if(!empty($_REQUEST["orientation"]))
     $orientation=$_REQUEST["orientation"];
    else
     $orientation='L';
    $sugar_smarty->assign("orientation",$orientation);     
    if(!empty($_REQUEST["pageformat"]))
     $pageformat=$_REQUEST["pageformat"];
    else
     $pageformat='';
    $sugar_smarty->assign("pageformat",$pageformat);
    if(!empty($_REQUEST["reporttitle"]))
     $reporttitle=$_REQUEST["reporttitle"];
    else
     $reporttitle='';
    $sugar_smarty->assign("reporttitle",$reporttitle);
 
 

    $cmodule=$_REQUEST["cmodule"];
    $sugar_smarty->assign("cmodule",$cmodule);        


       $sugar_smarty->assign("labpagina",$mod_strings['LABPAGE']);
       $sugar_smarty->assign("laborient",$mod_strings['LABORIENTAION']);  
       $sugar_smarty->assign("labtit",$mod_strings['LABTITLE']);
       $sugar_smarty->assign("labprintlogo",$mod_strings['LABPRINTLOGO']);
       $sugar_smarty->assign("lablist_reportsaved",$mod_strings['LABREPORTSAVED']);
       $sugar_smarty->assign("labsavereport",$mod_strings['LABSAVEREPORT']);
       $sugar_smarty->assign("labhelpreport",$mod_strings['LABHELPREPORT']);
       $sugar_smarty->assign("labhelpuserguide",$mod_strings['LABHELPUSERGUIDE']);       
       $sugar_smarty->assign("labPDFFormat",$mod_strings['LABPDFFORMAT']);
       $sugar_smarty->assign("labDateGroup",$mod_strings['LABDATEGROUP']);
       $sugar_smarty->assign("labreportprivate",$mod_strings['LABREPORTPRIVATE']);
       $sugar_smarty->assign("labreportrun",$mod_strings['LABREPORTRUN']);
       $sugar_smarty->assign("labreport_type",$mod_strings['LABREPORTTYPE']);
       $sugar_smarty->assign("labreportsavedesc",$mod_strings['LABREPORTSAVEDESC']);
       $sugar_smarty->assign("labdategroupdesc",$mod_strings['LABDATEGROUPDESC']);
       $sugar_smarty->assign("labformatreport",$mod_strings['FORMATREPORT']);   
       $sugar_smarty->assign("labmorefields",$mod_strings['MOREFIELDS']);                                                           
       $sugar_smarty->assign("labcreatereport",$mod_strings['CREATEREPORT']);                                                              
       $sugar_smarty->assign("labdesignreport",$mod_strings['DESIGNREPORT']);
       $sugar_smarty->assign("labfieldtogroup",$mod_strings['LABFIELDTOGROUP']);
       $sugar_smarty->assign("labfieldtosum",$mod_strings['LABFIELDTOSUM']);
       $sugar_smarty->assign("labfieldslist",$mod_strings['DESC_FIELDSLIST']);
       $sugar_smarty->assign("labsum_chart",$mod_strings['DESC_SUMCHART']);
       $sugar_smarty->assign("labcrosstab",$mod_strings['DESC_CROSSTAB']);
       $sugar_smarty->assign("labcolumnsfield",$mod_strings['CROSSTAB_COLUMN_FIELD']);
       $sugar_smarty->assign("labrowsfield",$mod_strings['CROSSTAB_ROW_FIELD']);
       $sugar_smarty->assign("labsetfiltersreport",$mod_strings['LABSETFILTERSFORM']);
       $sugar_smarty->assign("labsparkline",$mod_strings['LABSPARKLINE']);      
       $sugar_smarty->assign("labcalculation",$mod_strings['LABCALCULATION']);
       $sugar_smarty->assign("labhelpreport",$mod_strings['LABHELPREPORT']);
       $sugar_smarty->assign("help_report_type",$mod_strings['HELP_REPORTTYPE']);
       $sugar_smarty->assign("help_report_title",$mod_strings['HELP_REPORTTITLE']);
       $sugar_smarty->assign("help_report_load",$mod_strings['HELP_REPORTLOAD']);
       $sugar_smarty->assign("help_getmorefields",$mod_strings['HELP_MOREFIELDS']);
       $sugar_smarty->assign("help_field_to_group",$mod_strings['HELP_COLUMNGROUP']);
       $sugar_smarty->assign("help_field_to_sum",$mod_strings['HELP_COLUMNSUM']);
       $sugar_smarty->assign("help_crosstab_cols_group",$mod_strings['HELP_CROSSTABCOLS']);
       $sugar_smarty->assign("help_crosstab_rows_group",$mod_strings['HELP_CROSSTABROWS']);
       $sugar_smarty->assign("help_crosstab_sum",$mod_strings['HELP_CROSSTAB_SUM']);
       $sugar_smarty->assign("help_formatopagina",$mod_strings['HELP_PAGEFORMAT']);
       $sugar_smarty->assign("help_formatodate",$mod_strings['HELP_DATEFORMAT']);
           
    $sugar_smarty->assign('report_names', array(
                                  $mod_strings['LABREP'],
                                  $mod_strings['LABREPCROSSTAB'],                                  
                                  $mod_strings['LABREPSUM'],
                                  $mod_strings['LABPIECHART'],
                                  $mod_strings['LABBARGRAPH'],
                                  $mod_strings['LABHISTOGRAM']
                                  ));
    $sugar_smarty->assign('report_val', array(
                                  'pdf_report',
                                  'pdf_report_crosstab',
                                  'pdf_report_summary',
                                  'pdf_pie_chart',
                                  'pdf_bar_chart',
                                  'pdf_hist_chart'
                                  ));
    $sugar_smarty->assign('formatodate_names', array(
                                  $mod_strings['LABDAY'],    
                                  $mod_strings['LABWEEK'],
                                  $mod_strings['LABMONTH'],
                                  $mod_strings['LABQUARTER'],
                                  $mod_strings['LABYEAR']
                                  ));
   $sugar_smarty->assign('formatodate_values', array(
                                  "d",    
                                  "w", 
                                  "m", 
                                  "q", 
                                  "y"
                                  ));
                                  
    $sugar_smarty->assign('formato_names', array(
                                  'A4',
                                  'A3',
                                  'A5',
                                  'Letter',
                                  'Legal', 
                                  'Custom'
                                  ));
   $sugar_smarty->assign('orient_names', array(
                                  $mod_strings['LABPORTRAIT'],
                                  $mod_strings['LABLANDSCAPE']
                                  ));   
   $sugar_smarty->assign('orient_val', array(
                                  'P',
                                  'L'
                                  ));    
    
    $sugar_smarty->display('custom/modules/SugarPrint/views/tpl/dialog1.tpl');
	}                      

}          

?>
  

  