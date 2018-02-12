<?php
namespace Componentary;

use ArrayIterator;

class FiniteStateMachine
{
    /**
     * @var ArrayIterator
     */
    private $queue;

    public function __construct($html)
    {
        //var_dump($html);

        $html = str_split($html);
        $this->queue = new ArrayIterator($html);
    }

    private function getNextChar()
    {
        $key = $this->queue->key();
        $this->queue->seek($key + 1);
        $char = $this->queue->current();
        $this->queue->seek($key);

        return $char;
    }

    private function getTagName($tag)
    {
        $from = (0 === strpos($tag, '</') ? 2 : 1);
        $space = strpos($tag, ' ');

        if (false !== $space) {
            return substr($tag, $from, $space - 1);
        }

        return rtrim(substr($tag, $from), '/>');
    }

    private function isOpenComponentTag($tag)
    {
        return ctype_upper($tag[1]);
    }

    private function isClosedComponentTag($tag)
    {
        return (0 === strpos($tag, '</')) or (substr($tag, -2) === '/>');
    }

    public function walk()
    {
        $this->queue->rewind();

        $openbrace = false;
        $inside = false;
        $componentName = false;
        $tag = $text = null;
        $glue = $finite = '';
        $selfRepeat = -1;
        $lastOpen = false;

        while ($this->queue->valid()) {
            $char = $this->queue->current();

            if (!$openbrace and $char === '<') {
                if ($inside) {
                    $glue .= $text;
                } else {
                    $finite .= $text;
                }

                $text = null;
                $openbrace = true;
            }

            if ($openbrace) {
                $tag .= $char;
            } else {
                $text .= $char;
            }

            if ($openbrace and ($char === '>' or ($char === '/' and $this->getNextChar() === '>'))) {
                if ($char === '/') {
                    $this->queue->next();
                    $tag .= $this->queue->current();
                }

                $isOpen = $this->isOpenComponentTag($tag);
                if (!$inside and $isOpen) {
                    $inside = true;
                    $lastOpen = $tag;
                    $componentName = $this->getTagName($tag);
                }

                if ($inside) {
                    $glue .= $tag;
                } else {
                    $finite .= $tag;
                }

                if ($inside) {
                    $sameTag = $componentName == $this->getTagName($tag);

                    if ($isOpen and $sameTag) {
                        $selfRepeat++;
                    }

                    $isClosed = $this->isClosedComponentTag($tag);

                    if ($isClosed and $sameTag and $selfRepeat === 0) {
                        $resolver = new Resolver($componentName);
                        list($attrs, $content) = Utils::parseAttributes($glue);
                        $finite .= (
                            $resolver->isValid()
                            ? $resolver->resolve($attrs, $content)
                            : (
                                $isOpen
                                    ? $glue
                                    : (
                                        $lastOpen .
                                            (new self($content))->walk() .
                                        $tag
                                    )
                                )
                        );

                        $glue = '';
                        $inside = $componentName = false;
                    }

                    if ($isClosed and $sameTag) {
                        $selfRepeat--;
                    }
                }

                $tag = null;
                $openbrace = false;
            }

            $this->queue->next();
        }

        if (null !== $text) {
            $finite .= $text;
        }

        return $finite;
    }
}
