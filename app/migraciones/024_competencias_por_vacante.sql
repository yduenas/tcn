-- Permite anular/reactivar competencias del catalogo de entrevista (mismo patron que
-- cargo_categorias/modalidades_trabajo, migracion 018), y que cada vacante elija cuales
-- de esas competencias se evaluan en la entrevista (mismo patron que vacante_evaluacion).
-- Pedido de Ytalo, 2026-07-15.

ALTER TABLE competencias ADD COLUMN activo INTEGER NOT NULL DEFAULT 1;

CREATE TABLE IF NOT EXISTS vacante_competencia (
    vacante_id INTEGER NOT NULL,
    competencia_id INTEGER NOT NULL,
    PRIMARY KEY (vacante_id, competencia_id),
    FOREIGN KEY (vacante_id) REFERENCES vacantes(id),
    FOREIGN KEY (competencia_id) REFERENCES competencias(id)
);
