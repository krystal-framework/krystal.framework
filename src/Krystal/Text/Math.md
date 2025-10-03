Math
====

A lightweight utility class providing common mathematical helpers for formatting, rounding, percentages, and price calculations.


## Ceiling

Rounds a number **upward** to the nearest multiple of the given significance.  

Useful when you want values aligned to a grid, step, or batch size (e.g., rounding prices, quantities, or time intervals).

    <?php
    
    use Krystal\Text\Math;
    
    echo Math::ceiling(5.2);          // 6   (nearest integer up)
    echo Math::ceiling(5.2, 0.5);     // 5.5 (nearest 0.5 up)
    echo Math::ceiling(5.2, 2);       // 6   (nearest multiple of 2 up)
    echo Math::ceiling(17, 10);       // 20  (nearest multiple of 10 up)
    echo Math::ceiling(-5.2, 2);      // -4  (rounds upward towards zero)


## Rounding

Formats a number to a given number of decimals **without rounding it**, unlike PHP’s built-in `number_format()`.  

This is useful when you need precise display formatting while keeping original numeric values intact (e.g., truncating monetary values or measurements).

    <?php
    
    use Krystal\Text\Math;
    
    echo Math::numberFormat(1234.5678);         // "1,234.56"
    echo Math::numberFormat(1234.5678, 3);      // "1,234.567"
    echo Math::numberFormat(1234.5, 2, ',', ' '); // "1 234,50"
    echo Math::numberFormat(9.999, 2);          // "9.99" (no rounding to 10.00)

## Round collection

Rounds all numeric values in an array to the given precision.  

This is handy for mass-formatting collections of prices, measurements, or any numeric dataset before display or storage.

    <?php
    
    use Krystal\Text\Math;
    
    $data = [10.555, 20.499, 30.789];
    
    print_r(Math::roundCollection($data));
    // [10.56, 20.50, 30.79]
    
    print_r(Math::roundCollection($data, 1));
    // [10.6, 20.5, 30.8]
    
    print_r(Math::roundCollection(['a' => 1.234, 'b' => 'text', 'c' => 5.678]));
    // ['a' => 1.23, 'b' => 'text', 'c' => 5.68]


## Average

Calculates the **arithmetic mean (average)** of a collection of values.  
Automatically skips empty arrays to avoid division by zero.

    <?php 
    
    use Krystal\Text\Math;
    
    echo Math::average([2, 4, 6, 8]);   // 5
    echo Math::average([10, 20, 30]);   // 20
    echo Math::average([]);             // 0
    echo Math::average([100]);          // 100

## Find percentage

Calculates the numeric value represented by a percentage of a given target.  

For example, finding 20% of 150 gives `30`.

    <?php
    
    use Krystal\Text\Math;
    
    echo Math::fromPercentage(200, 10);   // 20.00
    echo Math::fromPercentage(150, 25);   // 37.50
    echo Math::fromPercentage(99.99, 5);  // 5.00
    echo Math::fromPercentage(50, 0);     // 0.00

## Calculate percentage

Calculates what percentage an **actual value** represents out of a **total**.  

For example, if 20 out of 50 is 40%.

    <?php
    
    use Krystal\Text\Math;
    
    echo Math::percentage(200, 50);       // 25.0
    echo Math::percentage(80, 20, 2);     // 25.00
    echo Math::percentage(100, 0);        // 0
    echo Math::percentage(0, 50);         // 0 (avoid division by zero)
    echo Math::percentage(300, 123, 0);   // 41

## Calculate discount

Calculates the **discounted price** based on the initial price and discount percentage.  

For example, applying 20% off to 100 USD gives 80 USD.

    <?php
    
    use Krystal\Text\Math;
    
    echo Math::getDiscount(100, 20);    // 80.00
    echo Math::getDiscount(250, 15);    // 212.50
    echo Math::getDiscount(99.99, 5);   // 94.99
    echo Math::getDiscount(50, 0);      // 50.00
