<?php

namespace Jadu\AddressFinderClient\Model\Address\Formatter;

class TitleCaseFormatter
{
    /**
     * @param string $text
     *
     * @return string
     */
    public static function format($text)
    {
        return ucwords(strtolower($text));
    }
}
