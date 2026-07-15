/***
CREACION
Modulo Postulaciones (secciones 1.1 y 6 del CLAUDE.md): candidatos, perfil reutilizable, pipeline.
END CREACION
***/

CREATE TABLE IF NOT EXISTS estados_postulacion (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    codigo TEXT NOT NULL UNIQUE,
    nombre TEXT NOT NULL,
    orden INTEGER NOT NULL,
    es_final INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS candidatos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombres TEXT NOT NULL,
    apellidos TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    telefono TEXT,
    dni TEXT,
    pretension_salarial DECIMAL,
    disponibilidad TEXT,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS candidato_experiencia (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    candidato_id INTEGER NOT NULL,
    empresa TEXT NOT NULL,
    cargo TEXT NOT NULL,
    fecha_inicio TEXT,
    fecha_fin TEXT,
    actualidad INTEGER NOT NULL DEFAULT 0,
    descripcion TEXT,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS candidato_educacion (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    candidato_id INTEGER NOT NULL,
    institucion TEXT NOT NULL,
    grado TEXT,
    campo_estudio TEXT,
    fecha_inicio TEXT,
    fecha_fin TEXT,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS postulante_cv (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    candidato_id INTEGER NOT NULL,
    archivo_path TEXT NOT NULL,
    peso_kb INTEGER NOT NULL,
    fecha_subida DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE
);

-- Consentimiento de proteccion de datos personales (seccion 1.1) - distinto de la
-- declaracion de veracidad del CV (seccion 6.4, se agrega cuando se construya esa pieza).
CREATE TABLE IF NOT EXISTS consentimiento_datos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    candidato_id INTEGER NOT NULL,
    texto_version TEXT NOT NULL,
    aceptado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ip_registro TEXT,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS postulaciones (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    candidato_id INTEGER NOT NULL,
    vacante_id INTEGER NOT NULL,
    estado_id INTEGER NOT NULL,
    fecha_postulacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_ultimo_cambio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (candidato_id, vacante_id),
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id),
    FOREIGN KEY (vacante_id) REFERENCES vacantes(id),
    FOREIGN KEY (estado_id) REFERENCES estados_postulacion(id)
);

CREATE TABLE IF NOT EXISTS postulacion_historial_estado (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    postulacion_id INTEGER NOT NULL,
    estado_id INTEGER NOT NULL,
    usuario_id INTEGER NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    comentario TEXT,
    FOREIGN KEY (postulacion_id) REFERENCES postulaciones(id),
    FOREIGN KEY (estado_id) REFERENCES estados_postulacion(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Catalogo unificado: concilia el pipeline interno (seccion 1.2: Nuevo/Preseleccionado/
-- Entrevista/Terna/Contratado-Descartado) con los estados de cara al postulante (seccion 1.1).
INSERT INTO estados_postulacion (codigo, nombre, orden, es_final) VALUES
    ('recibida', 'Recibida', 1, 0),
    ('en_revision', 'En revisión', 2, 0),
    ('preseleccionado', 'Preseleccionado', 3, 0),
    ('entrevista', 'Entrevista', 4, 0),
    ('terna_final', 'Terna final', 5, 0),
    ('contratado', 'Contratado', 6, 1),
    ('descartado', 'Descartado', 7, 1);
