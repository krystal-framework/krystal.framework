<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Uzbek translation file with English source keys.
 */

return [
    // Core rules
    'The :attribute field is required.' => ':attribute maydoni toʻldirilishi majburiy.',
    'The :attribute field must be a valid email address.' => ':attribute maydoni haqiqiy elektron pochta manzili boʻlishi kerak.',
    'The :attribute field must be between :min and :max.' => ':attribute maydoni :min va :max orasida boʻlishi kerak.',

    // Numeric rules
    'The :attribute field must be an even number.' => ':attribute maydoni juft son boʻlishi kerak.',
    'The :attribute field must be a valid float.' => ':attribute maydoni haqiqiy haqiqiy son (float) boʻlishi kerak.',
    'The :attribute field must be strictly greater than :min.' => ':attribute maydoni :min qiymatidan qatʼiy katta boʻlishi kerak.',
    'The :attribute field must be a valid integer.' => ':attribute maydoni haqiqiy butun son boʻlishi kerak.',
    'The :attribute field must be less than or equal to :max.' => ':attribute maydoni :max qiymatidan kichik yoki unga teng boʻlishi kerak.',
    'The :attribute field must be strictly less than :max.' => ':attribute maydoni :max qiymatidan qatʼiy kichik boʻlishi kerak.',
    'The :attribute field must be a negative number.' => ':attribute maydoni manfiy son boʻlishi kerak.',
    'The :attribute field must be a valid number.' => ':attribute maydoni haqiqiy son boʻlishi kerak.',
    'The :attribute field must be an odd number.' => ':attribute maydoni toq son boʻlishi kerak.',
    'The :attribute field must be a positive number.' => ':attribute maydoni musbat son boʻlishi kerak.',

    // String and charset rules
    'The :attribute field must match the specified charset :charset.' => ':attribute maydoni koʻrsatilgan :charset kodirovkasiga mos kelishi kerak.',
    'The :attribute field must contain the substring :needle.' => ':attribute maydoni :needle qism satrini oʻz ichiga olishi kerak.',
    'The :attribute field must end with the suffix :suffix.' => ':attribute maydoni :suffix suffiksi bilan tugashi kerak.',
    'The :attribute field must contain lowercase letters only.' => ':attribute maydoni faqat kichik harflardan iborat boʻlishi kerak.',
    'The :attribute field must not exceed a maximum length of :max characters.' => ':attribute maydoni uzunligi :max belgidan oshmasligi kerak.',
    'The :attribute field must be at least :min characters long.' => ':attribute maydoni uzunligi kamida :min belgi boʻlishi kerak.',
    'The :attribute field content must not contain text characters.' => ':attribute maydoni tarkibida matnli belgilar boʻlmasligi kerak.',
    'The :attribute text string structure must not contain HTML or XML tags.' => ':attribute matn satri tuzilishida HTML yoki XML teglari boʻlmasligi kerak.',
    'The :attribute field must start with the prefix :prefix.' => ':attribute maydoni :prefix prefiksi bilan boshlanishi kerak.',
    'The :attribute field must contain uppercase letters only.' => ':attribute maydoni faqat katta harflardan iborat boʻlishi kerak.',

    // Presence and state
    'The :attribute field must result in an empty structural state.' => ':attribute maydoni boʻsh tarkibiy holatga olib kelishi kerak.',
    'The :attribute field must possess an active data payload state.' => ':attribute maydoni faol maʼlumotlar holatiga ega boʻlishi kerak.',
    'The :attribute field must not be equal to the specified restricted input value.' => ':attribute maydoni koʻrsatilgan cheklangan kiritish qiymatiga teng boʻlmasligi kerak.',

    // Network and domain patterns
    'The entered security captcha value is incorrect.' => 'Kiritilgan xavfsizlik kapchasi qiymati notoʻgʻri.',
    'The :attribute field must match a valid internet domain path description.' => ':attribute maydoni haqiqiy internet domeni yoʻli tavsifiga mos kelishi kerak.',
    'The value of the :attribute field must be identical to the specified target.' => ':attribute maydoni qiymati koʻrsatilgan maqsadli qiymat bilan bir xil boʻlishi kerak.',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => ':attribute maydoni haqiqiy IPv4 yoki IPv6 infratuzilma manzili boʻlishi kerak.',
    'The :attribute field must match a valid hardware interface MAC address.' => ':attribute maydoni apparat interfeysining haqiqiy MAC-manziliga mos kelishi kerak.',
    'The structural evaluation format criterion configured for :attribute is invalid.' => ':attribute uchun sozlangan tarkibiy baholash formati mezoni yaroqsiz.',
    'The :attribute field must resolve to a fully qualified web URL path address.' => ':attribute maydoni toʻliq aniqlangan veb-URL manzili boʻlishi kerak.',
    'The :attribute field must consist only of valid structural hexadecimal digits.' => ':attribute maydoni faqat haqiqiy oʻn oltilik raqamlardan iborat boʻlishi kerak.',

    // Serialization and unique data mappings
    'The :attribute field must resolve to a valid logical boolean state.' => ':attribute maydoni haqiqiy mantiqiy bul (boolean) holatiga ega boʻlishi kerak.',
    'The passed payload structure in :attribute is not executable by the engine.' => ':attribute ichida uzatilgan maʼlumotlar tuzilmasini tizim bajara olmaydi.',
    'The selected option for :attribute falls outside the validated compilation index list.' => ':attribute uchun tanlangan variant tekshirilgan kompilyatsiya indekslari roʻyxatidan tashqarida.',
    'The context parameters in :attribute must represent an error-free JSON structure.' => ':attribute ichidagi kontekst parametrlari xatosiz JSON tuzilmasini ifodalashi kerak.',
    'The string stream in :attribute cannot be cleanly deserialized into native data.' => ':attribute ichidagi satr oqimini mahalliy maʼlumotlarga toʻgʻri de-serializatsiya qilib boʻlmaydi.',
    'The value assigned to the :attribute field already exists and violates uniqueness constraints.' => ':attribute maydoniga tayinlangan qiymat allaqachon mavjud va takrorlanmaslik cheklovini buzadi.',

    // Chronological constraints
    'The calendar date format structure in :attribute does not match the template :format.' => ':attribute maydonidagi kalendar sana formati :format shabloniga mos kelmaydi.',
    'The chronological layout match sequence between :attribute and :target failed.' => ':attribute va :target oʻrtasidagi xronologik tartib mosligi muvaffaqiyatsiz tugadi.',
    'The value assigned to the :attribute field must correspond to a standard calendar day configuration.' => ':attribute maydoniga tayinlangan qiymat standart kalendar kuni konfiguratsiyasiga mos kelishi kerak.',
    'The execution block :attribute must result in a valid UNIX epoch timestamp coordinate.' => ':attribute bajarilish bloki haqiqiy UNIX davri vaqt tamgʻasi (timestamp) koordinatasiga olib kelishi kerak.',
    'The specified timeline reference in :attribute must match a 4-digit calendar year index.' => ':attribute ichida koʻrsatilgan vaqt shkalasi havolasi 4 xonali kalendar yili indeksiga mos kelishi kerak.',

    // Server file-system checks
    'The local execution system cannot find a valid directory path target corresponding to :attribute.' => 'Mahalliy tizim :attribute maydoniga mos keladigan haqiqiy katalog yoʻlini topa olmadi.',
    'The system rejected the file extension attached to the target payload parameter :attribute.' => 'Tizim :attribute maqsadli parametriga biriktirilgan fayl kengaytmasini rad etdi.',
    'The localized storage allocation route configured in :attribute is not an active target file.' => ':attribute ichida sozlangan mahalliylashtirilgan saqlash yoʻnalishi faol maqsadli fayl emas.',
    'The system lacks the structural file-system permission assignments to read :attribute.' => 'Tizimda :attribute faylini oʻqish uchun fayl tizimi ruxsatnomalari yetarli emas.',
    'The storage space constraints assigned for the file processing payload :attribute exceeded maximum byte values.' => ':attribute faylini qayta ishlash uchun ajratilgan xotira hajmi cheklovlari maksimal bayt qiymatlaridan oshib ketdi.',

    // Geospatial schemas
    'The geographic projection context index coordinate for :attribute must be between -90 and 90 degrees.' => ':attribute uchun geografik proyeksiya kontekstining indeks koordinatasi -90 va 90 daraja orasida boʻlishi kerak.',
    'The geographic projection context index coordinate for :attribute must be between -180 and 180 degrees.' => ':attribute group uchun geografik proyeksiya kontekstining indeks koordinatasi -180 va 180 daraja orasida boʻlishi kerak.',
];