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
                $key = [
                    '_f' => $attributeValue->formatJalaliDate(),
                    '_ft' => $attributeValue->format('Y/n/j H:i'),
                    '_ftt' => $attributeValue->formatJalaliDatetime(),
                ];
                foreach ($key as $key => $item) {
                    if ($matches[2] == $key) {
                        return $item;
                    }
                }
                return $attributeValue;
            }
        }

        return parent::getAttribute($key);
    }

    public function setAttribute($key, $value)
    {
        if (!($key && $value && preg_match('/^([A-Za-z0-9_]+)_fa(|_f|_ft|_ftt)$/', $key, $matches))) {
            return parent::setAttribute($key, $value);
        }
        if ($this->isDateAttribute($matches[1])) {
            if ($value instanceof Verta) {
                return $this->setAttribute($matches[1], $value->DateTime());
            }
            try {
                $value = Verta::parse($value);
            } catch (Exception $exception) {}
        }
    }
}
