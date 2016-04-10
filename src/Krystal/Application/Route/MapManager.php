<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Route;

use Krystal\Stdlib\ArrayUtils;
use RuntimeException;

final class MapManager implements MapManagerInterface
{
    /**
     * Current route map
     * 
     * @var array
     */
    private $map = array();

    /**
     * Parser for route notations
     * 
     * @var \Krystal\Application\Route\NotationInterface
     */
    private $notation;

    /**
     * State initialization
     * 
     * @param array $map
     * @param \Krystal\Application\Route\NotationInterface $notation
     * @return void
     */
    public function __construct(array $map, RouteNotationInterface $notation = null)
    {
        $this->map = $map;
        $this->notation = $notation;
    }

    /**
     * Converts route notation to PSR-compliant
     * 
     * @param string $notation Route's notation
     * @return string
     */
    public function toCompliant($notation)
    {
        return $this->notation->toCompliant($notation);
    }

    /**
     * Gets associated options by URI template
     * 
     * @param string $uriTemplate
     * @param string $option Optionally can be filtered by some existing option
     * @return array
     */
    public function getDataByUriTemplate($uriTemplate, $option = null)
    {
        if (isset($this->map[$uriTemplate])) {
            $target = $this->map[$uriTemplate];

            if ($option !== null) {
                // The option might not be set, so ensure
                if (!isset($target[$option])) {
                    throw new RuntimeException(sprintf('Can not read non-existing option %s for "%s"', $option, $uriTemplate));
                } else {
                    return $target[$option];
                }

            } else {
                return $target;
            }

        } else {
            throw new RuntimeException(sprintf(
                'URI "%s" does not belong to route map. Cannot get a controller in %s', $uriTemplate, __METHOD__
            ));
        }
    }

    /**
     * Returns all loaded controllers
     * 
     * @return array
     */
    public function getControllers()
    {
        $map = $this->map;
        $result = array();

        foreach ($map as $template => $options) {
            if (isset($options['controller'])) {
                $result[] = $options['controller'];
            }
        }

        return $result;
    }

    /**
     * Finds all URI templates associated with a controller
     * 
     * @param string $controller
     * @return string
     */
    public function findUriTemplatesByController($controller)
    {
        $result = array();

        foreach ($this->map as $uriTemplate => $options) {
            if (isset($options['controller']) && $options['controller'] == $controller) {
                array_push($result, $uriTemplate);
            }
        }

        return $result;
    }

    /**
     * Gets URL template by its associated controller
     * This method is  basically used when building URLs by their associated controllers
     * 
     * @param string $controller (Module:Controler@action)
     * @return string|boolean
     */
    public function getUrlTemplateByController($controller)
    {
        $result = $this->findUriTemplatesByController($controller);

        // Now check the results of search
        if (isset($result[0])) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * Returns URI map
     * 
     * @return array
     */
    public function getURIMap()
    {
        return array_keys($this->map);
    }

    /**
     * Returns a controller by its associated URI template
     * 
     * @param string $uriTemplate URI template, not actual one
     * @throws \RuntimeException if URI doesn't belong to route map
     * @return string|boolean
     */
    public function getControllerByURITemplate($uriTemplate)
    {
        $controller = $this->getDataByUriTemplate($uriTemplate, 'controller');
        $separatorPosition = strpos($controller, '@');

        if ($separatorPosition !== false) {

            $controller = substr($controller, 0, $separatorPosition);
            return $this->notation->toClassPath($controller);
        } else {

            // No separator
            return false;
        }
    }

    /**
     * Returns action by associated URI template
     * 
     * @param string $uriTemplate
     * @throws \RuntimeException if $uriTemplate does not belong to route map
     * @return string|boolean
     */
    public function getActionByURITemplate($uriTemplate)
    {
        $controller = $this->getDataByUriTemplate($uriTemplate, 'controller');
        $separatorPosition = strpos($controller, '@');

        if ($separatorPosition !== false) {
            $action = substr($controller, $separatorPosition + 1);
            return $action;

        } else {

            // No separator
            return false;
        }
    }
}