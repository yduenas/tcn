//alert('Hola LULU');

$(function() {       

    $('#myTable').DataTable({
    	"language": {
		      //      "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
		            "url": "../public/lib/datatables/Spanish.json"
		        },
        dom: 'Bfrtip',
        buttons: [
        //   'copy', 'csv', 'excel', 'pdf', 'print'
        //    { extend:'copy'},
        //    { extend:'csv', title: 'Nombre Archivo',},
        //    { extend:'excel'},
        //    { extend:'pdf'},
        //    { extend:'print'},

            {
              extend: 'excel',
              text: 'XLS',
              className: 'exportExcel',
              title: 'Nombre Archivo - ' + "<?php echo date('Y-m-d H:i:s');?>",
              filename: 'ED_OBJETIVOS-'  + "<?php echo date('Y-m-d H:i:s');?>",
              exportOptions: {
                modifier: {
                  page: 'all'
                }
              }
            }, 
            {
              extend: 'copy',
              text: 'Porta Papeles',
              className: 'exportExcel',
              key: {
                key: 'c',
                altKey: true
              }
            },
            {
              extend: 'pdf',
              text: 'PDFs',
              className: 'exportPDF',
              title: 'Nombre Archivo',
              pageSize: 'A3', //'LEGAL',
              key: {
                key: 'c',
                altKey: true
              }
            },
            {
              text: 'Alert Js',
              className: 'exportExcel',
              action: function(e, dt, node, config) {
                alert('Activated!');
                // console.log(table);

                // new $.fn.dataTable.Buttons(table, {
                //   buttons: [{
                //     text: 'gfdsgfsd',
                //     action: function(e, dt, node, config) {
                //       alert('ok!');
                //     }
                //   }]
                // });
              }
            }
        ]
    });

  });

// Editor de texto enriquecido (Summernote) para Objetivo del puesto/Funciones
// de Vacantes (seccion 1.2) -- pedido de Ytalo, 2026-07-14. Gateado por
// if($('.rich-editor').length) para no activarse en paginas sin ese campo.
$(function(){
	if($('.rich-editor').length){
		$('.rich-editor').summernote({
			height: 180,
			toolbar: [
				['style', ['bold', 'italic', 'underline', 'clear']],
				['para', ['ul', 'ol', 'paragraph']],
				['insert', ['link']]
			]
		});
	}
});

// Select2 (select con busqueda) en todo elemento con class="select2" -- pedido de Ytalo,
// 2026-07-15, arrancando por el filtro de Ubicacion del buscador publico de vacantes
// (antes un <input type="text"> libre, ahora un <select> de las ubicaciones publicadas
// realmente en uso). Generico por clase para reutilizarse en cualquier otro select sin
// volver a tocar este archivo. select2 y su CSS ya estaban vendorizados en el proyecto
// (Libreria5/Libreria15 de librerias.php) pero nunca se habian usado hasta ahora.
$(function(){
	if($('.select2').length){
		$('.select2').select2({
			allowClear: true,
			placeholder: function(){ return $(this).data('placeholder') || ''; },
			language: {
				noResults: function(){ return 'Sin resultados'; },
				searching: function(){ return 'Buscando...'; }
			}
		});
	}
});

// Confirmacion con SweetAlert antes de ejecutar un link de accion sensible --
// pedido de Ytalo, 2026-07-14, para el boton "Cerrar" de Vacantes (antes solo
// tenia un confirm() nativo del navegador). Generico por clase (no por id), asi
// que se puede reutilizar en cualquier otro link agregando class="confirmar-swal"
// + data-mensaje="..." sin tocar este archivo de nuevo.
$(function(){
	$(document).on('click', '.confirmar-swal', function(e){
		e.preventDefault();
		var destino = this.href;
		var mensaje = $(this).data('mensaje') || '¿Estás seguro?';
		swal({
			title: '¿Confirmar acción?',
			text: mensaje,
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#1B4C91',
			confirmButtonText: 'Sí, continuar',
			cancelButtonText: 'Cancelar',
			closeOnConfirm: true
		}, function(esConfirmado){
			if(esConfirmado){ window.location.href = destino; }
		});
	});
});

// DataTables (buscador + orden + largo de pagina (seleccionable) + export a
// Excel) en toda tabla con class="data-table" -- pedido de Ytalo, 2026-07-14,
// arrancando por la tabla de Postulantes de una Vacante (#tablaPostulantes).
// Generico por clase para poder reutilizarse en cualquier otra tabla del
// panel sin volver a tocar este archivo -- basta con agregar class="data-table"
// a la <table>. La ultima columna (normalmente una accion sin valor de texto
// real, ej. "Mover a") se excluye del export.
// El nombre del archivo exportado toma el atributo data-export-name de la
// propia tabla (ej. "Postulantes - Jefe Comercial"); si no se define, usa un
// nombre generico -- pedido de Ytalo, 2026-07-15, para que el Excel descargado
// diga a que vacante/reporte pertenece en vez de un nombre sin contexto.
// Columnas ocultas en pantalla (pero SI incluidas en el Excel, comportamiento
// por defecto de DataTables Buttons) via data-hidden-columns="8,9" (indices de
// columna separados por coma) -- pedido de Ytalo, 2026-07-15, arrancando por
// "Postulado"/"Fecha del ultimo estado" en la tabla de postulantes por vacante.
$(function(){
	$('.data-table').each(function(){
		var $tabla = $(this);
		var nombreExport = $tabla.data('export-name') || 'Exportar';
		var columnDefs = [];
		var ocultas = $tabla.data('hidden-columns');
		if(ocultas !== undefined && ocultas !== ''){
			var indices = String(ocultas).split(',').map(function(n){ return parseInt(n, 10); });
			columnDefs.push({ targets: indices, visible: false });
		}
		$tabla.DataTable({
			language: { url: RUTA_URL + 'public/lib/datatables/Spanish.json' },
			pageLength: 50,
			lengthMenu: [10, 25, 50, 100],
			dom: 'lBfrtip',
			columnDefs: columnDefs,
			buttons: [
				{
					extend: 'excel',
					text: 'Exportar a Excel',
					className: 'btn btn-sm btn-outline-success',
					title: nombreExport,
					filename: nombreExport,
					exportOptions: { columns: ':not(:last-child)' }
				}
			]
		});
	});
});
