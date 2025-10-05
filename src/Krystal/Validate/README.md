Validate
=======

As a rule of thumb, you should never trust data that comes from the request. Almost everything that supplied by end-users must be validated in some or another way, if you want to build a secure system. Krystal's validation component would help you to manage validation rules for input fields.

# Getting started

Validation should be done only in controllers. Controllers do instantiate a validation service and determine whether user's data looks valid. Then depending on that, you either return error messages or do the upcoming thing, such as inserting or updating a record.

Validation component has only one service, which is called `validatorFactory` and is available as a property in controllers. To instantiate a validator, you have to call `build()` on it providing configuration.

As an example of typical initialization, it might look like so:

    $formValidator = $this->validatorFactory->build(array(
        'input' => array(
            'source' => $this->request->getPost(),
            'definition' => array(
                '...field...' => '...rules...'
            )
        )
    ));

Let's de-construct that step by step.

1. First of all, we define a target input in `input -> source`, which is a copy of $_POST
2. Second in `input -> definition` we define a target key in the source we specified and attach validation rules on it.

Now let's learn how to defile validation rules.

# Configuration

The configuration stored under `validator` component. It has only two options : `render` and `translate`. The `render` option a strategy for returning error messages by `getErrors()` method. Here's a list of all available renderers:

## MessagesOnly

Returns array of error messages.

## Standard

Returns an array with field names and their associated error messages.

## StandardJson

Does exactly what `Standard`, but converts a result-set to JSON string. 

## JsonCollection

Returns an array with errors `messages` and `keys`. This can be used to highlight fields that have errors on client-side.

# Defining validation rules

A validation rule for a field defines whether a field is optional and its collection of constraints. The `required` key specifies whether to run validation on particual field in case it's not empty. For example, when setting `requred` to `false`, the validation will be runned in case the target field isn't empty. The `rules` key defines an array with constraints and their associated options to be applied to that particular input.

For example, suppose you have a simple form, that has only `email` field and it looks like this:

    <form>
      <input type="text" name="email" />
    </form>

Typically, for email fields we have two requirements: it must be filled and it must look like as a real email-address. To implement such validation rule, we'd define two built-in constraints `NotEmpty` and `EmailPattern`.

    $formValidator = $this->validatorFactory->build(array(
        'input' => array(
            'source' => $this->request->getPost(),
            'definition' => array(
                'email' => array(
                    'required' => true,
                    'rules' => array(
                        'NotEmpty' => array(
                            'message' => 'Email cannot be blank'
                        ),
                        'EmailPattern' => array(
                            'message' => 'Invalid email format'
                        )
                    )
                )
            )
        )
    ));

After instantiating the validation service, you would usually want to run validation process itself. And if it fails, you would want to retrieve error messages. To do so, the validation service has two methods: `isValid()` and `getErrors()`

    if ($formValidator->isValid()) {
        // Do the rest
    
    } else {
    
        // Fail and return error messages
        return $formValidator->getErrors();
    }

# Input constrains


In case a constraint has parameters, they must be defined as array in `value` section. In case there's only one parameter, you can define it as a value itself. For example, for `Between` constraint the definition might look so:

    'value' => array(1, 10)

For `RegExMatch` constraint, that might look so:

    'value' => '~(.*)~'

### Between

Checks whether numeric value is in range between specified values.

Parameters: First value is a starting range. Second value is an ending range.

### Boolean

Checks whether provided value looks as a boolean. Non-empty values, `true`, '1' are considered as true.

### Callable

Checks whether provider string value can be considered as PHP callable. For example, that can be a function name, or a class method in a format like `Class::method`.

### CAPTCHA

Validates CAPTCHA's response.

Parameters: Requires CAPTCHA service.


### Charset

Checks whether provided charset is supported by current PHP environment.


### Contains

Checks whether string contains a character defined in collection.

Parameters: A string that represents a character to be matched.
Or an array of such string.

### DateFormat

Checks whether string looks as a date format.

### DateFormatMatch

Checks whether string matches a particular date format.

Parameters: Date format, or an array of date formats.


### Day

Checks whether string value is less than 31.


### DirectoryPath

Checks whether string value is a path to a directory.


### Domain

Checks whether value looks like as a domain.


### EmailPattern

Checks whether a string looks like as email address.

### Empty

Checks whether string is empty.


### EndsWith

Checks whether string ends with a value.

Parameters: The value to be checked at the end itself.

### Even

Checks whether numeric string is even.

### Extension

Checks whether string contains an extension.

Parameters: The extension itself without a dot.


### FilePath

Checks whether value points to a file path on the file-system.

### FileReadable

Checks whether value points to a file path, which is readable.

### FileSize

Checks whether file size is expected

Parameters: File size in bytes

### Float

Checks whether value is float.


### GreaterThan

Checks whether value is greater than.

Parameters: The value to be compared against.


### Identity

Checks whether values are identical.

Parameters: Target value to be compared.


### InCollection

Checks whether a value in collection (i.e belongs to particular set of values).

Parameters: The array of values itself (i.e the collection)

### Integer

Checks whether value is an integer.

### IpPattern

Checks whether value looks as IP address.

### Json

Checks whether value represents JSON string.

### LessOrEqual

Checks whether value is less than or equals.

Parameters: The target value to be compared.

### LessThan

Checks whether value is less than.

### Lowercase

Checks whether a value is lower-cased.

### MacAddress

Checks whether value looks as a MAC-address.

### MaxLength

Checks the maximal length of a value.

Parameters

The maximal length.

### MinLength

Checks the minimal length of a value.

Parameters

The minimal length.

### Negative

Checks whether a value is negative.

### NoChar

Checks whether a value doesn't contain a character.

Parameters: An array of characters or a character string to be matched against a value.

### NoTags

Checks whether a string hasn't HTML tags.

Parameters: An optional array of allowed tag names (like `a`, `h1`, etc).

### NotEmpty

Checks whether a value isn't empty.

### Numeric

Checks whether a value is numeric.

### Odd

Checks whether a value is odd.

### Positive

Checks whether a value is positive.

### RegEx

Checks whether regular expression is valid.

### RegExMatch

Checks whether a value matches regular expression.

Parameters: Regular expression to be matched against a value.


### Serialized

Checks whether a value has been serialized.

Parameters: Serialization adapter. To learn more, refer to Serialization component.

### StartsWith

Checks whether a value starts with a character.

Parameters: The character itself.

### Timestamp

Checks whether a value looks as a UNIX-timestamp.

### Uppercase

Checks whether a value is upper-cased.

### UrlPattern

Checks whether a value looks as URL.

### XDigit

Checks whether a value is like XDigit.

### Year

Checks whether a value looks like a year.


# Validating files

Sometimes in your forms, you might have a `file` input that lets your users to upload files. In case you expect users to upload a photo, then you would want to make sure that they selected an image, not a document or malicious php-script. To validate files, you'd simply append new `file` input. Its configuration is defined in exactly way as in `input`.

    $formValidator = $this->validatorFactory->build(array(
        'input' => array(
            'source' => $this->request->getPost(),
            'definition' => array(
                '...field...' => '...rules...'
            )
        ),
        'file' => array( // <- Append this new file input
            'source' => $this->request->getFiles(),
            'definition' => array(
                '...field...' => '...rules...'
            )
        )
    ));

# Files constraints


### Extension

Check whether files have a valid extension.

Parameters: An array of valid extension (without dots)

### FilenameMaxLength

Checks the legth of uploaded file names.

Parameters: The optional length, which is 255 by default.

### FilenameRegEx

Validates file names by RegEx pattern.

Parameters: The RegEx pattern

### FileSize

Validates whether uploaded files exceed defined allowed filesize.

Parameters: The maximal allowed value, the operator to be applied when doing comparison.

### FormSize

Validates whether uploaded files exceed MAX_FILE_SIZE directive defined in a form.

### IniSize

Validates whether uploaded files exceed `upload_max_filesize` directive in `php.ini`

### IsUploadedFile

Validates whether uploaded files have been uploaded natively via user's form.

### NotEmpty

Checks whether at least file has been uploaded.

### Partial

Checks whether a file has been uploaded partially.

### TmpDir

Checks whether a file can't be uploaded due to missing temporary directory.

### Type

Checks whether MIME-Type of uploaded files matches to expected one.

Parameters: The expected mime-type.

### UploadAmount

Restricts uploaded files not to exceed defined amount.

Parameters: The desired amount.

### WriteFail

Checks whether uploaded files have written to disk successfully.


# Validation patterns

If you develop large application, you'd soon note how much duplicated rules you have. For example, in several places you might have rules for `title` field, that are completely identical. In order to reduce duplication in validation rules and make them reusable, you can use validation patterns. Validation patterns are represented via an object that internally returns an array of definitions.

So instead, of writing rules like so.

    'definition' => array(
        '...field...' => '...rules...'
    )

you can now write patterned rules like so:

    'definition' => array(
        '...field...' => new Pattern\SomePattern()
    )

In order to attach a pattern, you need to import a base namespace.

    use Krystal\Validate\Pattern; // <- Add this right after namespace declaration

and then you can use it like this:

    'definition' => array(
        'email' => new Pattern\Email(),
        // ...
    )

where `Email` is just an example of built-in pattern.

## Available patterns

Here is a complete list of available patterns.

- Address (checks whether address isn't empty and hasn't HTML tags)
- Captcha (checks whether CAPTCHA is valid)
- Comment (checks whether a string hasn't HTML tags)
- ClassName (checks whether class name isn't empty and hasn't HTML tags)
- Content (checks whether content isn't empty)
- Currency (checks whether currency isn't empty and hasn't HTML tags)
- DateFormat (checks whether supplied date format is valid. As a first argument it accepts the format itself)
- Description (checks whether description isn't empty)
- Email (checks whether email isn't empty and it looks as a valid one)
- File (append all generic constraints to a file)
- FullText (checks whether full description isn't empty)
- Height (check whether height is numeric and not empty)
- ImageFile (checks whether selected file looks as valid image)
- ImageHeight (checks whether image height is numeric and not empty)
- ImageQuality (checks whether image quality is numeric, is not empty and its range between 1 and 100)
- ImageWidth (checks whether image width is numeric and is not empty)
- IntroText (checks whether introduction text isn't empty)
- LanguageCode (checks whether language code isn't empty)
- Login (checks whether login isn't empty and hasn't HTML tags)
- Message (checks whether message isn't empty and hasn't HTML tags)
- Name (checks whether name isn't empty and hasn't HTML tags)
- Order (checks whether order isn't empty, is numeric and greater than 0)
- Password (checks whether password isn't empty)
- PasswordConfirmation (checks whether passwords are identical. As a first argument it accepts a password)
- PerPageCount (checks whether value is numeric, isn't empty and greater than 0)
- Phone (checks whether a value isn't empty and matches a pattern)
- Price (checks whether a value isn't empty, is numeric and greater than 0)
- Query (checks whether a value isn't empty and greater than 3)
- TemplateName (checks whether a value isn't empty and contains only English letters)
- ThemeName (the same as TemplateName, but has different messages)
- ThumbHeight, ThumbWidth (check whether a value isn't empty and greater than 0)
- Title (checks whether a value isn't empty and hasn't HTML tags)
- Url (checks whether a value looks as URL)
- Username (checks whether a value isn't empty and its length is between 3 and 15 and it hasn't HTML tags)
- Width (checks whether a value isn't empty, is numeric and its greater than 1)