
Zodiacal
=====

A tool for determining zodiac signs based on date ranges.

## Basic Usage

Instantiate with Month and Day

    <?php
    
    use Krystal\Date\Zodiacal;
    
    // Create for March 15th
    $zodiac = new Zodiacal(3, 15); 
    
    // Get the zodiac sign
    echo $zodiac->getSign(); // Outputs: Pisces

## With DateTime Object

    <?php
    
    use Krystal\Date\Zodiacal;
    
    // Create from a specific date
    $date = new \DateTime('1990-07-31');
    $zodiac = Zodiacal::fromDateTime($date);
    
    echo $zodiac->getSign(); // Outputs: Leo

# Methods

## Get sign

Returns the zodiac sign name for the current date.

    $sign = $zodiac->getSign(); // Returns string (e.g. "Leo")

## Verify

Check if the current date matches a specific sign (case-insensitive).

    if ($zodiac->is('leo')) {
        echo "You're a Leo!";
    }

## Get all signs

Method that returns all available zodiac signs.

    $zodiacal = new Zodiacal(1,14);
    print_r($zodiacal->getAvailableSigns());

Output

    Array
    (
        [0] => Aries
        [1] => Taurus
        // ... etc
    )



