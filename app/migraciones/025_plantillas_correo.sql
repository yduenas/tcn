/***
CREACION
Modulo de Plantillas de correo (pedido de Ytalo, 2026-07-16): 3 categorias editables
desde el panel -- recuperacion de contrasena (hoy fija en Logins.php, pendiente original
de la seccion 1.4 del CLAUDE.md), postulacion recibida (dispara al postular, Portal::enviar())
y una por cada etapa del pipeline (dispara en Postulacion::cambiarEstado()). Cada plantilla
de postulacion/etapa tiene su propio check "activo" para poder deshabilitar el envio de
esa etapa puntual en el futuro sin tocar codigo -- la de recuperacion de contrasena no
tiene ese check (siempre se envia, no tiene sentido apagarla).

configuracion_correo agrega el nombre de remitente (FromName) como dato editable, separado
de la cuenta SMTP real (CORREO_ENVIO_CORREO en config), para que los correos puedan mostrar
un remitente distinto al buzon tecnico que realmente los envia.
END CREACION
***/

CREATE TABLE IF NOT EXISTS plantillas_correo (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tipo TEXT NOT NULL CHECK (tipo IN ('recuperacion_password', 'postulacion_recibida', 'cambio_estado')),
    estado_id INTEGER NULL,
    asunto TEXT NOT NULL,
    cuerpo_html TEXT NOT NULL,
    activo INTEGER NOT NULL DEFAULT 1,
    UNIQUE (tipo, estado_id),
    FOREIGN KEY (estado_id) REFERENCES estados_postulacion(id)
);

CREATE TABLE IF NOT EXISTS configuracion_correo (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    remitente_nombre TEXT NOT NULL DEFAULT 'TCN Complement'
);

INSERT INTO configuracion_correo (id, remitente_nombre) VALUES (1, 'TCN Complement');

INSERT INTO plantillas_correo (tipo, estado_id, asunto, cuerpo_html, activo) VALUES
('recuperacion_password', NULL, 'Recupera tu contraseña — TCN Complement',
 '<p>Hola {nombre},</p><p>Recibimos una solicitud para restablecer tu contraseña. Este enlace es válido por 1 hora:</p><p><a href="{link}">{link}</a></p><p>Si no solicitaste esto, puedes ignorar este correo.</p>',
 1),
('postulacion_recibida', NULL, 'Recibimos tu postulación — {vacante}',
 '<p>Hola {nombre},</p><p>Gracias por postular a <strong>{vacante}</strong>. Hemos recibido tu información correctamente.</p><p>Puedes revisar el estado de tu postulación en cualquier momento aquí: <a href="{link_estado}">{link_estado}</a></p>',
 1);

INSERT INTO plantillas_correo (tipo, estado_id, asunto, cuerpo_html, activo)
SELECT 'cambio_estado', id,
    'Actualización de tu postulación — {vacante}',
    CASE codigo
        WHEN 'recibida' THEN '<p>Hola {nombre},</p><p>Tu postulación a <strong>{vacante}</strong> fue registrada.</p><p>Revisa el estado aquí: <a href="{link_estado}">{link_estado}</a></p>'
        WHEN 'en_revision' THEN '<p>Hola {nombre},</p><p>Tu postulación a <strong>{vacante}</strong> está en revisión.</p><p>Revisa el estado aquí: <a href="{link_estado}">{link_estado}</a></p>'
        WHEN 'preseleccionado' THEN '<p>Hola {nombre},</p><p>Buenas noticias: fuiste preseleccionado para <strong>{vacante}</strong>.</p><p>Revisa el estado aquí: <a href="{link_estado}">{link_estado}</a></p>'
        WHEN 'entrevista' THEN '<p>Hola {nombre},</p><p>Te contactaremos para coordinar una entrevista para <strong>{vacante}</strong>.</p><p>Revisa el estado aquí: <a href="{link_estado}">{link_estado}</a></p>'
        WHEN 'terna_final' THEN '<p>Hola {nombre},</p><p>Tu perfil avanzó a la terna final para <strong>{vacante}</strong>.</p><p>Revisa el estado aquí: <a href="{link_estado}">{link_estado}</a></p>'
        WHEN 'pre_contratado' THEN '<p>Hola {nombre},</p><p>Estás en la etapa de pre-contratación para <strong>{vacante}</strong>.</p><p>Revisa el estado aquí: <a href="{link_estado}">{link_estado}</a></p>'
        WHEN 'contratado' THEN '<p>Hola {nombre},</p><p>¡Felicidades! Fuiste contratado para <strong>{vacante}</strong>.</p><p>Revisa el estado aquí: <a href="{link_estado}">{link_estado}</a></p>'
        WHEN 'descartado' THEN '<p>Hola {nombre},</p><p>Gracias por tu interés en <strong>{vacante}</strong>. En esta oportunidad decidimos continuar con otro candidato.</p><p>Revisa el estado aquí: <a href="{link_estado}">{link_estado}</a></p>'
        WHEN 'desertado' THEN '<p>Hola {nombre},</p><p>Registramos que decidiste no continuar en el proceso para <strong>{vacante}</strong>.</p><p>Revisa el estado aquí: <a href="{link_estado}">{link_estado}</a></p>'
    END,
    1
FROM estados_postulacion;

INSERT INTO permisos (codigo, descripcion, categoria) VALUES
    ('configurar_plantillas_correo', 'Administrar plantillas de correo y remitente', 'sistema');

INSERT INTO perfil_permiso (perfil_id, permiso_id)
SELECT (SELECT id FROM perfiles WHERE nombre = 'Administrador'), id FROM permisos
WHERE codigo = 'configurar_plantillas_correo';
