<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Response;

interface FileDownloaderInterface
{
	/**
	 * Sends downloadable headers for a file
	 * 
	 * @param string $filename A path to the target file
	 * @param string $alias Basename name can be optionally changed
	 * @throws \RuntimeException If can't access the target file
	 * @return void
	 */
	public function download($target, $alias = null);
}
