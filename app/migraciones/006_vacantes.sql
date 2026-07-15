/***
CREACION
Modulo Vacantes (secciones 1.2 y 1.3.1 del CLAUDE.md): catalogos + tabla vacantes.
END CREACION
***/

CREATE TABLE IF NOT EXISTS cargo_categorias (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS modalidades_trabajo (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS vacantes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    empresa_id INTEGER NOT NULL,
    titulo TEXT NOT NULL,
    descripcion TEXT,
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
    FOREIGN KEY (empresa_id) REFERENCES empresas(id),
    FOREIGN KEY (cargo_categoria_id) REFERENCES cargo_categorias(id),
    FOREIGN KEY (modalidad_id) REFERENCES modalidades_trabajo(id),
    FOREIGN KEY (seleccionador_id) REFERENCES usuarios(id)
);

INSERT INTO cargo_categorias (nombre) VALUES
    ('Administración'), ('Ventas'), ('Tecnología'), ('Operaciones'),
    ('Recursos Humanos'), ('Finanzas'), ('Logística'), ('Atención al cliente');

INSERT INTO modalidades_trabajo (nombre) VALUES
    ('Presencial'), ('Remoto'), ('Híbrido');
