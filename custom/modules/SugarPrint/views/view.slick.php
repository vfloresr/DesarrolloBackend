<?php
require_once('custom/modules/SugarPrint/views/SugarPrintCls.php');

class Viewslick extends SugarView {

	function Viewslick() {
 		parent::SugarView();
	}
 
	function display() 
  {   
  global $mod_strings;
  $sp = new SugarPrintCls();
  session_start();      
  $sp->init();  
  $sp->report_type=$_SESSION["report_type"];              
  $sp->colTitles=$_SESSION["tableheaderarray"];  
  $sp->colSelect=$_SESSION["tableselectarray"];
  $sp->reporttitle=$_SESSION['reporttitle'];    
  $sp->reporttitle=$_SESSION['reporttitle'];  
  $sp->sparkline=$_SESSION['sparkline'];
  $sp->basepath="custom/modules/SugarPrint/views/slick/";
?>
  <link rel="stylesheet" href="<?php echo $sp->basepath;?>/slick.grid.css" type="text/css"/>
  <link rel="stylesheet" href="<?php echo $sp->basepath;?>/css/smoothness/jquery-ui-1.8.16.custom.css" type="text/css"/>
  <link rel="stylesheet" href="<?php echo $sp->basepath;?>/mainstylegrid.css" type="text/css"/>
  <link rel="stylesheet" href="<?php echo $sp->basepath;?>/controls/slick.columnpicker.css" type="text/css"/>
  <style>

    .slick-group-title[level='0'] {
      font-weight: bold;
    }

    .slick-group-title[level='1'] {
      text-decoration: underline;
    }

    .slick-group-title[level='2'] {
      font-style: italic;
    }
  </style>
<table style="width:100%">
   <tbody>
      <tr>
      <td>
        <div class="moduleTitle">
          <h2><?php echo $sp->reporttitle;?></h2>
        </div>
            <div class="options-panel" style="width:100%;">
              <div style="padding:6px;float:left;">
              <?php
               if(($sp->report_type!='pdf_report_crosstab')&&($sp->report_type!='pdf_report_summary')) { 
              ?>
               <?php
                for($i=0;$i<count($sp->colSelect);$i++){
                   if($sp->colSelect[$i]["group"]==1)
                   {
                     echo '<button onclick="dataView.collapseAllGroups()">'.$mod_strings['COLLAPSEGROUPS'].'</button>';
                     echo '<button onclick="dataView.expandAllGroups()">'.$mod_strings['EXPANDGROUPS'].'</button>';
                     echo '<button onclick="dataView.setGrouping([])">'.$mod_strings['CLEARGROUPS'].'</button>';
                     echo '<button onclick="makegroup()">'.$mod_strings['GROUPBY'].'</button>';    
                     break;                
                   }
                }
              }
              ?>
              <button onclick="setwidthgrid(1);"><?php echo $mod_strings['EXPANDGRID'];?></button>
              <button onclick="setwidthgrid(0);"><?php echo $mod_strings['COLLAPSEGRID'];?></button>
              </div>
              <div style="float:right;margin-right:20px;">
                <label><?php echo $mod_strings['FILTERGRID'];?>:</label>
                  <input type=text id="txtSearch" style="width:120px;">
              </div>    
            </div>
      </td>
    </tr>
   </tbody>
</table>
<div id="myGrid" style="height:450px;"></div>

<script src="<?php echo $sp->basepath;?>/lib/jquery-ui-1.8.16.custom.min.js"></script>
<script src="<?php echo $sp->basepath;?>/lib/jquery.event.drag-2.2.js"></script>

<script src="<?php echo $sp->basepath;?>/slick.core.js"></script>
<script src="<?php echo $sp->basepath;?>/slick.formatters.js"></script>
<script src="<?php echo $sp->basepath;?>/slick.editors.js"></script>
<script src="<?php echo $sp->basepath;?>/plugins/slick.cellrangedecorator.js"></script>
<script src="<?php echo $sp->basepath;?>/plugins/slick.rowselectionmodel.js"></script>
<script src="<?php echo $sp->basepath;?>/plugins/slick.cellselectionmodel.js"></script>
<script src="<?php echo $sp->basepath;?>/slick.grid.js"></script>
<script src="<?php echo $sp->basepath;?>/slick.groupitemmetadataprovider.js"></script>
<script src="<?php echo $sp->basepath;?>/slick.dataview.js"></script>
<script src="<?php echo $sp->basepath;?>/controls/slick.columnpicker.js"></script>
<?php
  if($sp->report_type=='pdf_report_crosstab')
   echo '<script src="'.$sp->basepath.'/lib/jquery.sparkline.min.js"></script>';
?>
<script>
var dataView;
var grid;
var data = [];
var columns = [
<?php
  $notfirst=false;
  switch($sp->report_type)
      {
      case "pdf_report_summary":
        $sortable="false"; 
        $i=0;
        echo '{id: "f_'.$i.'", name: "'.$sp->colSelect[$i]["label"].'", field: "f_'.$i.'", minWidth: 40, sortable: '.$sortable.'},';
        $i=1;
        echo '{id: "f_'.$i.'", name: "'.$sp->colSelect[$i]["label"].'", field: "f_'.$i.'", minWidth: 40, sortable: '.$sortable.'}';
        break;
      case "pdf_report_crosstab":
        $sortable="false";
        $done=false;
        for($i=0;$i<count($sp->colSelect);$i++){
           if(($sp->colSelect[$i]["sel"]==1)||($sp->colSelect[$i]["group"]==1))
           {
              if($notfirst) 
                echo ",";
              echo '{id: "f_'.$i.'", name: "'.$sp->colSelect[$i]["label"].'", field: "f_'.$i.'", minWidth: 40, sortable: '.$sortable.'}';
              $notfirst=true;
           }
          if(($notfirst)&&(!$done)&&($sp->sparkline==1))  
          {   
            $done=true;    
            echo ',{id: "chart", name: "Chart", minWidth: 40, sortable: false, formatter: waitingFormatter, rerenderOnResize: true, asyncPostRender: renderSparkline}';
          } 
        }
        break;
      default:
        $sortable="true";
        for($i=0;$i<count($sp->colSelect);$i++){
           if(($sp->colSelect[$i]["sel"]==1)||($sp->colSelect[$i]["group"]==1)||($sp->colSelect[$i]["calc"]!=''))
           {
              if($notfirst) 
                echo ",";
              switch ($sp->colSelect[$i]["calc"])
              {
               case "sum":
                if($sp->colSelect[$i]["curr"])
                 echo '{id: "f_'.$i.'", name: "'.$sp->colSelect[$i]["label"].'", field: "f_'.$i.'", minWidth: 40, sortable: '.$sortable.',groupTotalsFormatter: sumTotalsCurrFormatter}';
                else
                 echo '{id: "f_'.$i.'", name: "'.$sp->colSelect[$i]["label"].'", field: "f_'.$i.'", minWidth: 40, sortable: '.$sortable.',groupTotalsFormatter: sumTotalsFormatter}';
                break;
               case "avg":
                if($sp->colSelect[$i]["curr"])
                 echo '{id: "f_'.$i.'", name: "'.$sp->colSelect[$i]["label"].'", field: "f_'.$i.'", minWidth: 40, sortable: '.$sortable.',groupTotalsFormatter: avgTotalsCurrFormatter}';               
                else   
                 echo '{id: "f_'.$i.'", name: "'.$sp->colSelect[$i]["label"].'", field: "f_'.$i.'", minWidth: 40, sortable: '.$sortable.',groupTotalsFormatter: avgTotalsFormatter}';                             
                 break;
               default:
                 echo '{id: "f_'.$i.'", name: "'.$sp->colSelect[$i]["label"].'", field: "f_'.$i.'", minWidth: 40, sortable: '.$sortable.'}';
              }
              $notfirst=true;
           }  
        }        
      }  

 /*
  {id: "sel", name: "#", field: "num"},
  {id: "title", name: "Title", field: "title",  sortable: true},
  {id: "duration", name: "Duration", field: "duration", sortable: true, groupTotalsFormatter: sumTotalsFormatter},
  {id: "%", name: "% Complete", field: "percentComplete", sortable: true, groupTotalsFormatter: avgTotalsFormatter},
  {id: "start", name: "Start", field: "start",sortable: true},
  {id: "finish", name: "Finish", field: "finish", sortable: true},
  {id: "test", name: "test*^q'", field: "test", sortable: true},  
  {id: "cost", name: "Cost", field: "cost",sortable: true, groupTotalsFormatter: sumTotalsFormatter},
  {id: "effort-driven", name: "Effort Driven", field: "effortDriven", formatter: Slick.Formatters.Checkmark, sortable: true}
*/
    
?>

];
<?php
if($sp->report_type=='pdf_report_crosstab')
{
   
?>
    var data_array={};
    
    function waitingFormatter(value) {
      return "wait...";
    }
    function renderSparkline(cellNode, row, dataContext, colDef) {
    
     var vals = [
      <?php
        $notfirst=false;
        for($i=1;$i+1<count($sp->colSelect);$i++){
           if(($sp->colSelect[$i]["sel"]==1)&&($s[$i]["group"]!=1))
           {
              if($notfirst) 
                echo ",";
              echo 'data_array[row]["f_'.$i.'"]';
              $notfirst=true;
           }
        }
      ?>  
        ];
        $(cellNode).empty().sparkline(vals, {width: "100%"});
      }
<?php
}
?>

function loadData() {
  data = [];            
  <?php       
        $d=$_SESSION['tablebodyarray'];

 
        switch($sp->report_type)
        {
            case "pdf_report_summary":     
              $notfirst=false;
              for($k=0;$k<count($d);$k++){

                echo 'var d = (data['.$k.'] = {});';   
                echo 'd["id"] = "id_'.$k.'";';
                $i=0;
                $d[$k][$i]= preg_replace('/\s+/', ' ', trim($d[$k][$i]));
                echo 'd["f_'.$i.'"] = "'.$d[$k][$i].'";';
                $i=1;
                $d[$k][$i]=trim(preg_replace('/\s\s+/', ' ', $d[$k][$i]));                
                echo 'd["f_'.$i.'"] = "'.$d[$k][$i].'";';
              }    

              break;
            case "pdf_report_crosstab":
              for($k=0;$k<count($d);$k++){
                echo 'data_array['.$k.']={};';
                for($i=1;$i+1<count($sp->colSelect);$i++){
                  $d[$k][$i]= preg_replace('/\s+/', ' ', trim($d[$k][$i]));
                  if(empty($d[$k][$i]))
                    echo 'data_array['.$k.']["f_'.$i.'"]=0;';
                  else
                    echo 'data_array['.$k.']["f_'.$i.'"]='.$d[$k][$i].';';
                  }  
              }            
              for($k=0;$k<count($d);$k++){             
                echo 'var d = (data['.$k.'] = {});';
                echo 'd["id"] = "id_'.$k.'";';
                for($i=0;$i<count($sp->colSelect);$i++){
                   $d[$k][$i]= preg_replace('/\s+/', ' ', trim($d[$k][$i]));
                   if(($sp->colSelect[$i]["sel"]==1)&&($s[$i]["group"]!=1)) 
                   {
                      if(($d[$k][$i]!="")&&($i>0))
                        echo 'd["f_'.$i.'"] = "'.$sp->get_format($d[$k][$i],$_SESSION["decim"],$_SESSION["curr_tot"]).'";';
                      else
                      if($i>0)
                        echo 'd["f_'.$i.'"] = 0;';
                      else
                        echo 'd["f_'.$i.'"] = "'.$d[$k][$i].'";';
                   }
                }
              }
              break;
            default:
             for($k=0;$k<count($d);$k++){
             
              echo 'var d = (data['.$k.'] = {});';
              echo 'd["id"] = "id_'.$k.'";';
              for($i=0;$i<count($sp->colSelect);$i++){
               $d[$k][$i]= preg_replace('/\s+/', ' ', trim($d[$k][$i]));
               if(($sp->colSelect[$i]["sel"]==1)||($sp->colSelect[$i]["group"]==1)||($sp->colSelect[$i]["calc"]!=''))
                 {
                  // if($sp->colSelect[$i]["calc"]!='')
                 //    echo 'd["f_'.$i.'"] = g_n("'.$d[$k][$sp->origcol($sp->colSelect[$i]["label"])].'");';
                  //  else
                    echo 'd["f_'.$i.'"] = "'.$d[$k][$sp->origcol($sp->colSelect[$i]["label"])].'";';
                 }
              }
            }
         }  
    ?>
  dataView.setItems(data);
}

var options = {
  enableCellNavigation: false,
  editable: false,
  forceFitColumns: true,
  enableAsyncPostRender: true
};

var sortcol = "f_0";
var sortdir = 1;

function avgTotalsFormatter(totals, columnDef) {
  var val = totals.avg && totals.avg[columnDef.field];
  if (val != null) {
    val=val.toFixed(2);    
    var parts = val.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");         
    return "<b>"+parts.join(".")+"</b>";
  }
  return "";
}

function avgTotalsCurrFormatter(totals, columnDef) {
  var val = totals.avg && totals.avg[columnDef.field];
  if (val != null) {
    val=val.toFixed(2);    
    var parts = val.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");         
    return "<b>"+"<?php echo $sp->currency;?> "+parts.join(".")+"</b>";
  }
  return "";
}
function sumTotalsCurrFormatter(totals, columnDef) {
  var val = totals.sum && totals.sum[columnDef.field];
  if (val != null) {
    val=val.toFixed(2);    
    var parts = val.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");         
    return "<b>"+"<?php echo $sp->currency;?> "+parts.join(".")+"</b>";    
  }
  return "";
}

function sumTotalsFormatter(totals, columnDef) {

  var val = totals.sum && totals.sum[columnDef.field];
  if (val != null) {
    val=val.toFixed(2);    
    var parts = val.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");         
    return "<b>"+parts.join(".")+"</b>";
  }
  return "";
}



function comparer(a, b) {
  var x = a[sortcol], y = b[sortcol];
  return (x == y ? 0 : (x > y ? 1 : -1));
}


function setwidthgrid(what) {

  if(what==1)
  {
    if($("#myGrid").width()>$("#content").width()*3)
      return;
    $("#myGrid").width($("#myGrid").width()*1.2);
    
  }         
  else
  {
   if($("#myGrid").width()<300)
     return;
   $("#myGrid").width($("#myGrid").width()/1.2); 
  }   
  loadgrid();
 

}

function makegroup() {
  dataView.setGrouping([
  <?php
    $notfirst=false;
    for($i=0;$i<count($sp->colSelect);$i++){
       if($sp->colSelect[$i]["group"]==1)
       {
          if($notfirst) 
            echo ',';
          echo '{';
          echo 'getter: "f_'.$i.'",';
          echo '  formatter: function (g) {';
          echo '  return  g.value + "  <span style=\'color:green\'>(" + g.count + " items)</span>";';
          echo '  },';   
          echo 'aggregateCollapsed: true,';  
          echo 'collapsed: false,';
          $notfc=false;
          echo '      aggregators :[';
          for($w=0;$w<count($sp->colSelect);$w++){
             if(($sp->colSelect[$w]["calc"]=="sum")||($sp->colSelect[$w]["calc"]=="avg"))
             {
               if($notfc) 
                 echo ',';
               if($sp->colSelect[$w]["calc"]=="sum")
                 echo ' new Slick.Data.Aggregators.Sum("f_'.$w.'")';  
                else
                 echo ' new Slick.Data.Aggregators.Avg("f_'.$w.'")'; 
               $notfc=true; 
             }
          } 
          echo ' ], ';
          echo 'lazyTotalsCalculation: true';          
          echo '}';           
          $notfirst=true;
       }
    }           
    ?>
      
  ]);
}

var searchString = "";

$(function () {

  $("#myGrid").width($("#content").width()-20);
  loadgrid();
})

function loadgrid()
{
  
  
  var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();
  dataView = new Slick.Data.DataView({
    groupItemMetadataProvider: groupItemMetadataProvider,
    inlineFilters: true
  });
  
  
  grid = new Slick.Grid("#myGrid", dataView, columns, options);

  // register the group item metadata provider to add expand/collapse group handlers
  grid.registerPlugin(groupItemMetadataProvider);
  grid.setSelectionModel(new Slick.RowSelectionModel());

  var columnpicker = new Slick.Controls.ColumnPicker(columns, grid, options);


  grid.onSort.subscribe(function (e, args) {
    sortdir = args.sortAsc ? 1 : -1;
    sortcol = args.sortCol.field;

    // using native sort with comparer
    // preferred method but can be very slow in IE with huge datasets
    dataView.sort(comparer, args.sortAsc);

  });

  // wire up model events to drive the grid
  dataView.onRowCountChanged.subscribe(function (e, args) {
    grid.updateRowCount();
    grid.render();
  });

  dataView.onRowsChanged.subscribe(function (e, args) {
    grid.invalidateRows(args.rows);
    grid.render();
  });

 
function myFilter(item) {
  var ret=true;            
  for(j=0;j<columns.length;j++)
  {
    ret=do_filter(item,columns[j].field);   
    if(ret)
     break;
  }
  return ret;
}


  // initialize the model after all the events have been hooked up


  dataView.beginUpdate();
  loadData();
  dataView.setFilter(myFilter);             
           
  <?php
  if(($sp->report_type!="pdf_report_crosstab")&&($sp->report_type!="pdf_report_summary"))              
   echo 'makegroup();';
  ?>      

  dataView.endUpdate();
  $("#gridContainer").resizable();

  $("#txtSearch").keyup(function (e) {
    Slick.GlobalEditorLock.cancelCurrentEdit();

    // clear on Esc
    if (e.which == 27) {
      this.value = "";
    }

    searchString = this.value;
    dataView.refresh();
  })
}

function do_filter(item,itemname){
    if (searchString != "" && item[itemname].toUpperCase().indexOf(searchString.toUpperCase()) == -1) {
      return false;
    }
   
    if (item.parent != null) {
      var parent = data[item.parent];
      while (parent) {
        if (parent._collapsed || (searchString != "" && parent[itemname].toUpperCase().indexOf(searchString.toUpperCase()) == -1)) {
          return false;
        }
        parent = data[parent.parent];
      }
    }

    return true;
}

function g_n(n)
{
 return n.replace( /[^\d.-]/g, '');
}

</script>                          

<?php
  clearSession();
	}  // end display

}          

?>
  

  