<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

final class RawSqlFragment implements RawSqlFragmentInterface
{
    /**
     * A fragment itself
     * 
     * @var string
     */
    private $fragment;

    /**
     * State initialization
     * 
     * @param string|\Krystal\Db\Sql\QueryBuilderInterface $fragment
     * @return void
     */
    public function __construct($fragment)
    {
        $this->fragment = $fragment;
    }

    /**
     * Returns defined fragment
     * 
     * @return string
     */
    public function getFragment()
    {
        if ($this->fragment instanceof QueryBuilderInterface) {
            return $this->fragment->getQueryString();
        } else {
            return $this->fragment;
        }
    }
}
