/***
CREACION
Permite editar y anular (baja logica) las categorias de cargo y modalidades de trabajo
ya creadas, sin romper las vacantes que ya las usan.
END CREACION
***/

ALTER TABLE cargo_categorias ADD COLUMN activo INTEGER NOT NULL DEFAULT 1;
ALTER TABLE modalidades_trabajo ADD COLUMN activo INTEGER NOT NULL DEFAULT 1;
