/***
CREACION
Temporizador con corte automatico (seccion 3): registra cuando el candidato inicia
cada evaluacion para poder calcular el tiempo restante y vencerla si se agota.
END CREACION
***/

ALTER TABLE postulacion_evaluacion ADD COLUMN fecha_inicio DATETIME NULL;
