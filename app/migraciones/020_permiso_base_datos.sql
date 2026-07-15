/***
CREACION
Permiso para el visor/ejecutor de base de datos (herramienta de mantenimiento en
hosting compartido, donde no hay acceso a un cliente SQLite externo). Solo Administrador
lo tiene por defecto: ejecutar SQL directo es una accion delicada.
END CREACION
***/

INSERT INTO permisos (codigo, descripcion, categoria) VALUES
    ('administrar_bd', 'Ver tablas y ejecutar SQL directo sobre la base de datos', 'sistema');

INSERT INTO perfil_permiso (perfil_id, permiso_id)
SELECT (SELECT id FROM perfiles WHERE nombre = 'Administrador'), id FROM permisos
WHERE codigo = 'administrar_bd';
