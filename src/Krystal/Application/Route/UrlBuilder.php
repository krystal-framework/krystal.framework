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

use RuntimeException;

final class UrlBuilder implements UrlBuilderInterface
{
    /**
     * Map manager
     * 
     * @var \Krystal\Application\Route\MapManager
     */
    private $mapManager;

    /**
     * Variable parameter in routes to be substituted
     * 
     * @const string
     */
    const ROUTE_PARAM_VAR = '(:var)';

    /**
     * State initialization
     * 
     * @param \Krystal\Application\Route\MapManager $mapManager
     * @return void
     */
    public function __construct(MapManager $mapManager)
    {
        $this->mapManager = $mapManager;
    }

    /**
     * Count amount of variables in given URI template
     * 
     * @param string $template URI template
     * @return integer
     */
    private function getVarCount($template)
    {
        return substr_count($template, self::ROUTE_PARAM_VAR);
    }

    /**
     * Checks whether URI template has at least one variable
     * 
     * @param string $template URI template
     * @return boolean
     */
    private function hasVar($template)
    {
        return strpos($template, self::ROUTE_PARAM_VAR) !== false;
    }

    /**
     * Prepares URL
     * 
     * @param string $controller
     * @param string $template URI template
     * @param array $vars
     * @throws \RuntimeException When count mismatches
     * @return string
     */
    private function prepare($controller, $template, array $vars)
    {
        $varCount = $this->getVarCount($template);
        $currentCount = count($vars);

        $template = str_replace(self::ROUTE_PARAM_VAR, '%s', $template);
        return vsprintf($template, $vars);
    }

    /**
     * Creates query URL
     * 
     * @param string $controller
     * @param array $args
     * @param string $index
     * @param boolean $decode
     * @return string
     */
    public function createQueryUrl($controller, array $args = array(), $index = 0, $decode = true)
    {
        $fragment = '?'.http_build_query($args);

        if ($decode === true) {
            $fragment = urldecode($fragment);
        }

        return $this->createUrl($controller, array(), $index) . $fragment;
    }

    /**
     * Creates URL
     * 
     * @param string $controller In <Module:Path@method> format
     * @param array $args A collection of arguments to be passed to the method
     * @param integer $index Index in case, one controller action has more than one route
     * @return string
     */
    public function createUrl($controller, array $args = array(), $index = 0)
    {
        $collection = $this->mapManager->findUriTemplatesByController($controller);

        if (!empty($collection)) {
            if (isset($collection[$index])) {
                return $this->createVariadicUrl($collection[$index], $args);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Creates variadic URL
     * 
     * @param string $haystack URI template
     * @param array $args Arguments to be substituted
     * @return string
     */
    private function createVariadicUrl($haystack, array $args)
    {
        $varCount = substr_count($haystack, self::ROUTE_PARAM_VAR);
        $argCount = count($args);

        // In case, the length is different
        if ($varCount != $argCount) {
            $difference = abs($argCount - $varCount);

            for ($i = 0; $i < $difference; $i++) {
                array_push($args, self::ROUTE_PARAM_VAR);
            }
        }

        $haystack = str_replace(self::ROUTE_PARAM_VAR, '%s', $haystack);
        return vsprintf($haystack, $args);
    }

    /**
     * Builds URL
     * 
     * @param string $controller Controller name in format <Module>:<Controller>@<Action>
     * @param array $vars
     * @return string Null on failure
     */
    public function build($controller, array $vars = array())
    {
        $template = $this->mapManager->getUrlTemplateByController($controller);

        if ($template !== false) {
            if ($this->hasVar($template)) {
                return $this->prepare($controller, $template, $vars);
            } else {
                return $template;
            }

        } else {
            // Wrong controller, so nothing to return there
            return null;
        }
    }
}
