var UIIdleTimeout = function () {

    return {

        //main function to initiate the module
        init: function () {

            // cache a reference to the countdown element so we don't have to query the DOM for it on each ping.
            var $countdown;

            $('body').append('<div class="modal fade" id="idle-timeout-dialog" data-backdrop="static"><div class="modal-dialog modal-small"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Su sesión esta por expirar.</h4></div><div class="modal-body"><p><i class="fa fa-warning"></i> Su sesión va a ser bloqueada en <span id="idle-timeout-counter"></span> segundos.</p><p>¿Desea seguir usando esta sesión?</p></div><div class="modal-footer"><button id="idle-timeout-dialog-logout" type="button" class="btn btn-default">No, Salir</button><button id="idle-timeout-dialog-keepalive" type="button" class="btn btn-primary" data-dismiss="modal">Si, seguir trabajando</button></div></div></div></div>');
                    
            // start the idle timer plugin
            $.idleTimeout('#idle-timeout-dialog', '.modal-content button:last', {
                idleAfter: 1800, //
                timeout: 5000, //30 seconds to timeout
                pollingInterval: 30, // 5 seconds
				warningLength: 20,
				titleMessage: 'Atención: en %s segundos se cierra su sesión | ',
                keepAliveURL: 'config/session_timeout.php',
                serverResponseEquals: 'OK',
                onTimeout: function(){
				    var data = {
						v_class : "log_out"
					}
				
					$.get(Configuraciones.getUrlConfiguraciones(),data,function(respuesta){
						$(location).attr('href',"login.php");
					});
                },
                onIdle: function(){
                    $('#idle-timeout-dialog').modal('show');
                    $countdown = $('#idle-timeout-counter');

                    $('#idle-timeout-dialog-keepalive').on('click', function () { 
                        $('#idle-timeout-dialog').modal('hide');
                    });

                    $('#idle-timeout-dialog-logout').on('click', function () { 
                        $('#idle-timeout-dialog').modal('hide');
                        $.idleTimeout.options.onTimeout.call(this);
                    });
                },
                onCountdown: function(counter){
                    $countdown.html(counter); // update the counter
                }
            });
            
        }

    };

}();