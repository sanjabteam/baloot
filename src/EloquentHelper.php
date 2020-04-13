<?php

namespace Baloot;

use Exception;
use Hekmatinasser\Verta\Verta;

trait EloquentHelper
{
    public function getAttribute($key)
    {
        if ($key && preg_match('/^([A-Za-z0-9_]+)_fa(|_f|_ft|_ftt)$/', $key, $matches)) {
            if ($this->isDateAttribute($matches[1])) {
                $attributeValue = Verta::instance($this->getAttribute($matches[1]));
                if ($matches[2] == '_f') {
                    return $attributeValue->formatJalaliDate();
                } elseif ($matches[2] == '_ft') {
                    return $attributeValue->format('Y/n/j H:i');
                } elseif ($matches[2] == '_ftt') {
                    return $attributeValue->formatJalaliDatetime();
                }

                return $attributeValue;
            }
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if ($key && $value && preg_match('/^([A-Za-z0-9_]+)_fa(|_f|_ft|_ftt)$/', $key, $matches)) {
            if ($this->isDateAttribute($matches[1])) {
                if (! ($value instanceof Verta)) {
                    try {
                        $value = Verta::parse($value);
                    } catch (Exception $exception) {
                    }
                }
                if ($value instanceof Verta) {
                    return $this->setAttribute($matches[1], $value->DateTime());
                }
            }
        }

        return parent::setAttribute($key, $value);
    }
}
