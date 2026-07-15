/***
CREACION
Contenido ampliado de la vacante: objetivo del puesto y funciones, como campos
de texto independientes de la descripcion general (pedido explicito del usuario).
END CREACION
***/

ALTER TABLE vacantes ADD COLUMN objetivo_puesto TEXT;
ALTER TABLE vacantes ADD COLUMN funciones TEXT;
