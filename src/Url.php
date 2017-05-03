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
    private static $default_scheme = 'unsupportedschemetype';

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

            if ($this->scheme === self::$default_scheme) {
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
            $url = self::$default_scheme . ':' . $url;
        } else if (false === stripos($url, '://')) {
            $url = self::$default_scheme . '://' . $url;
        }

        return $url;
    }

    /**
     * Build URL from parts
     *
     * @param  array $parts map
     *
     * @return string
     */
    private function join(array $parts)
    {
        $inline = '';
        if (isset($parts['scheme'])) {
            $inline .= $parts['scheme'] . '://';
        }

        if (isset($parts['user'])) {
            $inline .= $parts['user'];
            if (isset($parts['pass'])) {
                $inline .= ':' . $parts['pass'];
            }

            $inline .= '@';
        }

        if (isset($parts['host'])) {
            $inline .= $parts['host'];
        }

        if (isset($parts['port'])) {
            $inline .= ':' . $parts['port'];
        }

        if (isset($parts['path'])) {
            $inline .= $parts['path'];
        }

        if (isset($parts['query'])) {
            $inline .= '?' . $parts['query'];
        }

        if (isset($parts['fragment'])) {
            $inline .= '#' . $parts['fragment'];
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
        $prepare = array_filter(self::$parts, function ($item) {
            return null !== $item;
        });

        if (isset($prepare['query'])) {
            $prepare['query'] = http_build_query($prepare['query']);
        }

        return $this->join($prepare);
    }
}