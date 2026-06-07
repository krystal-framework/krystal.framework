<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Hindi translation file with English source keys.
 */

return [
    // Core rules
    'The :attribute field is required.' => ':attribute फ़ील्ड आवश्यक है।',
    'The :attribute field must be a valid email address.' => ':attribute फ़ील्ड एक वैध ईमेल पता होना चाहिए।',
    'The :attribute field must be between :min and :max.' => ':attribute फ़ील्ड :min और :max के बीच होना चाहिए।',

    // Numeric rules
    'The :attribute field must be an even number.' => ':attribute फ़ील्ड एक सम संख्या (even number) होनी चाहिए।',
    'The :attribute field must be a valid float.' => ':attribute फ़ील्ड एक वैध फ़्लोट (float) होना चाहिए।',
    'The :attribute field must be strictly greater than :min.' => ':attribute फ़ील्ड :min से बड़ा होना चाहिए।',
    'The :attribute field must be a valid integer.' => ':attribute फ़ील्ड एक वैध पूर्णांक (integer) होना चाहिए।',
    'The :attribute field must be less than or equal to :max.' => ':attribute फ़ील्ड :max से कम या उसके बराबर होना चाहिए।',
    'The :attribute field must be strictly less than :max.' => ':attribute फ़ील्ड :max से कम होना चाहिए।',
    'The :attribute field must be a negative number.' => ':attribute फ़ील्ड एक ऋणात्मक संख्या (negative number) होनी चाहिए।',
    'The :attribute field must be a valid number.' => ':attribute फ़ील्ड एक वैध संख्या होनी चाहिए।',
    'The :attribute field must be an odd number.' => ':attribute फ़ील्ड एक विषम संख्या (odd number) होनी चाहिए।',
    'The :attribute field must be a positive number.' => ':attribute फ़ील्ड एक धनात्मक संख्या (positive number) होनी चाहिए।',

    // String and charset rules
    'The :attribute field must match the specified charset :charset.' => ':attribute फ़ील्ड निर्दिष्ट वर्णसेट (charset) :charset से मेल खाना चाहिए।',
    'The :attribute field must contain the substring :needle.' => ':attribute फ़ील्ड में उप-स्ट्रिंग (substring) :needle होनी चाहिए।',
    'The :attribute field must end with the suffix :suffix.' => ':attribute फ़ील्ड प्रत्यय (suffix) :suffix के साथ समाप्त होना चाहिए।',
    'The :attribute field must contain lowercase letters only.' => ':attribute फ़ील्ड में केवल छोटे अक्षर होने चाहिए।',
    'The :attribute field must not exceed a maximum length of :max characters.' => ':attribute फ़ील्ड की लंबाई :max वर्णों (characters) से अधिक नहीं होनी चाहिए।',
    'The :attribute field must be at least :min characters long.' => ':attribute फ़ील्ड कम से कम :min वर्ण लंबा होना चाहिए।',
    'The :attribute field content must not contain text characters.' => ':attribute फ़ील्ड की सामग्री में टेक्स्ट वर्ण नहीं होने चाहिए।',
    'The :attribute text string structure must not contain HTML or XML tags.' => ':attribute टेक्स्ट स्ट्रिंग संरचना में HTML या XML टैग नहीं होने चाहिए।',
    'The :attribute field must start with the prefix :prefix.' => ':attribute फ़ील्ड उपसर्ग (prefix) :prefix के साथ शुरू होना चाहिए।',
    'The :attribute field must contain uppercase letters only.' => ':attribute फ़ील्ड में केवल बड़े अक्षर होने चाहिए।',

    // Presence and state
    'The :attribute field must result in an empty structural state.' => ':attribute फ़ील्ड का परिणाम एक खाली संरचनात्मक स्थिति होना चाहिए।',
    'The :attribute field must possess an active data payload state.' => ':attribute फ़ील्ड में एक सक्रिय डेटा पेलोड स्थिति होनी चाहिए।',
    'The :attribute field must not be equal to the specified restricted input value.' => ':attribute फ़ील्ड निर्दिष्ट प्रतिबंधित इनपुट मान के बराबर नहीं होना चाहिए।',

    // Network and domain patterns
    'The entered security captcha value is incorrect.' => 'दर्ज किया गया सुरक्षा कैप्चा मान गलत है।',
    'The :attribute field must match a valid internet domain path description.' => ':attribute फ़ील्ड एक वैध इंटरनेट डोमेन पथ विवरण से मेल खाना चाहिए।',
    'The value of the :attribute field must be identical to the specified target.' => ':attribute फ़ील्ड का मान निर्दिष्ट लक्ष्य के समान होना चाहिए।',
    'The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address.' => ':attribute फ़ील्ड एक वैध IPv4 या IPv6 इंफ्रास्ट्रक्चर पते में रिज़ॉल्व होना चाहिए।',
    'The :attribute field must match a valid hardware interface MAC address.' => ':attribute फ़ील्ड एक वैध हार्डवेयर इंटरफ़ेस मैक (MAC) पते से मेल खाना चाहिए।',
    'The structural evaluation format criterion configured for :attribute is invalid.' => ':attribute के लिए कॉन्फ़िगर किया गया संरचनात्मक मूल्यांकन प्रारूप मानदंड अमान्य है।',
    'The :attribute field must resolve to a fully qualified web URL path address.' => ':attribute फ़ील्ड एक पूर्ण योग्य वेब URL पथ पते में रिज़ॉल्व होना चाहिए।',
    'The :attribute field must consist only of valid structural hexadecimal digits.' => ':attribute फ़ील्ड में केवल वैध संरचनात्मक हेक्साडेसिमल अंक होने चाहिए।',

    // Serialization and unique data mappings
    'The :attribute field must resolve to a valid logical boolean state.' => ':attribute फ़ील्ड एक वैध तार्किक बूलियन स्थिति में रिज़ॉल्व होना चाहिए।',
    'The passed payload structure in :attribute is not executable by the engine.' => ':attribute में पास की गई पेलोड संरचना इंजन द्वारा निष्पादन योग्य नहीं है।',
    'The selected option for :attribute falls outside the validated compilation index list.' => ':attribute के लिए चुना गया विकल्प मान्य संकलन अनुक्रमणिका सूची से बाहर है।',
    'The context parameters in :attribute must represent an error-free JSON structure.' => ':attribute में संदर्भ पैरामीटर एक त्रुटि रहित JSON संरचना का प्रतिनिधित्व करने चाहिए।',
    'The string stream in :attribute cannot be cleanly deserialized into native data.' => ':attribute में स्ट्रिंग स्ट्रीम को मूल डेटा में साफ़ रूप से डीसीरियलाइज़ नहीं किया जा सकता है।',
    'The value assigned to the :attribute field already exists and violates uniqueness constraints.' => ':attribute फ़ील्ड को दिया गया मान पहले से मौजूद है और विशिष्टता प्रतिबंधों का उल्लंघन करता है।',

    // Chronological constraints
    'The calendar date format structure in :attribute does not match the template :format.' => ':attribute में कैलेंडर दिनांक प्रारूप संरचना टेम्पलेट :format से मेल नहीं खाती है।',
    'The chronological layout match sequence between :attribute and :target failed.' => ':attribute और :target के बीच कालानुक्रमिक लेआउट मिलान अनुक्रम विफल रहा।',
    'The value assigned to the :attribute field must correspond to a standard calendar day configuration.' => ':attribute फ़ील्ड को दिया गया मान एक मानक कैलेंडर दिन कॉन्फ़िगरेशन के अनुरूप होना चाहिए।',
    'The execution block :attribute must result in a valid UNIX epoch timestamp coordinate.' => 'निष्पादन ब्लॉक :attribute का परिणाम एक वैध UNIX युग टाइमस्टैम्प निर्देशांक होना चाहिए।',
    'The specified timeline reference in :attribute must match a 4-digit calendar year index.' => ':attribute में निर्दिष्ट समयरेखा संदर्भ 4-अंकीय कैलेंडर वर्ष अनुक्रमणिका से मेल खाना चाहिए।',

    // Server file-system checks
    'The local execution system cannot find a valid directory path target corresponding to :attribute.' => 'स्थानीय निष्पादन प्रणाली को :attribute के अनुरूप एक वैध निर्देशिका पथ लक्ष्य नहीं मिल सकता है।',
    'The system rejected the file extension attached to the target payload parameter :attribute.' => 'प्रणाली ने लक्ष्य पेलोड पैरामीटर :attribute से जुड़े फ़ाइल एक्सटेंशन को अस्वीकार कर दिया।',
    'The localized storage allocation route configured in :attribute is not an active target file.' => ':attribute में कॉन्फ़िगर किया गया स्थानीयकृत संग्रहण आवंटन मार्ग एक सक्रिय लक्ष्य फ़ाइल नहीं है।',
    'The system lacks the structural file-system permission assignments to read :attribute.' => 'प्रणाली में :attribute को पढ़ने के लिए संरचनात्मक फ़ाइल-प्रणाली अनुमति असाइनमेंट की कमी है।',
    'The storage space constraints assigned for the file processing payload :attribute exceeded maximum byte values.' => 'फ़ाइल प्रोसेसिंग पेलोड :attribute के लिए सौंपे गए स्टोरेज स्पेस प्रतिबंध अधिकतम बाइट मानों से अधिक हो गए हैं।',

    // Geospatial schemas
    'The geographic projection context index coordinate for :attribute must be between -90 and 90 degrees.' => ':attribute के लिए भौगोलिक प्रक्षेपण संदर्भ अनुक्रमणिका निर्देशांक -90 और 90 डिग्री के बीच होना चाहिए।',
    'The geographic projection context index coordinate for :attribute must be between -180 and 180 degrees.' => ':attribute के लिए भौगोलिक प्रक्षेपण संदर्भ अनुक्रमणिका निर्देशांक -180 और 180 डिग्री के बीच होना चाहिए।',
];