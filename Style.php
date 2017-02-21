<?php
namespace Elementary;

use Exception;
/**
 * Inline style generator
 *
 * @package  Elementary
 * @author   undercloud <lodashes@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     http://github.com/undercloud/elementary
 */
class Style
{
    /**
     * @var array
     */
    protected $map = [];

    /**
     * @param array|string $style declaration
     */
    public function __construct($style = [])
    {
        if (!is_array($style) and !is_string($style)) {
            throw new Exception(sprintf(
                'Argument 1 must be array or string, %s given',
                gettype($style)
            ));
        }

        if (is_array($style)) {
            foreach ($style as $key => $val) {
                $key = $this->normalizeKey($key);
                $this->map[$key] = $val;
            }
        } else {
            $this->map = $this->parse($style);
        }
    }

    /**
     * Parse inline styles
     *
     * @param string $style pairs
     *
     * @return array
     */
    private function parse($style)
    {
        $pairs = array_filter(explode(';', $style));

        $map = [];
        foreach ($pairs as $pair) {
            list($key, $val) = explode(':', $pair, 2);
            $map[$key] = $val;
        }

        return $map;
    }

    /**
     * Normalize style key
     *
     * @param string $key [description]
     *
     * @return string
     */
    private function normalizeKey($key)
    {
        return preg_replace_callback('~[A-Z]~', function ($match) {
            return '-' . strtolower($match[0]);
        }, $key);
    }

    /**
     * Check property exists
     *
     * @param string $key name
     * @return boolean
     */
    public function has($key)
    {
        $key = $this->normalizeKey($key);

        return isset($this->map[$key]);
    }

    /**
     * Magic __set
     *
     * @param string $key name
     * @param string $val property
     */
    public function __set($key, $val)
    {
        $key = $this->normalizeKey($key);

        $this->map[$key] = $val;
    }

    /**
     * Magic __get
     *
     * @param string $key property
     * @return mixed
     */
    public function __get($key)
    {
        $key = $this->normalizeKey($key);

        return (isset($this->map[$key]) ? $this->map[$key] : null);
    }

    /**
     * Build inline style
     *
     * @return string
     */
    public function build()
    {
        $fn = function ($key, $val) {
            return $key . ':' . (string) $val;
        };

        $pairs = array_map(
            $fn,
            array_keys($this->map),
            array_values($this->map)
        );

        return trim(implode(';', $pairs));
    }

    /**
     * Magic __toString
     *
     * @return string
     */
    public function __toString()
    {
        return $this->build();
    }
}