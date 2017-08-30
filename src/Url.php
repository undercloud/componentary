<?php
namespace Componentary;

/**
 * URL Builder
 *
 * @package Componentary
 * @author  undercloud <lodashes@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    http://github.com/undercloud/componentary
 */
class Url
{
    /**
     * @var string
     */
    private static $defaultScheme = 'unsupportedschemetype';

    /**
     * @var array
     */
    private static $parts = [
        'scheme','user','pass','host',
        'port','path','query','fragment'
    ];

    /**
     * @param string|null $url value
     */
    public function __construct($url = null)
    {
        if (null === $url) {
            foreach (self::$parts as $part) {
                $this->$part = null;
            }
        } else {
            $url = $this->normalize($url);

            $parsed = @parse_url($url);

            foreach (self::$parts as $part) {
                $this->$part = ((isset($parsed[$part])) ? $parsed[$part] : null);
            }

            if ($this->query) {
                parse_str($this->query, $this->query);
            } else {
                $this->query = [];
            }

            if ($this->scheme === self::$defaultScheme) {
                $this->scheme = null;
            }
        }
    }

    /**
     * Normalize URL scheme
     *
     * @param string $url value
     *
     * @return string
     */
    private function normalize($url)
    {
        if (0 === stripos($url, '//')) {
            $url = self::$defaultScheme . ':' . $url;
        } else if (false === stripos($url, '://')) {
            $url = self::$defaultScheme . '://' . $url;
        }

        return $url;
    }

    /**
     * Build URL from parts
     *
     * @return string
     */
    private function join()
    {
        $inline = '';
        if (isset($this->scheme)) {
            $inline .= $this->scheme . '://';
        }

        if (isset($this->user)) {
            $inline .= $this->user;
            if (isset($this->pass)) {
                $inline .= ':' . $this->pass;
            }

            $inline .= '@';
        }

        if (isset($this->host)) {
            $inline .= $this->host;
        }

        if (isset($this->port)) {
            $inline .= ':' . $this->port;
        }

        if (isset($this->path)) {
            $inline .= $this->path;
        }

        if (isset($this->query)) {
            $inline .= '?' . http_build_query((array) $this->query);
        }

        if (isset($this->fragment)) {
            $inline .= '#' . $this->fragment;
        }

        return $inline;
    }

    /**
     * __toString magic
     *
     * @return string
     */
    public function __toString()
    {
        return $this->join();
    }
}