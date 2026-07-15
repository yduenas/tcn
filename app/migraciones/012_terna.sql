/***
CREACION
Modulo Terna final (secciones 1.2 y 1.3 del CLAUDE.md): comparador de candidatos
y envio de la terna a la empresa cliente.
END CREACION
***/

CREATE TABLE IF NOT EXISTS ternas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    vacante_id INTEGER NOT NULL UNIQUE,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_envio DATETIME NULL,
    enviado_por_usuario_id INTEGER NULL,
    FOREIGN KEY (vacante_id) REFERENCES vacantes(id),
    FOREIGN KEY (enviado_por_usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS terna_candidato (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    terna_id INTEGER NOT NULL,
    postulacion_id INTEGER NOT NULL,
    orden INTEGER NOT NULL DEFAULT 0,
    UNIQUE (terna_id, postulacion_id),
    FOREIGN KEY (terna_id) REFERENCES ternas(id),
    FOREIGN KEY (postulacion_id) REFERENCES postulaciones(id)
);
