<html>
{literal}  
  <head>
   	<link rel="stylesheet" href="modules/SugarPrint/css/dialogtabstyle.css">
    <link type="text/css" rel="stylesheet" href="modules/SugarPrint/css/jquery.qtip.min.css" />
  </head>
  <script type="text/javascript" src="modules/SugarPrint/js/jquery.qtip.min.js"></script>
  <script>
  $(function() {
      $('.hasT').each(function() { 
          $(this).qtip({
              content: {
                  text: $(this).next('div') 
              }
          });
      });
  });
  </script>
{/literal}
<body>
<form>
<input type="hidden"  name="idreport" id="idreport">
</form>
<div class="tabGroup">
    <input type="radio" name="tabGroup1" id="rad1" class="tab1" checked="checked"/>
    <label for="rad1">{$labreportrun}</label>
 
    <input type="radio" name="tabGroup1" id="rad2" class="tab2"/>
    <label for="rad2">{$labdesignreport}</label>
     
    <input type="radio" name="tabGroup1" id="rad3" class="tab3"/>
    <label for="rad3">{$labformatreport}</label>

    <input type="radio" name="tabGroup1" id="rad4" class="tab4"/>
    <label for="rad4">{$labsavereport}</label>

    <input type="radio" name="tabGroup1" id="rad5" class="tab5"/>
    <label for="rad5">{$labhelpreport}</label>

    <br/>

    <div class="tab1">
    <fieldset> 
    <legend>{$labcreatereport}</legend> 
        <table width="100%" cellspacing="5px;" cellpadding="5px;"><tbody>
          <tr>
          <td class="primacol" >{$labreport_type}:
          </td>      
          <td valign="top" nowrap="" align="left"  colspan=2>
           <select id="report_type" onchange="switch_design();">
            {html_options values=$report_val output=$report_names selected=$report_type}
           </select>&nbsp; 
           <img border="0" class="hasT" alt="Information" src="modules/SugarPrint/images/helpInline.gif">
           <div style="display:none;">
              {$help_report_type}
          </div>
          </td>
          </tr>
          <tr>
              <td class="primacol" valign="top" nowrap="" align="left">{$labtit}:</td>
              <td>
                <input type="text" value="{$reporttitle}" id="reporttitle" size="39" maxlength="250">&nbsp; 
                <img border="0" class="hasT" alt="Information" src="modules/SugarPrint/images/helpInline.gif">
                <div style="display:none;">
                  {$help_report_title}
                </div>
              </td>
            </tr>
        </table>
     </fieldset>  
    <fieldset> 
    <legend>{$lablist_reportsaved}</legend> 
       <table width="100%" cellspacing="5px;" cellpadding="5px;"><tbody>
        <tr>
        <td class="primacol" >{$labreportrun}:
        </td>      
        <td valign="top" nowrap="" align="left"  colspan=2>
         <select id="report_list" onchange="loadreport(0);">
         </select>&nbsp; 
          <img border="0" class="hasT" alt="Information" src="modules/SugarPrint/images/helpInline.gif">
          <div style="display:none;">
            {$help_report_load}
          </div>
        </td>
        </tr>
        <tr>
          <td class="primacol" >
          </td>      
          <td valign="top" nowrap="" align="left">            
          <input style="display:none;" type="button" id="set_filters_form" value="{$labsetfiltersreport}" onclick="loadreport(1);">
          </td>
        </tr>
        </table>   
    </fieldset>

    </div>
    <div class="tab2">
      <div id="for_report">
        <table width="100%" cellspacing="1px;" cellpadding="1px;"><tbody>
        <tr>   
          <td valign="top" >
              <div style="float:left;width:70%;padding-bottom:10px;">{$labfieldslist}</div>
              <div class="divextrafields" style="float:right;text-align:right;">
              <input type="button" onclick="getmorefields();return false;" value="{$labmorefields}">&nbsp; 
              <img border="0" class="hasT" alt="Information" src="modules/SugarPrint/images/helpInline.gif">
              <div style="display:none;">
                {$help_getmorefields}
              </div>
              </div>
          </td>                          
        </tr>
        <tr>
          <td valign="top" nowrap="" align="left"> 
            <ul id="diagsortable">
            </ul>
          </td>
          </tr>
        </tr>
        </table>
      </div>
      
      <div id="for_sum_chart" style="display:none;"> 
        <table width="100%" cellspacing="1px;" cellpadding="1px;"><tbody>
          <tr>   
          <td valign="top" colspan="2">
              <div style="float:left;width:70%;padding-bottom:10px;">{$labsum_chart}</div>
              <div class="divextrafields" style="float:right;text-align:right;">
              <input type="button" onclick="getmorefields();return false;" value="{$labmorefields}">&nbsp; 
              <img border="0" class="hasT" alt="Information" src="modules/SugarPrint/images/helpInline.gif">
              <div style="display:none;">
                {$help_getmorefields}
              </div>
              </div>
          </td>                           
          </tr>  
          <tr>
            <td class="primacol" ></td>
            <td>
            
            </td>
          </tr>
          <tr>
            <td class="primacol" valign="top" nowrap="" align="left">{$labfieldtogroup}:</td>
            <td>
            <select id="sum_chart_group"></select>&nbsp; 
              <img border="0" class="hasT" alt="Information" src="modules/SugarPrint/images/helpInline.gif">
              <div style="display:none;">
                {$help_field_to_group}
              </div>
            </td>
          </tr>
          <tr>
            <td class="primacol" valign="top" nowrap="" align="left">{$labfieldtosum}:</td>
            <td>
            <select id="sum_chart_calc"></select>&nbsp; 
              <img border="0" class="hasT" alt="Information" src="modules/SugarPrint/images/helpInline.gif">
              <div style="display:none;">
                {$help_field_to_sum}
              </div>
            </td>
          </tr>
          <tr>
            <td class="primacol" valign="top" nowrap="" align="left">{$labcalculation}:</td>
            <td>
            <select id="sum_chart_sum"></select>
            </td>
          </tr>          
        </table>
      </div>  
      
      <div id="for_crosstab" style="display:none;"> 
        <table width="100%" cellspacing="1px;" cellpadding="1px;"><tbody>
          <tr>   
          <td valign="top" colspan="2">
              <div style="float:left;width:70%;padding-bottom:10px;">{$labcrosstab}</div>
              <div class="divextrafields" style="float:right;text-align:right;">
              <input type="button" onclick="getmorefields();return false;" value="{$labmorefields}">&nbsp; 
              <img border="0" class="hasT" alt="Information" src="modules/SugarPrint/images/helpInline.gif">
              <div style="display:none;">
                {$help_getmorefields}
              </div>
              </div>
          </td>                           
          </tr>  
          <tr>
            <td class="primacol" ></td>
            <td>
            </td>
          </tr>
          <tr>
            <td class="primacol" valign="top" nowrap="" align="left">{$labcolumnsfield}:</td>
            <td>
            <select id="crosstab_cols_group"></select>&nbsp; 
            <img border="0" class="hasT" alt="Information" src="modules/SugarPrint/images/helpInline.gif">
            <div style="display:none;">
              {$help_crosstab_cols_group}
            </div>
            </td>
          </tr>
          <tr>
            <td class="primacol" valign="top" nowrap="" align="left">{$labrowsfield}:</td>
            <td>
            <select id="crosstab_rows_group"></select>&nbsp;
            <img border="0" class="hasT" alt="Information" src="modules/SugarPrint/images/helpInline.gif">
            <div style="display:none;">
              {$help_crosstab_rows_group}
            </div>
            </td>
          </tr>          
          <tr>
            <td class="primacol" valign="top" nowrap="" align="left">{$labfieldtosum}:</td>
            <td>
            <select id="crosstab_sum"></select>&nbsp; 
            <img border="0" class="hasT" alt="Information" src="modules/SugarPrint/images/helpInline.gif">
            <div style="display:none;">
              {$help_crosstab_sum}
            </div>
            </td>
          </tr>
          <tr>
            <td class="primacol" valign="top" nowrap="" align="left">{$labcalculation}:</td>
            <td>
            <select id="crosstab_calc"></select>
            </td>
          </tr>
          <tr>
            <td class="primacol" >
            </td>      
            <td valign="top" nowrap="" align="left">            
            <input type="checkbox" id="addsparkline" value="1" checked>&nbsp;{$labsparkline}
            </td>
          </tr>

        </table>
      </div>  
    </div>
    
    <div class="tab3">
    <fieldset> 
      <legend>{$labPDFFormat}</legend> 
        <table width="100%" cellspacing="0px;" cellpadding="3px;"><tbody>
        <tr>
          <td class="primacol" valign="top" nowrap="" align="left">{$labpagina}:</td>
          <td>
          <select name="formatopagina" id=formatopagina onchange="formatocustom('#Dialog1');" size="{$optformato|@count}">
             {html_options values=$formato_names output=$formato_names }   
          </select>&nbsp; 
            <img border="0" class="hasT" alt="Information" src="modules/SugarPrint/images/helpInline.gif">
            <div style="display:none;">
              {$help_formatopagina}
            </div>  
          <div id="divcustomformat" nowrap style="margin-top: 5px; height:10px;visibility:hidden;">w (pt):&nbsp;<input type="text" style="width:30px;" id="customwidth">&nbsp;h (pt):&nbsp;<input type="text" style="width:30px;" id="customheight"></div>
          </td>
        </tr>
        <tr>
          <td valign="top" nowrap="" align="left">{$laborient}:</td>
          <td>
          <select id="orientamento">
             {html_options values=$orient_val output=$orient_names selected=$orientation}
          </select>
          </td>
        </tr>
        <tr>
          <td class="primacol" >
          </td>      
          <td valign="top" nowrap="" align="left">            
          <input type="checkbox" id="stampalogo" value="1" checked>&nbsp;{$labprintlogo}
          </td>
        </tr>
      </table>
     </fieldset> 
    <fieldset> 
      <legend>{$labDateGroup}</legend> 
        <table width="100%" cellspacing="0px;" cellpadding="3px;"><tbody>
          <tr>   
          <td valign="top" colspan="2">{$labdategroupdesc}
          </td>
        </tr>
         <tr>   
          <td valign="top" colspan="2">&nbsp;
          </td>
        </tr>
        <tr>
          <td class="primacol" valign="top" nowrap="" align="left">{$labDateGroup}:</td>
          <td>
          <select name="formatodate" id=formatodate >
             {html_options values=$formatodate_values output=$formatodate_names selected="m"}   
          </select>&nbsp; 
            <img border="0" class="hasT" alt="Information" src="modules/SugarPrint/images/helpInline.gif">
            <div style="display:none;">
              {$help_formatodate}
            </div>  
          </td>
        </tr>
      </table>
     </fieldset> 
    </div>
    
    <div class="tab4">
        <table width="100%" cellspacing="0px;" cellpadding="3px;"><tbody>
        <tr><td style="height:30px;" colspan="2">{$labreportsavedesc}</td></tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
          <td class="primacol" >
          </td>      
          <td valign="top" nowrap="" align="left">            
          <input type="checkbox" id="privatereport" value="1" >&nbsp;{$labreportprivate}
          </td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr><td colspan="2"><div id="save_status"></div></td></tr>
        <tr>
          <td class="primacol" >
          </td>      
          <td valign="top" nowrap="" align="left">            
          <input type="button" id="savereport" value="{$labsavereport}" onclick="savereport();">
          </td>
        </tr>
      </table>
    </div> 

  <div class="tab5"> 
      <fieldset> 
      <legend>{$labhelpreport}</legend> 
      <br>
        <form action="custom/modules/SugarPrint/help/SugarPrintUserGuide.pdf" target=_blank>
            <input type="submit" value="{$labhelpuserguide}">
        </form>
      </fieldset>
  </div>
  
</div>  
<div style="color:red;text-align:center;float:left;width:100%;" id="erroredialog"></div>
</body>
</html>

