<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Bootstrap5\Carousel;

use Krystal\Form\NodeElement;

final class CarouselMaker
{
    /**
     * Carousel slides
     * 
     * @var array
     */
    private $slides = array();

    /**
     * Carousel options
     * 
     * @var array
     */
    private $options = array();

    /**
     * State initialization
     * 
     * @param array $slides
     * @param array $options
     * @return void
     */
    public function __construct(array $slides, array $options = array())
    {
        $this->slides = $slides;
        $this->options = $options;
    }

    /**
     * Render carousel
     * 
     * @return string
     */
    public function render()
    {
        // Default options
        $defaults = array(
            'fade' => false,
            'indicators' => true,
            'controls' => true,
            'touch' => true,
            'dark' => false,
            'pause' => 'hover',
            'keyboard' => true,
            'ride' => 'carousel',
            'interval' => 5000 // 5 Seconds
        );

        // Generate unique carousel ID
        $target = isset($this->options['id']) ? $this->options['id'] : 'carousel-id-' . uniqid();

        // Merge defaults with overridden options
        $options = array_merge($defaults, $this->options);

        $carousel = $this->createCarousel($target, $this->slides, $options);
        return $carousel->render();
    }

    /**
     * Render carousel
     * 
     * @param string $target
     * @param array $slides
     * @param array $options
     * @return \Krystal\Form\NodeElement
     */
    private function createCarousel($target, array $slides, array $options)
    {
        // Do we have more than one slide?
        $singular = count($slides) <= 1;
        $carouselClass = $options['fade'] == true ? 'carousel slide carousel-fade' : 'carousel slide';

        if ($options['dark'] == true) {
            $carouselClass .= ' carousel-dark';
        }

        $wrapper = new NodeElement();
        $wrapper->openTag('div')
                ->addAttributes(array(
                    'id' => $target,
                    'class' => $carouselClass,
                    'data-bs-ride' => 'carousel',
                    'data-bs-touch' => $options['touch'] == true ? 'true' : 'false',
                    'data-bs-interval' => is_numeric($options['interval']) ? $options['interval'] : 'false',
                    'data-bs-keyboard' => $options['keyboard'] == true ? 'true' : 'false',
                    'data-bs-ride' => (is_bool($options['ride']) ? ($options['ride'] == true ? 'true' : 'false') : $options['ride'])
                ));

        // Hovering on active carousel
        if (isset($options['pause'])) {
            $wrapper->addAttribute('data-bs-pause', (bool) $options['pause'] ? 'hover' : 'false');
        }

        // Do we need indicators?
        if ($options['indicators'] == true && !$singular) {
            $wrapper->appendChild($this->createIndicators($target, $slides));
        }

        // Append slides
        $wrapper->appendChild($this->createItems($slides));

        // Do we need controls?
        if ($options['controls'] == true && !$singular) {
            $wrapper->appendChildren($this->createControls($target, $slides));
        }

        $wrapper->closeTag();
        return $wrapper;
    }

    /**
     * Create controls
     * 
     * @param string $target Name of parent container
     * @return array
     */
    private function createControls($target)
    {
        return array(
            $this->createControl($target, 'prev'),
            $this->createControl($target, 'next')
        );
    }

    /**
     * Create single arrow
     * 
     * @param string $target Name of parent container
     * @param string $type Either next or prev
     * @return \Krystal\Form\NodeElement
     */
    private function createControl($target, $type)
    {
        $icon = new NodeElement();
        $icon->openTag('span')
             ->addAttributes(array(
                'class' => sprintf('carousel-control-%s-icon', $type),
                'aria-hidden' => 'true'
            ))
            ->finalize()
            ->closeTag();

        $hidden = new NodeElement();
        $hidden->openTag('span')
               ->addAttribute('class', 'visually-hidden')
               ->setText($type != 'next' ? 'Previous' : 'Next')
               ->closeTag();
        
        $button = new NodeElement();
        $button->openTag('button')
               ->addAttributes(array(
                    'class' => sprintf('carousel-control-%s', $type),
                    'type' => 'button',
                    'data-bs-target' => '#' . $target,
                    'data-bs-slide' => $type
                ))
                ->appendChildren(array($icon, $hidden))
                ->closeTag();

        return $button;
    }

    /**
     * Create slide indicators
     * 
     * @param string $target Name of parent container
     * @param array $slides
     * @return \Krystal\Form\NodeElement
     */
    private function createIndicators($target, array $slides)
    {
        $wrapper = new NodeElement();
        $wrapper->openTag('div')
                ->addAttribute('class', 'carousel-indicators');

        foreach ($slides as $index => $slide) {
            $child = $this->createIndicator($target, $index, $index == 0);
            $wrapper->appendChild($child);
        }

        $wrapper->closeTag();
        return $wrapper;
    }

    /**
     * Create indicator
     * 
     * @param string $target Name of parent container
     * @param int $index
     * @param boolean $active Whether current indicator is active
     * @return \Krystal\Form\NodeElement
     */
    private function createIndicator($target, $index, $active)
    {
        $button = new NodeElement();
        $button->openTag('button')
               ->addAttributes(array(
                'type' => 'button',
                'data-bs-target' => '#' . $target,
                'data-bs-slide-to' => $index,
                'class' => $active ? 'active' : null,
                'aria-current' => $active ? 'true' : 'false'
               ))
              ->finalize()
              ->closeTag();

        return $button;
    }

    /**
     * Create items
     * 
     * @param array $items
     * @return \Krystal\Form\NodeElement
     */
    private function createItems(array $items)
    {
        $wrapper = new NodeElement();
        $wrapper->openTag('div')
                ->addAttribute('class', 'carousel-inner');

        foreach ($items as $index => $item) {
            $child = $this->createItem(
                $item['src'],
                isset($item['alt']) ? $item['alt'] : null,
                $index == 0, 
                isset($item['caption']) ? $item['caption'] : null,
                isset($item['interval']) ? $item['interval'] : null
            );

            $wrapper->appendChild($child);
        }

        $wrapper->closeTag();
        return $wrapper;
    }

    /**
     * Create inner item element
     * 
     * @param string $src Image path
     * @param string $alt Alternate name
     * @param boolean $active Whether this one is active
     * @param string|array Optional caption
     * @param int $interval Individual interval
     * @return \Krystal\Form\NodeElement
     */
    private function createItem($src, $alt, $active, $caption, $interval = null)
    {
        // Create image element
        $img = new NodeElement();
        $img->openTag('img')
            ->addAttributes(array(
                'src' => $src,
                'class' => 'img-fluid d-block w-100',
                'alt' => $alt
            ))
            ->finalize();

        $item = new NodeElement();
        $item->openTag('div')
             ->addAttribute('class', $active ? 'carousel-item active' : 'carousel-item');

        // Item interval
        if (is_numeric($interval)) {
            $item->addAttribute('data-bs-interval', $interval);
        }

        $item->appendChild($img);

        if ($caption !== null){
            $item->appendChild($this->createItemCaption($caption));
        }

        $item->closeTag();
        return $item;
    }

    /**
     * Create item caption
     * 
     * @param string|array $caption
     * @return \Krystal\Form\NodeElement
     */
    private function createItemCaption($caption)
    {
        $wrapper = new NodeElement();
        $wrapper->openTag('div')
                ->addAttribute('class', 'carousel-caption')
                ->finalize();

        if (is_array($caption)) {
            if (isset($caption['title'])) {
                $title = new NodeElement();
                $title->openTag('h5')
                      ->finalize()
                      ->setText($caption['title'])
                      ->closeTag();

                // Append title element
                $wrapper->appendChild($title);
            }

            if (isset($caption['description'])) {
                $description = new NodeElement();
                $description->openTag('p')
                            ->finalize()
                            ->setText(nl2br($caption['description']))
                            ->closeTag();

                // Append description element
                $wrapper->appendChild($description);
            }
        } else {
            // Append raw HTML
            $wrapper->append($caption);
        }

        $wrapper->closeTag();
        return $wrapper;
    }
}
