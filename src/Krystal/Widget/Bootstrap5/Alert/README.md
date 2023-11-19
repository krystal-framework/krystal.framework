
Bootstrap 5 Alert Widget
==========

Alert is a simple way to notify users about that something is happened. One common example of Alert usage is a flash message, that a contact form has been submitted.

The constructor of `\Krystal\Widget\Bootstrap5\AlertMaker` accepts 5 arguments:

`$type (string)` - Required. The type of the alert to be rendered (See below)

`$content (string)` - Required. HTML/Text content of the Alert

`$header (string)` - Optional. The heading caption of the alert.

`$dismissible (boolean)` - Optional. Whether close button must be rendered. By default is `true`.

`$animate (boolean)` - Optional. Whether animation must be performed on close. By default is `true`.

Usage example:
----

    <?php
    
    use Krystal\Widget\Bootstrap5\Alert\AlertMaker;
    
    $text = 'We will contact you soon';
    $header = 'Thank you!';
    
    $alert = new AlertMaker(AlertMaker::ALERT_PRIMARY, $text, $header);
    
    echo $alert->render();

Which renders HTML like this:

    <div role="alert" class="alert alert-primary alert-dismissible fade show">
        <h4 class="alert-heading">Thank you!</h4>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        We will contact you soon
    </div>

Available type constants:

    AlertMaker::ALERT_PRIMARY
    AlertMaker::ALERT_SECONDARY
    AlertMaker::ALERT_SUCCESS
    AlertMaker::ALERT_DANGER
    AlertMaker::ALERT_WARNING
    AlertMaker::ALERT_INFO
    AlertMaker::ALERT_LIGHT
    AlertMaker::ALERT_DARK

