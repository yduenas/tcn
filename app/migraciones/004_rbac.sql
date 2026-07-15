/***
CREACION
Modulo RBAC: permisos, perfiles, perfil_permiso, usuarios
Motor: SQLite (Base3)
END CREACION
***/

DROP TABLE IF EXISTS usuarios;

CREATE TABLE IF NOT EXISTS permisos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    codigo TEXT NOT NULL UNIQUE,
    descripcion TEXT NOT NULL,
    categoria TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS perfiles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL UNIQUE,
    descripcion TEXT,
    es_predefinido INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS perfil_permiso (
    perfil_id INTEGER NOT NULL,
    permiso_id INTEGER NOT NULL,
    PRIMARY KEY (perfil_id, permiso_id),
    FOREIGN KEY (perfil_id) REFERENCES perfiles(id) ON DELETE CASCADE,
    FOREIGN KEY (permiso_id) REFERENCES permisos(id) ON DELETE CASCADE
);

-- empresa_id no lleva FK todavia: la tabla `empresas` se crea en el siguiente modulo (ver seccion 7 del CLAUDE.md).
CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombres TEXT NOT NULL,
    apellidos TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    perfil_id INTEGER NOT NULL,
    empresa_id INTEGER NULL,
    estado TEXT NOT NULL DEFAULT 'activo',
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (perfil_id) REFERENCES perfiles(id)
);

-- Catalogo base de permisos (seccion 2 del CLAUDE.md)
INSERT INTO permisos (codigo, descripcion, categoria) VALUES
    ('crear_vacante', 'Crear vacante', 'vacantes'),
    ('editar_vacante', 'Editar vacante', 'vacantes'),
    ('publicar_vacante', 'Publicar / despublicar vacante', 'vacantes'),
    ('ver_postulantes', 'Ver postulantes de una vacante', 'postulantes'),
    ('agendar_entrevista', 'Agendar entrevista', 'entrevistas'),
    ('calificar_entrevista', 'Registrar calificacion de entrevista', 'entrevistas'),
    ('ver_terna', 'Ver terna final', 'terna'),
    ('aprobar_terna', 'Aprobar y enviar terna final a la empresa', 'terna'),
    ('crear_usuario', 'Crear usuarios', 'usuarios'),
    ('editar_usuario', 'Editar usuarios', 'usuarios'),
    ('crear_empresa', 'Crear / editar empresa', 'empresas'),
    ('dar_baja_empresa', 'Dar de baja una empresa', 'empresas'),
    ('configurar_evaluaciones', 'Configurar catalogo de evaluaciones', 'evaluaciones'),
    ('ver_datos_sensibles_evaluacion', 'Ver resultados detallados de evaluaciones', 'evaluaciones'),
    ('ver_reportes_globales', 'Ver reportes y metricas globales', 'reportes'),
    ('exportar_pdf', 'Exportar reporte PDF de candidato', 'reportes'),
    ('configurar_perfiles', 'Crear / editar perfiles y permisos', 'sistema'),
    ('ver_auditoria', 'Ver logs de auditoria', 'sistema');

-- Perfiles de fabrica (seccion 2: predefinidos, pero el admin puede crear mas)
INSERT INTO perfiles (nombre, descripcion, es_predefinido) VALUES
    ('Administrador', 'Control total del sistema', 1),
    ('Seleccionador', 'Gestiona vacantes, postulantes, entrevistas y terna', 1),
    ('Empresa', 'Ve el avance y la terna final de sus propias vacantes', 1);

-- Administrador: todos los permisos
INSERT INTO perfil_permiso (perfil_id, permiso_id)
SELECT (SELECT id FROM perfiles WHERE nombre = 'Administrador'), id FROM permisos;

-- Seleccionador
INSERT INTO perfil_permiso (perfil_id, permiso_id)
SELECT (SELECT id FROM perfiles WHERE nombre = 'Seleccionador'), id FROM permisos
WHERE codigo IN (
    'crear_vacante', 'editar_vacante', 'publicar_vacante', 'ver_postulantes',
    'agendar_entrevista', 'calificar_entrevista', 'ver_terna', 'aprobar_terna',
    'ver_datos_sensibles_evaluacion', 'exportar_pdf'
);

-- Empresa
INSERT INTO perfil_permiso (perfil_id, permiso_id)
SELECT (SELECT id FROM perfiles WHERE nombre = 'Empresa'), id FROM permisos
WHERE codigo IN ('ver_terna', 'ver_reportes_globales');
