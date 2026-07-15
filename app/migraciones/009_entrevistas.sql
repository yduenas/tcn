/***
CREACION
Modulo Entrevistas (seccion 1.2 del CLAUDE.md): agendar, notas, calificacion cualitativa por competencia.
END CREACION
***/

CREATE TABLE IF NOT EXISTS competencias (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS entrevistas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    postulacion_id INTEGER NOT NULL,
    entrevistador_usuario_id INTEGER NOT NULL,
    fecha_agendada DATETIME NOT NULL,
    fecha_realizada DATETIME NULL,
    notas TEXT,
    recomendacion TEXT CHECK (recomendacion IN ('recomendado', 'recomendado_con_reservas', 'no_recomendado')),
    FOREIGN KEY (postulacion_id) REFERENCES postulaciones(id),
    FOREIGN KEY (entrevistador_usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS entrevista_calificacion_competencia (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    entrevista_id INTEGER NOT NULL,
    competencia_id INTEGER NOT NULL,
    calificacion INTEGER NOT NULL CHECK (calificacion BETWEEN 1 AND 5),
    comentario TEXT,
    FOREIGN KEY (entrevista_id) REFERENCES entrevistas(id),
    FOREIGN KEY (competencia_id) REFERENCES competencias(id)
);

-- Catalogo de competencias (seccion 3.1)
INSERT INTO competencias (nombre) VALUES
    ('Integridad'), ('Compromiso'), ('Trabajo en equipo'), ('Orientación a resultados'),
    ('Adaptabilidad al cambio'), ('Comunicación efectiva'), ('Iniciativa'),
    ('Autocontrol y manejo de la presión'), ('Orientación al cliente'), ('Resolución de problemas');
