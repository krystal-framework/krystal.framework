<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Russian translation file with English source keys.
 */

return [
    // Core framework rules
    'The :attribute field is required.' => 'Поле :attribute обязательно для заполнения.',
    'The :attribute must be a valid email address.' => 'Поле :attribute должно быть действительным адресом электронной почты.',
    'The :attribute must be between :min and :max.' => 'Поле :attribute должно быть между :min и :max.',

    // Expanded numeric rules
    'The :attribute must be an even number.' => 'Поле :attribute должно быть четным числом.',
    'The :attribute must be a valid floating-point number.' => 'Поле :attribute должно быть действительным числом с плавающей запятой.',
    'The :attribute must be strictly greater than :min.' => 'Поле :attribute должно быть строго больше, чем :min.',
    'The :attribute must be a valid integer.' => 'Поле :attribute должно быть действительным целым числом.',
    'The :attribute must be less than or equal to :max.' => 'Поле :attribute должно быть меньше или равно :max.',
    'The :attribute must be strictly less than :max.' => 'Поле :attribute должно быть строго меньше, чем :max.',
    'The :attribute must be a negative number.' => 'Поле :attribute должно быть отрицательным числом.',
    'The :attribute must be a valid number description.' => 'Поле :attribute должно быть действительным числом.',
    'The :attribute must be an odd number.' => 'Поле :attribute должно быть нечетным числом.',
    'The :attribute must be a positive number.' => 'Поле :attribute должно быть положительным числом.',

    // Expanded string and charset rules
    'The :attribute must conform to the specified :charset character encoding.' => 'Поле :attribute должно соответствовать указанной кокодировке :charset.',
    'The :attribute must contain the sub-string phrase: :needle.' => 'Поле :attribute должно содержать подстроку :needle.',
    'The :attribute must end with the specified suffix: :suffix.' => 'Поле :attribute должно заканчиваться суффиксом :suffix.',
    'The :attribute must contain lowercase characters only.' => 'Поле :attribute должно содержать только строчные буквы.',
    'The :attribute must not exceed a maximum length of :max characters.' => 'Поле :attribute не должно превышать максимальную длину в :max символов.',
    'The :attribute must be at least :min characters.' => 'Поле :attribute должно быть не менее :min символов в длину.',
    'The :attribute field payload must not contain any text characters.' => 'Содержимое поля :attribute не должно содержать текстовых символов.',
    'The :attribute text string structure cannot contain HTML or XML tags.' => 'Структура текстовой строки :attribute не должна содержать тегов HTML или XML.',
    'The :attribute must start with the specified prefix: :prefix.' => 'Поле :attribute должно начинаться с префикса :prefix.',
    'The :attribute must contain uppercase characters only.' => 'Поле :attribute должно содержать только заглавные буквы.',

    // Presence and state
    'The :attribute must evaluate to an empty structural state.' => 'Поле :attribute должно приводить к пустому структурному состоянию.',
    'The :attribute field must possess an active data state payload.' => 'Поле :attribute должно обладать активным состоянием полезной нагрузки данных.',
    'The :attribute cannot equal the specified restricted entry option value.' => 'Поле :attribute не должно быть равно указанному ограниченному значению ввода.',

    // Network, framework formats, and token patterns
    'The verification security captcha entry input value is incorrect.' => 'Введенное значение капчи безопасности неверно.',
    'The :attribute must match a valid network internet domain path description.' => 'Поле :attribute должно соответствовать действительному описанию пути интернет-домена.',
    'The :attribute field value must be identical to the specified comparison target.' => 'Значение поля :attribute должно быть идентичным указанному целевому значению.',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => 'Поле :attribute должно разрешаться в действительный адрес инфраструктуры IPv4 или IPv6.',
    'The :attribute must match a valid hardware interface MAC address specification.' => 'Поле :attribute должно соответствовать действительному MAC-адресу аппаратного интерфейса.',
    'The structural evaluation format criteria configured for :attribute is invalid.' => 'Критерий формата структурной оценки, настроенный для :attribute, недействителен.',
    'The :attribute must resolve to a fully qualified web URL path address.' => 'Поле :attribute должно разрешаться в полностью определенный адрес пути веб-URL.',
    'The :attribute can consist of valid structural hexadecimal digits only.' => 'Поле :attribute должно состоять только из действительных структурных шестнадцатеричных цифр.',

    // Complex runtime data and serialization mappings
    'The :attribute field must resolve to a valid logical boolean state wrapper.' => 'Поле :attribute должно разрешаться в действительное логическое булево состояние.',
    'The passed payload structure inside :attribute is not executable by the engine.' => 'Переданная структура полезной нагрузки в :attribute не может быть выполнена движком.',
    'The selected :attribute choice is outside the validated compilation index list.' => 'Выбранный вариант для :attribute выходит за пределы проверенного списка индексов компиляции.',
    'The context parameters inside :attribute must constitute an un-broken JSON structure.' => 'Параметры контекста в :attribute должны представлять собой безошибочную структуру JSON.',
    'The string stream in :attribute cannot be cleanly un-serialized into native data.' => 'Строковый поток в :attribute не может быть корректно десериализован в нативные данные.',
    'The value assigned to :attribute is duplicated and violates uniqueness constraints.' => 'Значение, присвоенное полю :attribute, уже существует и нарушает ограничения уникальности.',

    // Chronological constraints
    'The calendar data format structure in :attribute does not match template: :format.' => 'Структура формата календарной даты в :attribute не соответствует шаблону :format.',
    'The chronological layout matching sequence between :attribute and :target failed.' => 'Последовательность соответствия хронологического макета между :attribute и :target не удалась.',
    'The value assigned to :attribute must represent a standard monthly day configuration.' => 'Значение, присвоенное полю :attribute, должно соответствовать стандартной конфигурации календарного дня.',
    'The execution block :attribute must evaluate to a valid UNIX epoch timestamp coordinate.' => 'Блок выполнения :attribute должен приводить к действительной координате временной метки эпохи UNIX.',
    'The provided timeline reference inside :attribute must match a 4-digit calendar year index.' => 'Указанная ссылка на временную шкалу в :attribute должна соответствовать 4-значному индексу календарного года.',

    // Operational server file-system checks
    'The local execution system cannot locate a valid directory path target matching :attribute.' => 'Локальная система выполнения не может найти действительный целевой путь к директории, соответствующий :attribute.',
    'The system rejected the file extension attached to the target payload parameter: :attribute.' => 'Система отклонила расширение файла, прикрепленное к целевому параметру полезной нагрузки :attribute.',
    'The localized storage map route configured inside :attribute is not an active target file.' => 'Настроенный локализованный маршрут распределения хранилища в :attribute не является активным целевым файлом.',
    'The system does not possess structural file system authorization maps to read :attribute.' => 'У системы отсутствуют структурные назначения прав файловой системы для чтения :attribute.',
    'The space configuration block constraints allocated to file processing payload :attribute exceeded maximum byte values.' => 'Ограничения дискового пространства, назначенные для полезной нагрузки обработки файлов :attribute, превысили максимальные значения в байтах.',
    'The :attribute must be an image (e.g., JPG, PNG, WEBP).' => ':attribute должно быть изображением (например, JPG, PNG, WEBP).',

    // Geospatial coordinate checking schemas
    'The geographical projection context index coordinate for :attribute must fall between -90 and 90 degrees.' => 'Координата индекса контекста географической проекции для :attribute должна быть от -90 до 90 градусов.',
    'The geographical projection context index coordinate for :attribute must fall between -180 and 180 degrees.' => 'Координата индекса контекста географической проекции для :attribute должна быть от -180 до 180 градусов.',
];