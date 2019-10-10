<?php

namespace SanjabHelpers;

use Exception;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Arr;

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
                    return $attributeValue->format("Y/n/j H:i");
                } elseif ($matches[2] == '_ftt') {
                    return $attributeValue->formatJalaliDatetime();
                }
                return $attributeValue;
            }
        }

        if ($key && preg_match('/^([A-Za-z0-9_]+)_aparat$/', $key, $matches)) {
            $attributeValue = $this->getAttribute($matches[1]);
            if (is_string($attributeValue) && $info = Arr::first(aparat_info([$attributeValue]))) {
                return $info;
            } elseif (is_array($attributeValue) && count($info = aparat_info($attributeValue))) {
                return $info;
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
