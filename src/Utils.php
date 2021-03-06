<?php
namespace Componentary;

/**
 * Utils stack
 *
 * @package Componentary
 * @author  undercloud <lodashes@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    http://github.com/undercloud/componentary
 */
class Utils
{
    /**
     * @var string
     */
    protected static $encoding = 'UTF-8';

    /**
     * Escape string
     *
     * @param string $string unescaped string
     *
     * @return string
     */
    public static function esc($string)
    {
        return htmlentities($string, ENT_QUOTES, self::$encoding, false);
    }

    /**
     * Unescape string
     *
     * @param string $string escaped string
     *
     * @return string
     */
    public static function unesc($string)
    {
        return html_entity_decode($string, ENT_QUOTES, self::$encoding);
    }

    /**
     * Stringify mixed value
     *
     * @param mixed $val value
     *
     * @return string
     */
    public static function stringify($val)
    {
        if (is_bool($val)) {
            return $val ? 'true' : 'false';
        }

        if (is_array($val) or is_object($val)) {
            return self::toJson($val, true);
        }

        return $val;
    }

    /**
     * Build attributes
     *
     * @param array $args map
     *
     * @return string
     */
    public static function buildAttributes(array $args)
    {
        $hooks = function ($val) {
            static $except = [
                'Componentary\Invoke',
                'Componentary\Url',
                'Componentary\Style',
                'Componentary\ClassList'
            ];

            return in_array(get_class($val), $except);
        };

        $pairs = [];
        foreach ($args as $key => $val) {
            if (null !== $val and !is_resource($val)) {
                if (is_object($val) and $hooks($val)) {
                    $val = (string) $val;
                }

                $val = self::stringify($val);
                $pairs[] = $key . '="' . self::esc($val) . '"';
            }
        }

        return implode(' ', $pairs);
    }

    /**
     * Check if entity is blank
     *
     * @param mixed $what value for check
     *
     * @return boolean
     */
    public static function isBlank($what)
    {
        if (is_string($what)) {
            $what = trim($what);
        }
        return (
            ($what === '')    or
            ($what === null)  or
            ($what === false) or
            ($what === [])
        );
    }

    /**
     * Check if entity is empty
     *
     * @param mixed $what value for check
     *
     * @return boolean
     */
    public static function isEmpty($what)
    {
        if (is_string($what)) {
            $what = trim($what);
        }

        return @empty($what);
    }

    /**
     * Convert all unicode symbols \uxxxx to html entity &#xxxx;
     *
     * @param string $string unicode string
     *
     * @return string
     */
    public static function unicode($string)
    {
        $callback = function ($item) {
            return '&#' . hexdec((int) $item[1]) . ';';
        };

        return preg_replace_callback('~\\\u([0-9]{4})~', $callback, $string);
    }

    /**
     * Template with placeholders
     *
     * @return string
     */
    public static function template()
    {
        $args = func_get_args();
        $tmpl = array_shift($args);
        $tmpl = str_replace('%', '%%', $tmpl);

        $args = array_map(
            function ($item) {
                if (is_array($item)) {
                    return implode(
                        ', ',
                        array_filter(
                            $item,
                            function ($item) {
                                return (is_scalar($item) and !self::isBlank($item));
                            }
                        )
                    );
                }
                return $item;
            },
            $args
        );

        $tmpl = preg_replace_callback(
            '~\{[0-9]{1,2}\}~',
            function ($item) {
                return '%' . trim($item[0], '{}') . '$s';
            },
            $tmpl
        );

        return vsprintf($tmpl, $args);
    }

    /**
     * Capitalize string
     *
     * @param string $string string
     *
     * @return string capitalized string
    */
    public static function capitalize($string, $lower = false)
    {
        if ($lower) {
            $string = mb_strtolower($string, self::$encoding);
        }

        return mb_strtoupper(mb_substr($string, 0, 1, self::$encoding), self::$encoding) .
               mb_substr($string, 1, mb_strlen($string, self::$encoding), self::$encoding);
    }

    /**
     * Capitalize all words in string
     *
     * @param string $string string
     *
     * @return string capitalized words
     */
    public static function capitalizeAll($string)
    {
        return mb_convert_case($string, MB_CASE_TITLE, self::$encoding);
    }

    /**
     * Uppercase string
     *
     * @param string $string string
     *
     * @return string
     */
    public static function upper($string)
    {
        return mb_strtoupper($string, self::$encoding);
    }

    /**
     * Lowercase string
     *
     * @param string $string string
     *
     * @return string
     */
    public static function lower($string)
    {
        return mb_strtolower($string, self::$encoding);
    }

    /**
     * Create abbreviation
     *
     * @param string $string [description]
     *
     * @return string
     */
    public static function abbr($string)
    {
        $string = (string) $string;

        if (!self::isBlank($string)) {
            $string = self::limit($string, 1, '.');
            $string = self::upper($string);
        }

        return $string;
    }

    /**
     * Remove double whitespace
     *
     * @param string $string string
     *
     * @return string
     **/
    public static function whitespace($string)
    {
        return preg_replace('/\s+/', ' ', $string);
    }

    /**
     * Limit string length
     *
     * @param string  $string  string
     * @param integer $limit   size
     * @param string  $postfix decoration
     *
     * @return string
     */
    public static function limit($string, $limit = 250, $postfix = '...')
    {
        if (mb_strlen($string, self::$encoding) > $limit) {
            return mb_substr($string, 0, $limit, self::$encoding) . $postfix;
        } else {
            return $string;
        }
    }

    /**
     * Limit string length soft
     *
     * @param string  $string  string
     * @param integer $limit   size
     * @param string  $postfix decoration
     *
     * @return string
     */
    public static function limitWords($string, $limit = 250, $postfix = '...')
    {
        if (mb_strlen($string, self::$encoding) > $limit) {
            $pos = mb_strpos($string, ' ', $limit, self::$encoding);
            if (false !== $pos) {
                return mb_substr($string, 0, $pos, self::$encoding) . $postfix;
            } else {
                return $string;
            }
        } else {
            return $string;
        }
    }

    /**
     * Limit string length by middle
     *
     * @param string  $string  string
     * @param integer $limit   size
     * @param string  $postfix decoration
     *
     * @return string
     */
    public static function limitMiddle($string, $limit = 250, $postfix = '...')
    {
        $len = mb_strlen($string, self::$encoding);
        if ($len > $limit) {
            $mid = (int)(($limit - 3) / 2);
            return (
                mb_substr($string, 0, $mid, self::$encoding) . $postfix .
                mb_substr($string, $len - $mid, $len, self::$encoding)
            );
        } else {
            return $string;
        }
    }

    /**
     * Ordinal number
     *
     * @param integer $cdnl number
     *
     * @return string
     */
    public static function ordinal($cdnl)
    {
        $cdnl = (int) $cdnl;
        $mod  = abs($cdnl) % 10;
        $ext  = ((abs($cdnl) %100 < 21 && abs($cdnl) %100 > 4) ? 'th'
            : (($mod < 4) ? ($mod < 3) ? ($mod < 2) ? ($mod < 1)
            ? 'th' : 'st' : 'nd' : 'rd' : 'th')
        );
        return $cdnl . $ext;
    }

    /**
     * Format number
     *
     * @param float $num       number
     * @param int   $precision precisoin, default 0
     *
     * @return string
     */
    public static function number($num, $precision = 0)
    {
        return number_format($num, $precision, '.', ' ');
    }

    /**
     * Bytes to human readable
     *
     * @param integer|string $size      size in bytes
     * @param integer        $precision precision
     *
     * @return string
     */
    public static function bytesHuman($size, $precision = 2)
    {
        $units = ['B', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Eb', 'Zb', 'Yb'];
        foreach ($units as $unit) {
            if (abs($size) >= 1024 && $unit != 'Yb') {
                $size = ($size / 1024);
            } else {
                return round($size, $precision) . ' ' . $unit;
            }
        }
    }

    /**
     * Long number to human readable
     *
     * @param integer|string $size      size in bytes
     * @param integer        $precision precision
     *
     * @return string
     */
    public static function roundHuman($size, $precision = 2)
    {
        $units = ['', 'K', 'M', 'B', 'T', 'Qa', 'Qi'];
        foreach ($units as $unit) {
            if (abs($size) >= 1000 && $unit != 'Qi') {
                $size = ($size / 1000);
            } else {
                return round($size, $precision) . ($unit ? (' ' . $unit) : '');
            }
        }
    }

    /**
     * Fake text generator
     *
     * @param integer $limit fake text length
     *
     * @return string
     */
    public static function lorem($limit = 544)
    {
        $msg = "Lorem ipsum dolor sit amet, consectetuer adipiscing elit, "
            . "sed diam nonummy nibh euismod tincidunt ut laoreet dolore "
            . "magna aliquam erat volutpat. Ut wisi enim ad minim veniam, "
            . "quis nostrud exerci tation ullamcorper suscipit lobortis nisl "
            . "ut aliquip ex ea commodo consequat. Duis autem vel eum iriure "
            . "dolor in hendrerit in vulputate velit esse molestie consequat, "
            . "vel illum dolore eu feugiat nulla facilisis at vero eros et "
            . "accumsan et iusto odio dignissim qui blandit praesent luptatum "
            . "zzril delenit augue duis dolore te feugait nulla facilisi. ";

        $len = strlen($msg);

        if ($limit > $len) {
            $msg = str_repeat($msg, (int)($limit / $len) + 1);
        }
        return self::limitWords($msg, $limit);
    }

    /**
     * JSON encode
     *
     * @param mixed   $data   for encoding
     * @param boolean $escape flag
     *
     * @throws RenderException
     *
     * @return string
     */
    public static function toJson($data, $escape = false)
    {
        json_encode(null);

        $flag = (
            JSON_HEX_TAG | JSON_HEX_AMP |
            JSON_HEX_APOS | JSON_HEX_QUOT
        );

        if (defined('JSON_PRESERVE_ZERO_FRACTION')) {
            $flag |= JSON_PRESERVE_ZERO_FRACTION;
        }

        if (defined('JSON_PARTIAL_OUTPUT_ON_ERROR')) {
            $flag |= JSON_PARTIAL_OUTPUT_ON_ERROR;
        }

        $data = json_encode($data, $flag);

        if ($errorCode = json_last_error()) {
            throw new RenderException(
                function_exists('json_last_error_msg')
                ? json_last_error_msg()
                : 'Cannot encode to JSON: ' . $errorCode
            );
        }

        if ($escape) {
            $data = self::esc($data);
        }

        return $data;
    }

    /**
     * Parse JSON
     *
     * @param string  $json  string
     * @param boolean $assoc if TRUE, returned objects will be cast into assoc arrays
     *
     * @throws RenderException
     *
     * @return mixed
     */
    public static function fromJson($json, $assoc = false)
    {
        $json = (string) $json;

        json_encode(null);

        $json = json_decode($json, $assoc);

        if ($errorCode = json_last_error()) {
            throw new RenderException(
                function_exists('json_last_error_msg')
                ? json_last_error_msg()
                : 'Cannot parse JSON string: ' . $errorCode
            );
        }

        return $json;
    }

    /**
     * Read data from file and encode to base64
     *
     * @param string  $path    /to/file
     * @param string  $mime    type
     * @param boolean $wrapUrl add url(...) wrap
     *
     * @return string
     */
    public static function dataUri($path, $mime, $wrapUrl = false)
    {
        $file = new SplFileObject($path);
        $contents = $file->fread($file->getSize());
        $contents = base64_encode($contents);

        return (
            ($wrapUrl ? 'url(' : '') .
            'data:' . $mime . ';base64,' . $contents
            ($wrapUrl ? ')' : '')
        );
    }
}
