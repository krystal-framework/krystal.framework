I18n
====

This component provides a service to handle string translations. The service is called `translator` and is available in controllers  as `$this->translator`. In template views you can use it like echo `$this->translate('...')` or its shortcut `$this->show('...')`.


# Configuration

The configuration is typically locate at the framework's configuration (usually at `/config/app.php`) file. It's under `translator` section. It has only two options.

`default` - species default language
`when` - takes an array of the following parameters

 `uri` - defines an array of URI fragments when default language must be overriden.
`language` - defines a language to be overridden.

So why and when would you tweak `when` section? Consider a web-site that has support for several languages. But you want an administration panel's language to be only a one language of your choice. In that case it would look as following:

     'default' => 'en',
     'when' => array(
       'uri' => array('/admin'),
       'language' => 'de'
    )


# Loading translations

In order to load translations, in your `Module.php` you have to implement a new method called `getTranslations()` that holds a language argument, defined in configuration file.  It would look like as following:


    class Module
    {
           public function getTranslations($language)
           {
              return require(__DIR__.'/Translations/'.$language.'/messages.php');
            }
    }


So for example, when you defined a default language as `de`, it would try to load an array from `__DIR__.'/Translations/de/messages.php`. As you might already guessed, `messages.php` itself should be a standalone file that returns an array of translations.


# Available methods

The service has the following methods

## extend(array, [array] ..)

Extends a dictionary at runtime.

## translate($string, array|string $placeholders)

Translates a string, optionally substituting placeholders. A placeholder must be defined as `%s`.

## translateArray($array)

Just like as `translate()` method translates a string in the target `$array` and returns a translated array.