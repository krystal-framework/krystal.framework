<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Kazakh translation file with English source keys.
 */

return [
    // Core rules
    'The :attribute field is required.' => ':attribute өрісі міндетті түрде толтырылуы тиіс.',
    'The :attribute field must be a valid email address.' => ':attribute өрісі жарамды электрондық пошта мекенжайы болуы керек.',
    'The :attribute field must be between :min and :max.' => ':attribute өрісі :min және :max аралығында болуы керек.',

    // Numeric rules
    'The :attribute field must be an even number.' => ':attribute өрісі жұп сан болуы керек.',
    'The :attribute field must be a valid float.' => ':attribute өрісі жарамды бөлшек сан (float) болуы керек.',
    'The :attribute field must be strictly greater than :min.' => ':attribute өрісі :min мәнінен қатаң үлкен болуы керек.',
    'The :attribute field must be a valid integer.' => ':attribute өрісі жарамды бүтін сан болуы керек.',
    'The :attribute field must be less than or equal to :max.' => ':attribute өрісі :max мәнінен кіші немесе оған тең болуы керек.',
    'The :attribute field must be strictly less than :max.' => ':attribute өрісі :max мәнінен қатаң кіші болуы керек.',
    'The :attribute field must be a negative number.' => ':attribute өрісі теріс сан болуы керек.',
    'The :attribute field must be a valid number.' => ':attribute өрісі жарамды сан болуы керек.',
    'The :attribute field must be an odd number.' => ':attribute өрісі тақ сан болуы керек.',
    'The :attribute field must be a positive number.' => ':attribute өрісі оң сан болуы керек.',

    // String and charset rules
    'The :attribute field must match the specified charset :charset.' => ':attribute өрісі көрсетілген :charset таңбалар жиынтығына сәйкес келуі керек.',
    'The :attribute field must contain the substring :needle.' => ':attribute өрісінде :needle ішкі жолы болуы керек.',
    'The :attribute field must end with the suffix :suffix.' => ':attribute өрісі :suffix жұрнағымен аяқталуы керек.',
    'The :attribute field must contain lowercase letters only.' => ':attribute өрісінде тек кіші әріптер болуы керек.',
    'The :attribute field must not exceed a maximum length of :max characters.' => ':attribute өрісінің ұзындығы :max таңбадан аспауы керек.',
    'The :attribute field must be at least :min characters long.' => ':attribute өрісі кемінде :min таңбадан тұруы керек.',
    'The :attribute field content must not contain text characters.' => ':attribute өрісінің мазмұнында мәтіндік таңбалар болмауы керек.',
    'The :attribute text string structure must not contain HTML or XML tags.' => ':attribute мәтіндік жолының құрылымында HTML немесе XML тегтері болмауы керек.',
    'The :attribute field must start with the prefix :prefix.' => ':attribute өрісі :prefix префиксімен басталуы керек.',
    'The :attribute field must contain uppercase letters only.' => ':attribute өрісінде тек бас әріптер болуы керек.',

    // Presence and state
    'The :attribute field must result in an empty structural state.' => ':attribute өрісі бос құрылымдық күйде болуы керек.',
    'The :attribute field must possess an active data payload state.' => ':attribute өрісінде белсенді деректер күйі болуы керек.',
    'The :attribute field must not be equal to the specified restricted input value.' => ':attribute өрісі көрсетілген шектелген енгізу мәніне тең болмауы керек.',

    // Network and domain patterns
    'The entered security captcha value is incorrect.' => 'Енгізілген қауіпсіздік капчасының мәні қате.',
    'The :attribute field must match a valid internet domain path description.' => ':attribute өрісі жарамды интернет доменінің жолына sәйкес келуі керек.',
    'The value of the :attribute field must be identical to the specified target.' => ':attribute өрісінің мәні көрсетілген нысанамен (:target) бірдей болуы керек.',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => ':attribute өрісі жарамды IPv4 немесе IPv6 инфрақұрылымдық мекенжайына шешілуі керек.',
    'The :attribute field must match a valid hardware interface MAC address.' => ':attribute өрісі жарамды аппараттық интерфейстің MAC-мекенжайына сәйкес келуі керек.',
    'The structural evaluation format criterion configured for :attribute is invalid.' => ':attribute үшін теңшелген құрылымдық бағалау форматының критерийі жарамсыз.',
    'The :attribute field must resolve to a fully qualified web URL path address.' => ':attribute өрісі толық жарамды веб URL мекенжайына шешілуі керек.',
    'The :attribute field must consist only of valid structural hexadecimal digits.' => ':attribute өрісі тек жарамды құрылымдық он алтылық цифрлардан тұруы керек.',

    // Serialization and unique data mappings
    'The :attribute field must resolve to a valid logical boolean state.' => ':attribute өрісі жарамды логикалық бульдік (boolean) күйге шешілуі керек.',
    'The passed payload structure in :attribute is not executable by the engine.' => ':attribute ішіндегі берілген құрылымды жүйе орындай алмайды.',
    'The selected option for :attribute falls outside the validated compilation index list.' => ':attribute үшін таңдалған опция тексерілген компиляция индекстерінің тізімінен тыс.',
    'The context parameters in :attribute must represent an error-free JSON structure.' => ':attribute ішіндегі контекст параметрлері қатесіз JSON құрылымын көрсетуі керек.',
    'The string stream in :attribute cannot be cleanly deserialized into native data.' => ':attribute ішіндегі жол ағынын ішкі деректерге таза десериализациялау мүмкін емес.',
    'The value assigned to the :attribute field already exists and violates uniqueness constraints.' => ':attribute өрісіне берілген мән бұрыннан бар және бірегейлік шектеуін бұзады.',

    // Chronological constraints
    'The calendar date format structure in :attribute does not match the template :format.' => ':attribute өрісіндегі күнтізбелік күн форматының құрылымы :format үлгісіне сәйкес келмейді.',
    'The chronological layout match sequence between :attribute and :target failed.' => ':attribute және :target арасындағы хронологиялық сәйкестік реттілігі сәтсіз аяқталды.',
    'The value assigned to the :attribute field must correspond to a standard calendar day configuration.' => ':attribute өрісіне берілген мән стандартты күнтізбелік күн конфигурациясына сәйкес келуі керек.',
    'The execution block :attribute must result in a valid UNIX epoch timestamp coordinate.' => ':attribute орындау блогы жарамды UNIX дәуірінің уақыт белгісінің (timestamp) координатасына әкелуі керек.',
    'The specified timeline reference in :attribute must match a 4-digit calendar year index.' => ':attribute ішінде көрсетілген уақыт шкаласының сілтемесі 4 таңбалы күнтізбелік жыл индексіне сәйкес келуі керек.',

    // Server file-system checks
    'The local execution system cannot find a valid directory path target corresponding to :attribute.' => 'Жергілікті орындау жүйесі :attribute өрісіне сәйкес келетін жарамды каталог жолын таба алмайды.',
    'The system rejected the file extension attached to the target payload parameter :attribute.' => 'Жүйе :attribute нысаналы параметріне тіркелген файл кеңейтімін қабылдамады.',
    'The localized storage allocation route configured in :attribute is not an active target file.' => ':attribute ішінде теңшелген жергілікті сақтау бағыты белсенді нысаналы файл емес.',
    'The system lacks the structural file-system permission assignments to read :attribute.' => 'Жүйеде :attribute файлын оқу үшін құрылымдық файлдық жүйе рұқсаттары жеткіліксіз.',
    'The storage space constraints assigned for the file processing payload :attribute exceeded maximum byte values.' => ':attribute файлын өңдеу үшін бөлінген жад көлемінің шектеулері максималды байт мәндерінен асып кетті.',

    // Geospatial schemas
    'The geographic projection context index coordinate for :attribute must be between -90 and 90 degrees.' => ':attribute үшін географиялық проекция контексінің индекс координатасы -90 және 90 градус аралығында болуы керек.',
    'The geographic projection context index coordinate for :attribute must be between -180 and 180 degrees.' => ':attribute үшін географиялық проекция контексінің индекс координатасы -180 және 180 градус аралығында болуы керек.',
];