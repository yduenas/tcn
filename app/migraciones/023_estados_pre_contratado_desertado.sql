-- Agrega dos etapas al embudo de postulaciones (pedido de Ytalo, 2026-07-15):
-- "Pre-contratado" antes de "Contratado", y "Desertó" al final del todo.
-- Reordena contratado/descartado para dejar hueco a pre_contratado en el medio.

UPDATE estados_postulacion SET orden = 8 WHERE codigo = 'descartado';
UPDATE estados_postulacion SET orden = 7 WHERE codigo = 'contratado';

INSERT INTO estados_postulacion (codigo, nombre, orden, es_final) VALUES
	('pre_contratado', 'Pre-contratado', 6, 0),
	('desertado', 'Desertó', 9, 1);
