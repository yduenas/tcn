/***
CREACION
Modulo Empresas (seccion 1.3 / 1.4 del CLAUDE.md): alta, edicion, baja logica.
Formaliza la FK usuarios.empresa_id -> empresas.id (SQLite exige recrear la tabla para agregarla).
END CREACION
***/

CREATE TABLE IF NOT EXISTS empresas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    ruc TEXT UNIQUE,
    logo_path TEXT NOT NULL,
    estado TEXT NOT NULL DEFAULT 'activa',
    fecha_baja DATETIME NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE usuarios_nuevo (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombres TEXT NOT NULL,
    apellidos TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    perfil_id INTEGER NOT NULL,
    empresa_id INTEGER NULL,
    estado TEXT NOT NULL DEFAULT 'activo',
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (perfil_id) REFERENCES perfiles(id),
    FOREIGN KEY (empresa_id) REFERENCES empresas(id)
);

INSERT INTO usuarios_nuevo (id, nombres, apellidos, email, password_hash, perfil_id, empresa_id, estado, fecha_creacion)
SELECT id, nombres, apellidos, email, password_hash, perfil_id, empresa_id, estado, fecha_creacion FROM usuarios;

DROP TABLE usuarios;
ALTER TABLE usuarios_nuevo RENAME TO usuarios;

INSERT INTO permisos (codigo, descripcion, categoria) VALUES
    ('editar_empresa', 'Editar datos de una empresa', 'empresas');

INSERT INTO perfil_permiso (perfil_id, permiso_id)
SELECT (SELECT id FROM perfiles WHERE nombre = 'Administrador'), id FROM permisos
WHERE codigo = 'editar_empresa';
