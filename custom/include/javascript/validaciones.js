/**
 * Date: 11/06/13
 * Written by: Andrew Mclaughlan
 * Company: SalesAgility
 */

$(document).ready(function()
{
    var now = new Date();
    now.setDate(now.getDate() + 5);
    mes = now.getMonth()+1;
    fecha = (now.getDate()+'/'+mes+'/'+now.getFullYear())

    if($('#date_closed').val()=='')   $('#date_closed').val(fecha);  
        
    $('#sales_stage').change(function()
    {
       /* valida_cierre();*/
                 
        return true;
        
    });

      

       $('#SAVE_HEADER').click(function()
    {
        /*valida_cierre();*/
        return false;
                 
        
        
    });





    function valida_cierre(){
            if($('#sales_stage').val() == 'ganado' && $('#crm_negocios_opportunities_name').val() == '')
                   alert('Para Cerrar positivamente debe ingresar el Número del negocio y el monto de la oportunidad'); 

            if($('#sales_stage').val() == 'perdido' && $('#crm_negocios_opportunities_name').val() == '')
                   alert('Para Cerrar negativamente debe ingresar el numero del negocio'); 

            if($('#motivo_c').val() != '' && $('#numero_negocio_c').val() != '')
                   alert('No puede ingresar número de negocio y motivo de rechazo al mismo tiempo');      
    }


});