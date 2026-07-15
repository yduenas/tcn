/***
CREACION
Permiso para el nuevo modulo "Cron" (pedido de Ytalo, 2026-07-16): el hosting real no
permite crear cron jobs, asi que en vez de un cron de verdad este modulo agrupa tareas
de mantenimiento periodico para ejecutarlas a mano desde el panel. Primera tarea:
limpiar CVs huerfanos (archivos en public/uploads/cv/ subidos via "Autocompletar desde
mi CV" cuya postulacion nunca se completo, asi que nunca quedaron registrados en
postulante_cv). Administrador por defecto, mismo criterio que administrar_bd/
administrar_migraciones.
END CREACION
***/

INSERT INTO permisos (codigo, descripcion, categoria) VALUES
    ('administrar_cron', 'Ver y ejecutar tareas de mantenimiento periodico', 'sistema');

INSERT INTO perfil_permiso (perfil_id, permiso_id)
SELECT (SELECT id FROM perfiles WHERE nombre = 'Administrador'), id FROM permisos
WHERE codigo = 'administrar_cron';
