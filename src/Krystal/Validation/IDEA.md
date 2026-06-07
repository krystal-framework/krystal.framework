# Technical Specification: Krystal Validation Engine

This document defines the architectural requirements, coding standards, and execution contracts for **Krystal Validation**, a standalone, type-safe validation library for PHP 7 and above. Inspired by the data-driven philosophy of Valitron, this engine extends validation behaviors to support deep wildcard array traversal and source-isolated file entity verification.

---

## Code Quality and Structural Mandates

To maintain strict architectural purity and ease of testing, developers must follow these engineering constraints.

### 1. Code Standards and Architecture

- **Namespace Isolation:** All components must reside strictly under the `Krystal\Validation` namespace.
- **Framework Independence:** The library must maintain zero global state and zero static dependencies. It must rely exclusively on dependency injection.
- **Variable Nomenclature:** Input datasets must never be assumed to originate from HTTP requests alone. Use generic terminology like `$data` instead of `$postData`.
- **Namespace Imports:** Class types used within method signatures or docblocks must be explicitly declared via `use` statements at the top of the file. Inline fully qualified class names (e.g., `\Vendor\Package\Class`) are strictly prohibited.
- **Component Separation:** Single Responsibility Principle (SRP) must be enforced. Distinct classes must handle isolated concerns: `ErrorBag`, `RuleRegistry`, `MessageResolver`, `Utility\PathResolver`, etc.

### 2. Syntax and Documentation

- **PHP Compatibility:** Code must target PHP 7.0+ compatibility.
- **Anonymous Functions:** Short arrow functions (`fn($v) => ...`) are banned. Traditional closure syntax must be used: `function($v) { return ...; }`.
- **Strict PSR Compliance:** Adhere strictly to PSR-12 coding style guidelines.
- **Exhaustive PHPDoc Declarations:** Every method, property, and parameters array must contain explicit structural descriptions. Generic types like `@var array` are rejected without contextual documentation.

#### Examples of Invalid vs. Valid Documentation

```php
// --- REJECTED ARCHITECTURE ---
/**
 * @var array
 */
private $rules = [];

// --- APPROVED ARCHITECTURE ---
/**
 * A collection of all registered validation rules indexed by rule name
 *
 * @var array<string, array>
 */
private $rules = [];
```

---

## Data Extraction and Traversal Engine

The execution pipeline processes data arrays or object pools by abstracting inputs through dedicated path-resolution layers.

### 1. The Field Entry Point

Data entry is initiated using the `field()` method, which targets standard multi-dimensional associative arrays.

```php
public function field(string $attribute, $label = null): FieldDefinition
```

- **Label Evaluation Queue:** If the `$label` parameter is omitted, the engine falls back to extracting the top-level segment of the notation string and applying `ucfirst()`.
- **Fluent Overrides:** Labels can be lazily or dynamically configured via a chained `label()` modifier method on the fluid definition instance.

```php
public function label(string|callable $label): self
```

#### Closure Signature for Labels

When a callable is passed as a label, it must match the following signature, receiving the fully resolved runtime path and the current target value:

```php
function(string $path, mixed $value): string
```

### 2. Wildcard Array Traversal

The engine must support nested scanning via asterisk (`*`) wildcards using dot notation syntax (e.g., `items.*.meta.*.sku`). The `PathResolver` utility recursively unrolls these paths into absolute coordinates against the source payload.

```php
// Resolves recursively across multi-dimensional input structures
$validator->field('name.*.*')
    ->addRule('minlen', 'The :attribute must have a minimal length of :min', ['min' => 5]);
```

---

## Gatekeeping and Presence Logic

By default, all rules are optional. If a targeted path does not exist, holds a null value, or evaluates as empty, execution skips subsequent rule chains unless an explicit requirement boundary is defined.

### 1. Strict Definition of "Empty"

A field path is skipped if its extracted value matches any of these conditions:

- An empty string (`''`) or a string containing only whitespace characters.
- An empty array (`[]`).
- A literal `null` pointer.
- A missing array key along the resolved path.

> **Critical Edge Case:** Integer `0` and numeric string `'0'` are explicitly treated as active, non-empty values and must trigger normal validation rule execution.

### 2. Enforcing Presence

To override the optional gatekeeper, the `required()` method forces validation execution regardless of content state.

```php
public function required(string $message = null): self
```

If the data fails the presence check, subsequent rules in that specific field loop are bypassed, and the error is logged using the provided or default structural template message.

---

## Rule Registry and Callback Signatures

1. Custom extension pools are managed through isolated execution registries. Callbacks receive rich architectural contexts to handle cross-field dependencies.
2. To ensure structural integrity and prevent silent failures in validation chains, the engine must perform strict verification. When calling addRule('rule') on a field definition, if the specified rule identifier is not found within the registered rule pool, the engine must immediately throw an \InvalidArgumentException.

### Full Callback Signature

Every validation rule callback must support up to five arguments, enabling contextual cross-reference checks against alternative parameters or uploaded files:

```php
function(mixed $value, array $options, array $data, array $files, string $path): bool
```

### 1. Dynamic Registration Methods

The orchestrator handles extensions through dedicated, type-isolated methods:

```php
public function setFieldRule(string $name, callable $callback, string $message = null): void;
public function setFileRule(string $name, callable $callback, string $message = null): void;
```

#### Rule Conflict Resolution

- If a developer attempts to register a custom rule matching an internal system keyword, the custom callback overrides the built-in rule.
- The system must immediately trigger an `E_USER_WARNING` notification when an internal rule is overridden, alerting the developer to potential structural behavior modifications.

### 2. Implementation Layout Inside the Engine

Internally, default systems and dependencies are defined inside the `RuleRegistry` tracking array structures:

```php
$this->ruleRegistry->setBuiltInFieldRules([
    'required' => [
        'callback' => function($value, array $options, array $data, array $files, string $path) {
            if (is_string($value)) {
                return trim($value) !== '';
            }
            return !empty($value) || $value === 0 || $value === '0';
        },
        'message' => 'The :attribute field is required.'
    ],
    'dependant' => [
        'callback' => function($value, array $options, array $data, array $files, string $path) {
            // Evaluates rules based on state metrics from $data and $files
            return isset($data['parent_id']) && (int)$value > 0;
        },
        'message' => 'The path dependency check failed.'
    ]
]);
```

---

## File Validation Contracts

To protect environments from type-mismatch vulnerabilities, structural data payloads and uploaded file payloads are processed through completely isolated rule execution pools.

### 1. Source Isolation

- `field()` targets the array structures passed in the first constructor parameter (`$data`). It resolves rules only from the **Field Rules** pool.
- `file()` targets object frameworks passed in the second constructor parameter (`$files`). It resolves rules only from the **File Rules** pool.

This separation ensures that a custom string validation rule named `size` will never conflict with a file validation rule named `size`.

### 2. The FileEntity Interface Expectations

The file processing loop expects arrays containing instances of `Krystal\Http\FileTransfer\FileEntity`. Rule handlers can safely interact with the following API contracts on these entities:

```php
namespace Krystal\Http\FileTransfer;

interface FileEntity
{
    public function getName(): string;       // Original client filename
    public function getUniqueName(): string; // Generated unique disk storage filename
    public function getType(): string;       // Client-side declared MIME type
    public function getMimeType(): string;   // Server-verified secure MIME type
    public function getTmpName(): string;    // Runtime scratchpad server path
    public function getError(): int;         // Native UPLOAD_ERR_* error constant
    public function getSize(): int;          // Data size measured in bytes
    public function getHumanSize(): string;  // Formatted display size string (e.g., "2.4 MB")
    public function isDangerous(): bool;     // Integrated payload vulnerability analysis
}
```

### Implementation Example

```php
$validator = new Validator($_POST, $this->request->getFiles());

$validator->file('photo')
    ->required()
    ->addRule('extension', null, ['whitelist' => ['jpg', 'png']])
    ->addRule('size', 'File must be less than 2 MB', ['max_size' => 2097152]);
```

---

## Message Interpolation and Internationalization

The processing framework manages localization and contextual string formatting through sequentially decoupled operations.

### 1. The Placeholder Translation Pipeline

When an assertion fails, string mutations must proceed in this exact sequence:

```
[Error Triggered] → [Fetch Raw Message Template] → [Execute I18n Translator] → [Parse Placeholder Tokens]
```

- **Translation Fetch:** If an instance of `Krystal\I18n\Translator` is injected via `setTranslator()`, the engine runs the message key or default template through `$translator->translate()` first. This guarantees clean language lookup dictionary matches before variables change the text.
- **Token Subscriptions:** The engine processes these system tokens:
  - `:attribute` — The human-readable label string assigned or fallback-parsed.
  - `:value` — The runtime string representation of the invalid item under test.
  - `:option_key` — Custom context variables passed in the `$options` array (e.g., `:min`, `:max`).

### 2. Option Array Processing Constraint

If an array parameter is passed inside the rule configuration choices, the formatting system must safely flatten it into a readable, comma-separated string representation.

```php
// Input array: ['whitelist' => ['jpg', 'png']]
// Template:    "Only :whitelist formats are allowed."
// Output:      "Only jpg, png formats are allowed."
```

> **Strict Error Boundary:** Only flat, single-dimensional arrays are supported. If a multi-dimensional array parameter is encountered during token formatting, the engine must immediately throw an `\InvalidArgumentException`. Unknown placeholder tokens must remain untouched.

---

## Control Flow and Output Formats

### 1. Loop Configurations

Developers can configure error tracking strategies down individual path evaluation pipelines using the `stopOnFailure()` switch:

```php
public function stopOnFailure(bool $flag): self
```

- `true` *(Default)*: The runtime engine stops processing subsequent rule definitions on a specific path the exact moment its first constraint fails, jumping instantly to the next sequence item.
- `false`: The validator executes every rule assigned to the active definition stack, gathering a comprehensive breakdown of all failures occurring on that single key.

### 2. Error Output Schema

When validation checks fail, running `$validator->getErrors()` returns a multi-dimensional associative collection array. The input key converts internal evaluation path descriptors back into standard HTML form element bracket notation formats, making frontend form binding completely frictionless.

```php
[
    [
        'input'   => 'user[1][profile][email]', // Dot-notation mapped back to HTML bracket names
        'label'   => 'Email Address',
        'rule'    => 'email',
        'message' => 'The Email Address must be a valid email address.',
        'value'   => 'malformed-string-data',
        'params'  => []
    ]
]
```
