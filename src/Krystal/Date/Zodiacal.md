Zodiacal Class
=====

A PHP class for determining zodiac signs based on date ranges.

# Basic Usage

Instantiate with Month and Day

use Krystal\Date\Zodiacal;

// Create for March 15th
$zodiac = new Zodiacal(3, 15); 

// Get the zodiac sign
echo $zodiac->getSign(); // Outputs: Pisces

# Instantiate with DateTime Object

use Krystal\Date\Zodiacal;

// Create from a specific date
$date = new \DateTime('1990-07-31');
$zodiac = Zodiacal::fromDateTime($date);

echo $zodiac->getSign(); // Outputs: Leo

# Methods

## getSign()

Returns the zodiac sign name for the current date.
$sign = $zodiac->getSign(); // Returns string (e.g. "Leo")

## is($sign)

Check if the current date matches a specific sign (case-insensitive).

if ($zodiac->is('leo')) {
    echo "You're a Leo!";
}

## getAvailableSigns()

Method that returns all available zodiac signs.

$zodiacal = new Zodiacal(1,14);
print_r($zodiacal->getAvailableSigns());

/*
Array
(
    [0] => Aries
    [1] => Taurus
    // ... etc
)
*/
