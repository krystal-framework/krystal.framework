## Validation Roadmap & Planned Rules

### Advanced File Validators
- **`dimensions`**: Restrict uploaded image dimensions with specific constraints (e.g., exact width/height, minimum/maximum boundaries, or strict aspect ratios like 16:9).
- **`mimetype`**: Deep-inspect uploaded files using `finfo` to verify real MIME types rather than trusting client-supplied headers.
- **`maxfilesize`**: Enforce cumulative upload size limits across multi-file arrays.
- **`maxfilescount`**: Limit the maximum number of files allowed in a single batch upload field.
- **`svg_safe`**: Sanitize and validate uploaded SVG files to prevent stored XSS and malicious script execution.