<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Logging;

final class LogLevel
{
    /**
     * System is unusable
     */
    const EMERGENCY = 'emergency';

    /**
     * Action must be taken immediately
     */
    const ALERT = 'alert';

    /**
     * Critical conditions
     */
    const CRITICAL = 'critical';

    /**
     * Runtime errors that do not require immediate action
     */
    const ERROR = 'error';

    /**
     * Exceptional occurrences that are not errors
     */
    const WARNING = 'warning';

    /**
     * Normal but significant events
     */
    const NOTICE = 'notice';

    /**
     * Interesting events
     */
    const INFO = 'info';

    /**
     * Detailed debug information
     */
    const DEBUG = 'debug';
}