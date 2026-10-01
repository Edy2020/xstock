<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Neutraliza celdas de texto que Excel interpretaría como fórmulas (inyección CSV).
     */
    protected function csvSafe($value)
    {
        if (is_string($value) && $value !== '' && in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'" . $value;
        }

        return $value;
    }
}
