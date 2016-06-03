<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Security;

interface Sanitizeable
{
    const FILTER_NONE = 0;
    const FILTER_FLOAT = 1;
    const FILTER_INT = 2;
    const FILTER_BOOL = 3;
    const FILTER_HTML = 4;
    const FILTER_TAGS = 5;
    const FILTER_SAFE_TAGS = 6;
}
