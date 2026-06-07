<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Spanish translation file with English source keys.
 */

return [
    // Core framework rules
    'The :attribute is required.' => 'El campo :attribute es obligatorio.',
    'The :attribute must be a valid email address.' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
    'The :attribute must be between :min and :max.' => 'El campo :attribute debe estar entre :min y :max.',

    // Expanded numeric rules
    'The :attribute must be an even number.' => 'El campo :attribute debe ser un número par.',
    'The :attribute must be a valid floating-point number.' => 'El campo :attribute debe ser un número de coma flotante válido.',
    'The :attribute must be strictly greater than :min.' => 'El campo :attribute debe ser estrictamente mayor que :min.',
    'The :attribute must be a valid integer.' => 'El campo :attribute debe ser un número entero válido.',
    'The :attribute must be less than or equal to :max.' => 'El campo :attribute debe ser menor o igual que :max.',
    'The :attribute must be strictly less than :max.' => 'El campo :attribute debe ser estrictamente menor que :max.',
    'The :attribute must be a negative number.' => 'El campo :attribute debe ser un número negativo.',
    'The :attribute must be a valid number description.' => 'El campo :attribute debe ser un número válido.',
    'The :attribute must be an odd number.' => 'El campo :attribute debe ser un número impar.',
    'The :attribute must be a positive number.' => 'El campo :attribute debe ser un número positivo.',

    // Expanded string and charset rules
    'The :attribute must conform to the specified :charset character encoding.' => 'El campo :attribute debe coincidir con el conjunto de caracteres especificado :charset.',
    'The :attribute must contain the sub-string phrase: :needle.' => 'El campo :attribute debe contener la subcadena :needle.',
    'The :attribute must end with the specified suffix: :suffix.' => 'El campo :attribute debe terminar con el sufijo :suffix.',
    'The :attribute must contain lowercase characters only.' => 'El campo :attribute debe contener solo letras minúsculas.',
    'The :attribute must not exceed a maximum length of :max characters.' => 'El campo :attribute no debe exceder la longitud máxima de :max caracteres.',
    'The :attribute must be at least :min characters.' => 'El campo :attribute debe tener al menos :min caracteres de longitud.',
    'The :attribute field payload must not contain any text characters.' => 'El contenido del campo :attribute no debe contener caracteres de texto.',
    'The :attribute text string structure cannot contain HTML or XML tags.' => 'La estructura de la cadena de texto :attribute no debe contener etiquetas HTML o XML.',
    'The :attribute must start with the specified prefix: :prefix.' => 'El campo :attribute debe comenzar con el prefijo :prefix.',
    'The :attribute must contain uppercase characters only.' => 'El campo :attribute debe contener solo letras mayúsculas.',

    // Presence and state
    'The :attribute must evaluate to an empty structural state.' => 'El campo :attribute debe dar como resultado un estado estructural vacío.',
    'The :attribute field must possess an active data state payload.' => 'El campo :attribute debe poseer un estado de carga útil de datos activo.',
    'The :attribute cannot equal the specified restricted entry option value.' => 'El campo :attribute no debe ser igual al valor de entrada restringido especificado.',

    // Network, framework formats, and token patterns
    'The verification security captcha entry input value is incorrect.' => 'El valor del captcha de seguridad introducido es incorrecto.',
    'The :attribute must match a valid network internet domain path description.' => 'El campo :attribute debe coincidir con una descripción de ruta de dominio de internet válida.',
    'The :attribute field value must be identical to the specified comparison target.' => 'El valor del campo :attribute debe ser idéntico al objetivo especificado.',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => 'El campo :attribute debe resolverse en una dirección de infraestructura IPv4 o IPv6 válida.',
    'The :attribute must match a valid hardware interface MAC address specification.' => 'El campo :attribute debe coincidir con una dirección MAC de interfaz de hardware válida.',
    'The structural evaluation format criteria configured for :attribute is invalid.' => 'El criterio de formato de evaluación estructural configurado para :attribute no es válido.',
    'The :attribute must resolve to a fully qualified web URL path address.' => 'El campo :attribute debe resolverse en una dirección de ruta URL web totalmente calificada.',
    'The :attribute can consist of valid structural hexadecimal digits only.' => 'El campo :attribute debe consistir solo de dígitos hexadecimales estructurales válidos.',

    // Complex runtime data and serialization mappings
    'The :attribute field must resolve to a valid logical boolean state wrapper.' => 'El campo :attribute debe resolverse en un estado booleano lógico válido.',
    'The passed payload structure inside :attribute is not executable by the engine.' => 'La estructura de carga útil pasada en :attribute no es ejecutable por el motor.',
    'The selected :attribute choice is outside the validated compilation index list.' => 'La opción seleccionada para :attribute queda fuera de la lista de índices de compilación validada.',
    'The context parameters inside :attribute must constitute an un-broken JSON structure.' => 'Los parámetros de contexto en :attribute deben representar una estructura JSON libre de errores.',
    'The string stream in :attribute cannot be cleanly un-serialized into native data.' => 'El flujo de cadena en :attribute no se puede deserializar limpiamente en datos nativos.',
    'The value assigned to :attribute is duplicated and violates uniqueness constraints.' => 'El valor asignado al campo :attribute ya existe y viola las restricciones de unicidad.',

    // Chronological constraints
    'The calendar data format structure in :attribute does not match template: :format.' => 'La estructura del formato de fecha del calendario en :attribute no coincide con la plantilla :format.',
    'The chronological layout matching sequence between :attribute and :target failed.' => 'La secuencia de coincidencia del diseño cronológico entre :attribute y :target falló.',
    'The value assigned to :attribute must represent a standard monthly day configuration.' => 'El valor asignado al campo :attribute debe corresponder a una configuración estándar de día del calendario.',
    'The execution block :attribute must evaluate to a valid UNIX epoch timestamp coordinate.' => 'El bloque de ejecución :attribute debe dar como resultado una coordenada de marca de tiempo de época UNIX válida.',
    'The provided timeline reference inside :attribute must match a 4-digit calendar year index.' => 'La referencia de línea de tiempo especificada en :attribute debe coincidir con un índice de año calendario de 4 dígitos.',

    // Operational server file-system checks
    'The local execution system cannot locate a valid directory path target matching :attribute.' => 'El sistema de ejecución local no puede encontrar un objetivo de ruta de directorio válido que corresponda a :attribute.',
    'The system rejected the file extension attached to the target payload parameter: :attribute.' => 'El sistema rechazó la extensión de archivo adjunta al parámetro de carga útil de destino :attribute.',
    'The localized storage map route configured inside :attribute is not an active target file.' => 'La ruta de asignación de almacenamiento localizada configurada en :attribute no es un archivo de destino activo.',
    'The system does not possess structural file system authorization maps to read :attribute.' => 'El sistema carece de las asignaciones de permisos estructurales del sistema de archivos para leer :attribute.',
    'The space configuration block constraints allocated to file processing payload :attribute exceeded maximum byte values.' => 'Las restricciones de espacio de almacenamiento asignadas para la carga útil de procesamiento de archivos :attribute superaron los valores máximos de bytes.',
    'The :attribute must be an image (e.g., JPG, PNG, WEBP).' => 'El :attribute debe ser una imagen (p. ej., JPG, PNG, WEBP).',

    // Geospatial coordinate checking schemas
    'The geographical projection context index coordinate for :attribute must fall between -90 and 90 degrees.' => 'La coordenada del índice de contexto de proyección geográfica para :attribute debe estar entre -90 y 90 grados.',
    'The geographical projection context index coordinate for :attribute must fall between -180 and 180 degrees.' => 'La coordenada del índice de contexto de proyección geográfica para :attribute debe estar entre -180 y 180 grados.',
];