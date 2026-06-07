
Validation
====

Krystal validation is a fast, zero-boilerplate validation library designed for modern web applications and APIs. It completely eliminates messy string-based configurations and complex model setup, giving you a fluid, type-safe way to validate both raw data arrays and file uploads.

## Key features

- Full IDE autocompletion: Chain your validation rules intuitively. Because it uses true method chaining instead of string pipes ('required|min:3'), your IDE catches typos instantly.

- Smart optional gatekeeping: By default, every field is optional. If a field is missing or empty, subsequent rules are skipped automatically — unless you explicitly chain required().

- Native wildcard array traversal: Validating multi-dimensional arrays or repeating form fields requires zero loops. Use simple dot-notation and asterisks (*).

- Automatic HTML form mapping: When nested array elements fail, the engine translates internal dot paths back into perfect HTML bracket notation (team[0][name]) for seamless frontend integration.

- Separated field and file rules: Dedicated rule chains for fields and files prevent accidental type-mismatch bugs when dealing with multipart form uploads.


## Basic usage

Instantiate the validator with your data payload, define your field rules using the fluent builder API, and check the result.

    <?php

    use Krystal\Validation\Validator;

    $data = [
        'username' => 'cherry',
        'email'    => 'not-an-email',
        'status'   => ''
    ];

    $validator = new Validator($data);

    $validator->field('username')
              ->required()
              ->addRule('minlength', null, ['min' => 4]);

    $validator->field('email')
              ->required()
              ->addRule('email');

    // This rule is skipped entirely because 'status' is empty and not required
    $validator->field('status')
              ->addRule('minlength', 'Status is too short', ['min' => 3]);

    if ($validator->isPassed()) {
        // Data is valid, proceed safely
    } else {
        $errors = $validator->getErrors();
    }


## Nested data and wildcards

You can use the * wildcard to validate deep structures effortlessly.

    $data = [
        'company' => 'My company',
        'team' => [
            ['name' => 'Ansher', 'role' => 'Developer'],
            ['name' => 'An',     'role' => 'Designer'] // Fails minlength
        ]
    ];

    $validator = new Validator($data);

    // Validates 'name' inside every item in the 'team' array
    $validator->field('team.*.name')
            ->required()
            ->addRule('minlength', 'The name must be at least :min chars.', ['min' => 3]);

If the validation fails on the second employee, the engine automatically calculates the exact array position and outputs the error key formatted for your frontend inputs: `team[1][name]`.


## Field labels

To keep your error messages looking professional, you can assign human-readable labels to your input keys. If you don't define one, the library automatically cleans up the key name for you.

    // 1. Literal labels (Best for standard forms)
    $validator->field('meta.title', 'SEO Title')->required();

    // 2. Dynamic path labels (Perfect for wildcards)
    $validator->field('items.*.sku')
        ->label(function (string $path, $value) {
            return "SKU code at row " . $path;
        });

    // 3. Automatic fallback
    // If no label is given, this naturally reads as "Billing" in errors
    $validator->field('billing.address.zipcode')->required();


## Built-in rules

The library ships with optimized, type-strict rules out of the box.

### Field rules

| Rule            | Options                                  | Default message template |
|-----------------|------------------------------------------|--------------------------|
| required        | None                                     | The :attribute field is required. |
| email           | None                                     | The :attribute must be a valid email address. |
| between         | ['min' => 10, 'max' => 20]               | The :attribute must be between :min and :max. |
| even            | None                                     | The :attribute must be an even number. |
| float           | None                                     | The :attribute must be a valid floating-point number. |
| greaterthan     | ['min' => 0]                             | The :attribute must be strictly greater than :min. |
| integer         | None                                     | The :attribute must be a valid integer. |
| lessorequal     | ['max' => 100]                           | The :attribute must be less than or equal to :max. |
| lessthan        | ['max' => 100]                           | The :attribute must be strictly less than :max. |
| negative        | None                                     | The :attribute must be a negative number. |
| numeric         | None                                     | The :attribute must be a valid number description. |
| odd             | None                                     | The :attribute must be an odd number. |
| positive        | None                                     | The :attribute must be a positive number. |
| charset         | ['charset' => 'UTF-8']                   | The :attribute must conform to the specified :charset character encoding. |
| contains        | ['needle' => 'phrase']                   | The :attribute must contain the sub-string phrase: :needle. |
| endswith        | ['suffix' => 'value']                    | The :attribute must end with the specified suffix: :suffix. |
| lowercase       | None                                     | The :attribute must contain lowercase characters only. |
| minlength       | ['min' => 5]                             | The :attribute must be at least :min characters. |
| maxlength       | ['max' => 255]                           | The :attribute must not exceed a maximum length of :max characters. |
| nochar          | None                                     | The :attribute field payload must not contain any text characters. |
| notags          | None                                     | The :attribute text string structure cannot contain HTML or XML tags. |
| startswith      | ['prefix' => 'value']                    | The :attribute must start with the specified prefix: :prefix. |
| uppercase       | None                                     | The :attribute must contain uppercase characters only. |
| emptyvalue      | None                                     | The :attribute must evaluate to an empty structural state. |
| notempty        | None                                     | The :attribute field must possess an active data state payload. |
| notequals       | ['value' => 'restricted']                | The :attribute cannot equal the specified restricted entry option value. |
| captcha         | ['expected' => 'secret']                 | The verification security captcha entry input value is incorrect. |
| domain          | None                                     | The :attribute must match a valid network internet domain path description. |
| identity        | ['value' => 'expected_match']            | The :attribute field value must be identical to the specified comparison target. |
| ippattern       | None                                     | The :attribute field must resolve to a valid IPv4 or IPv6 infrastructure address. |
| macaddress      | None                                     | The :attribute must match a valid hardware interface MAC address specification. |
| regex           | ['pattern' => '/^pattern$/']             | The structural evaluation format criteria configured for :attribute is invalid. |
| urlpattern      | None                                     | The :attribute must resolve to a fully qualified web URL path address. |
| xdigit          | None                                     | The :attribute can consist of valid structural hexadecimal digits only. |
| boolean         | None                                     | The :attribute field must resolve to a valid logical boolean state wrapper. |
| callable        | None                                     | The passed payload structure inside :attribute is not executable by the engine. |
| incollection    | ['collection' => [...]]                  | The selected :attribute choice is outside the validated compilation index list. |
| json            | None                                     | The context parameters inside :attribute must constitute an un-broken JSON structure. |
| serialized      | None                                     | The string stream in :attribute cannot be cleanly un-serialized into native data. |
| unique          | ['pool' => [...]]                        | The value assigned to :attribute is duplicated and violates uniqueness constraints. |
| dateformat      | ['format' => 'Y-m-d']                    | The calendar data format structure in :attribute does not match template: :format. |
| dateformatmatch | ['target' => 'field', 'format' => 'Y-m-d'] | The chronological layout matching sequence between :attribute and :target failed. |
| day             | None                                     | The value assigned to :attribute must represent a standard monthly day configuration. |
| timestamp       | None                                     | The execution block :attribute must evaluate to a valid UNIX epoch timestamp coordinate. |
| year            | None                                     | The provided timeline reference inside :attribute must match a 4-digit calendar year index. |
| directorypath   | None                                     | The local execution system cannot locate a valid directory path target matching :attribute. |
| extension       | ['allowed' => ['jpg', 'png']]            | The system rejected the file extension attached to the target payload parameter: :attribute. |
| filepath        | None                                     | The localized storage map route configured inside :attribute is not an active target file. |
| filereadable    | None                                     | The system does not possess structural file system authorization maps to read :attribute. |
| filesize        | ['max' => 1048576]                       | The space configuration block constraints allocated to file processing payload :attribute exceeded maximum byte values. |
| latitude        | None                                     | The geographical projection context index coordinate for :attribute must fall between -90 and 90 degrees. |
| longitude       | None                                     | The geographical projection context index coordinate for :attribute must fall between -180 and 180 degrees. |


### File rules

| Rule            | Options                                  | Default message template |
|-----------------|------------------------------------------|--------------------------|
| extension       | ['allowed' => 'pdf', 'doc']              | The system rejected the file extension attached to the target payload parameter: :attribute. |
| image           | None                                     | The :attribute must be an image (e.g., JPG, PNG, WEBP). |

## Custom rules

You can extend the validator instantly using closure functions. Field rules and file rules are kept in isolated pools, meaning you can register a custom size rule for fields without breaking file validations.


## Custom field rules
Your closure receives the value and the full data payload context, making cross-field validation simple:

    // Define a custom rule
    $validator->setFieldRule('even', function($value) {
        return $value % 2 === 0;
    }, 'The :attribute must be an even number');

    // Use it
    $validator->field('number')
              ->addRule('even');


## Custom file rules

File rules automatically receive a secure FileEntity object, preventing common upload security footguns.

    // Define a custom rule
    $validator->setFileRule('docs', function ($file, array $options) {
            return in_array($file->getExtension(), $options['extensions'], true);
    }, 'Only these formats are permitted: :extensions.');

    // Usage
    $validator->file('attachments.*')
            ->required()
            ->addRule('docs', null, ['extensions' => ['pdf', 'docx']]);

## Dynamic error messages

Error templates support automatic token replacements to make messages dynamic:

`:attribute` — Automatically replaced with the human label.
`:value` — Automatically replaced with what the user entered.

Custom options — Any parameter passed in your options array (like :min or :extensions) is instantly injected. If an option is a flat array, it is automatically converted into a clean comma-separated string.

## Internationalization

The library supports full localization for all error messages and labels. You can inject a Translator instance to manage multi-language support across your entire application.

    // Inside your controller
    $validator->setTranslator($this->translator, 'zh');

**Automatic Loading**

The second parameter is optional. If you provide a locale code (e.g., 'zh'), the library will automatically attempt to load pre-packaged, built-in translation files for that specific language.

**Global Scope**

Once a translator is configured, all loaded translations are applied globally. This includes system-defined error templates, your custom user-defined messages, and field labels.

**Built-in Locales**

The library comes with ready-to-use translations for default messahe. The following locales are available: de, es, fr, hi, ja, kz, pt, ru, uz, and zh.

## Stopping on first error

You can control whether the validator collects every single failure or stops immediately on the first error it runs into.

    // Halts checking a field path the exact second a rule fails (Default)
    $validator->stopOnFailure(true);

    // Processes all rules in the chain to gather comprehensive field feedback
    $validator->stopOnFailure(false);

## Error output format

When `$validator->isPassed()` returns false, running `$validator->getErrors()` provides a clean data array structured perfectly for API JSON responses or frontend state stores.

    [
        [
            'input'   => 'users[1][profile][email]', // Form-ready input name
            'label'   => 'Email Address',
            'rule'    => 'email',
            'message' => 'The Email Address must be a valid email address.',
            'value'   => 'corrupted-input-text',
            'params'  => []
        ]
    ]