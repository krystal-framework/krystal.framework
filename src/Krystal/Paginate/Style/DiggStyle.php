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
        $start = 3;

        if ($amount > $start) {

            // this specifies the range of pages we want to show in the middle
            $min = max($current - 2, 2);
            $max = min($current + 2, $amount - 1);

            // we always show the first page
            $result[] = 1;

            // we're more than one space away from the beginning, so we need a separator
            if ($min > 2) {
                $result[] = '...';
            }

            // generate the middle numbers
            for ($i = $min; $i < $max + 1; $i++) {
                $result[] = $i;
            }

            // we're more than one space away from the end, so we need a separator
            if ($max < $amount - 1) {
                $result[] = '...';
            }

            // we always show the last page, which is the same as amount
            $result[] = $amount;

            return $result;

        } else {

            // It's not worth using a style adapter, because amount of pages is less than 3
            return range(1, $start);
        }
    }
}
