<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Tool;

interface FileHandlerInterface
{
    /**
     * Delete all images associated with target id
     * When no $image provided, then it will removed all images inside $id folder
     * 
     * @param string $id Target id
     * @param string $image Optional filter. Image basename to be removed for all dimensions
     * @return boolean
     */
    public function delete($id, $image = null);
}
