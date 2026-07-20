<?php
require_once __DIR__.'/pdfparser/autoload.php';

/**
 * Extraccion de datos del CV sin IA (seccion 6.2/6.3 del CLAUDE.md):
 * texto plano via smalot/pdfparser + heuristicas por expresiones regulares.
 * Si el PDF es una imagen escaneada sin texto embebido, no se extrae nada
 * (limitacion aceptada y documentada: no se usa OCR).
 */
class ExtractorCV{

		public static function extraerTexto($rutaArchivo){
			try {
				$parser = new \Smalot\PdfParser\Parser();
				$pdf = $parser->parseFile($rutaArchivo);
				return $pdf->getText();
			} catch (\Exception $e) {
				return '';
			}
		}

		public static function extraerDatos($texto){
			return [
				'email' => self::extraerEmail($texto),
				'telefono' => self::extraerTelefono($texto),
				'nombre' => self::extraerNombre($texto),
				'educacion' => self::extraerEducacion($texto),
				'experiencia' => self::extraerExperiencia($texto),
				'habilidades' => self::extraerHabilidades($texto),
			];
		}

		private static function lineas($texto){
			return preg_split('/\r\n|\r|\n/', $texto);
		}

		/** Email: [\w.-]+@[\w.-]+\.\w+ **/
		private static function extraerEmail($texto){
			if(preg_match('/[\w.-]+@[\w.-]+\.\w+/', $texto, $m)){
				return $m[0];
			}
			return null;
		}

		/** Telefono: 9+ digitos, con o sin espacios/guiones **/
		private static function extraerTelefono($texto){
			if(preg_match('/(\+?\d[\d\s\-]{7,}\d)/', $texto, $m)){
				$numero = preg_replace('/\s+/', ' ', trim($m[0]));
				if(preg_match('/\d/', $numero) && strlen(preg_replace('/\D/', '', $numero)) >= 9){
					return $numero;
				}
			}
			return null;
		}

		/** Nombre: primera linea no vacia del documento.
		 * Muchos CVs alinean el nombre a la izquierda y un dato de contacto (telefono,
		 * "Cel.: ...") a la derecha de esa misma linea visual, separados por varios
		 * espacios en blanco (ej. "YTALO DUEÑAS MARTINO          Cel.: +51 994 084 294")
		 * -- reportado por Ytalo, 2026-07-20: el campo Nombres quedaba con la linea
		 * completa, telefono incluido. Se toma solo el primer bloque antes de ese salto
		 * de columna, y se recorta ademas cualquier etiqueta de contacto pegada sin ese
		 * espaciado (sin bloque de columna real). Si una linea queda vacia tras esto
		 * (ej. la primera linea real era puro dato de contacto), se sigue con la
		 * siguiente linea no vacia en vez de devolver nada. **/
		private static function extraerNombre($texto){
			foreach(self::lineas($texto) as $linea){
				$linea = trim($linea);
				if($linea === ''){
					continue;
				}
				$bloques = preg_split('/\s{2,}|\t/', $linea);
				$nombre = trim($bloques[0]);
				$nombre = preg_replace('/\s*(Cel(ular)?\.?|Tel(éfono|efono)?\.?|Email|Correo|E-?mail)\s*:?\s*.*/i', '', $nombre);
				$nombre = trim($nombre, " \t\n\r\0\x0B-–—:");
				if($nombre !== ''){
					return $nombre;
				}
			}
			return null;
		}

		/** Educacion: lineas cercanas a palabras clave **/
		private static function extraerEducacion($texto){
			$palabrasClave = ['Universidad', 'Instituto', 'Bachiller', 'Licenciado', 'Licenciada', 'Magíster', 'Maestría', 'Doctorado'];
			return self::bloquesCercaDe($texto, $palabrasClave);
		}

		/** Experiencia: patrones de fechas (2020-2022 o "Actualidad"/"Presente") y texto circundante **/
		private static function extraerExperiencia($texto){
			$lineas = self::lineas($texto);
			$bloques = [];
			foreach($lineas as $i => $linea){
				if(preg_match('/\d{4}\s*-\s*(\d{4}|Actualidad|Presente)/i', $linea)){
					$inicio = max(0, $i - 1);
					$fin = min(count($lineas) - 1, $i + 1);
					$bloque = trim(implode(' — ', array_filter(array_map('trim', array_slice($lineas, $inicio, $fin - $inicio + 1)))));
					if($bloque !== ''){
						$bloques[] = $bloque;
					}
				}
			}
			return $bloques;
		}

		/** Habilidades: seccion con encabezado Habilidades/Skills/Competencias y la lista siguiente **/
		private static function extraerHabilidades($texto){
			$lineas = self::lineas($texto);
			foreach($lineas as $i => $linea){
				if(preg_match('/^\s*(Habilidades|Skills|Competencias)\s*:?\s*$/i', trim($linea))){
					$siguiente = trim($lineas[$i + 1] ?? '');
					if($siguiente === ''){
						continue;
					}
					$items = preg_split('/[,;•]+/', $siguiente);
					return array_values(array_filter(array_map('trim', $items), function($v){ return $v !== ''; }));
				}
			}
			return [];
		}

		private static function bloquesCercaDe($texto, array $palabrasClave){
			$lineas = self::lineas($texto);
			$patron = '/'.implode('|', array_map(function($p){ return preg_quote($p, '/'); }, $palabrasClave)).'/i';
			$bloques = [];
			foreach($lineas as $linea){
				$linea = trim($linea);
				if($linea !== '' && preg_match($patron, $linea)){
					$bloques[] = $linea;
				}
			}
			return $bloques;
		}

 }
