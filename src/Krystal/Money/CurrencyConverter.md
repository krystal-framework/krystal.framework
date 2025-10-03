Currency converter
=======

A utility class for handling money conversions between currencies. It uses a rate map defined relative to USD, lets you set an initial amount in one currency, and then convert that value into any other supported currency with consistent results.

## Initialization

When you create a new instance of `CurrencyConverter`, you must pass in an **array of exchange rates** where each rate is defined **relative to USD**. In this array:

-   USD is always the anchor with a value of 1.0.
-   Every other entry defines how much 1 USD is worth in that currency

Example:

    <?php
    
    use Krystal\Text\CurrencyConverter;
    
    // Initialize the converter with a set of rates
    $converter = new CurrencyConverter([
        'USD' => 1.0,   // Base reference
        'EUR' => 0.95,  // 1 USD = 0.95 EUR
        'GBP' => 0.82,  // 1 USD = 0.82 GBP
        'JPY' => 136.5  // 1 USD = 136.5 JPY
    ]);

Now, the converter knows that **1 USD = 0.95 EUR** and **1 USD = 0.82 GBP**.

## Set base

The `set()` method is how you define **how much money you have in a specific currency** and tell the converter which currency that amount is expressed in.

    // Set the base amount in EUR
    $converter->set(100, 'EUR');

By calling `set($amount, $currency)`:

1.  You tell the converter, for example, “I have 100 EUR.”
    
2.  The converter converts this amount internally into the **base reference value**, using the rate of EUR.
    
3.  Later, when you call `convert($currency)`, it can accurately calculate how much your 100 EUR is worth in USD, GBP, JPY, etc.


## Convert

The `convert()` method takes the **base amount you previously set with `set()`** (internally stored as a USD-equivalent value) and converts it into the target currency you request.

    // Convert to USD
    echo $converter->convert('USD'); // 105.26
    
    // Convert to GBP
    echo $converter->convert('GBP'); // 122.0

## Check if a currency is available

The `isAvailable()` method checks whether a given currency code exists in the converter’s internal rate map (the array of rates you passed during initialization).

    $isAvailable = $converter->isAvailable('JPY');
    
    var_dump($isAvailable); // False
