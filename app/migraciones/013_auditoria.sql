/***
CREACION
Auditoria de acciones sensibles (seccion 1.4): cambios de perfil, bajas de empresa,
evaluaciones forzadas.
END CREACION
***/

CREATE TABLE IF NOT EXISTS auditoria_log (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario_id INTEGER NULL,
    accion TEXT NOT NULL,
    entidad TEXT NOT NULL,
    entidad_id INTEGER,
    detalle TEXT,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
