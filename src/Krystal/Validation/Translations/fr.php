<?php

/**
 * This file is part of the Krystal Framework
 * 
 * French translation file with English source keys.
 */

return [
    // Core framework rules
    'The :attribute is required.' => 'Le champ :attribute est obligatoire.',
    'The :attribute must be a valid email address.' => 'Le champ :attribute doit être une adresse e-mail valide.',
    'The :attribute must be between :min and :max.' => 'Le champ :attribute doit être entre :min et :max.',

    // Expanded numeric rules
    'The :attribute must be an even number.' => 'Le champ :attribute doit être un nombre pair.',
    'The :attribute must be a valid floating-point number.' => 'Le champ :attribute doit être un nombre à virgule flottante valide.',
    'The :attribute must be strictly greater than :min.' => 'Le champ :attribute doit être strictement supérieur à :min.',
    'The :attribute must be a valid integer.' => 'Le champ :attribute doit être un nombre entier valide.',
    'The :attribute must be less than or equal to :max.' => 'Le champ :attribute doit être inférieur ou égal à :max.',
    'The :attribute must be strictly less than :max.' => 'Le champ :attribute doit être strictement inférieur à :max.',
    'The :attribute must be a negative number.' => 'Le champ :attribute doit être un nombre négatif.',
    'The :attribute must be a valid number description.' => 'Le champ :attribute doit être un nombre valide.',
    'The :attribute must be an odd number.' => 'Le champ :attribute doit être un nombre impair.',
    'The :attribute must be a positive number.' => 'Le champ :attribute doit être un nombre positif.',

    // Expanded string and charset rules
    'The :attribute must conform to the specified :charset character encoding.' => 'Le champ :attribute doit correspondre au jeu de caractères spécifié :charset.',
    'The :attribute must contain the sub-string phrase: :needle.' => 'Le champ :attribute doit contenir la sous-chaîne :needle.',
    'The :attribute must end with the specified suffix: :suffix.' => 'Le champ :attribute doit se terminer par le suffixe :suffix.',
    'The :attribute must contain lowercase characters only.' => 'Le champ :attribute doit contenir uniquement des lettres minuscules.',
    'The :attribute must not exceed a maximum length of :max characters.' => 'Le champ :attribute ne doit pas dépasser une longueur maximale de :max caractères.',
    'The :attribute must be at least :min characters.' => 'Le champ :attribute doit compter au moins :min caractères.',
    'The :attribute field payload must not contain any text characters.' => 'Le contenu du champ :attribute ne doit pas contenir de caractères de texte.',
    'The :attribute text string structure cannot contain HTML or XML tags.' => 'La structure de la chaîne de texte :attribute ne doit pas contenir de balises HTML ou XML.',
    'The :attribute must start with the specified prefix: :prefix.' => 'Le champ :attribute doit commencer par le préfixe :prefix.',
    'The :attribute must contain uppercase characters only.' => 'Le champ :attribute doit contenir uniquement des lettres majuscules.',

    // Presence and state
    'The :attribute must evaluate to an empty structural state.' => 'Le champ :attribute doit donner un état structurel vide.',
    'The :attribute field must possess an active data state payload.' => 'Le champ :attribute doit posséder un état de charge utile de données actif.',
    'The :attribute cannot equal the specified restricted entry option value.' => 'Le champ :attribute ne doit pas être égal à la valeur d\'entrée restreinte spécifiée.',

    // Network, framework formats, and token patterns
    'The verification security captcha entry input value is incorrect.' => 'La valeur du captcha de sécurité saisie est incorrecte.',
    'The :attribute must match a valid network internet domain path description.' => 'Le champ :attribute doit correspondre à une description de chemin de domaine internet valide.',
    'The :attribute field value must be identical to the specified comparison target.' => 'La valeur du champ :attribute doit être identique à la cible spécifiée.',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => 'Le champ :attribute doit se résoudre en une adresse d\'infrastructure IPv4 ou IPv6 valide.',
    'The :attribute must match a valid hardware interface MAC address specification.' => 'Le champ :attribute doit correspondre à une adresse MAC d\'interface matérielle valide.',
    'The structural evaluation format criteria configured for :attribute is invalid.' => 'Le critère de format d\'évaluation structurelle configuré pour :attribute est invalide.',
    'The :attribute must resolve to a fully qualified web URL path address.' => 'Le champ :attribute doit se résoudre en une adresse de chemin URL web entièrement qualifiée.',
    'The :attribute can consist of valid structural hexadecimal digits only.' => 'Le champ :attribute doit être composé uniquement de chiffres hexadécimaux structurels valides.',

    // Complex runtime data and serialization mappings
    'The :attribute field must resolve to a valid logical boolean state wrapper.' => 'Le champ :attribute doit se résoudre en un état booléen logique valide.',
    'The passed payload structure inside :attribute is not executable by the engine.' => 'La structure de charge utile transmise dans :attribute n\'est pas exécutable par le moteur.',
    'The selected :attribute choice is outside the validated compilation index list.' => 'L\'option sélectionnée pour :attribute se trouve en dehors de la liste d\'index de compilation validée.',
    'The context parameters inside :attribute must constitute an un-broken JSON structure.' => 'Les paramètres de contexte de :attribute doivent représenter une structure JSON sans erreur.',
    'The string stream in :attribute cannot be cleanly un-serialized into native data.' => 'Le flux de chaîne dans :attribute ne peut pas être désérialisé proprement en données natives.',
    'The value assigned to :attribute is duplicated and violates uniqueness constraints.' => 'La valeur attribuée au champ :attribute existe déjà et viole les contraintes d\'unicité.',

    // Chronological constraints
    'The calendar data format structure in :attribute does not match template: :format.' => 'La structure du format de date de calendrier dans :attribute ne correspond pas au modèle :format.',
    'The chronological layout matching sequence between :attribute and :target failed.' => 'La séquence de correspondance de la disposition chronologique entre :attribute et :target a échoué.',
    'The value assigned to :attribute must represent a standard monthly day configuration.' => 'La valeur attribuée au champ :attribute doit correspondre à une configuration de jour de calendrier standard.',
    'The execution block :attribute must evaluate to a valid UNIX epoch timestamp coordinate.' => 'Le bloc d\'exécution :attribute doit donner une coordonnée de horodatage d\'époque UNIX valide.',
    'The provided timeline reference inside :attribute must match a 4-digit calendar year index.' => 'La référence de la chronologie spécifiée dans :attribute doit correspondre à un index d\'année de calendrier à 4 chiffres.',

    // Operational server file-system checks
    'The local execution system cannot locate a valid directory path target matching :attribute.' => 'Le système d\'exécution local ne trouve pas de cible de chemin de répertoire valide correspondant à :attribute.',
    'The system rejected the file extension attached to the target payload parameter: :attribute.' => 'Le système a rejeté l\'extension de fichier jointe au paramètre de charge utile cible :attribute.',
    'The localized storage map route configured inside :attribute is not an active target file.' => 'La route d\'allocation de stockage localisée configurée dans :attribute n\'est pas un fichier cible actif.',
    'The system does not possess structural file system authorization maps to read :attribute.' => 'Le système ne dispose pas des attributions de permissions de système de fichiers structurels pour lire :attribute.',
    'The space configuration block constraints allocated to file processing payload :attribute exceeded maximum byte values.' => 'Les contraintes d\'espace de stockage attribuées à la charge utile de traitement de fichier :attribute ont dépassé les valeurs maximales en octets.',
    'The :attribute must be an image (e.g., JPG, PNG, WEBP).' => 'Le :attribute doit être une image (par ex. JPG, PNG, WEBP).',

    // Geospatial coordinate checking schemas
    'The geographical projection context index coordinate for :attribute must fall between -90 and 90 degrees.' => 'La coordonnée d\'index de contexte de projection géographique pour :attribute doit être comprise entre -90 et 90 degrés.',
    'The geographical projection context index coordinate for :attribute must fall between -180 and 180 degrees.' => 'La coordonnée d\'index de contexte de projection géographique pour :attribute doit être comprise entre -180 et 180 degrés.',
];