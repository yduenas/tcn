/***
CREACION
Modulo Evaluaciones (secciones 3 y 1.2 del CLAUDE.md): catalogo, preguntas/opciones,
vigencia por candidato, asignacion a postulaciones, y asignacion de evaluaciones por vacante.
Los datos de preguntas sembrados aqui son un set DEMO reducido para probar el mecanismo
completo (DISC, opcion unica, SJT) - el banco completo (15/15/30 preguntas) se completa
desde el panel de administracion (permiso configurar_evaluaciones), no se hardcodea.
END CREACION
***/

CREATE TABLE IF NOT EXISTS evaluaciones (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL UNIQUE,
    tipo TEXT NOT NULL,
    tiempo_limite_min INTEGER,
    instrucciones TEXT,
    vigencia_meses INTEGER
);

CREATE TABLE IF NOT EXISTS evaluacion_preguntas (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    evaluacion_id INTEGER NOT NULL,
    enunciado TEXT NOT NULL,
    tipo_pregunta TEXT NOT NULL CHECK (tipo_pregunta IN ('opcion_unica', 'forzada', 'sjt')),
    orden INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones(id)
);

CREATE TABLE IF NOT EXISTS evaluacion_opciones (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    pregunta_id INTEGER NOT NULL,
    texto TEXT NOT NULL,
    puntaje INTEGER NOT NULL DEFAULT 0,
    etiqueta TEXT,
    orden INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (pregunta_id) REFERENCES evaluacion_preguntas(id)
);

-- Solo aplica a preguntas tipo 'sjt' (Valores/Competencias)
CREATE TABLE IF NOT EXISTS evaluacion_competencias (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    pregunta_id INTEGER NOT NULL UNIQUE,
    competencia_nombre TEXT NOT NULL,
    FOREIGN KEY (pregunta_id) REFERENCES evaluacion_preguntas(id)
);

CREATE TABLE IF NOT EXISTS candidato_evaluacion (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    candidato_id INTEGER NOT NULL,
    evaluacion_id INTEGER NOT NULL,
    fecha_realizada DATETIME NULL,
    fecha_vencimiento DATETIME NULL,
    resultado_json TEXT NULL,
    estado TEXT NOT NULL DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'vigente', 'vencida', 'anulada')),
    forzada_por_postulacion_id INTEGER NULL,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id),
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones(id)
);

CREATE TABLE IF NOT EXISTS postulacion_evaluacion (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    postulacion_id INTEGER NOT NULL,
    candidato_evaluacion_id INTEGER NOT NULL,
    estado TEXT NOT NULL DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'en_progreso', 'completada', 'reutilizada')),
    FOREIGN KEY (postulacion_id) REFERENCES postulaciones(id),
    FOREIGN KEY (candidato_evaluacion_id) REFERENCES candidato_evaluacion(id)
);

-- rol distingue: 'unica' (Matematica/Verbal), 'mas'/'menos' (DISC forzada y SJT de Valores)
CREATE TABLE IF NOT EXISTS respuestas_candidato (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    candidato_evaluacion_id INTEGER NOT NULL,
    pregunta_id INTEGER NOT NULL,
    opcion_id INTEGER NOT NULL,
    rol TEXT NOT NULL CHECK (rol IN ('unica', 'mas', 'menos')),
    FOREIGN KEY (candidato_evaluacion_id) REFERENCES candidato_evaluacion(id),
    FOREIGN KEY (pregunta_id) REFERENCES evaluacion_preguntas(id),
    FOREIGN KEY (opcion_id) REFERENCES evaluacion_opciones(id)
);

CREATE TABLE IF NOT EXISTS token_evaluacion (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    postulacion_id INTEGER NOT NULL UNIQUE,
    token TEXT NOT NULL UNIQUE,
    fecha_generado DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (postulacion_id) REFERENCES postulaciones(id)
);

CREATE TABLE IF NOT EXISTS vacante_evaluacion (
    vacante_id INTEGER NOT NULL,
    evaluacion_id INTEGER NOT NULL,
    obligatoria INTEGER NOT NULL DEFAULT 1,
    PRIMARY KEY (vacante_id, evaluacion_id),
    FOREIGN KEY (vacante_id) REFERENCES vacantes(id),
    FOREIGN KEY (evaluacion_id) REFERENCES evaluaciones(id)
);

-- ===== Catalogo base (seccion 3.1) =====
INSERT INTO evaluaciones (nombre, tipo, tiempo_limite_min, instrucciones, vigencia_meses) VALUES
    ('DISC', 'disc', 15, 'Para cada situación, elige la opción que más se parece a ti y la que menos se parece a ti.', 24),
    ('Matemática', 'opcion_unica', 20, 'Responde cada pregunta seleccionando la única respuesta correcta. Tienes 20 minutos.', 12),
    ('Verbal', 'opcion_unica', 20, 'Responde cada pregunta seleccionando la única respuesta correcta. Tienes 20 minutos.', 12),
    ('Valores y Competencias', 'sjt', 20, 'Para cada escenario, elige la opción más efectiva y la menos efectiva.', 12);

-- ===== DISC (demo: 3 preguntas ipsativas, 4 opciones D/I/S/C cada una) =====
INSERT INTO evaluacion_preguntas (evaluacion_id, enunciado, tipo_pregunta, orden) VALUES
    ((SELECT id FROM evaluaciones WHERE nombre='DISC'), 'En el trabajo en equipo, tiendo a...', 'forzada', 1),
    ((SELECT id FROM evaluaciones WHERE nombre='DISC'), 'Cuando enfrento un problema urgente...', 'forzada', 2),
    ((SELECT id FROM evaluaciones WHERE nombre='DISC'), 'Mi estilo de comunicación es...', 'forzada', 3);

INSERT INTO evaluacion_opciones (pregunta_id, texto, etiqueta, orden) VALUES
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado='En el trabajo en equipo, tiendo a...'), 'Tomar el control y dirigir las decisiones', 'D', 1),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado='En el trabajo en equipo, tiendo a...'), 'Motivar y entusiasmar al equipo', 'I', 2),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado='En el trabajo en equipo, tiendo a...'), 'Buscar consenso y mantener la armonía', 'S', 3),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado='En el trabajo en equipo, tiendo a...'), 'Analizar los datos antes de decidir', 'C', 4),

    ((SELECT id FROM evaluacion_preguntas WHERE enunciado='Cuando enfrento un problema urgente...'), 'Actúo rápido y decido de inmediato', 'D', 1),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado='Cuando enfrento un problema urgente...'), 'Busco el apoyo y opinión de otros', 'I', 2),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado='Cuando enfrento un problema urgente...'), 'Prefiero seguir un proceso conocido y estable', 'S', 3),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado='Cuando enfrento un problema urgente...'), 'Reviso cuidadosamente antes de actuar', 'C', 4),

    ((SELECT id FROM evaluacion_preguntas WHERE enunciado='Mi estilo de comunicación es...'), 'Directo y orientado a resultados', 'D', 1),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado='Mi estilo de comunicación es...'), 'Entusiasta y expresivo', 'I', 2),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado='Mi estilo de comunicación es...'), 'Paciente y conciliador', 'S', 3),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado='Mi estilo de comunicación es...'), 'Preciso y basado en hechos', 'C', 4);

-- ===== Matemática (demo: 3 preguntas de opcion unica) =====
INSERT INTO evaluacion_preguntas (evaluacion_id, enunciado, tipo_pregunta, orden) VALUES
    ((SELECT id FROM evaluaciones WHERE nombre='Matemática'), 'Si un producto cuesta S/ 80 y tiene un descuento del 25%, ¿cuál es el precio final?', 'opcion_unica', 1),
    ((SELECT id FROM evaluaciones WHERE nombre='Matemática'), '¿Cuál es el siguiente número en la secuencia: 2, 4, 8, 16, ...?', 'opcion_unica', 2),
    ((SELECT id FROM evaluaciones WHERE nombre='Matemática'), 'Un tren recorre 180 km en 3 horas. ¿Cuál es su velocidad promedio?', 'opcion_unica', 3);

INSERT INTO evaluacion_opciones (pregunta_id, texto, puntaje, orden) VALUES
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Si un producto cuesta%'), 'S/ 60', 1, 1),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Si un producto cuesta%'), 'S/ 65', 0, 2),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Si un producto cuesta%'), 'S/ 55', 0, 3),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Si un producto cuesta%'), 'S/ 70', 0, 4),

    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE '¿Cuál es el siguiente número%'), '24', 0, 1),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE '¿Cuál es el siguiente número%'), '32', 1, 2),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE '¿Cuál es el siguiente número%'), '20', 0, 3),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE '¿Cuál es el siguiente número%'), '18', 0, 4),

    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Un tren recorre%'), '45 km/h', 0, 1),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Un tren recorre%'), '60 km/h', 1, 2),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Un tren recorre%'), '50 km/h', 0, 3),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Un tren recorre%'), '90 km/h', 0, 4);

-- ===== Verbal (demo: 3 preguntas de opcion unica) =====
INSERT INTO evaluacion_preguntas (evaluacion_id, enunciado, tipo_pregunta, orden) VALUES
    ((SELECT id FROM evaluaciones WHERE nombre='Verbal'), "Selecciona el sinónimo de 'diligente'", 'opcion_unica', 1),
    ((SELECT id FROM evaluaciones WHERE nombre='Verbal'), 'Elige la palabra que NO pertenece al grupo', 'opcion_unica', 2),
    ((SELECT id FROM evaluaciones WHERE nombre='Verbal'), 'Completa la analogía: Libro es a Leer como Música es a ___', 'opcion_unica', 3);

INSERT INTO evaluacion_opciones (pregunta_id, texto, puntaje, orden) VALUES
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Selecciona el sinónimo%'), 'Perezoso', 0, 1),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Selecciona el sinónimo%'), 'Aplicado', 1, 2),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Selecciona el sinónimo%'), 'Confuso', 0, 3),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Selecciona el sinónimo%'), 'Distraído', 0, 4),

    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Elige la palabra que NO%'), 'Manzana', 0, 1),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Elige la palabra que NO%'), 'Pera', 0, 2),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Elige la palabra que NO%'), 'Zanahoria', 1, 3),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Elige la palabra que NO%'), 'Naranja', 0, 4),

    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Completa la analogía%'), 'Ver', 0, 1),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Completa la analogía%'), 'Escuchar', 1, 2),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Completa la analogía%'), 'Pintar', 0, 3),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Completa la analogía%'), 'Cocinar', 0, 4);

-- ===== Valores y Competencias (demo: 3 escenarios SJT, 1 por competencia) =====
INSERT INTO evaluacion_preguntas (evaluacion_id, enunciado, tipo_pregunta, orden) VALUES
    ((SELECT id FROM evaluaciones WHERE nombre='Valores y Competencias'), 'Un compañero de equipo no está cumpliendo con su parte del proyecto. ¿Qué opción es más y menos efectiva?', 'sjt', 1),
    ((SELECT id FROM evaluaciones WHERE nombre='Valores y Competencias'), 'Tienes una fecha límite ajustada y notas que no vas a cumplir el plazo. ¿Qué opción es más y menos efectiva?', 'sjt', 2),
    ((SELECT id FROM evaluaciones WHERE nombre='Valores y Competencias'), 'Debes explicar un tema técnico complejo a un cliente sin conocimientos técnicos. ¿Qué opción es más efectiva?', 'sjt', 3);

INSERT INTO evaluacion_competencias (pregunta_id, competencia_nombre) VALUES
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Un compañero de equipo%'), 'Trabajo en equipo'),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Tienes una fecha límite%'), 'Orientación a resultados'),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Debes explicar un tema%'), 'Comunicación efectiva');

INSERT INTO evaluacion_opciones (pregunta_id, texto, puntaje, orden) VALUES
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Un compañero de equipo%'), 'Hablar directamente con él para entender la situación y ofrecer ayuda', 3, 1),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Un compañero de equipo%'), 'Informar al jefe inmediatamente sin hablar antes con el compañero', 1, 2),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Un compañero de equipo%'), 'Asumir tú su parte del trabajo sin decir nada', 0, 3),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Un compañero de equipo%'), 'Ignorar el problema y esperar que se resuelva solo', 0, 4),

    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Tienes una fecha límite%'), 'Avisas con anticipación y propones un plan para cumplir o ajustar el alcance', 3, 1),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Tienes una fecha límite%'), 'Entregas lo que tengas a tiempo aunque esté incompleto, sin avisar', 1, 2),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Tienes una fecha límite%'), 'Esperas hasta el último momento para comunicar el retraso', 0, 3),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Tienes una fecha límite%'), 'No entregas nada y evitas la conversación', 0, 4),

    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Debes explicar un tema%'), 'Adaptas el lenguaje usando ejemplos simples y verificas que entendió', 3, 1),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Debes explicar un tema%'), 'Usas los mismos términos técnicos que usarías con un colega', 1, 2),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Debes explicar un tema%'), 'Envías documentación técnica extensa sin explicación adicional', 0, 3),
    ((SELECT id FROM evaluacion_preguntas WHERE enunciado LIKE 'Debes explicar un tema%'), 'Evitas explicar y delegas la conversación a otra persona', 0, 4);
