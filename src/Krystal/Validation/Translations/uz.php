<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Uzbek translation file with English source keys.
 */

return [
    // Core framework rules
    'The :attribute field is required.' => ':attribute maydoni toʻldirilishi majburiy.',
    'The :attribute must be a valid email address.' => ':attribute haqiqiy elektron pochta manzili boʻlishi kerak.',
    'The :attribute must be between :min and :max.' => ':attribute :min va :max orasida boʻlishi kerak.',

    // Expanded numeric rules
    'The :attribute must be an even number.' => ':attribute juft son boʻlishi kerak.',
    'The :attribute must be a valid floating-point number.' => ':attribute haqiqiy haqiqiy son (float) boʻlishi kerak.',
    'The :attribute must be strictly greater than :min.' => ':attribute :min qiymatidan qatʼiy katta boʻlishi kerak.',
    'The :attribute must be a valid integer.' => ':attribute haqiqiy butun son boʻlishi kerak.',
    'The :attribute must be less than or equal to :max.' => ':attribute :max qiymatidan kichik yoki unga teng boʻlishi kerak.',
    'The :attribute must be strictly less than :max.' => ':attribute :max qiymatidan qatʼiy kichik boʻlishi kerak.',
    'The :attribute must be a negative number.' => ':attribute manfiy son boʻlishi kerak.',
    'The :attribute must be a valid number description.' => ':attribute haqiqiy son boʻlishi kerak.',
    'The :attribute must be an odd number.' => ':attribute toq son boʻlishi kerak.',
    'The :attribute must be a positive number.' => ':attribute musbat son boʻlishi kerak.',

    // Expanded string and charset rules
    'The :attribute must conform to the specified :charset character encoding.' => ':attribute koʻrsatilgan :charset kodirovkasiga mos kelishi kerak.',
    'The :attribute must contain the sub-string phrase: :needle.' => ':attribute :needle qism satrini oʻz ichiga olishi kerak.',
    'The :attribute must end with the specified suffix: :suffix.' => ':attribute :suffix suffiksi bilan tugashi kerak.',
    'The :attribute must contain lowercase characters only.' => ':attribute faqat kichik harflardan iborat boʻlishi kerak.',
    'The :attribute must not exceed a maximum length of :max characters.' => ':attribute uzunligi :max belgidan oshmasligi kerak.',
    'The :attribute must be at least :min characters.' => ':attribute uzunligi kamida :min belgi boʻlishi kerak.',
    'The :attribute field payload must not contain any text characters.' => ':attribute maydoni tarkibida matnli belgilar boʻlmasligi kerak.',
    'The :attribute text string structure cannot contain HTML or XML tags.' => ':attribute matn satri tuzilishida HTML yoki XML teglari boʻlmasligi kerak.',
    'The :attribute must start with the specified prefix: :prefix.' => ':attribute :prefix prefiksi bilan boshlanishi kerak.',
    'The :attribute must contain uppercase characters only.' => ':attribute faqat katta harflardan iborat boʻlishi kerak.',

    // Presence and state
    'The :attribute must evaluate to an empty structural state.' => ':attribute boʻsh tarkibiy holatga olib kelishi kerak.',
    'The :attribute field must possess an active data state payload.' => ':attribute faol maʼlumotlar holatiga ega boʻlishi kerak.',
    'The :attribute cannot equal the specified restricted entry option value.' => ':attribute koʻrsatilgan cheklangan kiritish qiymatiga teng boʻlmasligi kerak.',

    // Network, framework formats, and token patterns
    'The verification security captcha entry input value is incorrect.' => 'Kiritilgan xavfsizlik kapchasi qiymati notoʻgʻri.',
    'The :attribute must match a valid network internet domain path description.' => ':attribute haqiqiy internet domeni yoʻli tavsifiga mos kelishi kerak.',
    'The :attribute field value must be identical to the specified comparison target.' => ':attribute qiymati koʻrsatilgan maqsadli qiymat bilan bir xil boʻlishi kerak.',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => ':attribute haqiqiy IPv4 yoki IPv6 infratuzilma manzili boʻlishi kerak.',
    'The :attribute must match a valid hardware interface MAC address specification.' => ':attribute apparat interfeysining haqiqiy MAC-manziliga mos kelishi kerak.',
    'The structural evaluation format criteria configured for :attribute is invalid.' => ':attribute uchun sozlangan tarkibiy baholash formati mezoni yaroqsiz.',
    'The :attribute must resolve to a fully qualified web URL path address.' => ':attribute toʻliq aniqlangan veb-URL manzili boʻlishi kerak.',
    'The :attribute can consist of valid structural hexadecimal digits only.' => ':attribute faqat haqiqiy oʻn oltilik raqamlardan iborat boʻlishi kerak.',

    // Complex runtime data and serialization mappings
    'The :attribute field must resolve to a valid logical boolean state wrapper.' => ':attribute haqiqiy mantiqiy bul (boolean) holatiga ega boʻlishi kerak.',
    'The passed payload structure inside :attribute is not executable by the engine.' => ':attribute ichida uzatilgan maʼlumotlar tuzilmasini tizim bajara olmaydi.',
    'The selected :attribute choice is outside the validated compilation index list.' => ':attribute uchun tanlangan variant tekshirilgan kompilyatsiya indekslari roʻyxatidan tashqarida.',
    'The context parameters inside :attribute must constitute an un-broken JSON structure.' => ':attribute ichidagi kontekst parametrlari xatosiz JSON tuzilmasini ifodalashi kerak.',
    'The string stream in :attribute cannot be cleanly un-serialized into native data.' => ':attribute ichidagi satr oqimini mahalliy maʼlumotlarga toʻgʻri de-serializatsiya qilib boʻlmaydi.',
    'The value assigned to :attribute is duplicated and violates uniqueness constraints.' => ':attribute maydoniga tayinlangan qiymat allaqachon mavjud va takrorlanmaslik cheklovini buzadi.',

    // Chronological constraints
    'The calendar data format structure in :attribute does not match template: :format.' => ':attribute maydonidagi kalendar sana formati :format shabloniga mos kelmaydi.',
    'The chronological layout matching sequence between :attribute and :target failed.' => ':attribute va :target oʻrtasidagi xronologik tartib mosligi muvaffaqiyatsiz tugadi.',
    'The value assigned to :attribute must represent a standard monthly day configuration.' => ':attribute maydoniga tayinlangan qiymat standart kalendar kuni konfiguratsiyasiga mos kelishi kerak.',
    'The execution block :attribute must evaluate to a valid UNIX epoch timestamp coordinate.' => ':attribute bajarilish bloki haqiqiy UNIX davri vaqt tamgʻasi (timestamp) koordinatasiga olib kelishi kerak.',
    'The provided timeline reference inside :attribute must match a 4-digit calendar year index.' => ':attribute ichida koʻrsatilgan vaqt shkalasi havolasi 4 xonali kalendar yili indeksiga mos kelishi kerak.',

    // Operational server file-system checks
    'The local execution system cannot locate a valid directory path target matching :attribute.' => 'Mahalliy tizim :attribute maydoniga mos keladigan haqiqiy katalog yoʻlini topa olmadi.',
    'The system rejected the file extension attached to the target payload parameter: :attribute.' => 'Tizim :attribute maqsadli parametriga biriktirilgan fayl kengaytmasini rad etdi.',
    'The localized storage map route configured inside :attribute is not an active target file.' => ':attribute ichida sozlangan mahalliylashtirilgan saqlash yoʻnalishi faol maqsadli fayl emas.',
    'The system does not possess structural file system authorization maps to read :attribute.' => 'Tizimda :attribute faylini oʻqish uchun fayl tizimi ruxsatnomalari yetarli emas.',
    'The space configuration block constraints allocated to file processing payload :attribute exceeded maximum byte values.' => ':attribute faylini qayta ishlash uchun ajratilgan xotira hajmi cheklovlari maksimal bayt qiymatlaridan oshib ketdi.',

    // Geospatial coordinate checking schemas
    'The geographical projection context index coordinate for :attribute must fall between -90 and 90 degrees.' => ':attribute uchun geografik proyeksiya kontekstining indeks koordinatasi -90 va 90 daraja orasida boʻlishi kerak.',
    'The geographical projection context index coordinate for :attribute must fall between -180 and 180 degrees.' => ':attribute uchun geografik proyeksiya kontekstining indeks koordinatasi -180 va 180 daraja orasida boʻlishi kerak.',
];