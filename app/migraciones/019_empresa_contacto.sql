/***
CREACION
Datos de contacto de la empresa cliente (seccion 1.3): nombre y medios de contacto,
direccion y presencia web, para que el seleccionador no dependa de canales externos.
END CREACION
***/

ALTER TABLE empresas ADD COLUMN contacto_nombre TEXT;
ALTER TABLE empresas ADD COLUMN contacto_telefono TEXT;
ALTER TABLE empresas ADD COLUMN contacto_email TEXT;
ALTER TABLE empresas ADD COLUMN direccion TEXT;
ALTER TABLE empresas ADD COLUMN sitio_web TEXT;
ALTER TABLE empresas ADD COLUMN linkedin TEXT;
