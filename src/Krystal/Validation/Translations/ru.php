<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Russian translation file with English source keys.
 */

return [
    // Core rules
    'The :attribute field is required.' => 'Поле :attribute обязательно для заполнения.',
    'The :attribute field must be a valid email address.' => 'Поле :attribute должно быть действительным адресом электронной почты.',
    'The :attribute field must be between :min and :max.' => 'Поле :attribute должно быть между :min и :max.',

    // Numeric rules
    'The :attribute field must be an even number.' => 'Поле :attribute должно быть четным числом.',
    'The :attribute field must be a valid float.' => 'Поле :attribute должно быть действительным числом с плавающей запятой.',
    'The :attribute field must be strictly greater than :min.' => 'Поле :attribute должно быть строго больше, чем :min.',
    'The :attribute field must be a valid integer.' => 'Поле :attribute должно быть действительным целым числом.',
    'The :attribute field must be less than or equal to :max.' => 'Поле :attribute должно быть меньше или равно :max.',
    'The :attribute field must be strictly less than :max.' => 'Поле :attribute должно быть строго меньше, чем :max.',
    'The :attribute field must be a negative number.' => 'Поле :attribute должно быть отрицательным числом.',
    'The :attribute field must be a valid number.' => 'Поле :attribute должно быть действительным числом.',
    'The :attribute field must be an odd number.' => 'Поле :attribute должно быть нечетным числом.',
    'The :attribute field must be a positive number.' => 'Поле :attribute должно быть положительным числом.',

    // String and charset rules
    'The :attribute field must match the specified charset :charset.' => 'Поле :attribute должно соответствовать указанной кокодировке :charset.',
    'The :attribute field must contain the substring :needle.' => 'Поле :attribute должно содержать подстроку :needle.',
    'The :attribute field must end with the suffix :suffix.' => 'Поле :attribute должно заканчиваться суффиксом :suffix.',
    'The :attribute field must contain lowercase letters only.' => 'Поле :attribute должно содержать только строчные буквы.',
    'The :attribute field must not exceed a maximum length of :max characters.' => 'Поле :attribute не должно превышать максимальную длину в :max символов.',
    'The :attribute field must be at least :min characters long.' => 'Поле :attribute должно быть не менее :min символов в длину.',
    'The :attribute field content must not contain text characters.' => 'Содержимое поля :attribute не должно содержать текстовых символов.',
    'The :attribute text string structure must not contain HTML or XML tags.' => 'Структура текстовой строки :attribute не должна содержать тегов HTML или XML.',
    'The :attribute field must start with the prefix :prefix.' => 'Поле :attribute должно начинаться с префикса :prefix.',
    'The :attribute field must contain uppercase letters only.' => 'Поле :attribute должно содержать только заглавные буквы.',

    // Presence and state
    'The :attribute field must result in an empty structural state.' => 'Поле :attribute должно приводить к пустому структурному состоянию.',
    'The :attribute field must possess an active data payload state.' => 'Поле :attribute должно обладать активным состоянием полезной нагрузки данных.',
    'The :attribute field must not be equal to the specified restricted input value.' => 'Поле :attribute не должно быть равно указанному ограниченному значению ввода.',

    // Network and domain patterns
    'The entered security captcha value is incorrect.' => 'Введенное значение капчи безопасности неверно.',
    'The :attribute field must match a valid internet domain path description.' => 'Поле :attribute должно соответствовать действительному описанию пути интернет-домена.',
    'The value of the :attribute field must be identical to the specified target.' => 'Значение поля :attribute должно быть идентичным указанному целевому значению.',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => 'Поле :attribute должно разрешаться в действительный адрес инфраструктуры IPv4 или IPv6.',
    'The :attribute field must match a valid hardware interface MAC address.' => 'Поле :attribute должно соответствовать действительному MAC-адресу аппаратного интерфейса.',
    'The structural evaluation format criterion configured for :attribute is invalid.' => 'Критерий формата структурной оценки, настроенный для :attribute, недействителен.',
    'The :attribute field must resolve to a fully qualified web URL path address.' => 'Поле :attribute должно разрешаться в полностью определенный адрес пути веб-URL.',
    'The :attribute field must consist only of valid structural hexadecimal digits.' => 'Поле :attribute должно состоять только из действительных структурных шестнадцатеричных цифр.',

    // Serialization and unique data mappings
    'The :attribute field must resolve to a valid logical boolean state.' => 'Поле :attribute должно разрешаться в действительное логическое булево состояние.',
    'The passed payload structure in :attribute is not executable by the engine.' => 'Переданная структура полезной нагрузки в :attribute не может быть выполнена движком.',
    'The selected option for :attribute falls outside the validated compilation index list.' => 'Выбранный вариант для :attribute выходит за пределы проверенного списка индексов компиляции.',
    'The context parameters in :attribute must represent an error-free JSON structure.' => 'Параметры контекста в :attribute должны представлять собой безошибочную структуру JSON.',
    'The string stream in :attribute cannot be cleanly deserialized into native data.' => 'Строковый поток в :attribute не может быть корректно десериализован в нативные данные.',
    'The value assigned to the :attribute field already exists and violates uniqueness constraints.' => 'Значение, присвоенное полю :attribute, уже существует и нарушает ограничения уникальности.',

    // Chronological constraints
    'The calendar date format structure in :attribute does not match the template :format.' => 'Структура формата календарной даты в :attribute не соответствует шаблону :format.',
    'The chronological layout match sequence between :attribute and :target failed.' => 'Последовательность соответствия хронологического макета между :attribute и :target не удалась.',
    'The value assigned to the :attribute field must correspond to a standard calendar day configuration.' => 'Значение, присвоенное полю :attribute, должно соответствовать стандартной конфигурации календарного дня.',
    'The execution block :attribute must result in a valid UNIX epoch timestamp coordinate.' => 'Блок выполнения :attribute должен приводить к действительной координате временной метки эпохи UNIX.',
    'The specified timeline reference in :attribute must match a 4-digit calendar year index.' => 'Указанная ссылка на временную шкалу в :attribute должна соответствовать 4-значному индексу календарного года.',

    // Server file-system checks
    'The local execution system cannot find a valid directory path target corresponding to :attribute.' => 'Локальная система выполнения не может найти действительный целевой путь к директории, соответствующий :attribute.',
    'The system rejected the file extension attached to the target payload parameter :attribute.' => 'Система отклонила расширение файла, прикрепленное к целевому параметру полезной нагрузки :attribute.',
    'The localized storage allocation route configured in :attribute is not an active target file.' => 'Настроенный локализованный маршрут распределения хранилища в :attribute не является активным целевым файлом.',
    'The system lacks the structural file-system permission assignments to read :attribute.' => 'У системы отсутствуют структурные назначения прав файловой системы для чтения :attribute.',
    'The storage space constraints assigned for the file processing payload :attribute exceeded maximum byte values.' => 'Ограничения дискового пространства, назначенные для полезной нагрузки обработки файлов :attribute, превысили максимальные значения в байтах.',

    // Geospatial schemas
    'The geographic projection context index coordinate for :attribute must be between -90 and 90 degrees.' => 'Координата индекса контекста географической проекции для :attribute должна быть от -90 до 90 градусов.',
    'The geographic projection context index coordinate for :attribute must be between -180 and 180 degrees.' => 'Координата индекса контекста географической проекции для :attribute должна быть от -180 до 180 градусов.',
];