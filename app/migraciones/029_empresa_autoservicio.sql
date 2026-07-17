-- Pedido de Ytalo, 2026-07-17: el perfil "Empresa" pasa a poder crear/gestionar
-- sus propias vacantes y hacer lo que un Seleccionador hace, pero scopeado a su
-- propia empresa (nunca a otras). Otorga los 8 permisos que le faltaban --
-- la verificacion real de "es tu propia vacante/empresa" vive en el codigo
-- (requiereDuenoDeVacante() en url_helper.php), no en este catalogo de permisos.
-- Idempotente: no duplica si ya se corrio antes.

INSERT INTO perfil_permiso (perfil_id, permiso_id)
SELECT (SELECT id FROM perfiles WHERE nombre = 'Empresa'), p.id
FROM permisos p
WHERE p.codigo IN (
	'crear_vacante', 'editar_vacante', 'publicar_vacante',
	'ver_postulantes', 'agendar_entrevista', 'calificar_entrevista',
	'ver_datos_sensibles_evaluacion', 'exportar_pdf',
	'crear_usuario'
)
AND NOT EXISTS (
	SELECT 1 FROM perfil_permiso pp
	WHERE pp.perfil_id = (SELECT id FROM perfiles WHERE nombre = 'Empresa')
	AND pp.permiso_id = p.id
);
