
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
              ->addRule('minlen', null, ['min' => 4]);

    $validator->field('email')
              ->required()
              ->addRule('email');

    // This rule is skipped entirely because 'status' is empty and not required
    $validator->field('status')
              ->addRule('minlen', 'Status is too short', ['min' => 3]);

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
            ['name' => 'An',     'role' => 'Designer'] // Fails minlen
        ]
    ];

    $validator = new Validator($data);

    // Validates 'name' inside every item in the 'team' array
    $validator->field('team.*.name')
            ->required()
            ->addRule('minlen', 'The name must be at least :min chars.', ['min' => 3]);

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


| Rule     | Options                    | Default message template                          |
|----------|----------------------------|---------------------------------------------------|
| required | None                       | The :attribute field is required.                 |
| email    | None                       | The :attribute must be a valid email address.     |
| minlen   | ['min' => 5]               | The :attribute must be at least :min characters.  |
| between  | ['min' => 10, 'max' => 20] | The :attribute must be between :min and :max.     |


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