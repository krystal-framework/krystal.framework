<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Spanish translation file with English source keys.
 */

return [
    // Core rules
    'The :attribute field is required.' => 'El campo :attribute es obligatorio.',
    'The :attribute field must be a valid email address.' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
    'The :attribute field must be between :min and :max.' => 'El campo :attribute debe estar entre :min y :max.',

    // Numeric rules
    'The :attribute field must be an even number.' => 'El campo :attribute debe ser un número par.',
    'The :attribute field must be a valid float.' => 'El campo :attribute debe ser un número de coma flotante válido.',
    'The :attribute field must be strictly greater than :min.' => 'El campo :attribute debe ser estrictamente mayor que :min.',
    'The :attribute field must be a valid integer.' => 'El campo :attribute debe ser un número entero válido.',
    'The :attribute field must be less than or equal to :max.' => 'El campo :attribute debe ser menor o igual que :max.',
    'The :attribute field must be strictly less than :max.' => 'El campo :attribute debe ser estrictamente menor que :max.',
    'The :attribute field must be a negative number.' => 'El campo :attribute debe ser un número negativo.',
    'The :attribute field must be a valid number.' => 'El campo :attribute debe ser un número válido.',
    'The :attribute field must be an odd number.' => 'El campo :attribute debe ser un número impar.',
    'The :attribute field must be a positive number.' => 'El campo :attribute debe ser un número positivo.',

    // String and charset rules
    'The :attribute field must match the specified charset :charset.' => 'El campo :attribute debe coincidir con el conjunto de caracteres especificado :charset.',
    'The :attribute field must contain the substring :needle.' => 'El campo :attribute debe contener la subcadena :needle.',
    'The :attribute field must end with the suffix :suffix.' => 'El campo :attribute debe terminar con el sufijo :suffix.',
    'The :attribute field must contain lowercase letters only.' => 'El campo :attribute debe contener solo letras minúsculas.',
    'The :attribute field must not exceed a maximum length of :max characters.' => 'El campo :attribute no debe exceder la longitud máxima de :max caracteres.',
    'The :attribute field must be at least :min characters long.' => 'El campo :attribute debe tener al menos :min caracteres de longitud.',
    'The :attribute field content must not contain text characters.' => 'El contenido del campo :attribute no debe contener caracteres de texto.',
    'The :attribute text string structure must not contain HTML or XML tags.' => 'La estructura de la cadena de texto :attribute no debe contener etiquetas HTML o XML.',
    'The :attribute field must start with the prefix :prefix.' => 'El campo :attribute debe comenzar con el prefijo :prefix.',
    'The :attribute field must contain uppercase letters only.' => 'El campo :attribute debe contener solo letras mayúsculas.',

    // Presence and state
    'The :attribute field must result in an empty structural state.' => 'El campo :attribute debe dar como resultado un estado estructural vacío.',
    'The :attribute field must possess an active data payload state.' => 'El campo :attribute debe poseer un estado de carga útil de datos activo.',
    'The :attribute field must not be equal to the specified restricted input value.' => 'El campo :attribute no debe ser igual al valor de entrada restringido especificado.',

    // Network and domain patterns
    'The entered security captcha value is incorrect.' => 'El valor del captcha de seguridad introducido es incorrecto.',
    'The :attribute field must match a valid internet domain path description.' => 'El campo :attribute debe coincidir con una descripción de ruta de dominio de internet válida.',
    'The value of the :attribute field must be identical to the specified target.' => 'El valor del campo :attribute debe ser idéntico al objetivo especificado.',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => 'El campo :attribute debe resolverse en una dirección de infraestructura IPv4 o IPv6 válida.',
    'The :attribute field must match a valid hardware interface MAC address.' => 'El campo :attribute debe coincidir con una dirección MAC de interfaz de hardware válida.',
    'The structural evaluation format criterion configured for :attribute is invalid.' => 'El criterio de formato de evaluación estructural configurado para :attribute no es válido.',
    'The :attribute field must resolve to a fully qualified web URL path address.' => 'El campo :attribute debe resolverse en una dirección de ruta URL web totalmente calificada.',
    'The :attribute field must consist only of valid structural hexadecimal digits.' => 'El campo :attribute debe consistir solo de dígitos hexadecimales estructurales válidos.',

    // Serialization and unique data mappings
    'The :attribute field must resolve to a valid logical boolean state.' => 'El campo :attribute debe resolverse en un estado booleano lógico válido.',
    'The passed payload structure in :attribute is not executable by the engine.' => 'La estructura de carga útil pasada en :attribute no es ejecutable por el motor.',
    'The selected option for :attribute falls outside the validated compilation index list.' => 'La opción seleccionada para :attribute queda fuera de la lista de índices de compilación validada.',
    'The context parameters in :attribute must represent an error-free JSON structure.' => 'Los parámetros de contexto en :attribute deben representar una estructura JSON libre de errores.',
    'The string stream in :attribute cannot be cleanly deserialized into native data.' => 'El flujo de cadena en :attribute no se puede deserializar limpiamente en datos nativos.',
    'The value assigned to the :attribute field already exists and violates uniqueness constraints.' => 'El valor asignado al campo :attribute ya existe y viola las restricciones de unicidad.',

    // Chronological constraints
    'The calendar date format structure in :attribute does not match the template :format.' => 'La estructura del formato de fecha del calendario en :attribute no coincide con la plantilla :format.',
    'The chronological layout match sequence between :attribute and :target failed.' => 'La secuencia de coincidencia del diseño cronológico entre :attribute y :target falló.',
    'The value assigned to the :attribute field must correspond to a standard calendar day configuration.' => 'El valor asignado al campo :attribute debe corresponder a una configuración estándar de día del calendario.',
    'The execution block :attribute must result in a valid UNIX epoch timestamp coordinate.' => 'El bloque de ejecución :attribute debe dar como resultado una coordenada de marca de tiempo de época UNIX válida.',
    'The specified timeline reference in :attribute must match a 4-digit calendar year index.' => 'La referencia de línea de tiempo especificada en :attribute debe coincidir con un índice de año calendario de 4 dígitos.',

    // Server file-system checks
    'The local execution system cannot find a valid directory path target corresponding to :attribute.' => 'El sistema de ejecución local no puede encontrar un objetivo de ruta de directorio válido que corresponda a :attribute.',
    'The system rejected the file extension attached to the target payload parameter :attribute.' => 'El sistema rechazó la extensión de archivo adjunta al parámetro de carga útil de destino :attribute.',
    'The localized storage allocation route configured in :attribute is not an active target file.' => 'La ruta de asignación de almacenamiento localizada configurada en :attribute no es un archivo de destino activo.',
    'The system lacks the structural file-system permission assignments to read :attribute.' => 'El sistema carece de las asignaciones de permisos estructurales del sistema de archivos para leer :attribute.',
    'The storage space constraints assigned for the file processing payload :attribute exceeded maximum byte values.' => 'Las restricciones de espacio de almacenamiento asignadas para la carga útil de procesamiento de archivos :attribute superaron los valores máximos de bytes.',

    // Geospatial schemas
    'The geographic projection context index coordinate for :attribute must be between -90 and 90 degrees.' => 'La coordenada del índice de contexto de proyección geográfica para :attribute debe estar entre -90 y 90 grados.',
    'The geographic projection context index coordinate for :attribute must be between -180 and 180 degrees.' => 'La coordenada del índice de contexto de proyección geográfica para :attribute debe estar entre -180 y 180 grados.',
];