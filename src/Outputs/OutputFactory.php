<?php

namespace EDP\LogTailer\Outputs;

use BadArgumentException;

class OutputFactory
{
    public static $outputs = [
        'mail' => Mail::class,
    ];

    public static function create($type, $options = null)
    {
        if (!array_key_exists($type, self::$outputs)) {
            throw new BadArgumentException("Type $type is not available");
        }

        return new self::$outputs[$type]($options);
    }
}
