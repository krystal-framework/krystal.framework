<?php

/**
 * This file is part of the Krystal Framework
 * 
 * German translation file with English source keys.
 */

return [
    // Core rules
    'The :attribute field is required.' => 'Das Feld :attribute ist erforderlich.',
    'The :attribute field must be a valid email address.' => 'Das Feld :attribute muss eine gültige E-Mail-Adresse sein.',
    'The :attribute field must be between :min and :max.' => 'Das Feld :attribute muss zwischen :min und :max liegen.',

    // Numeric rules
    'The :attribute field must be an even number.' => 'Das Feld :attribute muss eine gerade Zahl sein.',
    'The :attribute field must be a valid float.' => 'Das Feld :attribute muss eine gültige Gleitkommazahl sein.',
    'The :attribute field must be strictly greater than :min.' => 'Das Feld :attribute muss strikt größer als :min sein.',
    'The :attribute field must be a valid integer.' => 'Das Feld :attribute muss eine gültige Ganzzahl sein.',
    'The :attribute field must be less than or equal to :max.' => 'Das Feld :attribute muss kleiner oder gleich :max sein.',
    'The :attribute field must be strictly less than :max.' => 'Das Feld :attribute muss strikt kleiner als :max sein.',
    'The :attribute field must be a negative number.' => 'Das Feld :attribute muss eine negative Zahl sein.',
    'The :attribute field must be a valid number.' => 'Das Feld :attribute muss eine gültige Nummernbeschreibung sein.',
    'The :attribute field must be an odd number.' => 'Das Feld :attribute muss eine ungerade Zahl sein.',
    'The :attribute field must be a positive number.' => 'Das Feld :attribute muss eine positive Zahl sein.',

    // String and charset rules
    'The :attribute field must match the specified charset :charset.' => 'Das Feld :attribute muss der angegebenen Zeichenkodierung :charset entsprechen.',
    'The :attribute field must contain the substring :needle.' => 'Das Feld :attribute muss die folgende Teilzeichenfolge enthalten: :needle.',
    'The :attribute field must end with the suffix :suffix.' => 'Das Feld :attribute muss mit dem angegebenen Suffix enden: :suffix.',
    'The :attribute field must contain lowercase letters only.' => 'Das Feld :attribute darf nur Kleinbuchstaben enthalten.',
    'The :attribute field must not exceed a maximum length of :max characters.' => 'Das Feld :attribute darf eine maximale Länge von :max Zeichen nicht überschreiten.',
    'The :attribute field must be at least :min characters long.' => 'Das Feld :attribute muss mindestens :min Zeichen lang sein.',
    'The :attribute field content must not contain text characters.' => 'Der Inhalt des Feldes :attribute darf keine Textzeichen enthalten.',
    'The :attribute text string structure must not contain HTML or XML tags.' => 'Die Textzeichenfolgenstruktur :attribute darf keine HTML- oder XML-Tags enthalten.',
    'The :attribute field must start with the prefix :prefix.' => 'Das Feld :attribute muss mit dem angegebenen Präfix beginnen: :prefix.',
    'The :attribute field must contain uppercase letters only.' => 'Das Feld :attribute darf nur Großbuchstaben enthalten.',

    // Presence and state
    'The :attribute field must result in an empty structural state.' => 'Das Feld :attribute muss zu einem leeren strukturellen Zustand führen.',
    'The :attribute field must possess an active data payload state.' => 'Das Feld :attribute muss einen aktiven Datennutzlastzustand besitzen.',
    'The :attribute field must not be equal to the specified restricted input value.' => 'Das Feld :attribute darf nicht gleich dem angegebenen eingeschränkten Eingabewert sein.',

    // Network and domain patterns
    'The entered security captcha value is incorrect.' => 'Der eingegebene Wert für das Sicherheits-Captcha ist nicht korrekt.',
    'The :attribute field must match a valid internet domain path description.' => 'Das Feld :attribute muss einer gültigen Internet-Domainpfad-Beschreibung entsprechen.',
    'The value of the :attribute field must be identical to the specified target.' => 'Der Wert des Feldes :attribute muss mit dem angegebenen Vergleichsziel identisch sein.',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => 'Das Feld :attribute muss sich in eine gültige IPv4- oder IPv6-Infrastrukturadresse auflösen lassen.',
    'The :attribute field must match a valid hardware interface MAC address.' => 'Das Feld :attribute muss einer gültigen MAC-Adresse der Hardwareschnittstelle entsprechen.',
    'The structural evaluation format criterion configured for :attribute is invalid.' => 'Das für :attribute konfigurierte strukturelle Bewertungsformatkriterium ist ungültig.',
    'The :attribute field must resolve to a fully qualified web URL path address.' => 'Das Feld :attribute muss sich in eine vollständig qualifizierte Web-URL-Pfadadresse auflösen lassen.',
    'The :attribute field must consist only of valid structural hexadecimal digits.' => 'Das Feld :attribute darf nur aus gültigen strukturellen Hexadezimalziffern bestehen.',

    // Serialization and unique data mappings
    'The :attribute field must resolve to a valid logical boolean state.' => 'Das Feld :attribute muss sich in einen gültigen logischen booleschen Zustand auflösen lassen.',
    'The passed payload structure in :attribute is not executable by the engine.' => 'Die übergebene Nutzlaststruktur in :attribute ist für die Engine nicht ausführbar.',
    'The selected option for :attribute falls outside the validated compilation index list.' => 'Die ausgewählte Option für :attribute liegt außerhalb der validierten Kompilierungsindexliste.',
    'The context parameters in :attribute must represent an error-free JSON structure.' => 'Die Kontextparameter in :attribute müssen eine fehlerfreie JSON-Struktur darstellen.',
    'The string stream in :attribute cannot be cleanly deserialized into native data.' => 'Der Zeichenfolgenstrom in :attribute kann nicht sauber in native Daten deserialisiert werden.',
    'The value assigned to the :attribute field already exists and violates uniqueness constraints.' => 'Der dem Feld :attribute zugewiesene Wert ist bereits vorhanden und verletzt die Eindeutigkeitsbeschränkungen.',

    // Chronological constraints
    'The calendar date format structure in :attribute does not match the template :format.' => 'Die Kalenderdatenformatstruktur in :attribute entspricht nicht der Vorlage: :format.',
    'The chronological layout match sequence between :attribute and :target failed.' => 'Die chronologische Layout-Abgleichsequenz zwischen :attribute und :target ist fehlgeschlagen.',
    'The value assigned to the :attribute field must correspond to a standard calendar day configuration.' => 'Der dem Feld :attribute zugewiesene Wert muss einer Standard-Monatstagskonfiguration entsprechen.',
    'The execution block :attribute must result in a valid UNIX epoch timestamp coordinate.' => 'Der Ausführungsblock :attribute muss zu einer gültigen UNIX-Epochen-Zeitstempelkoordinate führen.',
    'The specified timeline reference in :attribute must match a 4-digit calendar year index.' => 'Die angegebene Zeitleistenreferenz in :attribute muss mit einem 4-stelligen Kalenderjahr-Index übereinstimmen.',

    // Server file-system checks
    'The local execution system cannot find a valid directory path target corresponding to :attribute.' => 'Das lokale Ausführungssystem kann kein gültiges Verzeichnispfad-Ziel finden, das :attribute entspricht.',
    'The system rejected the file extension attached to the target payload parameter :attribute.' => 'Das System hat die Dateierweiterung abgelehnt, die an den Zielnutzlastparameter :attribute angehängt ist.',
    'The localized storage allocation route configured in :attribute is not an active target file.' => 'Die in :attribute konfigurierte lokalisierte Speicherzuordnungsroute ist keine aktive Zieldatei.',
    'The system lacks the structural file-system permission assignments to read :attribute.' => 'Dem System fehlen die strukturellen Dateisystem-Berechtigungszuordnungen, um :attribute zu lesen.',
    'The storage space constraints assigned for the file processing payload :attribute exceeded maximum byte values.' => 'Die für die Dateiverarbeitungsnutzlast :attribute zugewiesenen Speicherplatzbeschränkungen haben die maximalen Bytewerte überschritten.',

    // Geospatial schemas
    'The geographic projection context index coordinate for :attribute must be between -90 and 90 degrees.' => 'Die geografische Projektionskontext-Indexkoordinate für :attribute muss zwischen -90 und 90 Grad liegen.',
    'The geographic projection context index coordinate for :attribute must be between -180 and 180 degrees.' => 'Die geografische Projektionskontext-Indexkoordinate für :attribute muss zwischen -180 und 180 Grad liegen.',
];