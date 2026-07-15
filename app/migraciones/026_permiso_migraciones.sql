/***
CREACION
Permiso para el nuevo modulo de Migraciones (pedido de Ytalo, 2026-07-16): ver que
migraciones ya corrieron (migracionDB.json) vs. que archivos .sql en app/migraciones/
siguen pendientes, y poder ejecutarlas desde el panel. Ejecutar SQL de un archivo es
una accion delicada -- Administrador por defecto, mismo criterio ya usado para
administrar_bd.
END CREACION
***/

INSERT INTO permisos (codigo, descripcion, categoria) VALUES
    ('administrar_migraciones', 'Ver y ejecutar migraciones de base de datos pendientes', 'sistema');

INSERT INTO perfil_permiso (perfil_id, permiso_id)
SELECT (SELECT id FROM perfiles WHERE nombre = 'Administrador'), id FROM permisos
WHERE codigo = 'administrar_migraciones';
