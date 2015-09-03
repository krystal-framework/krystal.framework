Date component
==============

This component provides basic tools to deal with dates.

# Time helper

There's a mini service that has time constants only. Here they are:

     use Krystal\Date\TimeHelper;
     
     echo TimeHelper::MINUTE; // 60
     echo TimeHelper::SECOND; // 1
     echo TimeHelper::DAY; // 86400
     echo TimeHelper::WEEK; // 604800
     echo TimeHelper::MONTH; // 2592000
     echo TimeHelper::YEAR; // 31536000
     
You can use these constants when setting a life time for a cache key, or for setting cookie's expiration date.
 
     
# Zodiacal

This service will help to determine a zodiacal sign of a person by provided month and day of birth. The usage is pretty-straightforward:

     use Krystal\Date\Zodiacal;
     
     $zodiacal = new Zodiacal('August', 29);
     echo $zodiacal->getSign(); // Virgo
     var_dump($zodiacal->is('Virgo')); // true

That's the common usage. It accepts a month name (must be in English) as a first argument, and a day of birth as a second. As you can see there are only two common methods to work with:

## is($sign)

Determines whether given sign belongs to provided date of birth

## getSign()

Returns a string that indicates the sign

## Complete list of extra methods

You might also call these methods to determine the sign (they all do return boolean value):

    isAries()
    isTaurus()
    isGemini()
    isCancer()
    isLeo()
    isVirgo()
    isScorpio()
    isLibra()
    isSagittarius()
    isCapricorn()
    isAquarius()
    isPisces()