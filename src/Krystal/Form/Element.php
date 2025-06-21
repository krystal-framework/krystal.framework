<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form;

use Krystal\Form\Element as Node;
use UnexpectedValueException;
use Closure;

class Element
{
    /**
     * Creates dynamic input element
     * 
     * @param string $type Input type
     * @param string $name
     * @param string $value
     * @param array $attributes Input attributes
     * @param array $extra Select option elements, in case 'select' is supplied as a type
     * @throws \UnexpectedValueException If unknown type supplied
     * @return string
     */
    public static function dynamic($type, $name, $value, array $attributes = array(), $extra = array())
    {
        switch ($type) {
            case 'text':
                return self::text($name, $value, $attributes);
            case 'password':
                return self::password($name, $value, $attributes);
            case 'url':
                return self::url($name, $value, $attributes);
            case 'range':
                return self::range($name, $value, $attributes);
            case 'number':
                return self::number($name, $value, $attributes);
            case 'hidden':
                return self::hidden($name, $value, $attributes);
            case 'date':
                return self::date($name, $value, $attributes);
            case 'time':
                return self::time($name, $value, $attributes);
            case 'color':
                return self::color($name, $value, $attributes);
            case 'textarea':
                return self::textarea($name, $value, $attributes);
            case 'radio':
                return self::radio($name, $value, (bool) $value, $attributes);
            case 'checkbox':
                return self::checkbox($name, $value, $attributes, false);
            case 'select':
                return self::select($name, $extra, $value, $attributes);
            case 'datalist':
                return self::datalist($name, $value, $extra, $attributes);
            default:
                throw new UnexpectedValueException(sprintf('Unexpected value supplied %s', $type));
        }
    }

    /**
     * Renders JSON+LD element
     * 
     * @param array $data
     * @return string
     */
    public static function jsonLd(array $data)
    {
        $node = new Node\JsonLd($data);
        return $node->render(array());
    }

    /**
     * Creates icon element
     * 
     * @param string $class
     * @return string
     */
    private static function iconInternal($class)
    {
        return sprintf('<i class="%s"></i> ', $class);
    }

    /**
     * Creates a link with inner icon
     * 
     * @param string $icon
     * @param string $link
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function icon($icon, $link, array $attributes = array())
    {
        return self::link(self::iconInternal($icon), $link, $attributes);
    }

    /**
     * Creates link element
     * 
     * @param string $text
     * @param string $link
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function link($text, $link = '#', array $attributes = array())
    {
        if (!is_null($link)) {
            $attributes['href'] = $link;
        }

        $node = new Node\Link($text);
        return $node->render($attributes);
    }

    /**
     * Creates link to social media
     * 
     * @param array $data Values generator
     * @param string $class Link class
     * @param boolean $newWindow Whether should be opened in new window
     * @param string $icon Optional icon class
     * @return string
     */
    private static function linkSocial(array $data, $class, $newWindow = true, $icon = null)
    {
        if ($icon != null) {
            $icon = self::iconInternal($icon);
        }

        return self::link($icon . $data['text'], $data['url'], [
            'class' => $class,
            'target' => $newWindow ? '_blank' : '_self',
        ]);
    }

    /**
     * Creates Facebook link
     * 
     * @param string $username Facebook username
     * @param string $text Link text. If not provided, username is used
     * @param string $class Optional link class
     * @param boolean $newWindow Whether should be opened in new window
     * @param string $icon Optional icon class
     * @return string
     */
    public static function linkFacebook($username, $text = null, $class = null, $newWindow = true, $icon = 'bi bi-facebook')
    {
        $data = [
            'url' => sprintf('https://www.facebook.com/%s', $username),
            'text' => $text == null ? $username : $text
        ];

        return self::linkSocial($data, $class, $newWindow, $icon);
    }

    /**
     * Creates Instagram link
     * 
     * @param string $username Instagram username
     * @param string $text Link text. If not provided, username is used
     * @param string $class Optional link class
     * @param boolean $newWindow Whether should be opened in new window
     * @param string $icon Optional icon class
     * @return string
     */
    public static function linkInstagram($username, $text = null, $class = null, $newWindow = true, $icon = 'bi bi-instagram')
    {
        $data = [
            'url' => sprintf('https://www.instagram.com/%s/', $username),
            'text' => $text == null ? $username : $text
        ];

        return self::linkSocial($data, $class, $newWindow, $icon);
    }

    /**
     * Creates Telegram link
     * 
     * @param string $username Telegram username
     * @param string $text Link text. If not provided, username is used
     * @param string $class Optional link class
     * @param boolean $newWindow Whether should be opened in new window
     * @param string $icon Optional icon class
     * @return string
     */
    public static function linkTelegram($username, $text = null, $class = null, $newWindow = true, $icon = 'bi bi-telegram')
    {
        $data = [
            'url' => sprintf('https://t.me/%s', $username),
            'text' => $text == null ? $username : $text
        ];

        return self::linkSocial($data, $class, $newWindow, $icon);
    }

    /**
     * Creates WhatsApp link
     * 
     * @param string $username WhatsApp username
     * @param string $text Link text. If not provided, username is used
     * @param string $class Optional link class
     * @param boolean $newWindow Whether should be opened in new window
     * @param string $icon Optional icon class
     * @return string
     */
    public static function linkWhatsApp($username, $text = null, $class = null, $newWindow = true, $icon = 'bi bi-whatsapp')
    {
        $data = [
            'url' => sprintf('https://wa.me/%s', $username),
            'text' => $text == null ? $username : $text
        ];

        return self::linkSocial($data, $class, $newWindow, $icon);
    }

    /**
     * Creates mailto attribute
     * 
     * @param string $email
     * @param array $params Optional params
     * @return string
     */
    public static function createMailTo($email, array $params = [])
    {
        if (!empty($params)) {
            $attribute = $email . '?' . http_build_query($params);
        } else {
            $attribute = $email;
        }

        return sprintf('mailto:%s', $attribute);
    }

    /**
     * Creates E-mail link
     * 
     * @param string $email
     * @param string $class Optional CSS class
     * @param boolean $newWindow Whether should be opened in new window
     * @param string $icon Optional icon class
     * @return string
     */
    public static function linkEmail($email, $class = null, $newWindow = true, $icon = null)
    {
        if ($icon != null) {
            $icon = self::iconInternal($icon);
        }

        return self::link($icon . $email, self::createMailTo($email), [
            'target' => $newWindow ? '_blank' : '_self',
            'class' => $class
        ]);
    }

    /**
     * Creates tel attribute
     * 
     * @param string $phone Raw phone string
     * @return string
     */
    public static function createTel($phone)
    {
        // Strip out all chars except numbers and +
        $filtered = preg_replace('/[^0-9+]/', '', $phone);

        return sprintf('tel:%s', $filtered);
    }

    /**
     * Creates phone link
     * 
     * @param string $phone
     * @param string $class Optional CSS class
     * @param string $icon Optional icon class
     * @return string
     */
    public static function linkPhone($phone, $class = null, $icon = null)
    {
        if ($icon != null) {
            $icon = self::iconInternal($icon);
        }

        return self::link($icon . $phone, self::createTel($phone), [
            'class' => $class
        ]);
    }

    /**
     * Creates label element
     * 
     * @param string $caption
     * @param string $for
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function label($caption, $for = null, array $attributes = array())
    {
        if (!is_null($for)) {
            $attributes['for'] = $for;
        }

        $node = new Node\Label($caption);
        return $node->render($attributes);
    }

    /**
     * Creates standalone "option" element
     * 
     * @param string $value
     * @param string $text
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function option($value, $text, array $attributes = array())
    {
        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        $node = new Node\Option($text);
        return $node->render($attributes);
    }

    /**
     * Creates reset button
     * 
     * @param string $text
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function reset($text, array $attributes = array())
    {
        $attributes['type'] = 'reset';
        return self::button($text, $attributes);
    }

    /**
     * Creates submit button
     * 
     * @param string $text
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function submit($text, array $attributes = array())
    {
        $attributes['type'] = 'submit';
        return self::button($text, $attributes);
    }

    /**
     * Creates button element
     * 
     * @param string $text
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function button($text, array $attributes = array())
    {
        $node = new Node\Button($text);
        return $node->render($attributes);
    }

    /**
     * Creates "Radio" node element
     * 
     * @param string $name Element name
     * @param string $name Element value
     * @param boolean $checked Whether it should be checked or not
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function radio($name, $value, $checked, array $attributes = array())
    {
        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        $node = new Node\Radio($checked);
        return $node->render($attributes);
    }

    /**
     * Creates "Checkbox" node element
     * 
     * @param string $name Element name
     * @param boolean $checked Whether it should be checked or not
     * @param array $attributes Extra attributes
     * @param boolean $serialize Whether the element should be serializeable or not
     * @return string
     */
    public static function checkbox($name, $checked, array $attributes = array(), $serialize = true)
    {
        if ($name !== null) {
            $attributes['name'] = $name;
        }

        $node = new Node\Checkbox($serialize, $checked);
        return $node->render($attributes);
    }

    /**
     * Renders file input with hidden name under the same name
     * 
     * @param string $name Shared name for both inputs
     * @param string $value Input value
     * @param string $accept What type of files the input should accept
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function fileData($name, $value, $accept, array $attributes = array())
    {
        $hidden = self::hidden($name, $value);
        $file = self::file($name, $accept, $attributes);

        return $hidden . $file;
    }

    /**
     * Creates "File" node element
     * 
     * @param string $name
     * @param string $accept What type of files the input should accept
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function file($name, $accept, array $attributes = array())
    {
        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($accept)) {
            $attributes['accept'] = $accept;
        }

        $node = new Node\File();
        return $node->render($attributes);
    }

    /**
     * Creates "Select" element with its child "Option" nodes
     * 
     * @param string $name
     * @param array $list
     * @param string|array $select Select child option
     * @param array $attributes Extra attributes
     * @param string|boolean $prompt Optional prompt text
     * @param \Closure $optionVisitor Optional visitor to build attributes of option tag
     * @return string
     */
    public static function select($name, array $list, $selected, array $attributes = array(), $prompt = false, Closure $optionVisitor = null)
    {
        if ($prompt !== false) {
            // Merge keeping indexes
            $list = array('' => $prompt) + $list;
        }

        $node = new Node\Select($list, $selected, array(), $optionVisitor);

        if ($name !== null) {
            $attributes['name'] = $name;
        }

        return $node->render($attributes);
    }

    /**
     * Renders datalist element
     * 
     * @param string $name Element name
     * @param string $value
     * @param array $list An array of list items
     * @param array $attributes Optional attributes
     * @return string
     */
    public static function datalist($name, $value, array $list, array $attributes = [])
    {
        $node = new Node\Datalist($list);

        if ($name !== null) {
            $attributes['name'] = $name;
        }

        if ($value !== null) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates textarea element
     * 
     * @param string $name Element name
     * @param string $text Text to be supplied between tags
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function textarea($name, $text, array $attributes = array())
    {
        if ($name !== null) {
            $attributes['name'] = $name;
        }

        $node = new Node\Textarea($text);
        return $node->render($attributes);
    }

    /**
     * Creates input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function input($name, $value, array $attributes = array())
    {
        $node = new Node\Input();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates text input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function text($name, $value, array $attributes = array())
    {
        $node = new Node\Text();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates color input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function color($name, $value, array $attributes = array())
    {
        $node = new Node\Color();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates date input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function time($name, $value, array $attributes = array())
    {
        $node = new Node\Time();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates date input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function date($name, $value, array $attributes = array())
    {
        $node = new Node\Date();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates datetime input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function datetime($name, $value, array $attributes = array())
    {
        $node = new Node\Datetime();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates week input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function week($name, $value, array $attributes = array())
    {
        $node = new Node\Week();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates month input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function month($name, $value, array $attributes = array())
    {
        $node = new Node\Month();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates email input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function email($name, $value, array $attributes = array())
    {
        $node = new Node\Email();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates hidden input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function hidden($name, $value, array $attributes = array())
    {
        $node = new Node\Hidden();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates number input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function number($name, $value, array $attributes = array())
    {
        $node = new Node\Number();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates range input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function range($name, $value, array $attributes = array())
    {
        $node = new Node\Range();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates URL input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function url($name, $value, array $attributes = array())
    {
        $node = new Node\Url();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Creates password input element
     * 
     * @param string $name Element name
     * @param string $value Element value
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function password($name, $value, array $attributes = array())
    {
        $node = new Node\Password();

        if (!is_null($name)) {
            $attributes['name'] = $name;
        }

        if (!is_null($value)) {
            $attributes['value'] = $value;
        }

        return $node->render($attributes);
    }

    /**
     * Renders object element
     * 
     * @param int $width
     * @param int $height
     * @param string $data
     * @return string
     */
    public static function object($width, $height, $data)
    {
        $node = new Node\Object();

        $attributes = array(
            'width' => $width,
            'height' => $height,
            'data' => $data
        );

        return $node->render($attributes);
    }

    /**
     * Renders audio element
     * 
     * @param array|string $src
     * @param array $attrs
     * @param string $error Error message can be overriden on demand
     * @return string
     */
    public static function audio($src, array $attrs = array(), $error = null)
    {
        $node = new Node\Audio($src, $error);

        return $node->render($attrs);
    }

    /**
     * Renders video element
     * 
     * @param array|string $src
     * @param array $attrs
     *   Available attributes
     *
     *   - boolean $autoplay Whether autoplay is required
     *   - boolean $loop Specifies that the video will start over again, every time it is finished
     *   - boolean $muted Specifies that the audio output of the video should be muted
     *   - int $width Optional width
     *   - int $height Optional height
     *   - string $poster Specifies an image to be shown while the video is downloading, or until the user hits the play button
     *
     * @param string $error Error message
     * @return string
     */
    public static function video($src, array $attrs = array(), $error = null)
    {
        $node = new Node\Video($src, $error);

        return $node->render($attrs);
    }

    /**
     * Creates image element
     * 
     * @param string $src Path to image
     * @param array $attributes Extra attributes
     * @return string
     */
    public static function image($src, array $attributes = array())
    {
        $node = new Node\Image();

        if (!is_null($src)) {
            $attributes['src'] = $src;
        }

        return $node->render($attributes);
    }
}
