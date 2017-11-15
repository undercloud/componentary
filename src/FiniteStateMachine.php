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
            return substr($tag, $from, $space);
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
        $tag = $text = $glue = $finite = '';
        $selfRepeat = -1;

        while ($this->queue->valid()) {
            $char = $this->queue->current();

            if ($char === '<') {
                $openbrace = true;
            }

            if ($openbrace) {
                $tag .= $char;
            } else {
                $text .= $char;
            }

            if ($openbrace and ($char === '>' or ($char === '/' and $this->getNextChar() === '>'))) {
                if ($inside) {
                    $glue .= $text;
                } else if (strlen($text)) {
                    $finite .= Utils::esc($text);
                }

                if ($char === '/') {
                    $this->queue->next();
                    $tag .= $this->queue->current();
                }

                $isOpen = $this->isOpenComponentTag($tag);
                if (!$inside and $isOpen) {
                    $inside = true;
                    $componentName = $this->getTagName($tag);
                }

                if ($inside) {
                    $sameTag = $componentName == $this->getTagName($tag);

                    if ($isOpen and $sameTag) {
                        $selfRepeat++;
                    }

                    $glue .= $tag;
                    $isClosed = $this->isClosedComponentTag($tag);

                    if ($isClosed and $sameTag and $selfRepeat === 0) {
                        $resolver = new Resolver($componentName);
                        list($attrs, $content) = Utils::parseAttributes($glue);

                        $finite .= (
                            $resolver->isValid()
                            ? $resolver->resolve($attrs, $content)
                            : $glue
                        );

                        $glue = '';
                        $inside = $componentName = false;
                    }

                    if ($isClosed and $sameTag) {
                        $selfRepeat--;
                    }
                } else{
                    $finite .= $tag;
                }

                $tag = $text = '';
                $openbrace = false;
            }

            $this->queue->next();
        }

        return $finite;
    }
}
