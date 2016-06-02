jQuery(document).ready(function($){
	$.fn.callMeLoad = function(option){
		var id = $(this).attr('id');
		if(id=='solicitudes_section')
		{
			if(Configuraciones.getConfiguraciones().privilegio_solcitides_web != "1")
				return false;
			
			SolicitudesWebTabla.cargarTabla();
			$('#solicitudes_web_tab li:eq(0) a').trigger( "click" );
		}
		else if(id=='cross_selling_section')
		{
			if(Configuraciones.getConfiguraciones().privilegio_croseling != "1")
				return false;
				
			  CrossSellingTabla.cargarTabla();
			 
			 
			if(FuncionesGenerales.getNumeroNegocioFiltro().estado) 
			{
				FuncionesGenerales.filtrarTablasDesdeBuscador();
			}
			else
			{
				$('#cross_selling_tab li:eq(0) a').tab('show');
				CrossSellingTabla.cargarTablaNuevas();
				CrossSellingTabla.abierto.fnFilter('');
			}
		}
		else if(id=='recompra_section')
		{
			if(Configuraciones.getConfiguraciones().privilegio_recompra != "1")
				return false;
				
			RecompraTabla.cargarTabla();	
			
			if(FuncionesGenerales.getNumeroNegocioFiltro().estado) 
			{
				FuncionesGenerales.filtrarTablasDesdeBuscador();
			}
			else
			{
				$('#recompra_tab li:eq(0) a').tab('show');
				RecompraTabla.cargarTablaNuevas();
				RecompraTabla.abierto_recompra.fnFilter('');
			}
		}
		else if(id=='fugados_section')
		{
			if(Configuraciones.getConfiguraciones().privilegio_fugados != "1")
				return false;
			
			FugadosTabla.cargarTabla();
			
			if(FuncionesGenerales.getNumeroNegocioFiltro().estado) 
			{	
				FuncionesGenerales.filtrarTablasDesdeBuscador();
			}
			else
			{
				$('#fugados_tab li:eq(0) a').tab('show');
				FugadosTabla.cargarTablaNuevas();
				FugadosTabla.abierto_fugados.fnFilter('');
			}
		}
		else if(id=='proximos_viajes_section')
		{
			if(Configuraciones.getConfiguraciones().privilegio_proximos_viajes != "1")
				return false;
			
			ProximosViajesTabla.cargarTabla();
			
			if(FuncionesGenerales.getNumeroNegocioFiltro().estado) 
			{				
				FuncionesGenerales.filtrarTablasDesdeBuscador();
			}
			else
			{
				$('#proximos_viajes_tab li:eq(0) a').tab('show');
				ProximosViajesTabla.cargarTablaNuevas();
				ProximosViajesTabla.abierto_viajes.fnFilter('');
			}
		}
		else if(id=='retorno_section')
		{
			if(Configuraciones.getConfiguraciones().privilegio_retornos != "1")
				return false;
			
			RetornoTabla.cargarTabla();
			
			if(FuncionesGenerales.getNumeroNegocioFiltro().estado) 
			{			
				FuncionesGenerales.filtrarTablasDesdeBuscador();
			}
			else
			{
				$('#retorno_tab li:eq(0) a').tab('show');
				RetornoTabla.cargarTablaNuevas();
				RetornoTabla.abierto_retorno.fnFilter('');
			}
		}
		else if(id=='cumpleanios_section')
		{
			if(Configuraciones.getConfiguraciones().privilegio_cumpleanio != "1")
				return false;
				
			CumpleanosTabla.cargarTabla();
		}
		else if(id=='clientes_section')
		{
			if(Configuraciones.getConfiguraciones().privilegio_cartera_cliente != "1")
				return false;
			if($('table#table_pasajeros').is(':visible'))	
				CarteraClientesTabla.cargarTablaPasajeros();
			if($('table#table_compradores').is(':visible'))
				CarteraClientesTabla.cargarTablaCompradores();
		}
		else if(id=='buscador_section')
		{
			if(Configuraciones.getConfiguraciones().privilegio_cartera_cliente != "1")
				return false;
				
			BuscardorTabla.cargarTabla();
		}else if(id =='prueba_section_menu'){
		
		  if(Configuraciones.getConfiguraciones().privilegio_cumpleanio != "1")
				return false;
				
			CumpleanosTabla.cargarTabla();
		
		}
		
		
		
	};
});