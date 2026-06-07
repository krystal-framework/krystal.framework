<?php

/**
 * This file is part of the Krystal Framework
 * 
 * German translation file with English source keys.
 */

return [
    // Core framework rules
    'The :attribute is required.' => 'Das Feld :attribute ist erforderlich.',
    'The :attribute must be a valid email address.' => 'Das Feld :attribute muss eine gültige E-Mail-Adresse sein.',
    'The :attribute must be between :min and :max.' => 'Das Feld :attribute muss zwischen :min und :max liegen.',

    // Expanded numeric rules
    'The :attribute must be an even number.' => 'Das Feld :attribute muss eine gerade Zahl sein.',
    'The :attribute must be a valid floating-point number.' => 'Das Feld :attribute muss eine gültige Gleitkommazahl sein.',
    'The :attribute must be strictly greater than :min.' => 'Das Feld :attribute muss strikt größer als :min sein.',
    'The :attribute must be a valid integer.' => 'Das Feld :attribute muss eine gültige Ganzzahl sein.',
    'The :attribute must be less than or equal to :max.' => 'Das Feld :attribute muss kleiner oder gleich :max sein.',
    'The :attribute must be strictly less than :max.' => 'Das Feld :attribute muss strikt kleiner als :max sein.',
    'The :attribute must be a negative number.' => 'Das Feld :attribute muss eine negative Zahl sein.',
    'The :attribute must be a valid number description.' => 'Das Feld :attribute muss eine gültige Nummernbeschreibung sein.',
    'The :attribute must be an odd number.' => 'Das Feld :attribute muss eine ungerade Zahl sein.',
    'The :attribute must be a positive number.' => 'Das Feld :attribute muss eine positive Zahl sein.',

    // Expanded string and charset rules
    'The :attribute must conform to the specified :charset character encoding.' => 'Das Feld :attribute muss der angegebenen Zeichenkodierung :charset entsprechen.',
    'The :attribute must contain the sub-string phrase: :needle.' => 'Das Feld :attribute muss die folgende Teilzeichenfolge enthalten: :needle.',
    'The :attribute must end with the specified suffix: :suffix.' => 'Das Feld :attribute muss mit dem angegebenen Suffix enden: :suffix.',
    'The :attribute must contain lowercase characters only.' => 'Das Feld :attribute darf nur Kleinbuchstaben enthalten.',
    'The :attribute must not exceed a maximum length of :max characters.' => 'Das Feld :attribute darf eine maximale Länge von :max Zeichen nicht überschreiten.',
    'The :attribute must be at least :min characters.' => 'Das Feld :attribute muss mindestens :min Zeichen lang sein.',
    'The :attribute field payload must not contain any text characters.' => 'Der Inhalt des Feldes :attribute darf keine Textzeichen enthalten.',
    'The :attribute text string structure cannot contain HTML or XML tags.' => 'Die Textzeichenfolgenstruktur :attribute darf keine HTML- oder XML-Tags enthalten.',
    'The :attribute must start with the specified prefix: :prefix.' => 'Das Feld :attribute muss mit dem angegebenen Präfix beginnen: :prefix.',
    'The :attribute must contain uppercase characters only.' => 'Das Feld :attribute darf nur Großbuchstaben enthalten.',

    // Presence and state
    'The :attribute must evaluate to an empty structural state.' => 'Das Feld :attribute muss zu einem leeren strukturellen Zustand führen.',
    'The :attribute field must possess an active data state payload.' => 'Das Feld :attribute muss einen aktiven Datennutzlastzustand besitzen.',
    'The :attribute cannot equal the specified restricted entry option value.' => 'Das Feld :attribute darf nicht gleich dem angegebenen eingeschränkten Eingabewert sein.',

    // Network, framework formats, and token patterns
    'The verification security captcha entry input value is incorrect.' => 'Der eingegebene Wert für das Sicherheits-Captcha ist nicht korrekt.',
    'The :attribute must match a valid network internet domain path description.' => 'Das Feld :attribute muss einer gültigen Internet-Domainpfad-Beschreibung entsprechen.',
    'The :attribute field value must be identical to the specified comparison target.' => 'Der Wert des Feldes :attribute muss mit dem angegebenen Vergleichsziel identisch sein.',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => 'Das Feld :attribute muss sich in eine gültige IPv4- oder IPv6-Infrastrukturadresse auflösen lassen.',
    'The :attribute must match a valid hardware interface MAC address specification.' => 'Das Feld :attribute muss einer gültigen MAC-Adresse der Hardwareschnittstelle entsprechen.',
    'The structural evaluation format criteria configured for :attribute is invalid.' => 'Das für :attribute konfigurierte strukturelle Bewertungsformatkriterium ist ungültig.',
    'The :attribute must resolve to a fully qualified web URL path address.' => 'Das Feld :attribute muss sich in eine vollständig qualifizierte Web-URL-Pfadadresse auflösen lassen.',
    'The :attribute can consist of valid structural hexadecimal digits only.' => 'Das Feld :attribute darf nur aus gültigen strukturellen Hexadezimalziffern bestehen.',

    // Complex runtime data and serialization mappings
    'The :attribute field must resolve to a valid logical boolean state wrapper.' => 'Das Feld :attribute muss sich in einen gültigen logischen booleschen Zustand auflösen lassen.',
    'The passed payload structure inside :attribute is not executable by the engine.' => 'Die übergebene Nutzlaststruktur in :attribute ist für die Engine nicht ausführbar.',
    'The selected :attribute choice is outside the validated compilation index list.' => 'Die ausgewählte Option für :attribute liegt außerhalb der validierten Kompilierungsindexliste.',
    'The context parameters inside :attribute must constitute an un-broken JSON structure.' => 'Die Kontextparameter in :attribute müssen eine fehlerfreie JSON-Struktur darstellen.',
    'The string stream in :attribute cannot be cleanly un-serialized into native data.' => 'Der Zeichenfolgenstrom in :attribute kann nicht sauber in native Daten deserialisiert werden.',
    'The value assigned to :attribute is duplicated and violates uniqueness constraints.' => 'Der dem Feld :attribute zugewiesene Wert ist bereits vorhanden und verletzt die Eindeutigkeitsbeschränkungen.',

    // Chronological constraints
    'The calendar data format structure in :attribute does not match template: :format.' => 'Die Kalenderdatenformatstruktur in :attribute entspricht nicht der Vorlage: :format.',
    'The chronological layout matching sequence between :attribute and :target failed.' => 'Die chronologische Layout-Abgleichsequenz zwischen :attribute und :target ist fehlgeschlagen.',
    'The value assigned to :attribute must represent a standard monthly day configuration.' => 'Der dem Feld :attribute zugewiesene Wert muss einer Standard-Monatstagskonfiguration entsprechen.',
    'The execution block :attribute must evaluate to a valid UNIX epoch timestamp coordinate.' => 'Der Ausführungsblock :attribute muss zu einer gültigen UNIX-Epochen-Zeitstempelkoordinate führen.',
    'The provided timeline reference inside :attribute must match a 4-digit calendar year index.' => 'Die angegebene Zeitleistenreferenz in :attribute muss mit einem 4-stelligen Kalenderjahr-Index übereinstimmen.',

    // Operational server file-system checks
    'The local execution system cannot locate a valid directory path target matching :attribute.' => 'Das lokale Ausführungssystem kann kein gültiges Verzeichnispfad-Ziel finden, das :attribute entspricht.',
    'The system rejected the file extension attached to the target payload parameter: :attribute.' => 'Das System hat die Dateierweiterung abgelehnt, die an den Zielnutzlastparameter :attribute angehängt ist.',
    'The localized storage map route configured inside :attribute is not an active target file.' => 'Die in :attribute konfigurierte lokalisierte Speicherzuordnungsroute ist keine aktive Zieldatei.',
    'The system does not possess structural file system authorization maps to read :attribute.' => 'Dem System fehlen die strukturellen Dateisystem-Berechtigungszuordnungen, um :attribute zu lesen.',
    'The space configuration block constraints allocated to file processing payload :attribute exceeded maximum byte values.' => 'Die für die Dateiverarbeitungsnutzlast :attribute zugewiesenen Speicherplatzbeschränkungen haben die maximalen Bytewerte überschritten.',

    // Geospatial coordinate checking schemas
    'The geographical projection context index coordinate for :attribute must fall between -90 and 90 degrees.' => 'Die geografische Projektionskontext-Indexkoordinate für :attribute muss zwischen -90 und 90 Grad liegen.',
    'The geographical projection context index coordinate for :attribute must fall between -180 and 180 degrees.' => 'Die geografische Projektionskontext-Indexkoordinate für :attribute muss zwischen -180 und 180 Grad liegen.',
];