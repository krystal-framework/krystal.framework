<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Kazakh translation file with English source keys.
 */

return [
    // Core framework rules
    'The :attribute field is required.' => ':attribute өрісі міндетті түрде толтырылуы тиіс.',
    'The :attribute must be a valid email address.' => ':attribute өрісі жарамды электрондық пошта мекенжайы болуы керек.',
    'The :attribute must be between :min and :max.' => ':attribute өрісі :min және :max аралығында болуы керек.',

    // Expanded numeric rules
    'The :attribute must be an even number.' => ':attribute өрісі жұп сан болуы керек.',
    'The :attribute must be a valid floating-point number.' => ':attribute өрісі жарамды бөлшек сан (float) болуы керек.',
    'The :attribute must be strictly greater than :min.' => ':attribute өрісі :min мәнінен қатаң үлкен болуы керек.',
    'The :attribute must be a valid integer.' => ':attribute өрісі жарамды бүтін сан болуы керек.',
    'The :attribute must be less than or equal to :max.' => ':attribute өрісі :max мәнінен кіші немесе оған тең болуы керек.',
    'The :attribute must be strictly less than :max.' => ':attribute өрісі :max мәнінен қатаң кіші болуы керек.',
    'The :attribute must be a negative number.' => ':attribute өрісі теріс сан болуы керек.',
    'The :attribute must be a valid number description.' => ':attribute өрісі жарамды сан болуы керек.',
    'The :attribute must be an odd number.' => ':attribute өрісі тақ сан болуы керек.',
    'The :attribute must be a positive number.' => ':attribute өрісі оң сан болуы керек.',

    // Expanded string and charset rules
    'The :attribute must conform to the specified :charset character encoding.' => ':attribute өрісі көрсетілген :charset таңбалар жиынтығына сәйкес келуі керек.',
    'The :attribute must contain the sub-string phrase: :needle.' => ':attribute өрісінде :needle ішкі жолы болуы керек.',
    'The :attribute must end with the specified suffix: :suffix.' => ':attribute өрісі :suffix жұрнағымен аяқталуы керек.',
    'The :attribute must contain lowercase characters only.' => ':attribute өрісінде тек кіші әріптер болуы керек.',
    'The :attribute must not exceed a maximum length of :max characters.' => ':attribute өрісінің ұзындығы :max таңбадан аспауы керек.',
    'The :attribute must be at least :min characters.' => ':attribute өрісі кемінде :min таңбадан тұруы керек.',
    'The :attribute field payload must not contain any text characters.' => ':attribute өрісінің мазмұнында мәтіндік таңбалар болмауы керек.',
    'The :attribute text string structure cannot contain HTML or XML tags.' => ':attribute мәтіндік жолының құрылымында HTML немесе XML тегтері болмауы керек.',
    'The :attribute must start with the specified prefix: :prefix.' => ':attribute өрісі :prefix префиксімен басталуы керек.',
    'The :attribute must contain uppercase characters only.' => ':attribute өрісінде тек бас әріптер болуы керек.',

    // Presence and state
    'The :attribute must evaluate to an empty structural state.' => ':attribute өрісі бос құрылымдық күйде болуы керек.',
    'The :attribute field must possess an active data state payload.' => ':attribute өрісінде белсенді деректер күйі болуы керек.',
    'The :attribute cannot equal the specified restricted entry option value.' => ':attribute өрісі көрсетілген шектелген енгізу мәніне тең болмауы керек.',

    // Network, framework formats, and token patterns
    'The verification security captcha entry input value is incorrect.' => 'Енгізілген қауіпсіздік капчасының мәні қате.',
    'The :attribute must match a valid network internet domain path description.' => ':attribute өрісі жарамды интернет доменінің жолына сәйкес келуі керек.',
    'The :attribute field value must be identical to the specified comparison target.' => ':attribute өрісінің мәні көрсетілген нысанамен (:target) бірдей болуы керек.',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => ':attribute өрісі жарамды IPv4 немесе IPv6 инфрақұрылымдық мекенжайына шешілуі керек.',
    'The :attribute must match a valid hardware interface MAC address specification.' => ':attribute өрісі жарамды аппараттық интерфейстің MAC-мекенжайына сәйкес келуі керек.',
    'The structural evaluation format criteria configured for :attribute is invalid.' => ':attribute үшін теңшелген құрылымдық бағалау форматының критерийі жарамсыз.',
    'The :attribute must resolve to a fully qualified web URL path address.' => ':attribute өрісі толық жарамды веб URL мекенжайына шешілуі керек.',
    'The :attribute can consist of valid structural hexadecimal digits only.' => ':attribute өрісі тек жарамды құрылымдық он алтылық цифрлардан тұруы керек.',

    // Complex runtime data and serialization mappings
    'The :attribute field must resolve to a valid logical boolean state wrapper.' => ':attribute өрісі жарамды логикалық бульдік (boolean) күйге шешілуі керек.',
    'The passed payload structure inside :attribute is not executable by the engine.' => ':attribute ішіндегі берілген құрылымды жүйе орындай алмайды.',
    'The selected :attribute choice is outside the validated compilation index list.' => ':attribute үшін таңдалған опция тексерілген компиляция индекстерінің тізімінен тыс.',
    'The context parameters inside :attribute must constitute an un-broken JSON structure.' => ':attribute ішіндегі контекст параметрлері қатесіз JSON құрылымын көрсетуі керек.',
    'The string stream in :attribute cannot be cleanly un-serialized into native data.' => ':attribute ішіндегі жол ағынын ішкі деректерге таза десериализациялау мүмкін емес.',
    'The value assigned to :attribute is duplicated and violates uniqueness constraints.' => ':attribute өрісіне берілген мән бұрыннан бар және бірегейлік шектеуін бұзады.',

    // Chronological constraints
    'The calendar data format structure in :attribute does not match template: :format.' => ':attribute өрісіндегі күнтізбелік күн форматының құрылымы :format үлгісіне сәйкес келмейді.',
    'The chronological layout matching sequence between :attribute and :target failed.' => ':attribute және :target арасындағы хронологиялық сәйкестік реттілігі сәтсіз аяқталды.',
    'The value assigned to :attribute must represent a standard monthly day configuration.' => ':attribute өрісіне берілген мән стандартты күнтізбелік күн конфигурациясына сәйкес келуі керек.',
    'The execution block :attribute must evaluate to a valid UNIX epoch timestamp coordinate.' => ':attribute орындау блогы жарамды UNIX дәуірінің уақыт белгісінің (timestamp) координатасына әкелуі керек.',
    'The provided timeline reference inside :attribute must match a 4-digit calendar year index.' => ':attribute ішінде көрсетілген уақыт шкаласының сілтемесі 4 таңбалы күнтізбелік жыл индексіне сәйкес келуі керек.',

    // Operational server file-system checks
    'The local execution system cannot locate a valid directory path target matching :attribute.' => 'Жергілікті орындау жүйесі :attribute өрісіне сәйкес келетін жарамды каталог жолын таба алмайды.',
    'The system rejected the file extension attached to the target payload parameter: :attribute.' => 'Жүйе :attribute нысаналы параметріне тіркелген файл кеңейтімін қабылдамады.',
    'The localized storage map route configured inside :attribute is not an active target file.' => ':attribute ішінде теңшелген жергілікті сақтау бағыты белсенді нысаналы файл емес.',
    'The system does not possess structural file system authorization maps to read :attribute.' => 'Жүйеде :attribute файлын оқу үшін құрылымдық файлдық жүйе рұқсаттары жеткіліксіз.',
    'The space configuration block constraints allocated to file processing payload :attribute exceeded maximum byte values.' => ':attribute файлын өңдеу үшін бөлінген жад көлемінің шектеулері максималды байт мәндерінен асып кетті.',

    // Geospatial coordinate checking schemas
    'The geographical projection context index coordinate for :attribute must fall between -90 and 90 degrees.' => ':attribute үшін географиялық проекция контексінің индекс координатасы -90 және 90 градус аралығында болуы керек.',
    'The geographical projection context index coordinate for :attribute must fall between -180 and 180 degrees.' => ':attribute үшін географиялық проекция контексінің индекс координатасы -180 және 180 градус аралығында болуы керек.',
];