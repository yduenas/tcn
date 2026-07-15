/***
CREACION
Agrega el check "copiar al Seleccionador (CC)" por plantilla de correo (pedido de
Ytalo, 2026-07-16): aplica a postulacion_recibida y cambio_estado, cada fila decide
por separado si esa etapa/evento copia al Seleccionador responsable de la vacante.
No aplica a recuperacion_password (no hay un seleccionador involucrado en ese flujo),
la vista simplemente no muestra el checkbox para esa fila.
END CREACION
***/

ALTER TABLE plantillas_correo ADD COLUMN cc_seleccionador INTEGER NOT NULL DEFAULT 0;
