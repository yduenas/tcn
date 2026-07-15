/***
CREACION
Habilidades del candidato (seccion 6.3) y declaracion de veracidad del CV (seccion 6.4),
distinta del consentimiento de proteccion de datos (consentimiento_datos, seccion 1.1).
END CREACION
***/

CREATE TABLE IF NOT EXISTS candidato_habilidad (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    candidato_id INTEGER NOT NULL,
    nombre TEXT NOT NULL,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS postulante_declaracion (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    candidato_id INTEGER NOT NULL,
    texto_version TEXT NOT NULL,
    aceptado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ip_registro TEXT,
    FOREIGN KEY (candidato_id) REFERENCES candidatos(id)
);
