<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Route;

interface MapManagerInterface
{
    /**
     * Converts route notation to PSR-compliant
     * 
     * @param string $notation Route's notation
     * @return string
     */
    public function toCompliant($notation);

    /**
     * Gets associated options by URI template
     * 
     * @param string $uriTemplate
     * @param string $option Optionally can be filtered by some existing option
     * @return array
     */
    public function getDataByUriTemplate($uriTemplate, $option = null);

    /**
     * Returns all loaded controllers
     * 
     * @return array
     */
    public function getControllers();

    /**
     * Finds all URI templates associated with a controller
     * 
     * @param string $controller
     * @return string
     */
    public function findUriTemplatesByController($controller);

    /**
     * Gets URL template by its associated controller
     * This method is  basically used when building URLs by their associated controllers
     * 
     * @param string $controller (Module:Controller@action)
     * @return string|boolean
     */
    public function getUrlTemplateByController($controller);

    /**
     * Returns URI map
     * 
     * @return array
     */
    public function getURIMap();

    /**
     * Returns a controller by its associated URI template
     * 
     * @param string $uriTemplate URI template, not actual one
     * @throws \RuntimeException if URI doesn't belong to route map
     * @return string|boolean
     */
    public function getControllerByURITemplate($uriTemplate);

    /**
     * Returns action by associated URI template
     * 
     * @param string $uriTemplate
     * @throws \RuntimeException if $uriTemplate does not belong to route map
     * @return string|boolean
     */
    public function getActionByURITemplate($uriTemplate);
}
