<?php   
      
include("custom/modules/SugarPrint/views/mpdf57/mpdf.php");
class SugarPrintCls {
 
  const BASECHARTURL="http://support.datamain.it/charts/easytheme/";
  const MODE='win-1252';
  public $pdf;
  public $report_type;    
  public $filename;
  public $basepath;
  public $css;
  public $html;
  public $author; 
  public $crmtitle; 
  public $reporttitle;
  public $crmlogo;  
  public $crmlogowidth; 
  public $crmfontname;  
  public $crmfontsize;           
  public $pageformat;
  public $orientation;
  public $cmodule;
  public $sparkline=1;
  public $colTitles=array();
  public $colAlign=array();
  public $colSelect=array();
  public $detailtablist=array();
  public $detailbodyarray=array();  


  public $crmPDF_MARGIN_LEFT;
  public $crmPDF_MARGIN_TOP;
  public $crmPDF_MARGIN_RIGHT;
  public $crmPDF_MARGIN_BOTTOM;
  public $crmPDF_MARGIN_HEADER;
  public $crmPDF_MARGIN_FOOTER;

  public $dateformat;
  public $timeformat;
  public $lang;
  public $default_number_grouping_seperator;
  public $default_decimal_seperator;
  public $sigDigits;
  public $currency;
  public $datareport;

 

	function main() 
  {
         
   global $current_user;
   global $current_language;
   global $mod_strings;
   session_start();
   $this->basepath="custom/modules/SugarPrint/views/";  
   
   if($_POST["_report_type"]=='calendar')
   {
    $this->print_calendar();
    exit();
   }
 
   if(!isset($_POST["_tablecomplete"]))
     exit();
   
   if($_POST["_tablecomplete"]==0)
    {
       if(isset($_POST["_subpanelname"]))
       {
            $_SESSION['tableheaderarray'][] =  $_POST["_tableheaderarray"];
            $_SESSION['tablealignarray'][]=  $_POST["_tablealignarray"];
            $_SESSION['subpanelname'][]=$_POST["_subpanelname"];
            $_SESSION['tablebodyarraypanel'][]=$_SESSION['tablebodyarray'];
            $_SESSION['tablebodyarray']=array();
       }
       else
       {
          if($_POST["_session"]==''){
            $_SESSION['tablebodyarray']=$_POST["_tablebodyarray"];  
            $_SESSION['tableidarray']=$_POST["_tableidarray"];        
          }
          else {
              $_SESSION['tablebodyarray'] = array_merge($_SESSION['tablebodyarray'],$_POST["_tablebodyarray"]);
              $_SESSION['tableidarray'] = array_merge($_SESSION['tableidarray'],$_POST["_tableidarray"]);
          }       
       }
        exit();
    }
  
  
    if($_POST["_tablecomplete"]!=0)
    {
        if(isset($_POST["_tablebodyarray"]))
        {
             if($_SESSION['tablebodyarray']=== NULL)
             {
                $_SESSION['tablebodyarray'] = $_POST["_tablebodyarray"];  
                $_SESSION['tableidarray'] = $_POST["_tableidarray"];
             }               
             else
             {   
                $_SESSION['tablebodyarray'] = array_merge($_SESSION['tablebodyarray'],$_POST["_tablebodyarray"]);
                $_SESSION['tableidarray'] = array_merge($_SESSION['tableidarray'],$_POST["_tableidarray"]);
             }   
        }
        $this->init();

        if(($_POST["_report_type"]!='detailview')&&($_POST["_report_type"]!='calendar')) 
        { 
            if(isset($_POST["_cmodule"]))
             $this->cmodule=$_POST["_cmodule"];
            
            $this->colSelect=$_POST["_tableselectarray"];
          	for($i=0;$i<count($this->colSelect);$i++){
        	    $this->colSelect[$i]["label"] =  $this->replchr($this->colSelect[$i]["label"]);
          	}                
            if(isset($_POST["_tablealignarray"]))
               $this->colAlign=$_POST["_tablealignarray"]; 
             else
               $this->colAlign=array();                
       
            for($i=0;$i<count($this->colTitles);$i++){ 
              if(!isset($this->colAlign[$i]))
                $this->colAlign[$i]='left';
            }
            
            $_SESSION['tablebodyarray']=stripslashes_deep($_SESSION['tablebodyarray']);
              
           // check if there are extra fields
           $estraf=false;
           for($i=0;$i<count($this->colSelect);$i++){
             if((!empty($this->colSelect[$i]["name"]))&&(($this->colSelect[$i]["group"]==1)||($this->colSelect[$i]["sel"]==1)||($this->colSelect[$i]["calc"]!="")))
              $estraf=true;       
           }
          
          // EXTRAFIELDS
           if($estraf)
           {
             for($k=0;$k<count($_SESSION['tablebodyarray']);$k++) {
                 if((!empty($_SESSION['tableidarray'][$k]))&&(!empty($this->cmodule)))
                 {
                     $o = BeanFactory::newBean($this->cmodule);
                     $o->retrieve($_SESSION['tableidarray'][$k]);
                     for($i=0;$i<count($this->colSelect);$i++){
                      if((!empty($this->colSelect[$i]["name"]))&&(($this->colSelect[$i]["group"]==1)||($this->colSelect[$i]["sel"]==1)||($this->colSelect[$i]["calc"]!="")))
                        {
                             $this->colAlign[$i]='left';
                             switch ($this->colSelect[$i]["datatype"]) {
                                  case 'currency':
                                     $val= $this->currency." ".number_format($o->{$this->colSelect[$i]["name"]},$this->sigDigits,$this->default_decimal_seperator,$this->default_number_grouping_seperator);
                                     break;
                                   case 'decimal':   
                                     $tmp  =  explode($this->default_decimal_seperator, $o->{$this->colSelect[$i]["name"]});
                                     $val= number_format($tmp[0],0,$this->default_decimal_seperator,$this->default_number_grouping_seperator);
                                     if(count($tmp>1))
                                       $val.=$this->default_decimal_seperator.$tmp[1];
                                     break;
                                  default:
                                    $val=$o->{$this->colSelect[$i]["name"]};
                              }
                             $_SESSION['tablebodyarray'][$k][$this->origcol($this->colSelect[$i]["label"])]=$val;
                             
                        } 
                     }               
                  }
              }
            } 
         }

      if(!empty($_POST["_report_slick"]))
         {
          $_SESSION['report_type']=$_POST["_report_type"];
          $_SESSION['reporttitle']=$_POST["_reporttitle"];
          switch($_POST["_report_type"])
                {
                case "pdf_report_summary":
                  $this->table_slicksummary($_SESSION["tablebodyarray"]); 
                  break;
                case "pdf_report_crosstab":
                  $_SESSION['sparkline']=$_POST["_sparkline"];
                  $this->table_crosstab($_SESSION["tablebodyarray"]); 
                  break;
                default:
                  $curr_tot=0;
                  $decim=0;
                  $d=$_SESSION['tablebodyarray'];
                  $isdate=-1;
                  for($k=0;$k<count($d);$k++){  
                   for($i=0;$i<count($this->colSelect);$i++){  
                    $lev=$this->origcol($this->colSelect[$i]["label"]);        
                    if(($this->colSelect[$i]["calc"]!='')&&(empty($this->colSelect[$i]["curr"]))&&(!empty($d[$k][$lev])))
                     {
                       $this->colSelect[$i]["curr"]=$this->is_currency($d[$k][$lev]);
                       $this->colSelect[$i]["decimal"]=$this->get_decimal($d[$k][$lev]);
                     } 
                     if($this->colSelect[$i]["group"]==1)
                     {
                      $tipocampo=$this->repcoltype($d,$lev);
                      if(($tipocampo=="date")||($tipocampo=="datetime"))     
                      {
                       $isdate=$lev;     
                       $_SESSION['tablebodyarray'][$k][$lev]=$this->getGroupfromDate($d[$k][$lev],$tipocampo);
                      }
                     } 
                   }  
                  }
                  if($isdate!=-1)
                  {
                    $sort = array();
                    foreach($_SESSION['tablebodyarray'] as $k=>$v) {
                      $sort[$isdate][$k] = $v[$isdate];
                    }
                    array_multisort($sort[$isdate], SORT_DESC,$_SESSION['tablebodyarray']);
                  }
                  $_SESSION["tableheaderarray"]=$this->colTitles;  
                  $_SESSION["tableselectarray"]=$this->colSelect;  
                  
                }
          echo "index.php?module=SugarPrint&action=slick";
          return; 
         }
         
// DETAILVIEW
        if($_POST["_report_type"]=='detailview')
        {  
            $this->initpdf();
            $this->html='<h1>'.$this->reporttitle.'</h1>';
            for($k=0;$k<count($this->detailbodyarray);$k++){
               if(!empty($this->detailtablist[$k]))
                 $this->html.='<h4>'.$this->detailtablist[$k].'</h4>';
                 $this->html.='<table class="tmain" width="100%">';
                 for($z=0;$z<count($this->detailbodyarray[$k]);$z++){
                  $label='';
                  $data='';
                  $this->html.='<tr>';                  
                  for($y=0;$y<count($this->detailbodyarray[$k][$z]);$y++){

                    if($y % 2 != 0)
                    {
                      $data=$this->detailbodyarray[$k][$z][$y];
                      $this->html.='<td class="ligthrow"><span style="text-align:left;">'.$label.'</span><br>';  
                      $this->html.='<span style="text-align:left;">'.$data.'</span></td>';  
                      $label='';
                      $data='';
                    }
                    else
                    {
                      $label=$this->detailbodyarray[$k][$z][$y];
                    }
                  }   
                  if(count($this->detailbodyarray[$k][$z])==2)
                    $this->html.='<td class="ligthrow"></td>';  
                  $this->html.='</tr>';    
               }
               $this->html.='</table>';                             
            }
            // SUBPANELS
            for($W=0;$W<count($_SESSION['tablebodyarraypanel']);$W++){

              $colTitles=$_SESSION["tableheaderarray"][$W];  
              $_tablealignarray=$_SESSION["_tablealignarray"][$W]; 
              $this->colSelect=array();
              $this->colTitles=array(); 
              $this->colAlign=array(); 
            	for($i=0;$i<count($colTitles);$i++){
                $tmpsel=array();
                $tmpsel["label"]= $this->replchr($colTitles[$i]);
                $tmpsel["sel"]=1; 
                $tmpsel["group"]=0; 
                $tmpsel["calc"]=""; 
                $tmpsel["name"]=""; 
          	    $this->colTitles[$i] = $tmpsel["label"];
            	  $this->colSelect[$i]=$tmpsel;
                if(isset($_tablealignarray[$i]))
                  $this->colAlign[$i]=$_tablealignarray[$i];
              }                
                                         
               $this->html.='<h4>'.$_SESSION['subpanelname'][$W].'</h4>';
               $this->html.='<table class="tmain" width="100%">';   
               $this->singletable_print($_SESSION['tablebodyarraypanel'][$W]); 
               $this->html.='</table>';

           }

            $this->pdf->WriteHTML($this->css.$this->html); 
         
            $this->filename.=".pdf";
            $this->pdf->Output($this->filename, 'F');
            clearDir($this->basepath."_temp");
            clearSession();     
            echo $this->filename; 
            return; 
        } 

// EXCEL 


        if(($_POST["_report_type"]=='xls_detailview')||($_POST["_report_type"]=='xls_listview'))
        {  
          $this->ExportXLS($this->report_type); 
          clearDir($this->basepath."_temp");
          clearSession(); 
          echo $this->filename;
          exit();
        } 
        
 
        
        $this->initpdf();
        $this->html="";
        if($this->report_type=="pdf_report")
        {
          $this->tables_print($_SESSION['tablebodyarray'],$this->reporttitle,-1,null);   
          $this->pdf->WriteHTML($this->css.$this->html); 
        }
        if($this->report_type=="pdf_report_summary")
        {
          $this->tables_print_totals($_SESSION['tablebodyarray'],$this->reporttitle);       
          $this->pdf->WriteHTML($this->css.$this->html); 
        } 
        if($this->report_type=="pdf_report_crosstab")
        {
          $this->tables_print_crosstab($_SESSION['tablebodyarray'],$this->reporttitle);       
          $this->pdf->WriteHTML($this->css.$this->html); 
        } 
             
        if($this->report_type=="chart")
        {
          $this->chart_type=$_POST["_dochart"];
          $xc=$this->crmPDF_MARGIN_LEFT+20;
          $yc=$this->crmPDF_MARGIN_TOP+20;
          $w=$this->pdf->getPageWidth()*0.8-$this->crmPDF_MARGIN_LEFT-$this->crmPDF_MARGIN_RIGHT;
          $h=$this->pdf->getPageHeight()*0.8-$this->crmPDF_MARGIN_TOP-$this->crmPDF_MARGIN_BOTTOM;
          $image=$this->print_chart($_SESSION['tablebodyarray'],$w,$h); 
          $this->pdf->Image($image,$xc,$yc,$w,$h,'PNG','','M',false,300,'C');             
        }  
        $this->filename.=".pdf";
        $this->pdf->Output($this->filename, 'F');
        clearDir($this->basepath."_temp");
        clearSession();     
        echo $this->filename;  
    }  
   
  } 

  
  
  /***********************
    function print_calendar
  ***********************/
  function print_calendar()
  {
     
     
      $this->init();
      $this->initpdf();
      if($_POST["_calendar"]==1)
      {
         $_headers[]=array('',$this->reporttitle);
         $_bodies[]=$_POST["_bodiescalendar"];
      }
  
      if($_POST["_calendar"]==2)
      {
         
         $_headers[]=$_POST["_headerscalendar"];
         $_bodies[]=$_POST["_bodiescalendar"];
      }
      if($_POST["_calendar"]>2)
      {
         $_headers=$_POST["_headerscalendar"];
         $_bodies=$_POST["_bodiescalendar"];
         if($_POST["_calendar"]==4)
            $_tablestitles=$_POST["_tablestitles"]; 
      }
      if(isset($_POST["_tablealignarray"]))
        $_tablealignarray=$_POST["_tablealignarray"]; 
       else
        $_tablealignarray=array();  
      if($_POST["_calendar"]==1)
      {
         $_tablealignarray=array('left','left');
      }
           
      for($W=0;$W<count($_headers);$W++)
      {
        $colTitles=$_headers[$W]; 
  
        $this->colTitles=array(); 
      	for($i=0;$i<count($colTitles);$i++){
          $this->colTitles[$i] = $colTitles[$i];
          $tmpsel=array();
          $tmpsel["label"]=$this->replchr($colTitles[$i]);
          $tmpsel["sel"]=1; 
          $tmpsel["group"]=0; 
          $tmpsel["calc"]=""; 
          $tmpsel["name"]=""; 
      	  $this->colSelect[$i]=$tmpsel;
          if(isset($_tablealignarray[$i]))
            $this->colAlign[$i]=$_tablealignarray[$i];
        }          
        if($_POST["_calendar"]==4)
            $tabletitle=$_tablestitles[$W];
        else
            $tabletitle="";
        $this->html='<h1>'.$tabletitle.'</h1>';
        $this->html.='<table class="tmain" width="100%">';
        $this->singletable_print($_bodies[$W]);  
        $this->html.='</table>';
        $this->pdf->WriteHTML($this->css.$this->html); 
     } 
     $this->filename.=".pdf";
     $this->pdf->Output($this->filename, 'F');
     clearDir($this->basepath."_temp");
     clearSession();     
     echo $this->filename;  
  }

  /***********************
    function print_chart
  ***********************/
  function print_chart($d,$imgwidth,$imgheight)
  {
        $tablechart=array();
        for($i=0;$i<count($this->colSelect);$i++){          
          $lev=$this->origcol($this->colSelect[$i]["label"]);
          if($this->colSelect[$i]["group"]==1)
           { 
             $tablechartlabels=array();
             $tipocampo=$this->repcoltype($d,$lev);
             $arrfil=$this->arrgrpval($d,$lev,$tipocampo);  
             if(($tipocampo=="date")||($tipocampo=="datetime"))
               asort($arrfil);
             foreach ($arrfil as $key=>$value) { 
               $af=$this->filterTable($d,$value,$lev,$tipocampo);
               if(count($af)>0)
               {   
                 if(strlen($value)>30)
                    $tablechartlabels[]=substr($value,0,30); 
                 else
                    $tablechartlabels[]=$value;
                 $tot=array();
                 $decim=array();
                 for($z=0;$z<count($this->colSelect);$z++){
                  if($this->colSelect[$z]["calc"]=="sum"){
                    $tot[$z]=0;
                    $decim[$z]=0;
                  } 
                 }  
                 for($k=0;$k<count($af);$k++){  
                    for($z=0;$z<count($this->colSelect);$z++){
                      $lev2=$this->origcol($this->colSelect[$z]["label"]);
                      if($this->colSelect[$z]["calc"]=="sum")
                      { 
                       $decim[$z]=$this->get_decimal($af[$k][$lev2]);
                       $tot[$z]+=$this->get_numeric($af[$k][$lev2]);           
                      }
                      if($this->colSelect[$z]["calc"]=="count")
                      { 
                       $tot[$z]++;           
                      }
                    }
                }
                $tablechart_row=array();
                for($z=0;$z<count($this->colSelect);$z++){
                  $lev2=$this->origcol($this->colSelect[$z]["label"]);
                  if($this->colSelect[$z]["calc"]=="sum")
                  { 
                   $tablechart_row[]=$this->get_format($tot[$z],$decim[$z],'');
                  }
                } 
                $tablechart[]=$tablechart_row;  
               }                      
             }
          } 
        }      
        $tablechartlegend=array();
        for($z=0;$z<count($this->colSelect);$z++){
         if($this->colSelect[$z]["calc"]=="sum")
            {
              $tablechartlegend[]=$this->colSelect[$z]["label"];    
            } 
        }   
    
        return $this->dochart($this->chart_type,$tablechart,$tablechartlabels,$tablechartlegend,$this->reporttitle,$imgwidth,$imgheight);
  }  
       
  /***********************
    function do_post_request
  ***********************/
  function do_post_request($url, $data, $optional_headers = null)
  {
      $params = array('http' => array(
                  'method' => 'POST',
                  'content' => $data
                ));
      if ($optional_headers !== null) {
        $params['http']['header'] = $optional_headers;
      }
      $ctx = stream_context_create($params);
      $fp = @fopen($url, 'rb', false, $ctx);
      if (!$fp) {
        throw new Exception("Problem with $url, $php_errormsg");
      }
      $response = @stream_get_contents($fp);
      if ($response === false) {
        throw new Exception("Problem reading data from $url, $php_errormsg");
      }
      return $response;
    }
    
    /***********************
      function dochart
    ***********************/
    function dochart($type,$data,$datalabels,$datalegend,$title,$w,$h)
  	{
         $datapost = array(
          "chart" => $type,   //pie bar line
          "width" => $w,
          "height" => $h,
          "data" => $data,      
          "labels" => $datalabels,
          "legend" => $datalegend
        );
        $postdata=http_build_query($datapost);
    
        return SugarPrintCls::BASECHARTURL.$this->do_post_request(SugarPrintCls::BASECHARTURL."getcharts2.php",$postdata);
    }

      /***********************
      function filterTable
      filter for group by report
    ***********************/
    function filterTable($farray,$valore,$colonna,$tipocampo) {

         $campodata=false;
         $dateformat=$this->dateformat;
         $timeformat=$this->timeformat;
         if(($tipocampo=="date")||($tipocampo=="datetime"))
            $campodata=true;
         return array_values(array_filter($farray, 
                function($arrayValue) use($valore,$colonna,$campodata,$tipocampo,$dateformat,$timeformat) 
                { 
                  if($campodata)
                  {   

                   if(!empty($valore))
                     {
                       $timecurrent =getGroupfromDate($arrayValue[$colonna],$tipocampo,$dateformat,$timeformat);
                       if($valore==$timecurrent)
                         return true;
                       else
                        return false;
                     } 
                     else
                     {
                        return false;
                     }                                  
                  }
                  else
                  {
                    return $arrayValue[$colonna] == $valore; 
                  }
                                 
                } 
                )); 
    } 
    

    /***********************
      function repcoltype
    ***********************/
    function repcoltype($d,$col) { 
       // date
       $isdate=true;
       foreach ($d as $key=>$subarr) {
          if(!empty($subarr[$col]))
          {
           $tmpdate=date_parse_from_format($this->dateformat, $subarr[$col]);
           if(count($tmpdate["errors"])>0)
           {
             $isdate=false;
             break;
           }            
          }           
       }
       if($isdate) 
          return "date";
       // datetime
       $isdatetime=true;
       foreach ($d as $key=>$subarr) {
          if(!empty($subarr[$col]))
          {
           $tmpdate=date_parse_from_format($this->dateformat." ".$this->timeformat, $subarr[$col]);
           if(count($tmpdate["errors"])>0)
           {
             $isdatetime=false;
             break;
           }            
          }           
       }   
       if($isdatetime)
          return "datetime";  
     return "text";
    }               


    /***********************
      function arrgrpval
    ***********************/
    function arrgrpval($d,$col,$tipocampo) { 
       $datagroup=array();
       $isdate=false;
       if(($tipocampo=="date")||($tipocampo=="datetime"))
       {
        $isdate=true;
       }
       foreach ($d as $key=>$subarr) { 
          if($isdate)
          {
            $datagroup[]=$this->getGroupfromDate($subarr[$col],$tipocampo);
          }
          else
          {
            $datagroup[$subarr[$col]]= $subarr[$col];      
         }          
       }  
      if($isdate)
      {
          $datagroup = array_unique($datagroup);  
          arsort($datagroup);    // sort descend
      }
      else
      {      
          $datagroup = array_unique($datagroup);  
          asort($datagroup);
      }          
      return $datagroup;
   }


    /***********************
      function singletable_print
    ***********************/
    function singletable_print($d)
    {
        $a=$this->colAlign;
        $s=$this->colSelect;
        $this->html.='<tr>';
        if((!isset($s))||(!isset($a)))
           return;    
              
        for($i=0;$i<count($s);$i++){
             if(!empty($s[$i]["sum"]))           
               $this->html.='<td class="tt" align="right">'.$s[$i]["label"].'</td>';           
             else
               if(($s[$i]["sel"]==1)&&($s[$i]["group"]!=1))
                 $this->html.='<td class="tt" align="'.$a[$i].'">'.$s[$i]["label"].'</td>';      
         
        }        
        
        $this->html.='</tr>';
        $oddrow="";
        for($k=0;$k<count($d);$k++){    
          if(empty($oddrow))
           $oddrow='class="oddrow"';
          else                                 
           $oddrow='';
          $this->html.='<tr '.$oddrow.'>';
          for($i=0;$i<count($s);$i++){         
           if(!empty($s[$i]["sum"]))           
             $this->html.='<td align="right">'.$d[$k][$this->origcol($s[$i]["label"])].'</td>';
           else
           if(($s[$i]["sel"]==1)&&($s[$i]["group"]!=1))
          //   $this->html.='<td align="'.$a[$i].'">'.$d[$k][$this->origcol($s[$i]["label"])].'</td>';   
             $this->html.='<td>'.$d[$k][$this->origcol($s[$i]["label"])].'</td>';   
          }
          $this->html.='</tr>';
        }
   
    }

  
     /***********************
      function table_crosstab
    ***********************/
    function table_crosstab($d)
    {       
        $body=array();

        $f_cols=-1;
        $f_tipocols='';
        $f_rows=-1;
        $f_tiporows='';
        $f_tipocalc='';
        $curr_tot=0;
        $decim=0;

        $row=array();
        $rowselect=array();      

        for($i=0;$i<count($this->colSelect);$i++){          
          $lev=$this->origcol($this->colSelect[$i]["label"]);
          if(($this->colSelect[$i]["group"]==1)&&($f_cols!=-1)&&($f_rows==-1))   // NB  $f_rows==-1  
           { 
             $f_tiporows=$this->repcoltype($d,$lev);  
             $arr_rows=$this->arrgrpval($d,$lev,$f_tiporows);
             $f_rows=$lev;
             $row[]=$this->colSelect[$i]["label"];
             $rowselect[]=array("sel"=>1,"label"=>$this->colSelect[$i]["label"],"group"=>0);
           }
          if(($this->colSelect[$i]["group"]==1)&&($f_cols==-1))       // first group==1 is columns field
           { 
             $f_tipocols=$this->repcoltype($d,$lev);
             $arr_cols=$this->arrgrpval($d,$lev,$f_tipocols);   
             if(($f_tipocols=="date")||($f_tipocols=="datetime"))
               asort($arr_cols);
             $f_cols=$lev;
           }
          if($this->colSelect[$i]["calc"]!="")
          {
           $f_tipocalc=$this->colSelect[$i]["calc"];
           $f_calc=$lev;
          }
                    
        }  
        foreach ($arr_cols as $key=>$value) { 
           $row[]=$value;
           $rowselect[]=array("sel"=>1,"label"=>$value,"group"=>0);
         }
        $row[]=$mod_strings['GRANDTOTAL'];
        $rowselect[]=array("sel"=>1,"label"=>$mod_strings['GRANDTOTAL'],"group"=>0);
        $_SESSION["tableheaderarray"]=$row;  
        $_SESSION["tableselectarray"]=$rowselect;
 
        $tot_C=array();
        $count_C=array();             
        foreach ($arr_rows as $keyrow=>$valuerow) {  
            $row=array();    
            $d1=$this->filterTable($d,$valuerow,$f_rows,$f_tiporows);         
            $row[]=$valuerow;
            $tot_R=0;
            $count_R=0;
            $CC=0;
            foreach ($arr_cols as $keycols=>$valuecols) { 
               $af=$this->filterTable($d1,$valuecols,$f_cols,$f_tipocols);
               $tot=0;
               for($k=0;$k<count($af);$k++){  
                    if(($f_tipocalc=="sum")||($f_tipocalc=="avg"))
                    { 
                     $curr_tot=$this->is_currency($af[$k][$f_calc]);
                     $decim=$this->get_decimal($af[$k][$f_calc]);
                     $tot+=$this->get_numeric($af[$k][$f_calc]);           
                    }
                    if($f_tipocalc=="count")
                    { 
                     $tot++;           
                    }
               }
               $count_R+=count($af); 
               if($f_tipocalc=="sum")   
                $tot_R+=$tot;             
               if($f_tipocalc=="avg")
                { 
                  $tot=$tot/count($af);      
                }
               if(count($af)>0)
                 $row[]= $tot;
               else
                 $row[]= "";
               
               if(empty($tot_C[$CC]))
                 $tot_C[$CC]=0;
               $tot_C[$CC]+=$tot;  
               if(empty($count_C[$CC]))
                 $count_C[$CC]=0;   
               $count_C[$CC]+=count($af);                 
               $CC++;
            }
            if($f_tipocalc=="sum")
              $row[]=$tot_R;
            if($f_tipocalc=="avg")
              $row[]=$tot_R/$count_R;
            if($f_tipocalc=="count")
              $row[]=$count_R;
            $body[]=$row;
         }
         $row=array();
         $tot_R=0;
         $count_R=0;
         $row[]=$mod_strings['GRANDTOTAL'];
         for($k=0;$k<count($tot_C);$k++){ 
            $tot_R+=$tot_C[$k];
            $count_R+=$tot_C[$k];
            if($f_tipocalc=="sum")
             $row[]=$tot_C[$k];
            if($f_tipocalc=="avg")
              $row[]=$tot_C[$k]/$count_C[$k];
            if($f_tipocalc=="count")
              $row[]=$count_C[$k];             
          }      
          if($f_tipocalc=="avg")
          {
          $tot_R/$count_R;
          }
         $row[]=$tot_R;
         $body[]=$row;
         $_SESSION["decim"]= $decim;
         $_SESSION["curr_tot"]= $curr_tot;
         $_SESSION["tablebodyarray"]= $body;

         return;
    }

    /***********************
      function tables_print_crosstab
    ***********************/
    function tables_print_crosstab($d,$t)
    {
        if(!empty($t))
          $this->html.='<h1>'.$t.'</h1>';
        
        $this->html.='<table class="tmain">';
        $f_cols=-1;
        $f_tipocols='';
        $f_rows=-1;
        $f_tiporows='';
        $f_tipocalc='';
        $curr_tot=0;
        $decim=0;
        for($i=0;$i<count($this->colSelect);$i++){          
          $lev=$this->origcol($this->colSelect[$i]["label"]);
          if(($this->colSelect[$i]["group"]==1)&&($f_cols!=-1)&&($f_rows==-1))      
           { 
             $f_tiporows=$this->repcoltype($d,$lev);  
             $arr_rows=$this->arrgrpval($d,$lev,$f_tiporows);
             $f_rows=$lev;
           }
          if(($this->colSelect[$i]["group"]==1)&&($f_cols==-1))       // first group==1 is columns field
           { 
             $f_tipocols=$this->repcoltype($d,$lev);
             $arr_cols=$this->arrgrpval($d,$lev,$f_tipocols);  
             if(($f_tipocols=="date")||($f_tipocols=="datetime"))
               asort($arr_cols);

             $f_cols=$lev;
           }
          if($this->colSelect[$i]["calc"]!="")
          {
           $f_tipocalc=$this->colSelect[$i]["calc"];
           $f_calc=$lev;
          }
          
           
        }  
        $this->html.='<tr>'; 
        $this->html.='<td class="tt" align="center">&nbsp;</td>';          
        foreach ($arr_cols as $key=>$value) { 
           $this->html.='<td class="tt" align="center">'.$value.'</td>';
         }
        $this->html.='<td class="tt" align="center">&nbsp;</td>';
        $this->html.='</tr>';  
        $tot_C=array();
        $count_C=array();
        foreach ($arr_rows as $keyrow=>$valuerow) {
            $d1=$this->filterTable($d,$valuerow,$f_rows,$f_tiporows);         
            if(empty($oddrow))
             $oddrow='class="oddrow"';
            else                                 
             $oddrow='';
            $this->html.='<tr '.$oddrow.'>';
            $this->html.='<td>'.$valuerow.'</td>';
            $tot_R=0;
            $count_R=0;
            $CC=0;
            foreach ($arr_cols as $keycols=>$valuecols) { 
               $af=$this->filterTable($d1,$valuecols,$f_cols,$f_tipocols);
               $tot=0;
               for($k=0;$k<count($af);$k++){  
                    if(($f_tipocalc=="sum")||($f_tipocalc=="avg"))
                    { 
                     $curr_tot=$this->is_currency($af[$k][$f_calc]);
                     $decim=$this->get_decimal($af[$k][$f_calc]);
                     $tot+=$this->get_numeric($af[$k][$f_calc]);           
                    }
                    if($f_tipocalc=="count")
                    { 
                     $tot++;           
                    }
               }
               $count_R+=count($af); 
               if($f_tipocalc=="sum")   
                $tot_R+=$tot;             
               if($f_tipocalc=="avg")
                { 
                  $tot=$tot/count($af);      
                }
               if(count($af)>0)
                 $this->html.='<td align="right">'.$this->get_format($tot,$decim,$curr_tot).'</td>';
               else
                 $this->html.='<td align="right">&nbsp;</td>';               
               if(empty($tot_C[$CC]))
                 $tot_C[$CC]=0;
               $tot_C[$CC]+=$tot;  
               if(empty($count_C[$CC]))
                 $count_C[$CC]=0;   
               $count_C[$CC]+=count($af);                 
               $CC++;
            }
            if($f_tipocalc=="sum")
              $this->html.='<td align="right">'.$this->get_format($tot_R,$decim,$curr_tot).'</td>';
            if($f_tipocalc=="avg")
              $this->html.='<td align="right">'.$this->get_format($tot_R/$count_R,$decim,$curr_tot).'</td>';
            if($f_tipocalc=="count")
              $this->html.='<td align="right">'.$this->get_format($count_R,$decim,$curr_tot).'</td>';
            $this->html.='</tr>';   
         } 
         $this->html.='<tr>';
         $this->html.='<td>'.$mod_strings['GRANDTOTAL'].'</td>';
         $tot_R=0;
         $count_R=0;
         for($k=0;$k<count($tot_C);$k++){ 
            $tot_R+=$tot_C[$k];
            $count_R+=$tot_C[$k];
            if($f_tipocalc=="sum")
             $this->html.='<td align="center">'.$this->get_format($tot_C[$k],$decim,$curr_tot).'</td>';
            if($f_tipocalc=="avg")
              $this->html.='<td align="center">'.$this->get_format($tot_C[$k]/$count_C[$k],$decim,$curr_tot).'</td>';
            if($f_tipocalc=="count")
              $this->html.='<td align="center">'.$this->get_format($count_C[$k],$decim,$curr_tot).'</td>';
          }      
          if($f_tipocalc=="avg")
          {
          $tot_R/$count_R;
          }
         $this->html.='<td align="center">'.$this->get_format($tot_R,$decim,$curr_tot).'</td>';
         $this->html.='</tr>';
         $this->html.='</table>';
    }


  
    /*****************************
      function table_slicksummary
    *****************************/
    function table_slicksummary($d)
    {
        $body=array(); 

        $row=array();
        $rowselect=array();      
        for($i=0;$i<count($this->colSelect);$i++){ 
          if($this->colSelect[$i]["group"]==1)
          {
           $row[]=$value;
           $rowselect[]=array("sel"=>1,"label"=>$this->colSelect[$i]["label"],"group"=>0);          
          }
        }
        for($i=0;$i<count($this->colSelect);$i++){ 
          if($this->colSelect[$i]["calc"]!='')
          {
           $row[]=$value;
           $rowselect[]=array("sel"=>1,"label"=>$this->colSelect[$i]["label"],"group"=>0);          
          }
        }       
        $_SESSION["tableheaderarray"]=$row;  
        $_SESSION["tableselectarray"]=$rowselect;
        $tot_R=0;
        $count_R=0;
        $curr_tot=0;
        $decim=0;
        for($i=0;$i<count($this->colSelect);$i++){          
          $lev=$this->origcol($this->colSelect[$i]["label"]);
          if($this->colSelect[$i]["group"]==1)
           { 
             $tipocampo=$this->repcoltype($d,$lev);
             $arrfil=$this->arrgrpval($d,$lev,$tipocampo);  
             
             foreach ($arrfil as $key=>$value) {  
               $row=array();             
               $af=$this->filterTable($d,$value,$lev,$tipocampo);
               if(count($af)>0)
               {   
                 $tot=array();
                 $curr_tot=array();
                 $decim=array();
                 for($z=0;$z<count($this->colSelect);$z++){
                  if($this->colSelect[$z]["calc"]!=""){
                    $tot[$z]=0;
                    $decim[$z]=0;
                  } 
                 }  
                 for($k=0;$k<count($af);$k++){  
                    for($z=0;$z<count($this->colSelect);$z++){
                      $lev2=$this->origcol($this->colSelect[$z]["label"]);
                      if(($this->colSelect[$z]["calc"]=="sum")||($this->colSelect[$z]["calc"]=="avg"))
                      { 
                       $f_tipocalc= $this->colSelect[$z]["calc"];
                       $curr_tot[$z]=$this->is_currency($af[$k][$lev2]);
                       $decim[$z]=$this->get_decimal($af[$k][$lev2]);
                       $decim= $decim[$z];
                       $tot[$z]+=$this->get_numeric($af[$k][$lev2]);    
                       $curr_tot= $curr_tot[$z];       
                      }
                      if($this->colSelect[$z]["calc"]=="count")
                      { 
                       $f_tipocalc= $this->colSelect[$z]["calc"];
                       $tot[$z]++;           
                      }

                    }
                 }
                 for($z=0;$z<count($this->colSelect);$z++){
                    if($this->colSelect[$z]["calc"]=="avg")
                    { 
                     $tot[$z]=$tot[$z]/count($af);      
                    }
                 } 
                 $row[]=$value;
                 for($z=0;$z<count($this->colSelect);$z++){
                      if($this->colSelect[$z]["calc"]!=""){ 
                       $row[]=$this->get_format($tot[$z],$decim[$z],$curr_tot[$z]);
                       $tot_R+=$tot[$z];
                       }
                 }
                 $count_R+=count($af);  
                 $body[]=$row;
               }                      
             }
             
           } 
        } 
   
       if($f_tipocalc=="avg")
        {
        $tot_R/$count_R;
        }
       $row=array(); 
       $row[]=$mod_strings['GRANDTOTAL']; 
       $row[]=$this->get_format($tot_R,$decim,$curr_tot);
       $body[]=$row;
       
      $_SESSION["tablebodyarray"]= $body;
    }



  
    /*****************************
      function tables_print_totals
    *****************************/
    function tables_print_totals($d,$t)
    {
          
        if(!empty($t))
          $this->html.='<h1>'.$t.'</h1>';
        
        $this->html.='<table class="tmain">';
        $this->html.='<tr>';
        for($z=0;$z<count($this->colSelect);$z++){
            if($this->colSelect[$z]["group"]==1)
                $this->html.='<td class="tt" align="'.$this->colAlign[$z].'">'.$this->colSelect[$z]["label"].'</td>';
         }
        for($z=0;$z<count($this->colSelect);$z++){
            if($this->colSelect[$z]["calc"]!="")
                $this->html.='<td class="tt right_al">'.$this->colSelect[$z]["label"].'</td>';
         }

        $this->html.='</tr>';         

        for($i=0;$i<count($this->colSelect);$i++){          
          $lev=$this->origcol($this->colSelect[$i]["label"]);
          if($this->colSelect[$i]["group"]==1)
           { 
             $tipocampo=$this->repcoltype($d,$lev);
             $arrfil=$this->arrgrpval($d,$lev,$tipocampo);  
             $oddrow="";
             foreach ($arrfil as $key=>$value) { 
               $af=$this->filterTable($d,$value,$lev,$tipocampo);
               if(count($af)>0)
               {   
      
                 if(empty($oddrow))
                  $oddrow='class="oddrow"';
                 else                                 
                  $oddrow='';
                 $this->html.='<tr '.$oddrow.'>';
              
                 $tot=array();
                 $curr_tot=array();
                 $decim=array();
                 for($z=0;$z<count($this->colSelect);$z++){
                  if($this->colSelect[$z]["calc"]!=""){
                    $tot[$z]=0;
                    $decim[$z]=0;
                  } 
                 }  
                 for($k=0;$k<count($af);$k++){  
                    for($z=0;$z<count($this->colSelect);$z++){
                      $lev2=$this->origcol($this->colSelect[$z]["label"]);
                      if(($this->colSelect[$z]["calc"]=="sum")||($this->colSelect[$z]["calc"]=="avg"))
                      { 
                       $curr_tot[$z]=$this->is_currency($af[$k][$lev2]);
                       $decim[$z]=$this->get_decimal($af[$k][$lev2]);
                       $tot[$z]+=$this->get_numeric($af[$k][$lev2]);           
                      }
                      if($this->colSelect[$z]["calc"]=="count")
                      { 
                       $tot[$z]++;           
                      }

                    }
                 }
                 for($z=0;$z<count($this->colSelect);$z++){
                    if($this->colSelect[$z]["calc"]=="avg")
                    { 
                     $tot[$z]=$tot[$z]/count($af);      
                    }
                 } 
                 $this->html.='<td>'.$value.'</td>';
                 for($z=0;$z<count($this->colSelect);$z++){
                      if($this->colSelect[$z]["calc"]!="") 
                       $this->html.='<td align="right">'.$this->get_format($tot[$z],$decim[$z],$curr_tot[$z]).'</td>';
                 }
                 $this->html.='</tr>';

               }                      
             }
             
           } 
        } 
        $this->html.='</table>';
    }


   
    /***********************
      function tables_print
    ***********************/
    function tables_print($d,$t,$clev,$grpname)
    {
        if(count($d)==0)
          return;
          
        if(!empty($t))
          $this->html.='<h1>'.$t.'</h1>';
        $isgroup=false; 
        $iscalc=false;
        $pad="";
        
        for($i=0;$i<count($this->colSelect);$i++){   
          if(!empty($this->colSelect[$i]["calc"]))
            $iscalc=true;
        } 
        for($i=0;$i<count($this->colSelect);$i++){          
          $lev=$this->origcol($this->colSelect[$i]["label"]);
          if(($this->colSelect[$i]["group"]==1)&&($clev!=-1))
             $pad.="&nbsp;&nbsp;&nbsp;";
          if(($this->colSelect[$i]["group"]==1)&&($i>$clev))
           { 
             $tipocampo=$this->repcoltype($d,$lev);
             $isgroup=true;
             $arrfil=$this->arrgrpval($d,$lev,$tipocampo);  
             foreach ($arrfil as $key=>$value) { 
               $af=$this->filterTable($d,$value,$lev,$tipocampo);
               if(count($af)>0)
               {
                  if($clev==-1)
                     $this->html.='<br><br><h3>'.$pad.$value.'</h3>';
                  else
                     $this->html.='<h4>'.$pad.$value.'</h4>';
                  $this->tables_print($af,null,$i,$pad.$value);   
               }                      
             }

             if(empty($grpname))
             {
                 if($iscalc)
                    $this->html.='<h3>'.$mod_strings['GRANDTOTAL'].'</h3>';
             }
             else
             {
     //            $this->html.='<h4>'.$grpname.'</h4>';
             }
 
             $this->html.='<table class="tmain" width="100%">'; 
             $this->printsummary($d,$grpname); 
             $this->html.='</table>';
             return;
           } 
        } 
        if(!$isgroup)
        {
           $this->html.='<table class="tmain" width="100%">';
           $this->singletable_print($d); 
           $this->printsummary($d); 
           $this->html.='</table>';
        }
    }
 
    /***********************
      function printsummary
    ***********************/
    function printsummary($d,$valc=null) { 
    
     if((!empty($valc))&&($this->colSelect[0]["calc"]!=""))
      {
        $this->html.='<tr><td><h3>'.$valc.':</h3></td></tr>';
      }
    
       $tot=array();
       $curr_tot=array();
       $decim=array();
       for($i=0;$i<count($this->colSelect);$i++){
        if($this->colSelect[$i]["calc"]!="")
         {
          $tot[$i]=0;
          $decim[$i]=0;
         }
       }
       $printtot=false;    
       for($k=0;$k<count($d);$k++){  
          for($i=0;$i<count($this->colSelect);$i++){
            $lev=$this->origcol($this->colSelect[$i]["label"]);
            if(($this->colSelect[$i]["calc"]=="sum")||($this->colSelect[$i]["calc"]=="avg"))
            { 
             $curr_tot[$i]=$this->is_currency($d[$k][$lev]);
             $decim[$i]=$this->get_decimal($d[$k][$lev]);
             $tot[$i]+=$this->get_numeric($d[$k][$lev]);           
             $printtot=true;
            }
            if($this->colSelect[$i]["calc"]=="count")
            { 
             $tot[$i]++;         
             $printtot=true;
            }

          }
       }
       for($i=0;$i<count($this->colSelect);$i++){
         if($this->colSelect[$i]["calc"]=="avg")
           $tot[$i]=$tot[$i]/count($d);
        }
       if($printtot)
       {
         $this->html.='<tr>';
         for($i=0;$i<count($this->colSelect);$i++){
            $lev=$this->origcol($this->colSelect[$i]["label"]);
            if(($this->colSelect[$i]["sel"]==1)&&($this->colSelect[$i]["group"]!=1))
              {
              switch ($this->colSelect[$i]["calc"])
                {
                case "sum":
                 $this->html.='<td class="bsx" align="'.$this->colAlign[$i].'">'.$this->colSelect[$i]["label"].' ('.$mod_strings['LABSUM'].')</td>';
                  break;
                case "avg":
                 $this->html.='<td class="bsx" align="'.$this->colAlign[$i].'">'.$this->colSelect[$i]["label"].' ('.$mod_strings['LABAVG'].')</td>';
                  break;
                case "count":
                 $this->html.='<td class="bsx" align="'.$this->colAlign[$i].'">'.$this->colSelect[$i]["label"].' ('.$mod_strings['LABCOUNT'].')</td>';
                  break;
                default:
                  $this->html.='<td class="bsx" ></td>';
                }
              }
         }
         
         $this->html.='</tr><tr>';         
         for($i=0;$i<count($this->colSelect);$i++){
            $lev=$this->origcol($this->colSelect[$i]["label"]);
            if(($i==0)&&(!empty($valc))&&($this->colSelect[0]["calc"]==""))
            {
              $this->html.='<td class="bsx" ><h3>'.$valc.':</h3></td>';
            }  
            else
            {
            if(($this->colSelect[$i]["sel"]==1)&&($this->colSelect[$i]["group"]!=1))
              if($this->colSelect[$i]["calc"]!="")
                $this->html.='<td class="right_al bsx_2">'.$this->get_format($tot[$i],$decim[$i],$curr_tot[$i]).'</td>';
              else                                                                  
                $this->html.='<td class="bsx"></td>';
            }

         }
         $this->html.='</tr>';         
      }      
    }
    
    /***********************
      function is_currency
    ***********************/
    function is_currency($val) { 
      $pos = strpos($val, $this->currency);
      if ($pos === false) 
        return ""; 
      else
        return $this->currency; 
    } 
      
    /***********************
      function get_decimal
    ***********************/
    function get_decimal($val) { 
      $pos = strpos($val, $this->default_decimal_seperator);
      if ($pos === false) 
        return 0; 
      else
        return strlen($val)-$pos-1; 
    }   
        
   /***********************
      function get_decimal
    ***********************/
    function get_format($val,$decim,$curr_tot) {  
      if(!empty($curr_tot))
        $strval = $curr_tot." ".number_format($val,$decim,$this->default_decimal_seperator,$this->default_number_grouping_seperator);
      else
        $strval = number_format($val,$decim,$this->default_decimal_seperator,$this->default_number_grouping_seperator);
      return $strval; 
    }         
                                                                                       
    /***********************
      function fieldtoxls
    ***********************/
    function fieldtoxls($val) { 

      $cn = str_replace($this->default_number_grouping_seperator, "", $val);
      $cn = str_replace($this->currency, "", $cn);   
      return $cn;
    } 

    /***********************
      function get_numeric
    ***********************/
    function get_numeric($val) { 

      $tmp=localeconv();
       
      $cn = str_replace($this->default_number_grouping_seperator, "", $val);
      $cn = str_replace($this->default_decimal_seperator, $tmp['decimal_point'], $cn);
      $cn = str_replace($this->currency, "", $cn);   
      $cn = str_replace(" ", "", $cn);        
       if (is_numeric($cn)) { 
         return $cn + 0; 
       } 
      return 0; 
    } 
    
    /***********************
      function origcol
    ***********************/
    function origcol($v)
    { 
     $z=-1;
     for($k=0;$k<count($this->colTitles);$k++){   
       if($this->colTitles[$k]==$v)
        $z=$k;
     } 
     if($z==-1)
     {
       $y=count($this->colTitles);
       for($k=0;$k<count($this->colSelect);$k++){    
          if($this->colSelect[$k]["label"]==$v)
             return $y;
          $y++;   
       }  
     }    
     return $z;
    }   

    /***********************
      function init
    ***********************/
    function init()
    {
 
       global $current_user;
       global $current_language;   
       require_once 'modules/Configurator/Configurator.php';
       $conf = new Configurator();
       $conf->loadConfig(); 
                 
       $this->dateformat = $current_user->getPreference('datef');  
       $this->timeformat = $current_user->getPreference('timef');  
       $this->lang=$current_language;
       $this->default_number_grouping_seperator= $current_user->getPreference('num_grp_sep');  
       $this->default_decimal_seperator= $current_user->getPreference('dec_sep');
       $this->sigDigits= $current_user->getPreference('default_currency_significant_digits');      
       $current_users_currency = new Currency();
     	 if($current_user->getPreference('currency')) 
            $current_users_currency->retrieve($current_user->getPreference('currency'));
    			else 
            $current_users_currency->retrieve('-99'); // use default if none set
       $currency = $current_users_currency;
       $this->currency =$currency->symbol;
     
       // load css  
      $this->css='<style>'.file_get_contents( $this->basepath.'../css/reportstyle.css').'</style>';
       
       $this->author=$conf->config['sugarprint_author'];
       if(empty($this->author))
          $this->author="Sugar Print";
          
       $this->crmfontname=$conf->config['SugarPrint_fontname'];
       if(empty($this->crmfontname))     
          $this->crmfontname="helvetica";
          
       $this->crmfontsize=$conf->config['SugarPrint_fontsize'];
       if(empty($this->crmfontsize))           
          $this->crmfontsize=9;

       if(isset($_POST["_detailtablist"]))
        $this->detailtablist=$_POST["_detailtablist"];

       if(isset($_POST["_detailbodyarray"]))
        $this->detailbodyarray=$_POST['_detailbodyarray'];

       $this->crmtitle=$_POST['_crmtitle'];                              

       $this->pageformat="A4";
       if(isset($_POST["_pageformat"])) {
           $this->pageformat=$_POST["_pageformat"];
           if($this->pageformat=='Custom')
           {
             $this->pageformat=array($_POST["_customwidth"],$_POST["_customheight"]);
           }  
       }
       else
       {
            $this->pageformat="A4";
       }
        
       if(isset($_POST["_orientation"]))
         $this->orientation=$_POST["_orientation"];
       else
         $this->orientation="L";
         
       $this->filename=$this->basepath."_temp/".uniqid();
         
       if($_POST["_mostralogo"]==1)
          {
              if(!empty($_POST["_companylogo"]))
              {
                 $this->crmlogo=$_POST["_companylogo"];
                 if (strpos($this->crmlogo, "?")!==false) 
                    {
                     $tmp=explode("?",$this->crmlogo);
                     $this->crmlogo=$tmp[0];
                     
                    }
              }
              else
              {   
                $this->crmlogo="";
              }
          }
          else
          {
              $this->crmlogo="";
          }

        $this->report_type=$_POST["_report_type"];
        $this->reporttitle=$_POST["_reporttitle"];
        $this->colTitles=$_POST["_tableheaderarray"];
      	for($i=0;$i<count($this->colTitles);$i++){
          	$this->colTitles[$i] =  $this->replchr($this->colTitles[$i]);
      	}                  
        $date = new DateTime();
        $this->datareport=$date->format($this->dateformat);


    }

    /***********************
      function replchr
    ***********************/
    function replchr($val)
    {      
    return preg_replace('~[^\s\p{L}\p{N}]++~u', '', htmlspecialchars_decode($val,ENT_QUOTES));
    }
     
    /***********************
      function initpdf
    ***********************/
    function initpdf()                                 
    {
   
       $this->crmPDF_MARGIN_LEFT= 10;
       $this->crmPDF_MARGIN_TOP= 12;
       $this->crmPDF_MARGIN_RIGHT=10;
       $this->crmPDF_MARGIN_BOTTOM=10;   
       $this->crmPDF_MARGIN_HEADER=0;
       $this->crmPDF_MARGIN_FOOTER= 5;
      
  
      // then set it to the value you think you need (experiment)
      ini_set('memory_limit','640M');
      ini_set('max_execution_time', 1200);
      
      //$mpdf=new mPDF('utf-8', array(190,236));
      
      $this->pdf=new mPDF(SugarPrintCls::MODE,$this->pageformat,$this->crmfontsize,'dejavusans',
            $this->crmPDF_MARGIN_LEFT,
            $this->crmPDF_MARGIN_RIGHT,
            $this->crmPDF_MARGIN_TOP,
            $this->crmPDF_MARGIN_BOTTOM,
            $this->crmPDF_MARGIN_HEADER,
            $this->crmPDF_MARGIN_FOOTER,
            $this->orientation); 
            
      $this->pdf->useOnlyCoreFonts = true;    // false is default
      $this->pdf->SetTitle($this->crmtitle);
      $this->pdf->SetAuthor($this->author);
      $this->pdf->useSubstitutions=false; 
      $this->pdf->simpleTables = true;
           
      // set default header data
      if(empty($this->crmlogo))
      {
        $this->crmlogowidth=0;
        $htmlheader="<table cellpadding=10><tr><td></td><td>".$this->reporttitle."<br>".$this->datareport."</td></tr></table>";
      }
      else
      {
        $new_height=40;
        list($width, $height) = getimagesize($this->crmlogo);
        $this->crmlogowidth = abs($new_height *$width/$height ); 
        if($this->crmlogowidth>120)
        {
        $this->crmlogowidth =120; 
        }
        $htmlheader="<table><tr><td><img width='".$this->crmlogowidth."' src='".$this->crmlogo."'></td><td width='20px'>&nbsp;</td><td>".$this->reporttitle."<br>".$this->datareport."</td></tr></table>";
      }
      $this->pdf->SetHTMLHeader($htmlheader);   
      $this->pdf->SetFooter('||{PAGENO}');
      
     //     $this->pdf->SetHeaderData($this->crmlogo, $this->crmlogowidth, $this->crmtitle, $this->reporttitle."\n".$this->datareport, array(0,64,255), array(0,64,128));
      //    $this->pdf->setFooterData(array(0,64,0), array(0,64,128));
       
        // set header and footer fonts

      //    $this->pdf->setHeaderFont(Array($this->crmfontname, '', $this->crmfontsize));
       //   $this->pdf->setFooterFont(Array($this->crmfontname, '', $this->crmfontsize));
 
   
    }


  
  /***********************
    function ExportXLS
  ***********************/
  function ExportXLS($what)
      {
    
         $_reporttitle=substr($this->reporttitle,0,29);
         require_once 'custom/modules/SugarPrint/views/PHPExcel/Classes/PHPExcel.php';       
         $objPHPExcel = new PHPExcel();
         $objPHPExcel->getProperties()
        							 ->setTitle($_reporttitle)
        							 ->setSubject($_reporttitle);                        
        if($what=='xls_listview')
        {
         $cellcol="A";
         $col=0;
         for($i=0;$i<count($this->colSelect);$i++)
         { 
           if($this->colSelect[$i]["sel"]==1)  { 
             $objPHPExcel->getActiveSheet()->setCellValue($cellcol.'1',$this->colSelect[$i]["label"]);
             $cellcol=getColumnLetter($col+2);
             $col++;
            }   
         } 
         $objPHPExcel->getActiveSheet()->getStyle('A1:'.$cellcol.'1')->applyFromArray(
         array('fill' 	=> array(
        								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
        								'color'		=> array('argb' => 'FFFFFF00')
        							),
        		  'borders' => array(
        								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
        							)
        		 )
        	);
        	$row=2;
          for($i=0;$i<count($_SESSION['tablebodyarray']);$i++) 
          {
            $cellcol="A";
            $col=0;
            for($k=0;$k<count($this->colSelect);$k++)
             { 
               if($this->colSelect[$k]["sel"]==1)  { 
                $txt=$this->fieldtoxls($_SESSION['tablebodyarray'][$i][$this->origcol($this->colSelect[$k]["label"])]);
                $objPHPExcel->getActiveSheet()->setCellValue($cellcol.(string)$row, $txt);
                $cellcol=getColumnLetter($col+2);    
                $col++;       
               }   
             } 
           	$row++;
        	}
          // Rename worksheet

          $objPHPExcel->getActiveSheet()->setTitle($_reporttitle);
  
        }
        if($what=='xls_detailview')         
        {   
          $cellcol="A";
          $col=0;
          $row=1;
          for($k=0;$k<count($this->detailbodyarray);$k++){
             $cellcol="A";
             if(!empty($this->detailtablist[$k]))
             {
               $objPHPExcel->getActiveSheet()->setCellValue($cellcol.(string)$row,$this->detailtablist[$k]);
               $row++;
             }
             for($z=0;$z<count($this->detailbodyarray[$k]);$z++){
                $label='';
                $data='';
                for($y=0;$y<count($this->detailbodyarray[$k][$z]);$y++){
                  if($y % 2 != 0)
                  {
                    $data=$this->detailbodyarray[$k][$z][$y];
                    $objPHPExcel->getActiveSheet()->setCellValue($cellcol.(string)$row,$label);
                    $cellcol="C";
                    $objPHPExcel->getActiveSheet()->setCellValue($cellcol.(string)$row,$data);                     
                    $label='';
                    $data='';
                    $row++;
                  }
                  else
                  {
                    $label=$this->detailbodyarray[$k][$z][$y];
                    $cellcol="B";
                  }
                }   
             }                             
          }
 
            for($W=0;$W<count($_SESSION['tablebodyarraypanel']);$W++)
            {
               $colTitles=$_SESSION["tableheaderarray"][$W]; 
              	for($i=0;$i<count($colTitles);$i++){
          	      $colTitles[$i] = $this->replchr($colTitles[$i]);
              	}                

               if($W>=0)
               {
                  $objPHPExcel->createSheet();
               }
               $objPHPExcel->setActiveSheetIndex($W+1);
               $objPHPExcel->getActiveSheet()->setTitle($_SESSION['subpanelname'][$W]);
               $cellcol="A";
               for($i=0;$i<count($colTitles);$i++)
               { 
                $objPHPExcel->getActiveSheet()->setCellValue($cellcol.'1', $colTitles[$i]);              
                 $cellcol=getColumnLetter($i+2);
               } 
  
               $objPHPExcel->getActiveSheet()->getStyle('A1:'.$cellcol.'1')->applyFromArray(
               array('fill' 	=> array(
              								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
              								'color'		=> array('argb' => 'FFFFFF00')
              							),
              		  'borders' => array(
              								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
              							)
              		 )
              	);     
                $row=2;               
                for($i=0;$i<count($_SESSION['tablebodyarraypanel'][$W]);$i++) 
                {
                  $cellcol="A";          	
                  for($col=0;$col<count($_SESSION['tablebodyarraypanel'][$W][0]);$col++) 
                    {
                      $txt=$this->fieldtoxls($_SESSION['tablebodyarraypanel'][$W][$i][$col]);
                      $objPHPExcel->getActiveSheet()->setCellValue($cellcol.(string)$row, $txt);
                      $cellcol=getColumnLetter($col+2);
                		}
                	$row++;
              	}
            }
        }	
        
        $objPHPExcel->setActiveSheetIndex(0);
        // Save Excel5 file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $this->filename.=".xls";
        $objWriter->save($this->filename);

      }                                                   

 


} 
// END CLASS

     /***********************
      function getGroupfromDate
      return group from date based on SugarCRM date format 
      ***********************/     
      function getGroupfromDate($data,$tipocampo,$dateformat,$timeformat) {
          if(empty($data))
            return "";
          if($tipocampo=="date")  
             $tmpdate=date_parse_from_format($dateformat, $data);    
          if($tipocampo=="datetime")
             $tmpdate=date_parse_from_format($dateformat." ".$timeformat, $data);

          $new = mktime(
              $tmpdate['hour'], 
              $tmpdate['minute'], 
              $tmpdate['second'], 
              $tmpdate['month'], 
              $tmpdate['day'], 
              $tmpdate['year']
          );

         
          if(!empty($_POST["_date_interval"]))
          {
             if($_POST["_date_interval"]=='d')
                return date($grp=$dateformat,$new); 
             if($_POST["_date_interval"]=='m')
                return date("Y-m",$new);
             if($_POST["_date_interval"]=='w')
                return date("Y-W",$new);
             if($_POST["_date_interval"]=='y')
                return date("Y",$new);
             if($_POST["_date_interval"]=='q')
              {
              $curMonth = date("m", $new);
              return date("Y",$new)."-".ceil($curMonth/3);
              }
          }
          else
          {
             return date("Y-m",$new);
          }   
      
      }

/***********************
  function getColumnLetter
***********************/
function getColumnLetter($columnNumber) {
        if ($columnNumber > 26) {
            $columnLetter = Chr(intval(($columnNumber - 1) / 26) + 64) . Chr((($columnNumber - 1) % 26) + 65);
        } else {
            $columnLetter = Chr($columnNumber + 64);
        }
        return $columnLetter;
}


/***********************
  function clearSession
***********************/
function clearSession(){
  $_SESSION['tablebodyarray']=array();
  $_SESSION['tableidarray']=array(); 
  $_SESSION['tableheaderarray']=array();
  $_SESSION['tablealignarray']=array();
  $_SESSION['subpanelname']=array();
  $_SESSION['tablebodyarray']=array();
  $_SESSION['tablebodyarraypanel']=array();

}

/***********************
function cleardir
clears pdf folder from files more old than yeasterday
default folder is _/temp
***********************/
function clearDir($tmpFolder) {
    if ($handle = opendir($tmpFolder)) {     
        while (false !== ($file = readdir($handle))) { 
          if(!is_dir($file))
          {
               $filelastmodified = filemtime($tmpFolder.'/'.trim($file));       
              if((time()-$filelastmodified) > 24*3600)
              {
                 unlink($tmpFolder.'/'.trim($file)); 
              }
           }     
        }
        closedir($handle); 
    }
}  
    
function stripslashes_deep($value)
{
    $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :
                stripslashes($value);
    return $value;
} 

function log_var($c_var)
{
  $log = fopen( dirname(__FILE__)."/_temp/logdump1.txt", "a" );  
  ob_start();
  var_dump($c_var);
  $data = ob_get_clean();
  fwrite($log, date("Y-m-d H:i:s")."\n");
  fwrite($log, $data);
  fclose($log);


}   
  
?>       