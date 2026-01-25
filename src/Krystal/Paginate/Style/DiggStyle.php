<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Paginate\Style;

final class DiggStyle implements StyleInterface
{
    /**
     * Start from
     * 
     * @var integer
     */
    private $start;

    /**
     * State initialization
     * 
     * @param integer $start
     * @return void
     */
    public function __construct($start = 3)
    {
        $this->start = $start;
    }

    /**
     * Returns filtered array via this style adapter
     * 
     * @param array $page Array of page numbers
     * @param integer $current Current page number
     * @return array
     */
    public function getPageNumbers(array $pages, $current)
    {
        $result = array();
        $amount = count($pages);

        // If we have fewer pages than the start limit, just show them all
        if ($amount <= $this->start) {
            return range(1, $amount);
        }

        // 1. Always show the first page
        $result[] = 1;

        // 2. Define the sliding window
        // We want to show a range of pages. If current is near the start, 
        // we show up to $this->start.
        $min = max($current - 2, 2);
        $max = min($current + 2, $amount - 1);

        // If we are near the beginning, ensure we show up to $this->start
        if ($current <= $this->start) {
            $min = 2;
            $max = max($this->start, $max);
        }

        // 3. Add separator if there's a gap between 1 and our min
        if ($min > 2) {
            $result[] = '...';
        }

        // 4. Generate the middle numbers
        for ($i = $min; $i <= $max; $i++) {
            // Ensure we don't duplicate the first or last page
            if ($i > 1 && $i < $amount) {
                $result[] = $i;
            }
        }

        // 5. Add separator if there's a gap between our max and the end
        if ($max < $amount - 1) {
            $result[] = '...';
        }

        // 6. Always show the last page
        $result[] = $amount;

        return $result;
    }
}
