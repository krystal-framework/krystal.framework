
Security component
================

This component provides basic utilities to protect from common vulnerabilities.

# CSRF protector

This service is available by default. In your template views, you'd always have a variable called `$csrfToken` which you can include in some hidden token field when submitting forms, like this:

    <form>
      <input type="hidden" name="token" value="<?php echo $csrfToken; ?>" />
     ....

Then in controllers, when handling form submission, you can grab a POST value named `token`  and validate it against current unique session token (via `csrfProtector` service), like this:

    // Grab the token's value from the POST request
    $token = $this->request->getPost('token');
    
    // Save boolean value that indicates whether a token is valid or not
    $isValid = $this->csrfProtector->isValid($token);

    if (!$isValid) {
        die('Invalid Token');
    }

## Ajax request

If you handle forms via AJAX, then you need to handle it a bit differently. First of all, add this meta-header in your global template layout, like this:

    <head>
      ...
         <meta name="csrf-token" content="<?php echo $csrfToken; ?>" />
      ...
    </head>

Assuming that you use Jquery, add this additional global header:

    $.ajaxSetup(
     	headers: {
    	  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    	}
    );

Then validate it in controllers, just like as we did in previous example, but use `$this->request->getMetaCsrfToken()` to get token's value instead of `$this->request->getPost('token')`.

# Filter class

    Krystal\Security\Filter

Currently this class provides one method to escape HTML in strings:

## escape($string)

Escapes the HTML in the string.