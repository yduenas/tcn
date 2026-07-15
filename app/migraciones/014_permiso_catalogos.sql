/***
CREACION
Nuevo permiso para administrar catalogos generales (categorias de cargo, modalidades, etc.)
desde el panel, sin tocar codigo.
END CREACION
***/

INSERT INTO permisos (codigo, descripcion, categoria) VALUES
    ('configurar_catalogos', 'Administrar catálogos generales (cargos, modalidades)', 'sistema');

INSERT INTO perfil_permiso (perfil_id, permiso_id)
SELECT (SELECT id FROM perfiles WHERE nombre = 'Administrador'), id FROM permisos
WHERE codigo = 'configurar_catalogos';
