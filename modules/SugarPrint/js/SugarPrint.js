var paththeme='modules/SugarPrint';
var _onclickcode;
var _testata='';
var _go=true;
var _annullareport=false;
var _offset=0;
var _tablebodyarray = [];
var _tableidarray = [];
var _tableheaderarray = [];
var _tableselectarray = [];
var _tablewidtharray = [];
var _tablealignarray = [];
var _session='';
var _orientation='L';
var _pageformat='A4';
var _customheight='';
var _customwidth='';
var _companylogo='';    
var _crmtitle='';
var _reporttitle='';
var _report_type='';
var _sparkline=1;
var _report_slick='';
var _dochart=0;
var _mostralogo=0;
var _detailtablist=[];    
var _detailbodyarray = []; 
var _subpanelname ='';
var _csubpanel=0;       
var _currentpagetype='';
var _contaloop=0;
var _date_interval='m';
var _headerscalendar=[]; 
var _bodiescalendar=[];
var check_sp=0;
var cmodule='';




function do_report(){
 var tmpoffset=0;
 var caricaalign=true;
 var tmpstr;
 var url_curr;
 var phpscript='';
 var _obj2;         
   
 if(_annullareport)
   return;

 phpscript="index.php?to_pdf=1&module=SugarPrint&action=reportpdf";

 url_curr=_onclickcode;
 tmpstr=_onclickcode.split("&");
 for(J=0;J<tmpstr.length;J++){
    if(tmpstr[J].indexOf('_offset')>0){
      var tmpstr2=tmpstr[J].split('=');
      url_curr=url_curr.replace(tmpstr[J],tmpstr2[0]+'='+_offset);      
    }
  }
  if(_currentpagetype!='listview')
    url_curr=url_curr+'&sort_order=asc';  
  $.ajax({
				type: "GET",
				url: url_curr,
				async:false,
 				dataType: "html",
				success: function(data) {	
      
        var _obj=$(data);
         
          _go=true;
          if(_currentpagetype=='listview')
          {
              if($("#listViewNextButton_top[disabled]", _obj).length>0)
                if($("#listViewNextButton_top", _obj).attr("disabled")=='disabled')
                  _go=false;    
          }      
          else                          
          {
              if($("button[name='listViewNextButton']", _obj).attr("disabled")=='disabled')
                _go=false;
          }
       //   $("img", _obj).remove(); 
        //   $(":checkbox", _obj).remove(); 
          $(".pagination", _obj).remove();
         //  $('a', _obj).contents().unwrap();
       //   $('div', _obj).contents().unwrap();
           var idcolindex=getcolumnid(_obj);
          if(_currentpagetype=='listview'){
             _obj2=$('.list>tbody>tr', _obj);
         
          }
          else
          {
            _obj2=_obj.find('tr');   
          }

          _obj2.not("#pagination").each(function(index) { 
                  var arrayrow = [];     
                  var cid='';                                          
                  if (_tablealignarray.length > 0) {
                      caricaalign=false;
                  }
                  var tableData = $(this).find('td');
                  if (tableData.length > 0) {
                      tableData.each(function(ccol) { 
                        if(_currentpagetype=='listview')
                         {
                             if((ccol>1)&&(ccol<_tableheaderarray.length+2)) 
                             {
                                 if($(this).find(":checkbox:checked").length>0)
                                   arrayrow.push(_tableheaderarray[ccol-2]);  
                                 else
                                   arrayrow.push($(this).text().trim()); 
                                 if(caricaalign)
                                 {
                                   _tablewidtharray.push($('.list tr:eq(3) td:eq('+ccol+')').width()); 
                                   if($(this).find(":checkbox").length>0)
                                   {
                                       _tablealignarray.push('center');           
                                   }
                                   else
                                   {
                                     if ($(this).attr("align") !== undefined) 
                                      _tablealignarray.push($(this).attr("align")); 
                                     else
                                      _tablealignarray.push('left');                              
                                   }
                                 }
                             }
                            if(ccol==idcolindex)
                              cid=getid($(this)); 
                          } 
                        if(_currentpagetype=='detailview')
                        {                                           
                            if($('#subpanel_list>li:eq('+_csubpanel+')').find('table.list').find('tr.oddListRowS1').length>0) 
                            {
                                 if($(this).find(":checkbox:checked").length>0)
                                   arrayrow.push("X");  
                                 else
                                   arrayrow.push($(this).text().trim()); 
 
                                 if(caricaalign)
                                 {
                                   if((ccol==0)&&($('#groupTabs >li:eq('+_csubpanel+') a').length>0))
                                       $('#groupTabs >li:eq('+_csubpanel+') a')[0].click();
                                   if ($(this).attr("align") !== undefined) 
                                    _tablealignarray.push($(this).attr("align")); 
                                   else
                                    _tablealignarray.push('left');                              
                                 }
                             }
                        }                            
                     });
                     if((idcolindex!=-1)&&(_currentpagetype=='listview'))
                      _tableidarray.push(cid);
                   } 
                   for(p=arrayrow.length;p<_tableheaderarray.length;p++)
                      arrayrow.push('');                   
                   _tablebodyarray.push( urlencoderow(arrayrow));          
               tmpoffset=tmpoffset+1;
            });  

            $.ajax({
        		type: "POST",
        		url: phpscript,
        		async:false,
        		dataType: "text",
      		  data: {	
               _tablecomplete: 0,  
               _session: _session, 
               _tableidarray:_tableidarray,
      				 _tablebodyarray:_tablebodyarray
      			},
            success: function(data) {	                                 
            }      
           });

           _session='true';
           _tablebodyarray=[];
           _tableidarray=[];
     
           if(_go)
           {
              if(_currentpagetype=='listview')
                 var _pagina='Records '+_offset.toString()
              else              
                 var _pagina=_subpanelname+' '+_offset.toString()
             // _offset=_offset+tmpoffset-1;
                 _offset=_offset+tmpoffset;
              _pagina=_pagina+' / '+_offset.toString();
              $("#workinprogress").html(SUGAR.language.get('app_list_strings', 'sugarprint')['MSGELAB_IN_CORSO']+': '+_pagina);      
             //setTimeout(function(){do_report();},300);
              do_report();
           }  
           else
           {   
             if(_currentpagetype=='listview')
                stampalistview();
                
             if(_currentpagetype=='detailview')  {
             
                  $.ajax({
              		type: "POST",
              		url: phpscript,
              		async:false,
              		dataType: "text",
            		  data: {	
                    _tablecomplete: 0,  
                    _session: _session,  
            				_subpanelname:_subpanelname,
            				_tablewidtharray:	_tablewidtharray,
                    _tablealignarray:	_tablealignarray,
                    _tableheaderarray:_tableheaderarray		
            			},
                  success: function(data) {	    
                                     
                  }      
                 });
              
                _csubpanel=_csubpanel+1;                
                _tablealignarray=[];
                _tablewidtharray=[];
                _tableheaderarray=[];
                if(_csubpanel<$("#subpanel_list > li").length)
                {
                  getsubpanels();
                  if((!_annullareport)&&(_csubpanel>=$("#subpanel_list > li").length))
                    stampadetailview();
                }
                else
                {
                  stampadetailview();
                } 
              
             }
          }         
				}
			});
	
}


function do_headerlistview(){
  if(_currentpagetype=='KReports')
  {
      var idrec=$('#formDetailView').find('input[name="record"]').val();
      $.ajax({
  		type: "POST",
  		url: "index.php?module=KReports&action=export_to_csv&to_pdf=on&record="+idrec,
  		async:false,
  		dataType: "text",
		  data: {	
			},
      success: function(data) {	
       // header
         var tmprows=data.split(",\n");
         var tmpheader=tmprows[0].replace(/:/g, '').replace(/\\n/g, '').split(',');
         for(j=0;j<tmpheader.length;j++)
         {
            _tableheaderarray.push(tmpheader[j]);    
         }    
         for(j=1;j<tmprows.length;j++)
         {
            var row=tmprows[j].split('","'); 
             row[0]=row[0].replace(/"/g, '');
             row[row.length-1]=row[row.length-1].replace(/"/g, '')
            _tablebodyarray.push(row);
         }                                  
      }      
     });    
     // set align
     $('.x-grid-table').find('.x-grid-row:eq(0) td').each(function(index) {  
       _tablealignarray.push($(this).find('.x-grid-cell-inner').css('text-align')); 
     }); 
     return; 
  }
  
  var caricaalign=true;
  var _obj=$("#content").clone();
  
    $("img", _obj).remove(); 
    $(":checkbox", _obj).remove(); 
    $(".pagination", _obj).remove();
    $('a', _obj).contents().unwrap();
  //  $('div', _obj).contents().unwrap();

    $('.list', _obj).find('tr').each(function(index) { 
    
         if(index==0)
         {
            if(_tableheaderarray.length==0){
                var arrayheaderrow = [];
                var tableheaderData = $('.list', _obj).find('th');
                if (tableheaderData.length > 0) {
                        tableheaderData.each(function() {
                        arrayheaderrow.push($(this).text().trim()); 
                      });  
                           
                 }              
                _tableheaderarray=arrayheaderrow; 
          
            }   
            return;              
         }
      });  
}

function getcolumnid(_obj){
    var modulo=$('#search_form input[name=module]').val();
    var colid=-1;
     $('.list>tbody', _obj).find('tr').each(function(index) {
          $(this).find('td').each(function(ccol) { 
                $(this).find('a').each(function(ind) {
                    var pu=urlpar($(this).attr('href'));
                    if(pu['module']==modulo)
                    {
                     colid=ccol;
                     return false;
                    } 
                }); 
                if(colid!=-1) 
                  return false;
          });    
        if(colid!=-1) 
           return false;
      }); 
   return colid;
}

function getid(_obj){  
  if(_obj.find('a').length==0)
    return "";
    var pu=urlpar(_obj.find('a').attr('href'));
     return pu['record']; 
}

function do_reportsinglepage(){
  if(_currentpagetype=='KReports')
  {
    $.ajax({
        		type: "POST",
  		      url: "index.php?to_pdf=1&module=SugarPrint&action=reportpdf",
        		async:false,
        		dataType: "text",
      		  data: {	
               _tablecomplete: 0,  
               _session: _session,
              _tableidarray:_tableidarray,   
      				_tablebodyarray:_tablebodyarray
      			},
            success: function(data) {	                                 
            }      
       });
    return;
  }



  var caricaalign=true;
  var _obj=$("#content").clone();
  
    $("img", _obj).remove(); 
//    $(":checkbox", _obj).remove(); 
    $(".pagination", _obj).remove();
  //   $('a', _obj).contents().unwrap();
 //   $('div', _obj).contents().unwrap();
    var idcolindex=getcolumnid(_obj);
    $('.list>tbody>tr', _obj).not("#pagination").each(function(index) { 
            var arrayrow = []; 
            var cid='';               
            var tableData =  $(this).find('td');
            $(this).find('td').each(function(ccol) { 
                if((ccol>1)&&(ccol<_tableheaderarray.length+2)) {                
                  if($(this).find(":checkbox:checked").length>0)
                  {
                     arrayrow.push(_tableheaderarray[ccol-2]); 
                     _tablealignarray.push("center"); 
                  }                     
                  else
                  {
                     arrayrow.push($(this).text().trim()); 
                     if (_tablealignarray.length == 0) {
                        _tablealignarray.push($(this).attr("align")); 
                      }
                  }                     
               }
               if(ccol==idcolindex)
                cid=getid($(this)); 
             });        
             if(idcolindex!=-1)
               _tableidarray.push(cid);
              
            _tablebodyarray.push(urlencoderow(arrayrow));
      });  
      $.ajax({
        		type: "POST",
  		      url: "index.php?to_pdf=1&module=SugarPrint&action=reportpdf",
        		async:false,
        		dataType: "text",
      		  data: {	
               _tablecomplete: 0,  
               _session: _session,
              _tableidarray:_tableidarray,   
      				_tablebodyarray:_tablebodyarray
      			},
            success: function(data) {	                                 
            }      
       });
}

// imposta parametri dialog 
function formatocustom(_dialog){
  $(_dialog).find("#erroredialog").text("");
  if($(_dialog).find("#formatopagina option:selected").val()=="Custom")
  { 
    $(_dialog).find("#divcustomformat").css("visibility", "visible");
    $(_dialog).find("#divcustomformat").css("height", "auto");
  } 
  else 
  {  
    $(_dialog).find("#divcustomformat").css("visibility", "hidden");
    $(_dialog).find("#divcustomformat").css("height", "10px");
  }
}



function switch_design()
{
    $("#for_report").css('display','none'); 
    $("#for_crosstab").css('display','none');
    $("#for_sum_chart").css('display','none');
    switch($("#report_type").val())
    {
    case 'pdf_report':
      $("#for_report").css('display','block');
      break;
    case 'pdf_report_crosstab':
      $("#for_crosstab").css('display','block');
      break;
    default:
     $("#for_sum_chart").css('display','block');     
    }

}             
               
function setmodule()
{
     if(_currentpagetype=='KReports')
       cmodule='KReports';
     else
       cmodule=$('#search_form input[name=module]').val();
}
function setdialogparam(){
     setmodule();
     var currentdialog='#Dialog1';
     var lilistamampi='';
     var optioncampi='<option value=""></option>'; 
     var optioncalc='<option selected value="sum">'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABSUM']+'</option><option value="avg">'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABAVG']+'</option><option value="count">'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABCOUNT']+'</option>';     
     var calcselect='';
     var _obj=$("#content").clone();
     var idcolindex=getcolumnid(_obj);
     if(idcolindex==-1)
     {
      $( ".divextrafields").css('display','none');
     }
     var header='<li class="unsortable" style="background-color:#FFF;"><div class="diagcol">&nbsp;</div><div class="diagcolcheck">'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABSELECT']+'</div><div class="diagcolcheck">'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABGROUP']+'</div><div class="diagcolradio" style="text-align:center">'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABCALC']+'</div></li>';
     
     do_headerlistview();
     
     for(J=0;J<_tableheaderarray.length;J++){
        calcselect='<input type="radio" name="radiocalc'+J+'" value="sum">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABSUM']+'&nbsp;';
        calcselect+='<input type="radio" name="radiocalc'+J+'" value="avg">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABAVG']+'&nbsp;';
        calcselect+='<input type="radio" name="radiocalc'+J+'" value="count">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABCOUNT']+'&nbsp;';
        calcselect+='<input type="radio" name="radiocalc'+J+'" value="">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABNONE']+'&nbsp;';
        lilistamampi+='<li type="" data="" label="'+_tableheaderarray[J].replace(/"/g, '')+'"><div class="diagcol">'+_tableheaderarray[J]+'</div>';
        lilistamampi+='<div class="diagcolcheck"><input id="chksel'+J+'" type="checkbox" checked><label for="chksel'+J+'"></label></div>';
        lilistamampi+='<div class="diagcolcheck"><input id="chkgrp'+J+'" type="checkbox"><label for="chkgrp'+J+'"></label></div>';
        lilistamampi+='<div class="diagcolradio">'+calcselect+'</div>';         
        lilistamampi+='</li>';
        optioncampi+='<option value="'+_tableheaderarray[J].replace(/"/g, '')+'">'+_tableheaderarray[J]+'</option>';
     }

      $( "#diagsortable" ).sortable({
      items: "li:not(.unsortable)"
     });

     $( "#diagsortable" ).disableSelection();

     if($('#diagsortable li').length==0)
     {
        $('#diagsortable').html(header+lilistamampi);
     }
     
     $("#sum_chart_group").append(optioncampi);
     $("#sum_chart_calc").append(optioncampi);
     $("#crosstab_cols_group").append(optioncampi);
     $("#crosstab_rows_group").append(optioncampi);
     $("#crosstab_sum").append(optioncampi);
     $("#sum_chart_sum").append(optioncalc);     
     $("#crosstab_calc").append(optioncalc);

     $.ajax({
    		type: "POST",
    		url: "index.php?to_pdf=1&module=SugarPrint&action=getsavedreports&cmodule="+cmodule,
    		async:false,
    		dataType: "json",
  		  data: {
  			}, 
    		success: function(data) {	
         var optrep="<option></option>";
         for(j=0;j<data.length;j++)     
         {
           optrep+="<option value='"+data[j].id+"'>"+data[j].name+"</option>";
         }
         $( "#report_list").find('option').remove();
         $( "#report_list").append(optrep);
       }       
     }); 
    
    $(currentdialog).find('#reporttitle').val(_reporttitle);
    $(currentdialog).find('#formatopagina').val(_pageformat);
    $(currentdialog).find('#orientamento').val(_orientation);
            
    if(_mostralogo==1)
       $(currentdialog).find('input[id=stampalogo]').attr('checked', _mostralogo);
  
    var pu=getCookie('reportid');
    if((pu!=undefined)&&(pu!=''))
    {
     loadreport(0,pu);
     setCookie('reportid', '',0);
    } 


}

function do0(what){
 _currentpagetype='';
 // START integration with KReports
 if((what=='KReports_pdf')||(what=='KReports_xls'))
 {
      _currentpagetype='KReports';
      /*
      var idrec=$('#formDetailView').find('input[name="record"]').val();
      $.ajax({
  		type: "POST",
  		url: "index.php?module=KReports&action=export_to_csv&to_pdf=on&record="+idrec,
  		async:false,
  		dataType: "text",
		  data: {	
			},
      success: function(data) {	
                                     
      }      
     });    
     return; 
     */          
 }
 
// END integration with KReports
 if((_currentpagetype=='')&&($(".detail.view").length>0))
  { 
  _currentpagetype='detailview';
  } 
  
 if((_currentpagetype=='')&&($(".list").length>0))
  { 
    _currentpagetype='listview';
    // NO RECORDS TO PRINT
    if($("#listViewNextButton_top").length<=0)
    {
     alert(SUGAR.language.get('app_list_strings', 'sugarprint')['MSGNOTHINGTOPRINT']);
     return;
    }  
  } 
  
 if((_currentpagetype=='')&&($(".monthHeader").length>0))
  { 
   if($('#form_settings').find('input[name=view]').val()=='year')
   {
     alert(SUGAR.language.get('app_list_strings', 'sugarprint')['MSGNOYEARREPROT']);
     return;
   }
   else
   {
   _currentpagetype='calendarview';
   }
  }

if(_currentpagetype=='')
  {
   alert(SUGAR.language.get('app_list_strings', 'sugarprint')['MSGNOTHINGTOPRINT']);
   return;
  }
  

 setDMReportPar();

 if((what=='xls')&&(_currentpagetype=='listview'))
    { 
       if(!setparameters())
        return;

      _report_type='xls_listview'; 
      do_headerlistview();
      $('<div id="DialogText2" ><div>'+SUGAR.language.get('app_list_strings', 'sugarprint')['MSGELAB_IN_CORSO']+' ...</div><div style="text-align:center;margin:15px;"><img src="'+paththeme+'/images/ajax-loader.gif"></div><div id="workinprogress">'+SUGAR.language.get('app_list_strings', 'sugarprint')['MSGANNULLA']+'</div></div>').dialog(
      { buttons: [
  
          {
              text: SUGAR.language.get('app_list_strings', 'sugarprint')['BUTTONANNULLA'],
              click: function() { 
                 _annullareport=true;
                 
                 $(this).dialog("close"); 
              }
          }
      ],
      modal: true,
      title:'SugarPrint',
      close: function(event, ui)
        {
            $(this).dialog('destroy').remove();
        }                    
      });
      
      do1(); 
 
      return;
    }
if(_currentpagetype=='calendarview')
 { 
  _reporttitle=$('.monthHeader > div:eq(1)').text().trim();
 } 
 if(_currentpagetype=='detailview')
  { 
    if(what=='xls') 
     _report_type= 'xls_detailview';
    if(what=='pdf') 
     _report_type= 'detailview';
 }
 if(_currentpagetype=='calendarview')
   _report_type= 'calendar';
 
 if((_currentpagetype=='detailview')||(_currentpagetype=='calendarview'))
  { 
   
    $('<div id="DialogText2" ><div>'+SUGAR.language.get('app_list_strings', 'sugarprint')['MSGELAB_IN_CORSO']+' ...</div><div style="text-align:center;margin:15px;"><img src="'+paththeme+'/images/ajax-loader.gif"></div><div id="workinprogress">'+SUGAR.language.get('app_list_strings', 'sugarprint')['MSGANNULLA']+'</div></div>').dialog(
    { buttons: [

        {
            text: SUGAR.language.get('app_list_strings', 'sugarprint')['BUTTONANNULLA'],
            click: function() { 
               _annullareport=true;
               
               $(this).dialog("close"); 
            }
        }
    ],
    modal: true,
    title:'SugarPrint',
    close: function(event, ui)
      {
          $(this).dialog('destroy').remove();
      }                    
    });
    
    
      if(_currentpagetype=='calendarview')
         do1calendar();                        
      else
         do1(); 

    return;
  }

var currentdialog='#Dialog1'; 
if((_currentpagetype=='listview')||(_currentpagetype=='KReports'))
  { 
   $('#Dialog1').dialog('destroy').remove();     
   jQuery('<div id="Dialog1"></div>').load('index.php?to_pdf=1&module=SugarPrint&action=getdialog',setdialogparam).dialog(
    { buttons: [
       {  
        id: "okbtnid",
        text: SUGAR.language.get('app_list_strings', 'sugarprint')['REPORT'],
        click: function() {  
              $(currentdialog).find("#erroredialog").text("");
              $(currentdialog).find("#erroredialogformato").text("");              
                            
              // page format
              if($(currentdialog).find("#formatopagina option:selected").val()=='Custom'){
                 if(!is_int($(currentdialog).find("#customwidth").val()))
                {
                   $(currentdialog).find("#erroredialog").text(SUGAR.language.get('app_list_strings', 'sugarprint')['MSGLARGH_INT']);
                   return;
                }
                if(!is_int($(currentdialog).find("#customheight").val()))
                {
                   $(currentdialog).find("#erroredialog").text(SUGAR.language.get('app_list_strings', 'sugarprint')['MSGALT_INT']);
                   return;
                }
                _customwidth=$(currentdialog).find("#customwidth").val();                
                _customheight=$(currentdialog).find("#customheight").val();
                _pageformat='Custom';  
                
              }
              else
              {
              _pageformat=$(currentdialog).find("#formatopagina option:selected").val();  
              }
              _orientation=$(currentdialog).find("#orientamento").val();
              
              if(!setparameters())
                return;
                   
              
              $(this).dialog("close");          
              $('<div id="DialogText2" ><div>'+SUGAR.language.get('app_list_strings', 'sugarprint')['MSGELAB_IN_CORSO']+' ...</div><div style="text-align:center;margin:15px;"><img src="'+paththeme+'/images/ajax-loader.gif"></div><div id="workinprogress">'+SUGAR.language.get('app_list_strings', 'sugarprint')['MSGANNULLA']+'</div></div>').dialog(
              { buttons: [

                  {
                      text: SUGAR.language.get('app_list_strings', 'sugarprint')['BUTTONANNULLA'],
                      click: function() { 
                         _annullareport=true;
                          $(this).dialog('destroy').remove();
                      }
                  }
              ],
              modal: true,
              title:'SugarPrint',
              close: function(event, ui)
                {
                    $(this).dialog('destroy').remove();
                }                    
              });
              do1(); 
              $(this).dialog('destroy').remove();     
                                              
        }
    },
    {
        text: SUGAR.language.get('app_list_strings', 'sugarprint')['EXPORTTOXLSX'],
        click: function() {  
           do0('xls');     
           $(this).dialog('destroy').remove(); 
        }
    },
    {
        text: SUGAR.language.get('app_list_strings', 'sugarprint')['EXPORTTOSLICK'],
        click: function() {  
           _report_slick='slick';
           $('#okbtnid').click();      
        }
    },                

    {
        text: SUGAR.language.get('app_list_strings', 'sugarprint')['BUTTONANNULLA'],
        click: function() { $(this).dialog('destroy').remove(); }
    }
           
  ],
  modal: false,
  width: 550,
  height: 450,
  title:'SugarPrint',
  close: function(event, ui)
  {
      $(this).dialog('destroy').remove();
  }  

});
        
}

}


function getmorefields()
{
     var lp=false;
     $('#diagsortable li').each(function(index){
         if(($(this).attr('datatype')!='')&&($(this).attr('datatype')!=undefined)){
           lp=true;
           return false;
        
         } 
     });
     if(lp) 
        return;
     
     var currentdialog='#Dialog1'; 
     setmodule();
     $.ajax({
    		type: "POST",
    		url: "index.php?to_pdf=1&module=SugarPrint&action=getaddfields",
    		async:false,
    		dataType: "json",
  		  data: {
     		  cmodule:cmodule      
  			}, 
        
    		success: function(data) {	
           var lilistamampi='';
           var optioncampi='';           
           var nf=$( "#diagsortable li").length;
           var cf=0;
           var calcselect='';
           var _select_list=data;
           var fp=false;
           for(J=0;J<_select_list.length;J++){
            fp=false;
            _select_list[J].label=_select_list[J].label.replace(':', '');
            $( "#diagsortable li").each(function(index){
               if($(this).attr('label')==_select_list[J].label)
                 fp=true;
            });
            if(!fp)
             {
              cf=nf+J;
              calcselect='<input type="radio" name="radiocalc'+cf+'" value="sum">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABSUM'];
              calcselect+='<input type="radio" name="radiocalc'+cf+'" value="avg">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABAVG'];
              calcselect+='<input type="radio" name="radiocalc'+cf+'" value="count">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABCOUNT'];
              calcselect+='<input type="radio" name="radiocalc'+cf+'" value="">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABNONE'];
              lilistamampi='<li class="morefields" datatype="'+_select_list[J].type+'"  label="'+_select_list[J].label+'" data="'+_select_list[J].name+'"><div class="diagcol">'+_select_list[J].label+'</div>';
              lilistamampi+='<div class="diagcolcheck"><input id="chksel'+cf+'" type="checkbox"><label for="chksel'+cf+'"></label></div>';
              lilistamampi+='<div class="diagcolcheck"><input id="chkgrp'+cf+'" type="checkbox"><label for="chkgrp'+cf+'"></label></div>';
              lilistamampi+='<div class="diagcolradio">'+calcselect+'</div>';         
              lilistamampi+='</li>';
              $( "#diagsortable").append(lilistamampi);
             }   
           }

           for(J=0;J<_select_list.length;J++){
            if((_select_list[J].datatype=="")||(_select_list[J].datatype=="undefined"))
              optioncampi+='<option datatype="" data="'+_select_list[J].name+'" value="'+_select_list[J].label+'">'+_select_list[J].label+'</option>';
            else
              optioncampi+='<option datatype="'+_select_list[J].type+'" data="'+_select_list[J].name+'" value="'+_select_list[J].label+'">'+_select_list[J].label+'</option>';
           }
           $("#sum_chart_group").append(optioncampi);
           $("#sum_chart_calc").append(optioncampi);
           for(J=0;J<_select_list.length;J++){
            if(_select_list[J].calc=="sum") 
             $("#sum_chart_calc option[value='" + _select_list[J].label + "']").prop("selected", true);             
            if(_select_list[J].group==1) 
             $("#sum_chart_group option[value='" + _select_list[J].label + "']").prop("selected", true);             
           }
           $("#crosstab_sum").append(optioncampi);
           $("#crosstab_cols_group").append(optioncampi);
           $("#crosstab_rows_group").append(optioncampi);           
           for(J=0;J<_select_list.length;J++){
            if(_select_list[J].calc=="sum") 
             $("#crosstab_sum option[value='" + _select_list[J].label + "']").prop("selected", true);             
            if(_select_list[J].group==1) 
             $("#crosstab_cols_group option[value='" + _select_list[J].label + "']").prop("selected", true);             
            if(_select_list[J].group==1) 
             $("#crosstab_rows_group option[value='" + _select_list[J].label + "']").prop("selected", true);             
           }
       }       
       }); 
} 

function loadreport(dofilters,reportid)
{
     var currentdialog='#Dialog1';
     var _id="";
     var _name="";
     var _privatereport=0;
     var _filters="";
     var _select_list="";
     var _format_pdf="";
     var _width_pdf=0;
     var _height_pdf=0;
     var _print_logo=1;
     var _report_module='';
     
     if(reportid==undefined)
        _id=$(currentdialog).find("#report_list").val();
     else
        _id=reportid;
     
     $(currentdialog).find('#set_filters_form').css('display','block');
     
     if(_id=='')                                                  
     {
       $(currentdialog).find("#erroredialog").text(SUGAR.language.get('app_list_strings', 'sugarprint')['MSGSELECTAREPORT']);
       return;
     }
     
     $(currentdialog).find("#report_list").val(_id);

     $.ajax({
    		type: "POST",
    		url: "index.php?to_pdf=1&module=SugarPrint&action=loadreport",
    		async:false,
    		dataType: "json",
  		  data: {
     		  _idreport:_id      
  			}, 
        
    		success: function(data) {	
            $(currentdialog).find("#reporttitle").val(data.name);
            $(currentdialog).find("#idreport").val(data.id);
            $(currentdialog).find("#customwidth").val(data.customwidth);
            $(currentdialog).find("#customheight").val(data.customheight);
            if(data.stampalogo==1)
              $(currentdialog).find("#stampalogo").attr('checked');
            else
              $(currentdialog).find("#stampalogo").removeAttr('checked');      
            if(data.privatereport==1)
              $(currentdialog).find("#privatereport").attr('checked');
            else
              $(currentdialog).find("#privatereport").removeAttr('checked');      
           $(currentdialog).find("#formatopagina").val(data.format_pdf); 
           $(currentdialog).find("#formatodate").val(data.date_interval);
           $(currentdialog).find("#report_type").val(data.report_type);
           $(currentdialog).find("#sum_chart_sum").val(data.calctype);
           $(currentdialog).find("#crosstab_calc").val(data.calctype);
           if(data.sparkline==1)
             $(currentdialog).find("#addsparkline").attr('checked', true);
           else
             $(currentdialog).find("#addsparkline").attr('checked', false);           
           switch_design();
           _select_list= eval(data.select_list);
           $('#diagsortable li').each(function(index){
            if(index>0) // no titles
             {                                     
              $(this).remove();
             } 
           });
           var lilistamampi='';
           var optioncampi='<option value=""></option>';
           var calcselect='';

           for(J=0;J<_select_list.length;J++){
            if((_select_list[J].datatype=="")||(_select_list[J].datatype=="undefined"))
              optioncampi+='<option datatype="" data="'+_select_list[J].name+'" value="'+_select_list[J].label+'">'+_select_list[J].label+'</option>';
            else
              optioncampi+='<option datatype="'+_select_list[J].datatype+'" data="'+_select_list[J].name+'" value="'+_select_list[J].label+'">'+_select_list[J].label+'</option>';

             if(_select_list[J].calc=="sum") 
              calcselect='<input type="radio" checked name="radiocalc'+J+'" value="sum">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABSUM'];
            else
              calcselect='<input type="radio" name="radiocalc'+J+'" value="sum">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABSUM'];
            if(_select_list[J].calc=="avg")
              calcselect+='<input type="radio" checked name="radiocalc'+J+'" value="avg">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABAVG'];
            else
              calcselect+='<input type="radio" name="radiocalc'+J+'" value="avg">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABAVG'];
            if(_select_list[J].calc=="avg")
              calcselect+='<input type="radio" checked name="radiocalc'+J+'" value="count">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABCOUNT'];
            else
              calcselect+='<input type="radio" name="radiocalc'+J+'" value="count">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABCOUNT'];
            if(_select_list[J].calc=="")
              calcselect+='<input type="radio" checked name="radiocalc'+J+'" value="">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABNONE'];
            else
              calcselect+='<input type="radio" name="radiocalc'+J+'" value="">&nbsp;'+SUGAR.language.get('app_list_strings', 'sugarprint')['LABNONE'];
            if((_select_list[J].datatype=="")||(_select_list[J].datatype=="undefined"))
              lilistamampi='<li datatype="" data="'+_select_list[J].name+'" label="'+_select_list[J].label+'"><div class="diagcol">'+_select_list[J].label+'</div>';
            else
              lilistamampi='<li class="morefields" datatype="'+_select_list[J].datatype+'" data="'+_select_list[J].name+'" label="'+_select_list[J].label+'"><div class="diagcol">'+_select_list[J].label+'</div>';
              
            if(_select_list[J].sel==1)
              lilistamampi+='<div class="diagcolcheck"><input id="chksel'+J+'" type="checkbox" checked><label for="chksel'+J+'"></label></div>';
            else
              lilistamampi+='<div class="diagcolcheck"><input id="chksel'+J+'" type="checkbox"><label for="chksel'+J+'"></label></div>';
            if(_select_list[J].group==1)
              lilistamampi+='<div class="diagcolcheck"><input id="chkgrp'+J+'" type="checkbox" checked><label for="chkgrp'+J+'"></label></div>';
            else
              lilistamampi+='<div class="diagcolcheck"><input id="chkgrp'+J+'" type="checkbox"><label for="chkgrp'+J+'"></label></div>';

            lilistamampi+='<div class="diagcolradio">'+calcselect+'</div>';         
            lilistamampi+='</li>';
              $( "#diagsortable" ).append(lilistamampi);
           }
          
           $("#sum_chart_group").append(optioncampi);
           $("#sum_chart_calc").append(optioncampi);
           $("#crosstab_sum").append(optioncampi);
           $("#crosstab_cols_group").append(optioncampi);
           $("#crosstab_rows_group").append(optioncampi);
           var first=true;
           for(J=0;J<_select_list.length;J++){
            if(_select_list[J].calc=="sum") 
            {
              $("#sum_chart_calc option[value='" + _select_list[J].label + "']").prop("selected", true);           
              $("#crosstab_sum option[value='" + _select_list[J].label + "']").prop("selected", true);
            }
             
            if(_select_list[J].group==1) 
            {            
             $("#sum_chart_group option[value='" + _select_list[J].label + "']").prop("selected", true);  
             if(!first) 
                 $("#crosstab_rows_group option[value='" + _select_list[J].label + "']").prop("selected", true);
             else       
                 $("#crosstab_cols_group option[value='" + _select_list[J].label + "']").prop("selected", true);
             first=false;
            }         
           }
           if(dofilters==1)
              setfilters(_id,eval(data.filters),0);
            
         return;
       }       
       }); 
}                                                

function getfilters()
{
 var fil="";
 var modulo=$('#search_form input[name=module]').val();
   if($('#'+modulo+'basic_searchSearchForm').attr('style')==0)
   {
      _obj=$('#'+modulo+'basic_searchSearchForm');
      strname="_basic";
   }
   else
   {
      _obj=$('#'+modulo+'advanced_searchSearchForm'); 
      strname="_advanced";
   }
   fil='{"type":"search","id":"basic_advanced","value":"'+strname+'"}'; 
   _obj.find('input:not([type=hidden][type=button])').each(function(index) { 
    
      if($(this).attr("id")!=undefined)  
      {
        if(fil!='')
         fil+=',';
        fil+='{"type":"'+$(this).attr("type")+'","id":"'+$(this).attr("id")+'","value":"'+$(this).val()+'"}';
      } 
   });
   _obj.find('select').each(function(index) { 
      if($(this).attr("id")!=undefined)  
      {
        var lstval="";
        $(this).find(':selected').each(function(i, selected){ 
          if(lstval!='')
            lstval+='|';
          lstval += $(selected).val(); 
        });
        if(lstval=='')
           lstval='!-'; // no option is selected: is different from empty option value
        if(fil!='')
           fil+=',';
        fil+='{"type":"select","id":"'+$(this).attr("id")+'","value":"'+lstval+'"}';
      }
   });   
 return fil;
}

function setfilters(reportid,fil,switch_search)
{
 var modulo=$('#search_form input[name=module]').val();
 var _obj;
 for(J=0;J<fil.length;J++){
  if(fil[J].id=='basic_advanced')
   {
       if((switch_search==1)&&($('#search_form_submit'+fil[J].value).length==0))
       {   
           setTimeout(function () {
              setfilters(reportid,fil,1);
            }, 500);
          return;
       }
       
       if((fil[J].value=='_advanced')&&($('#'+modulo+'basic_searchSearchForm').attr('style')==0))   // is basic
       {
           $('#advanced_search_link').click();
           setTimeout(function () {
              setfilters(reportid,fil,1);
            }, 500);
          return;           
       }
       if((fil[J].value=='_basic')&&($('#'+modulo+'advanced_searchSearchForm').attr('style')==0))   // is basic
       {
           $('#basic_search_link').click();   
           setTimeout(function () {
              setfilters(reportid,fil,1);
            }, 500);
          return;
       }
       _obj=$('#search_form_submit'+fil[J].value);
   }
  else
  {
    if(fil[J].type=='select')
      $.each(fil[J].value.split("|"), function(i,e){
        if(e!='!-')
           $("#"+fil[J].id+" option[value='" + e + "']").prop("selected", true);
      });

    if(fil[J].type=='text')
       $('#'+fil[J].id).val(fil[J].value);
    if((fil[J].type=='radio')||(fil[J].type=='checkbox'))
       $('#'+fil[J].id).filter('[value="'+fil[J].value+'"]').prop('checked', true);
  }
 }
 // save cookie with reportid
 setCookie('reportid', reportid,0);
  //$('#search_form').attr('action',$('#search_form').attr('action')+'&reportid='+reportid);
 _obj.click();
}

function savereport()
{
     var currentdialog='#Dialog1';
     var _id="";
     var _name="";
     var _privatereport=0;
     var _filters="";
     var _select_list="";
     var _format_pdf="";
     var _width_pdf=0;
     var _height_pdf=0;
     var _print_logo=1;
     var _calctype='sum';
     var _report_module=$('.search_form').find('input[name=module]').val();
     


      $(currentdialog).find("#erroredialogformato").text("");              
      $(currentdialog).find("#save_status").text("");                    
      // check if name is not empty
      if($(currentdialog).find("#reporttitle").val()==''){
        {
           $(currentdialog).find("#erroredialog").text(SUGAR.language.get('app_list_strings', 'sugarprint')['MSGNAMENOTEMPTY']);
           return;
        } 
      }  
    
     _report_type=$(currentdialog).find("#report_type option:selected").val();
    
      if($(currentdialog).find("#stampalogo").attr('checked')) 
        _print_logo=$(currentdialog).find("#stampalogo").val();
      _id=$(currentdialog).find("#idreport").val();
      _name=$(currentdialog).find("#reporttitle").val();
      if($(currentdialog).find("#privatereport").attr('checked'))     
        _privatereport=$(currentdialog).find("#privatereport").val();
      _format_pdf=$(currentdialog).find("#formatopagina option:selected").val();  
      if($(currentdialog).find("#privatereport").attr('checked'))
      _sparkline=$(currentdialog).find("#addsparkline").val();     
      else
      _sparkline=0;
      _date_interval=$(currentdialog).find("#formatodate option:selected").val();      
     
     if($(currentdialog).find("#report_type").prop('selectedIndex')==1) // crosstab
      _calctype=$(currentdialog).find("#crosstab_calc option:selected").val();
     else
      _calctype=$(currentdialog).find("#sum_chart_sum option:selected").val();     
     
      if($(currentdialog).find("#formatopagina option:selected").val()=="Custom")
      { 
        _width_pdf=$(currentdialog).find("#customwidth").val();
        _height_pdf=$(currentdialog).find("#customheight").val();
      } 

     if($(currentdialog).find("#report_type").prop('selectedIndex')==0)   // report
     {
         $('#diagsortable li').each(function(index){
         if(index>0) // no titles
         {
           var grp=0;
           var clc='';
           var csel=0;
           if($(this).find('input[id^="chkgrp"]:checked').length > 0)
            grp=1;
           if($(this).find('input[type="radio"]:checked').val()!=undefined)
             clc=$(this).find('input[type="radio"]:checked').val();
           if($(this).find('input[id^="chksel"]:checked').length > 0)
             csel=1;
           if(_select_list!="")
             _select_list+=',';
           var datatype=$(this).attr('datatype');
           if(datatype==undefined) 
            datatype="";
           _select_list+='{"label":"'+$(this).attr('label')+'","name":"'+$(this).attr('data')+'","datatype":"'+datatype+'","sel":'+csel+',"calc":"'+clc+'","group":'+grp+'}';
         } 
       });
     } 
     if($(currentdialog).find("#report_type").prop('selectedIndex')==1) // crosstab
     {
         if(($("#crosstab_cols_group").prop('selectedIndex')==0)||($("#crosstab_rows_group").prop('selectedIndex')==0)||($("#crosstab_sum").prop('selectedIndex')==0))
         {
           $(currentdialog).find("#erroredialog").text(SUGAR.language.get('app_list_strings', 'sugarprint')['MSG_ERRSELECT']);
           return;
         }
         _select_list+='{"label":"'+$("#crosstab_sum").find(":selected").val()+'","name":"","sel":0,"calc":"sum","group":0}';
       // first is always cols
         _select_list+=',';
         _select_list+='{"label":"'+$("#crosstab_cols_group").find(":selected").val()+'","name":"","sel":1,"calc":"","group":1}';
         _select_list+=',';
         _select_list+='{"label":"'+$("#crosstab_rows_group").find(":selected").val()+'","name":"","sel":1,"calc":"","group":1}';
     }
     if($(currentdialog).find("#report_type").prop('selectedIndex')>1) // summary and chart
     {     
         if(($("#sum_chart_group").prop('selectedIndex')==0)||($("#sum_chart_calc").prop('selectedIndex')==0))
         {
           $(currentdialog).find("#erroredialog").text(SUGAR.language.get('app_list_strings', 'sugarprint')['MSG_ERRSELECT']);
           return;
         }
         _select_list+='{"label":"'+$("#sum_chart_calc").find(":selected").val()+'","name":"","sel":0,"calc":"sum","group":0}';
         _select_list+=',';
         _select_list+='{"label":"'+$("#sum_chart_group").find(":selected").val()+'","name":"","sel":1,"calc":"","group":1}';
     }

     _filters=getfilters();
     
      $.ajax({
    		type: "POST",
    		url: "index.php?to_pdf=1&module=SugarPrint&action=savereport",
    		async:false,
    		dataType: "text",
  		  data: {
     		  id:_id,
     		  name:_name,    
          report_type:_report_type,
          sparkline:_sparkline,
     		  privatereport:_privatereport,
     		  filters:'['+_filters+']',          
     		  select_list:'['+_select_list+']',
     		  format_pdf:_format_pdf,          
     		  width_pdf:_width_pdf,
     		  height_pdf:_height_pdf, 
          calctype:_calctype,         
     		  print_logo:_print_logo,
          date_interval:_date_interval,
     		  report_module:_report_module   
  			}, 

    		success: function(data) {	
         $(currentdialog).find("#save_status").text(data);
         return;
       }       
       }); 

}

function setparameters()
{
      var currentdialog='#Dialog1';
      _date_interval=$(currentdialog).find("#formatodate option:selected").val();
      _reporttitle=$(currentdialog).find("#reporttitle").val(); 
      _report_type=$("#report_type").val();
        
       _tableselectarray=[]; 
        switch(_report_type)
        {
          case 'pdf_report':
               $('#diagsortable li').each(function(index){
               if(index>0) // no titles
               {
                 var grp=0;
                 var clc='';
                 var csel=0;
                 if($(this).find('input[id^="chkgrp"]:checked').length > 0)
                  grp=1;
                 if($(this).find('input[type="radio"]:checked').val()!=undefined)
                   clc=$(this).find('input[type="radio"]:checked').val();
                 if($(this).find('input[id^="chksel"]:checked').length > 0)
                   csel=1;
                if(clc!='') // if you need calc you have to select column
                  csel=1;
                 _tableselectarray.push({"datatype":$(this).attr('datatype'),"label":$(this).attr('label'),"name":$(this).attr('data'),"sel":csel,"calc":clc,"group":grp });
               } 
             });
             break;
          case 'pdf_report_crosstab':
             if(($("#crosstab_cols_group").prop('selectedIndex')==0)||($("#crosstab_sum").prop('selectedIndex')==0)||($("#crosstab_rows_group").prop('selectedIndex')==0))
             {
               $(currentdialog).find("#erroredialog").text(SUGAR.language.get('app_list_strings', 'sugarprint')['MSG_ERRSELECT']);
               return false;
             }
            if($(currentdialog).find('input[id="addsparkline"]:checked').length > 0)
              _sparkline=1;  
            else
              _sparkline=0;
              
             var calctype=$("#crosstab_calc").find(":selected");
             var calcsel_dd=$("#crosstab_sum").find(":selected");
             var colssel_dd=$("#crosstab_cols_group").find(":selected");
             var rowssel_dd=$("#crosstab_rows_group").find(":selected");
             _tableselectarray.push({"datatype":calcsel_dd.attr('datatype'),"label":calcsel_dd.val(),"name":calcsel_dd.attr('data'),"sel":0,"calc":calctype.val(),"group":0 });
             _tableselectarray.push({"datatype":colssel_dd.attr('datatype'),"label":colssel_dd.val(),"name":colssel_dd.attr('data'),"sel":1,"calc":"","group":1 });
             _tableselectarray.push({"datatype":rowssel_dd.attr('datatype'),"label":rowssel_dd.val(),"name":rowssel_dd.attr('data'),"sel":1,"calc":"","group":1 });
             break;
          default:
            if(_report_type=='pdf_pie_chart')
                 _dochart=1;
            if(_report_type=='pdf_bar_chart')
                 _dochart=2;    
            if(_report_type=='pdf_hist_chart')
                 _dochart=3;    

           if(($("#sum_chart_group").prop('selectedIndex')==0)||($("#sum_chart_calc").prop('selectedIndex')==0))
           {
             $(currentdialog).find("#erroredialog").text(SUGAR.language.get('app_list_strings', 'sugarprint')['MSG_ERRSELECT']);
             return false;
           }
          var sum_chart_sum=$("#sum_chart_sum").find(":selected");
           var calcsel_dd=$("#sum_chart_calc").find(":selected");
           var groupsel_dd=$("#sum_chart_group").find(":selected");
           _tableselectarray.push({"datatype":calcsel_dd.attr('datatype'),"label":calcsel_dd.val(),"name":calcsel_dd.attr('data'),"sel":0,"calc":sum_chart_sum.val(),"group":0 });
           _tableselectarray.push({"datatype":groupsel_dd.attr('datatype'),"label":groupsel_dd.val(),"name":groupsel_dd.attr('data'),"sel":1,"calc":"","group":1 });

          }
      // print logo
      if($(currentdialog).find("#stampalogo").attr('checked')) 
         _mostralogo=$(currentdialog).find("#stampalogo").val();
      else
         _mostralogo=0;
  return true;
}

function setDMReportPar()
{
  var tmptitle;
  _contaloop=0;
  if($("#companyLogo").find("img").length>0)
  {
    var tmpa=$("#companyLogo").find("img").attr("src").split(",");
   _companylogo=tmpa[0];
  } 
  else
  {
   _companylogo='';
  }
  
  _report_slick='';
  _crmtitle=document.title;    
  _reporttitle=$('#search_form input[name=module]').val();    
  if(_reporttitle=='')
  {
    var _objtitle=$(".moduleTitle h2").clone();          
    $('a', _objtitle).remove();
    $('span', _objtitle).remove();
    _reporttitle=_objtitle.text();   
  }    
  _session='';  
  _tablebodyarray=[];               
  _tableidarray=[];  
  _tableheaderarray=[];
  _detailbodyarray=[];
  _subpanelname='';
  _detailtablist=[];
  _go=true;
  _offset=0;  
  _annullareport=false;
}


function stampadetailview(){
      _annullareport=true;        
      $.ajax({
    		type: "POST",
    		url: "index.php?to_pdf=1&module=SugarPrint&action=reportpdf",
    		async:false,
    		dataType: "text",
  		  data: {
     		 _report_type:_report_type,
          _session:_session,
     		  _tablecomplete:1, 
     		  _mostralogo:1, 
     		  _orientation:_orientation,
      	  _pageformat:_pageformat,
      	  _customwidth:_customwidth,
      	  _customheight:_customheight,
  				_crmtitle:	_crmtitle,
  				_companylogo:	_companylogo,
  				_reporttitle:	_reporttitle,
  				_detailbodyarray:_detailbodyarray,
  				_detailtablist:_detailtablist
  			}, 
  			
    		success: function(data) {	
         $("#DialogText2").dialog("close");
         	var win=window.open(data.replace(/^\s\s*/, '').replace(/\s\s*$/, '')); 
       }       
       }); 

}

function stampalistview(){
    setmodule();
    _annullareport=true;
    $.ajax({
  		type: "POST",
  		url: "index.php?to_pdf=1&module=SugarPrint&action=reportpdf",
  		async:false,
  		dataType: "text",
		  data: {
   		  _session:_session,
   		  _tablecomplete:1,
   		  _cmodule:cmodule,
     		_mostralogo:_mostralogo, 
        _report_type:_report_type,
        _sparkline:_sparkline,
        _report_slick:_report_slick,
        _dochart:_dochart,
   		  _orientation:_orientation,
     	  _customwidth:_customwidth,
     	  _customheight:_customheight,
   		  _pageformat:_pageformat,
				_crmtitle:	_crmtitle,
				_companylogo:	_companylogo,
				_reporttitle:	_reporttitle,
      	_date_interval:	_date_interval,
				_tablewidtharray:	_tablewidtharray,
        _tablealignarray:	_tablealignarray,
        _tableheaderarray:_tableheaderarray,
        _tableselectarray:_tableselectarray

			}, 
  		success: function(data) {	      
                         
          $("#DialogText2").dialog("close");
         	var win=window.open(data.replace(/^\s\s*/, '').replace(/\s\s*$/, '')); 
    
     }       
     }); 
}

function getsubpanels(){

      var _objsubpanels=$("#subpanel_list > li"); 
      _objsubpanels.each(function(index) {  
            if(_csubpanel==index){
                $(this).find(".formHeader.h3Row td:first").each(function(index2) { 
                     _subpanelname=$(this).find("h3").find("span").text();                 
                 });
                 $(".list", $(this)).find("tr").each(function(index3) {  
                       if(_tableheaderarray.length==0){
                              var arrayheaderrow = [];
                              var tableheaderData = $(this).find('th');
                              if (tableheaderData.length > 0) {
                                      tableheaderData.each(function() {
                                      var testoc=$(this).text().trim();
                                      arrayheaderrow.push(testoc); 
                                      if(testoc!='')
                                        _tablewidtharray.push($(this).width()); 
                                      else
                                        _tablewidtharray.push(0);                                       
                                    });                                  
                               }              
                              _tableheaderarray=arrayheaderrow;               
                       }   
                   });  
                var url_subpanel=$(this).find(".listViewThLinkS1:first").attr("href");
                if(url_subpanel!=undefined)
                {
                 var tmpsplit=url_subpanel.split("'");          
                 _onclickcode=tmpsplit[3];   
                 _offset=0;   
                 do_report();                               
                }
                else
                {
                _csubpanel++;
                }
              }
               
       });
    //    if(_csubpanel>=$("#subpanel_list > li").length)
    //       stampadetailview();
     //    else
       if(_objsubpanels.length<=0)
          stampadetailview();
                                          
}

function do1calendar(){
   var nomeleft='.left_time_col';
   var nomeheaderleft='.left_time_col';
   var nomeheaderweek='.week_block';
   var nomeweek='.week_block';
   var num_col=0;

    nomeleft='#cal-scrollable>.left_col';
    nomeheaderleft='.left_col';
    nomeheaderweek='.week';
    nomeweek='#cal-scrollable>.week';
    num_col=1;
    
   
   var tipo='';
   
   var tmplen=$(nomeheaderleft).length;
   
   var rowcalendar=[]; 
   var weekcalendar=[]; 
   var rowheaderscalendar=[]; 
   var tablestitles=[];
   _headerscalendar=[]; 
   _bodiescalendar=[]; 
  
   _tablealignarray=[]; 
   _tablewidtharray=[]; 
   
  if($('.monthCalBody').length>0)
     tmplen=0; //condiviso
    
  if(tmplen==1+num_col)   // giorno
   {
      tipo=1;
      _headerscalendar.push("");
      _tablealignarray.push("center");
      _tablewidtharray.push($(nomeleft+'>div').width());
      $(nomeweek+'>div').each(function(index) { 
          _headerscalendar.push("");
          _tablealignarray.push("left");
          _tablewidtharray.push($(nomeweek+'>div:eq('+index+')>div').width());
      });  
      var primarigadati=-1;
      var ultimarigadati=-1;

      $(nomeleft+'>div').each(function(index) { 
          rowcalendar=[];          
          rowcalendar.push($(this).text().trim());
          $(nomeweek+'>div').each(function(index2) {
            testo=""; 
            if($(nomeweek+'>div:eq('+index2+')>div:eq('+index+')').find('div').length>0)
            { 
               slot=1;
               $(nomeweek+'>div:eq('+index2+')>div:eq('+index+')').find('div').each(function(index02) {             
                  if($(this).attr('duration_coef'))
                   slot=$(this).attr('duration_coef');
                });   
               testo=$(nomeweek+'>div:eq('+index2+')>div:eq('+index+')').find('.head').text().trim();
               testo=testo+"\n"+$(nomeweek+'>div:eq('+index2+')>div:eq('+index+')').find('.contain').text().trim()+"|"+slot;
               if((testo!="")&&(primarigadati==-1))
               {
                  primarigadati=index;
                  if((rowcalendar[0]=="")&&(primarigadati>0))
                    primarigadati=primarigadati-1;
               }
               if(testo!="") {
                 ultimarigadati=index+parseInt(slot)+1;
               }                           
                  
             }   
             rowcalendar.push(testo);
             
          });
          _bodiescalendar.push(rowcalendar);
            
      });   
      if(ultimarigadati!=-1) 
      {
        if(ultimarigadati<88)
           ultimarigadati=88;
         _bodiescalendar.splice(ultimarigadati,_bodiescalendar.length);
      }                               
      
      if(primarigadati!=-1){
         if(primarigadati>32)
           primarigadati=32;
         _bodiescalendar.splice(0,primarigadati);
      }
        
   }   
     
   if(tmplen==2+num_col)   // settimana
   {
      tipo=2;     
      _headerscalendar.push("");
      _tablealignarray.push("center");
      _tablewidtharray.push($(nomeheaderleft+':eq(1)>div').width());
      $(nomeheaderweek+':eq(0)>div').each(function(index) { 
          _headerscalendar.push($(this).text().trim());
           _tablealignarray.push("left");
           _tablewidtharray.push($(nomeheaderweek+':eq(0)>div:eq('+index+')>div').width());
      });  
      var primarigadati=-1;
      var ultimarigadati=-1;
      var num_col_left=1+num_col;
      $(nomeheaderleft+':eq('+num_col_left+')>div').each(function(index) { 
          rowcalendar=[];          
          rowcalendar.push($(this).text().trim());
          $(nomeheaderweek+':eq('+num_col_left+')>div').each(function(index2) {
            testo=""; 
            if($(nomeheaderweek+':eq('+num_col_left+')>div:eq('+index2+')>div:eq('+index+')').find('div').length>0)
            { 
               slot=1;
               $(nomeheaderweek+':eq('+num_col_left+')>div:eq('+index2+')>div:eq('+index+')').find('div').each(function(index02) {             
                  if($(this).attr('duration_coef'))
                   slot=$(this).attr('duration_coef');
                });                 

               testo=$(nomeheaderweek+':eq('+num_col_left+')>div:eq('+index2+')>div:eq('+index+')').find('.head').text().trim();
               testo=testo+"\n"+$(nomeheaderweek+':eq('+num_col_left+')>div:eq('+index2+')>div:eq('+index+')').find('.contain').text().trim()+"|"+slot;
               if((testo!="")&&(primarigadati==-1))
               {
                  primarigadati=index;
                  if((rowcalendar[0]=="")&&(primarigadati>0))
                    primarigadati=primarigadati-1;
               }
               if(testo!="") {
                 ultimarigadati=index+parseInt(slot)+1;
               }                           
                  
             }   
             
             rowcalendar.push(testo);
             
          });
          _bodiescalendar.push(rowcalendar);
            
      });                                   
      if(ultimarigadati!=-1) 
      {
        if(ultimarigadati<88)
           ultimarigadati=88;
         _bodiescalendar.splice(ultimarigadati,_bodiescalendar.length);
      }                               
      
      if(primarigadati!=-1)
      {
         if(primarigadati>32)
           primarigadati=32;
         _bodiescalendar.splice(0,primarigadati);
      } 
 
   }
 
   if(tmplen>2+num_col)   // mese
   {
      tipo=3;
        // for each week
        $('#cal-grid>div').each(function(index) {
            rowheaderscalendar=[];
            $(this).find('.col_head').each(function(indexhead) {
              rowheaderscalendar.push($(this).text().trim());
              if(index==0)
              {
                _tablealignarray.push("left");
                _tablewidtharray.push($(this).width());
              }
           });
           rowcalendar=[];
           rowcalendar.push("");      // first column is empty on body
           $(this).find('.cal-basic').find('.col').each(function(indexbody) {
                testo=""; 
                if($(this).find('div').length>0)
                { 
                   slot=1;
                   $(this).find('div').each(function(index02) {             
                      if($(this).attr('duration_coef'))
                       slot=$(this).attr('duration_coef');
                    });  
                   testo=$(this).find('.head').text().trim();
                   if(testo!="")
                      testo=testo+"\n"+$(this).find('.contain').text().trim()+"|"+slot;                                       
                 }               
                 rowcalendar.push(testo);
           });
        weekcalendar.push(rowcalendar); 
        _headerscalendar.push(rowheaderscalendar);             
        _bodiescalendar.push(weekcalendar);
        weekcalendar=[];      
        });

   }     
   
    if(tmplen==0)   // condiviso
   {
     tipo=4;     

       $(".monthCalBody").each(function(indexw) {
           tablestitles.push($(this).text().trim());
        });
       var _tableuser=$('div[user_name]'); 
       _tableuser.each(function(index) {
           rowheaderscalendar=[];
           $(this).find('.col_head').each(function(index2) { 
              rowheaderscalendar.push($(this).text().trim());
              if(index==0)
               {
                 _tablealignarray.push("center");
                 _tablewidtharray.push($(this).width());
               }
           });
           var _tableweek= $(this).find('.week:eq(2)');
           $(this).find('.left_slot').each(function(rowindex) { 
               rowcalendar=[];
               rowcalendar.push($(this).text().trim());
               _tableweek.find('.col').each(function(index3) {
                  var _cella=$(this).find('.slot:eq('+rowindex+')'); 
                  testo="";
                  slot=1;
                  _cella.find('div').each(function(index02) {             
                    if($(this).attr('duration_coef'))
                     slot=$(this).attr('duration_coef');
                   });   
                  testo=_cella.find('.head').text().trim();
                  if(testo!="")
                    testo=testo+"\n"+_cella.find('.contain').text().trim()+"|"+slot;  
                  rowcalendar.push(testo);                                     
              });               
              weekcalendar.push(rowcalendar);    
          });
          _headerscalendar.push(rowheaderscalendar);             
          _bodiescalendar.push(weekcalendar);
          weekcalendar=[];                                         
      });

   }   
   
    _annullareport=true;        
   $.ajax({
		type: "POST",
		url: "index.php?to_pdf=1&module=SugarPrint&action=reportpdf",
		async:false,
		dataType: "text",
	  data: {
      _tablecomplete:1,
      _report_type:'calendar',
 		  _mostralogo:1, 
 		  _orientation:_orientation,
  	  _pageformat:_pageformat,
  	  _customwidth:_customwidth,
  	  _customheight:_customheight,
			_crmtitle:	_crmtitle,
			_companylogo:	_companylogo,
			_reporttitle:	_reporttitle,
      _bodiescalendar:_bodiescalendar,
      _calendar:tipo,		  
      _tablestitles:tablestitles,
			_headerscalendar:_headerscalendar,
			_tablewidtharray:_tablewidtharray,
      _tablealignarray:_tablealignarray
		}, 
		
		success: function(data) {	
     $("#DialogText2").dialog("close");
     	var win=window.open(data.replace(/^\s\s*/, '').replace(/\s\s*$/, '')); 
   }       
   }); 

}


function do1(){ 
                                         
//================
// DETAIL VIEW   
//================

  if(_currentpagetype=='detailview')
  {
  
      if($('.yui-nav').length>0)
      {
         $('.yui-nav').find('li').each(function(index){ 
           _detailtablist.push($(this).find('a').text().trim());   
           $('.yui-content>div:eq('+index+')').each(function(index2){ 
               $(this).find('.detail.view').each(function(index3){  
                 if(index3>0) 
                 {
                  tmp=$(this).find('h4').clone();  
                  $('script',tmp).empty();  
                  _detailtablist.push(tmp.text().trim());
                 }
               });
           }); 
         });
      }
      else
      {
        $('.detail.view').each(function(index){       
                  tmp=$(this).find('h4').clone();  
                  $('script',tmp).empty();  
                  _detailtablist.push(tmp.text().trim());
         });
      }   
   
      
      var _detailbodyarray1=[];
      $(".detail.view").each(function(index) {
            var haselementstable=false; 
            $('.detail.view:eq('+index+') tr').each(function(index2) {
               if($(this).parent('.detail tbody tbody').length==0)
               {    
                var arraybodyrow = [];
                var tableData = $(this).find('td');
                var haselementsrow=false;
                $('a', $(this)).contents().unwrap();
                tableData.each(function(ccol) { 
                    // checks for checkbox
                     tmp=$(this).clone();
                     $('input:checked',tmp).replaceWith('[ X ] ');
                     $(':checkbox',tmp).not(':checked').replaceWith('[ ] ');
                     $('style',tmp).empty();
                     $('script',tmp).empty();  
                     haselementsrow=true;
                     arraybodyrow.push(tmp.text().replace(/(\r\n|\n|\r)/gm," ").trim());     
                                                  
                }); 
                if(haselementsrow)
                {
                   _detailbodyarray1.push(urlencoderow(arraybodyrow));
                   haselementstable=true; 
                }     
               }
              });
              if(haselementstable)
                _detailbodyarray.push(_detailbodyarray1);              
              _detailbodyarray1=[];             
          });
   
    // sottopannelli    
      _csubpanel=0;
      getsubpanels();
      
      return;
      
  }
//================
// KReports   
//================
  if(_currentpagetype=='KReports')
   {
        do_reportsinglepage();
        stampalistview();      
   }

//================
// LISTVIEW   
//================
    
    if(($("#listViewNextButton_top").attr("disabled")=='disabled')&&($("#listViewPrevButton_top").attr("disabled")=='disabled'))
    {
        do_reportsinglepage();
        stampalistview();  
    }
    else
    {
          var _objform=$("#MassUpdate").find('input');
          var tmpurl=''; 
          _objform.each(function() { 
          var nomeinput=$(this).attr("name");
          var valueinput=''; 
          if(nomeinput!='mass[]'){  
              valueinput= $(this).attr("value");
              if(nomeinput=='massupdate')
                  valueinput='false';
              if(nomeinput=='action')
                  valueinput='index';
                  
              if(tmpurl=='')
                tmpurl='index.php?'+nomeinput+'='+valueinput; 
              else
                tmpurl=tmpurl+'&'+nomeinput+'='+valueinput;            
          }   
          }); 
          _onclickcode=tmpurl; 
          if(_onclickcode=='')
          {
             return;
          }
          do_report();

    }
  
}



function setreportlistview()
{
  var pdfxls='';
  
  if(check_sp>5)
   return;
  check_sp++; 
  
 if($("#sugarprintlinks").length>0)
 {
    check_sp=6; 
    return;
 }


 if($('#formDetailView').find('input[name="module"]').val()=='KReports')
 {
     pdfxls='<span id="sugarprintlinks" class="pageNumbers" style="cursor:pointer;" onclick="do0(\'KReports_pdf\');"><img style="display:inline;vertical-align:bottom" src="'+paththeme+'/images/SugarPrint.png"></span>';
     $(".utils").prepend(pdfxls);        
     check_sp=6;
     return;      
       
 }

  
  if(($(".list").length >0)||($(".detail.view").length>0)||($(".monthHeader").length>0))
  {   
   $("#sitemapLink").css('width','auto');
   var pdf=false;
   $(".menuItem").each(function(index) {
    if($(this).attr('onclick').indexOf("export")>0)
     pdf=true;
   }); 
   if(($('[id^=export_listview]').length>0)||(pdf)||($(".monthHeader").length>0))   
     pdfxls='<span id="sugarprintlinks" class="pageNumbers" style="position: relative;cursor:pointer;" onclick="do0(\'pdf\');"><img style="bottom:-6px; position: absolute; left: -100px;" src="'+paththeme+'/images/SugarPrint.png"></span>';
        
   if($(".detail.view").length>0)
   {
      pdfxls='<span id="sugarprintlinks" class="pageNumbers" style="cursor:pointer;" onclick="do0(\'pdf\');"><img style="vertical-align:bottom" src="'+paththeme+'/images/SugarPrintPdf.png"></span><span id="sugarprintlinks" class="pageNumbers" style="cursor:pointer;" onclick="do0(\'xls\');"><img style="vertical-align:bottom" src="'+paththeme+'/images/SugarPrintExcel.png"></span>';
      if($(".actionsContainer .action_buttons .clear").length>0)
        $(".actionsContainer .action_buttons .clear").before(pdfxls);
      else
        $(".actionsContainer").append(pdfxls);        
   }
   else
   {
    if($(".monthHeader").length>0)
    {
      pdfxls='<span id="sugarprintlinks" class="pageNumbers" style="cursor:pointer;" onclick="do0(\'pdf\');"><img style="vertical-align:bottom" src="'+paththeme+'/images/SugarPrintPdf.png"></span>';
      $("#day-tab").parent().append(pdfxls);
    }
    else
    {
      $(".paginationChangeButtons").prepend(pdfxls);    
    }
  
   }
   var pu=getCookie('reportid');
   if((pu!=undefined)&&(pu!=''))
    {
      do0('pdf');     
    } 

   check_sp=6; 
           
  }               
}                     


function clickall(){
   check_sp=0;
  setlinks1()    
} 

$(window).on('hashchange', function() {
   check_sp=0;
   setTimeout(setlinks1,500);  
});

        
function setlinks1(){
  if($("#sugarprintlinks").length==0)
  {
    setreportlistview();
  }
  else
  {
    setTimeout(setlinks1,500);
  }


};

//*********************************
// READY
//*********************************
if(window.jQuery)  
{
    $(function() 
    {
     setlinks1();
    });
                
    $(document).click(function(e) {
      clickall();
    });
}

      
//*********************************
// FUNZIONI GENERICHE
//*********************************
function urlpar(c_url)
{
    var vars = [], hash;
        var q = c_url.split('?')[1];
        if(q != undefined){
            q = q.split('&');
            for(var i = 0; i < q.length; i++){
                hash = q[i].split('=');
                vars.push(hash[1]);
                vars[hash[0]] = hash[1];
            }
    }
    return vars;
}

function setCookie(name, value, expires, path, domain, secure){
	document.cookie = name + "=" + escape(value) + "; ";	
	if(expires){
		expires = setExpiration(expires);
		document.cookie += "expires=" + expires + "; ";
	}
	if(path){
		document.cookie += "path=" + path + "; ";
	}
	if(domain){
		document.cookie += "domain=" + domain + "; ";
	}
	if(secure){
		document.cookie += "secure; ";
	}
}

function setExpiration(cookieLife){
    var today = new Date();
    var expr;
    if (cookieLife!=0)
     expr = new Date(today.getTime() + cookieLife * 24 * 60 * 60 * 1000);
    else
     expr = new Date(today.getTime() + 15 * 1000); // 15 sec
    return  expr.toGMTString();
}
function getCookie(w){
	cName = "";
	pCOOKIES = new Array();
	pCOOKIES = document.cookie.split('; ');
	for(bb = 0; bb < pCOOKIES.length; bb++){
		NmeVal  = new Array();
		NmeVal  = pCOOKIES[bb].split('=');
		if(NmeVal[0] == w){
			cName = unescape(NmeVal[1]);
		}
	}
	return cName;
}
function is_int(value){ 
  if((parseFloat(value) == parseInt(value)) && !isNaN(value)){
       return true;
   } else { 
      return false;
   } 
}


function urlencoderow(ar) {
 return ar;
}
