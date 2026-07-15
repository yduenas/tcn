/***
CREACION
Ytalo, 2026-07-15: "tanto el email como el dni deben de ser unicos" -- email ya
tenia UNIQUE real en el esquema (candidatos.email TEXT NOT NULL UNIQUE) y se
respeta desde el inicio via Candidato::guardarPerfil() (busca por correo antes
de insertar). El DNI no tenia ninguna restriccion -- dos correos distintos
podian registrar el mismo DNI, creando dos perfiles para la misma persona.

Indice UNICO real sobre dni (no solo validacion en la app, mismo criterio que
email). Como el DNI es opcional en el formulario publico, los valores vacios
se guardan como NULL (no '') desde ahora -- SQLite permite multiples NULL en
un indice UNIQUE, pero trataria '' como un valor real repetible-solo-una-vez,
lo que bloquearia por error a cualquier segundo candidato que simplemente no
diera su DNI. Verificado antes de crear el indice: no habia duplicados reales
de DNI en la tabla.
END CREACION
***/

UPDATE candidatos SET dni = NULL WHERE dni = '';

CREATE UNIQUE INDEX IF NOT EXISTS idx_candidatos_dni ON candidatos(dni);
