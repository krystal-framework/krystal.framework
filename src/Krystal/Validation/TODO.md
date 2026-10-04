## Validation Roadmap & Planned Rules

### General Field Validators
- **`creditcard`**: Validate major credit card number formats (Visa, MasterCard, Amex, Discover) using the Luhn algorithm.
- **`ssn`**: Validate US Social Security Number formatting and structural integrity.
- **`iban` / `bic`**: International Bank Account Number and Bank Identifier Code validation for financial transactions.
- **`phone`**: International telephone number validation conforming to standard ITU-T E.164 formatting.
- **`color`**: Validate hexadecimal (`#fff`, `#ffffff`), RGB, and RGBA color codes.
- **`slug`**: Ensure a string contains only URL-safe characters (alphanumeric, dashes, and underscores).
- **`json_schema`**: Validate a JSON payload structure against a predefined schema definition.

### Advanced File Validators
- **`dimensions`**: Restrict uploaded image dimensions with specific constraints (e.g., exact width/height, minimum/maximum boundaries, or strict aspect ratios like 16:9).
- **`mimetype`**: Deep-inspect uploaded files using `finfo` to verify real MIME types rather than trusting client-supplied headers.
- **`maxfilesize`**: Enforce cumulative upload size limits across multi-file arrays.
- **`maxfilescount`**: Limit the maximum number of files allowed in a single batch upload field.
- **`svg_safe`**: Sanitize and validate uploaded SVG files to prevent stored XSS and malicious script execution.