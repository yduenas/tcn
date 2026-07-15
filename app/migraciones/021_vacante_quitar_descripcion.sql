/***
CREACION
Ytalo, 2026-07-14: "ese campo no deberia existir" -- el campo "Descripcion
general" de Vacante era redundante con "Objetivo del puesto"/"Funciones"
(seccion 1.2/1.4 del CLAUDE.md solo pide esos dos). Se elimina la columna
por completo, no solo se oculta en el formulario.

SQLite 3.28 (version instalada en este entorno) no soporta
ALTER TABLE ... DROP COLUMN (recien se agrego en 3.35) -- se usa el patron
clasico de SQLite para eliminar una columna: crear la tabla nueva sin la
columna, copiar los datos, borrar la vieja, renombrar la nueva. El "id" se
preserva tal cual, asi que las FK de otras tablas hacia vacantes(id)
(vacante_evaluacion, postulaciones, etc.) siguen validas sin tocarlas.
END CREACION
***/

PRAGMA foreign_keys = OFF;

CREATE TABLE vacantes_nueva (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	empresa_id INTEGER NOT NULL,
	titulo TEXT NOT NULL,
	cargo_categoria_id INTEGER NOT NULL,
	modalidad_id INTEGER NOT NULL,
	ubicacion TEXT,
	salario_min DECIMAL,
	salario_max DECIMAL,
	es_anonima INTEGER NOT NULL DEFAULT 0,
	ocultar_salario INTEGER NOT NULL DEFAULT 0,
	estado TEXT NOT NULL DEFAULT 'borrador' CHECK (estado IN ('borrador', 'publicada', 'despublicada', 'cerrada')),
	seleccionador_id INTEGER NOT NULL,
	fecha_publicacion DATETIME NULL,
	fecha_cierre DATETIME NULL,
	fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	objetivo_puesto TEXT,
	funciones TEXT,
	FOREIGN KEY (empresa_id) REFERENCES empresas(id),
	FOREIGN KEY (cargo_categoria_id) REFERENCES cargo_categorias(id),
	FOREIGN KEY (modalidad_id) REFERENCES modalidades_trabajo(id),
	FOREIGN KEY (seleccionador_id) REFERENCES usuarios(id)
);

INSERT INTO vacantes_nueva (
	id, empresa_id, titulo, cargo_categoria_id, modalidad_id, ubicacion, salario_min, salario_max,
	es_anonima, ocultar_salario, estado, seleccionador_id, fecha_publicacion, fecha_cierre, fecha_creacion,
	objetivo_puesto, funciones
)
SELECT
	id, empresa_id, titulo, cargo_categoria_id, modalidad_id, ubicacion, salario_min, salario_max,
	es_anonima, ocultar_salario, estado, seleccionador_id, fecha_publicacion, fecha_cierre, fecha_creacion,
	objetivo_puesto, funciones
FROM vacantes;

DROP TABLE vacantes;
ALTER TABLE vacantes_nueva RENAME TO vacantes;

PRAGMA foreign_keys = ON;
