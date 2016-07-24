<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Response;

interface StatusGeneratorInterface
{
    /**
     * Checks whether status code is valid
     * 
     * @param string $code
     * @return boolean
     */
    public function isValid($code);

    /**
     * Returns description by its associated code
     * 
     * @param integer $code Status code
     * @throws \OutOfRangeException If the supplied code is out of range
     * @return string
     */
    public function getDescriptionByStatusCode($code);

    /**
     * Generates status header by associated code
     * 
     * @param integer $code
     * @return string|boolean
     */
    public function generate($code);
}
