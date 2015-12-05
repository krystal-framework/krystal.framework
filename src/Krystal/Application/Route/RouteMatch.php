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

final class RouteMatch implements RouteMatchInterface
{
    /**
     * Matched URI itself
     * 
     * @var string
     */
    private $matchedURI;

    /**
     * Matched URI template
     * 
     * @var string
     */
    private $matchedURITemplate;

    /**
     * Route request method
     * 
     * @var string
     */
    private $method;

    /**
     * Route variables
     * 
     * @var array
     */
    private $variables = array();

    /**
     * Defines a matched URI
     * 
     * @param string $matchedURI
     * @return \Krystal\Application\Route\RouteMatch
     */
    public function setMatchedURI($matchedURI)
    {
        $this->matchedURI = $matchedURI;
        return $this;
    }

    /**
     * Returns matched URI
     * 
     * @return string
     */
    public function getMatchedURI()
    {
        return $this->matchedURI;
    }

    /**
     * Defines matched URI template
     * 
     * @param string $matchedURITemplate
     * @return \Krystal\Application\Route\RouteMatch
     */
    public function setMatchedURITemplate($matchedURITemplate)
    {
        $this->matchedURITemplate = $matchedURITemplate;
        return $this;
    }

    /**
     * Returns a matched URI template
     * 
     * @return string
     */
    public function getMatchedURITemplate()
    {
        return $this->matchedURITemplate;
    }

    /**
     * Defines a method
     * 
     * @param string $method
     * @return \Krystal\Application\Route\RouteMatch
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Returns route method
     * 
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Defines route variables
     * 
     * @param array $variables
     * @return \Krystal\Application\Route\RouteMatch
     */
    public function setVariables(array $variables)
    {
        $this->variables = $variables;
        return $this;
    }

    /**
     * Return route variables
     * 
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }
}
