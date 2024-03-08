
Offcanvas widget
=====

Simple usage:

<?php

use Krystal\Widget\Bootstrap5\Offcanvas\OffcanvasMaker;

$offcanvas = new OffcanvasMaker;

echo $offcanvas->renderButton('Open canvas', 'btn btn-primary');
echo $offcanvas->renderOffcanvas('Header', 'Body');

?>


Options
-------

`scrolling`

Whether to enabled body scrolling

`static`

Whether static backdrop required

`placement`

Whether to place Offcanvas. Possible values are: `top, bottom, start, end`