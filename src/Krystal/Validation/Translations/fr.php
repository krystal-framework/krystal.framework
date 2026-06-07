<?php

/**
 * This file is part of the Krystal Framework
 * 
 * French translation file with English source keys.
 */

return [
    // Core rules
    'The :attribute field is required.' => 'Le champ :attribute est obligatoire.',
    'The :attribute field must be a valid email address.' => 'Le champ :attribute doit être une adresse e-mail valide.',
    'The :attribute field must be between :min and :max.' => 'Le champ :attribute doit être entre :min et :max.',

    // Numeric rules
    'The :attribute field must be an even number.' => 'Le champ :attribute doit être un nombre pair.',
    'The :attribute field must be a valid float.' => 'Le champ :attribute doit être un nombre à virgule flottante valide.',
    'The :attribute field must be strictly greater than :min.' => 'Le champ :attribute doit être strictement supérieur à :min.',
    'The :attribute field must be a valid integer.' => 'Le champ :attribute doit être un nombre entier valide.',
    'The :attribute field must be less than or equal to :max.' => 'Le champ :attribute doit être inférieur ou égal à :max.',
    'The :attribute field must be strictly less than :max.' => 'Le champ :attribute doit être strictement inférieur à :max.',
    'The :attribute field must be a negative number.' => 'Le champ :attribute doit être un nombre négatif.',
    'The :attribute field must be a valid number.' => 'Le champ :attribute doit être un nombre valide.',
    'The :attribute field must be an odd number.' => 'Le champ :attribute doit être un nombre impair.',
    'The :attribute field must be a positive number.' => 'Le champ :attribute doit être un nombre positif.',

    // String and charset rules
    'The :attribute field must match the specified charset :charset.' => 'Le champ :attribute doit correspondre au jeu de caractères spécifié :charset.',
    'The :attribute field must contain the substring :needle.' => 'Le champ :attribute doit contenir la sous-chaîne :needle.',
    'The :attribute field must end with the suffix :suffix.' => 'Le champ :attribute doit se terminer par le suffixe :suffix.',
    'The :attribute field must contain lowercase letters only.' => 'Le champ :attribute doit contenir uniquement des lettres minuscules.',
    'The :attribute field must not exceed a maximum length of :max characters.' => 'Le champ :attribute ne doit pas dépasser une longueur maximale de :max caractères.',
    'The :attribute field must be at least :min characters long.' => 'Le champ :attribute doit compter au moins :min caractères.',
    'The :attribute field content must not contain text characters.' => 'Le contenu du champ :attribute ne doit pas contenir de caractères de texte.',
    'The :attribute text string structure must not contain HTML or XML tags.' => 'La structure de la chaîne de texte :attribute ne doit pas contenir de balises HTML ou XML.',
    'The :attribute field must start with the prefix :prefix.' => 'Le champ :attribute doit commencer par le préfixe :prefix.',
    'The :attribute field must contain uppercase letters only.' => 'Le champ :attribute doit contenir uniquement des lettres majuscules.',

    // Presence and state
    'The :attribute field must result in an empty structural state.' => 'Le champ :attribute doit donner un état structurel vide.',
    'The :attribute field must possess an active data payload state.' => 'Le champ :attribute doit posséder un état de charge utile de données actif.',
    'The :attribute field must not be equal to the specified restricted input value.' => 'Le champ :attribute ne doit pas être égal à la valeur d\'entrée restreinte spécifiée.',

    // Network and domain patterns
    'The entered security captcha value is incorrect.' => 'La valeur du captcha de sécurité saisie est incorrecte.',
    'The :attribute field must match a valid internet domain path description.' => 'Le champ :attribute doit correspondre à une description de chemin de domaine internet valide.',
    'The value of the :attribute field must be identical to the specified target.' => 'La valeur du champ :attribute doit être identique à la cible spécifiée.',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => 'Le champ :attribute doit se résoudre en une adresse d\'infrastructure IPv4 ou IPv6 valide.',
    'The :attribute field must match a valid hardware interface MAC address.' => 'Le champ :attribute doit correspondre à une adresse MAC d\'interface matérielle valide.',
    'The structural evaluation format criterion configured for :attribute is invalid.' => 'Le critère de format d\'évaluation structurelle configuré pour :attribute est invalide.',
    'The :attribute field must resolve to a fully qualified web URL path address.' => 'Le champ :attribute doit se résoudre en une adresse de chemin URL web entièrement qualifiée.',
    'The :attribute field must consist only of valid structural hexadecimal digits.' => 'Le champ :attribute doit être composé uniquement de chiffres hexadécimaux structurels valides.',

    // Serialization and unique data mappings
    'The :attribute field must resolve to a valid logical boolean state.' => 'Le champ :attribute doit se résoudre en un état booléen logique valide.',
    'The passed payload structure in :attribute is not executable by the engine.' => 'La structure de charge utile transmise dans :attribute n\'est pas exécutable par le moteur.',
    'The selected option for :attribute falls outside the validated compilation index list.' => 'L\'option sélectionnée pour :attribute se trouve en dehors de la liste d\'index de compilation validée.',
    'The context parameters in :attribute must represent an error-free JSON structure.' => 'Les paramètres de contexte de :attribute doivent représenter une structure JSON sans erreur.',
    'The string stream in :attribute cannot be cleanly deserialized into native data.' => 'Le flux de chaîne dans :attribute ne peut pas être désérialisé proprement en données natives.',
    'The value assigned to the :attribute field already exists and violates uniqueness constraints.' => 'La valeur attribuée au champ :attribute existe déjà et viole les contraintes d\'unicité.',

    // Chronological constraints
    'The calendar date format structure in :attribute does not match the template :format.' => 'La structure du format de date de calendrier dans :attribute ne correspond pas au modèle :format.',
    'The chronological layout match sequence between :attribute and :target failed.' => 'La séquence de correspondance de la disposition chronologique entre :attribute et :target a échoué.',
    'The value assigned to the :attribute field must correspond to a standard calendar day configuration.' => 'La valeur attribuée au champ :attribute doit correspondre à une configuration de jour de calendrier standard.',
    'The execution block :attribute must result in a valid UNIX epoch timestamp coordinate.' => 'Le bloc d\'exécution :attribute doit donner une coordonnée de horodatage d\'époque UNIX valide.',
    'The specified timeline reference in :attribute must match a 4-digit calendar year index.' => 'La référence de la chronologie spécifiée dans :attribute doit correspondre à un index d\'année de calendrier à 4 chiffres.',

    // Server file-system checks
    'The local execution system cannot find a valid directory path target corresponding to :attribute.' => 'Le système d\'exécution local ne trouve pas de cible de chemin de répertoire valide correspondant à :attribute.',
    'The system rejected the file extension attached to the target payload parameter :attribute.' => 'Le système a rejeté l\'extension de fichier jointe au paramètre de charge utile cible :attribute.',
    'The localized storage allocation route configured in :attribute is not an active target file.' => 'La route d\'allocation de stockage localisée configurée dans :attribute n\'est pas un fichier cible actif.',
    'The system lacks the structural file-system permission assignments to read :attribute.' => 'Le système ne dispose pas des attributions de permissions de système de fichiers structurels pour lire :attribute.',
    'The storage space constraints assigned for the file processing payload :attribute exceeded maximum byte values.' => 'Les contraintes d\'espace de stockage attribuées à la charge utile de traitement de fichier :attribute ont dépassé les valeurs maximales en octets.',

    // Geospatial schemas
    'The geographic projection context index coordinate for :attribute must be between -90 and 90 degrees.' => 'La coordonnée d\'index de contexte de projection géographique pour :attribute doit être comprise entre -90 et 90 degrés.',
    'The geographic projection context index coordinate for :attribute must be between -180 and 180 degrees.' => 'La coordonnée d\'index de contexte de projection géographique pour :attribute doit être comprise entre -180 et 180 degrés.',
];