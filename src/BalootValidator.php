<?php

namespace Baloot;

use Illuminate\Validation\Validator;

class BalootValidator extends Validator
{
    public $validationMessages = [
        'jdate'                  => ':attribute'.' تاریخ شمسی معتبر نمی باشد.',
        'jdate_equal'            => ':attribute'.' تاریخ شمسی برابر '.':fa-date'.' نمی باشد.',
        'jdate_not_equal'        => ':attribute'.' تاریخ شمسی نامساوی '.':fa-date'.' نمی باشد.',
        'jdatetime'              => ':attribute'.' تاریخ و زمان شمسی معتبر نمی باشد.',
        'jdatetime_equal'        => ':attribute'.' تاریخ و زمان شمسی مساوی '.':fa-date'.' نمی باشد.',
        'jdatetime_not_equal'    => ':attribute'.' تاریخ و زمان شمسی نامساوی '.':fa-date'.' نمی باشد.',
        'jdate_after'            => ':attribute'.' تاریخ شمسی باید بعد از '.':fa-date'.' باشد.',
        'jdate_after_equal'      => ':attribute'.' تاریخ شمسی باید بعد یا برابر از '.':fa-date'.' باشد.',
        'jdatetime_after'        => ':attribute'.' تاریخ و زمان شمسی باید بعد از '.':fa-date'.' باشد.',
        'jdatetime_after_equal'  => ':attribute'.' تاریخ و زمان شمسی باید بعد یا برابر از '.':fa-date'.' باشد.',
        'jdate_before'           => ':attribute'.' تاریخ شمسی باید قبل از '.':fa-date'.' باشد.',
        'jdate_before_equal'     => ':attribute'.' تاریخ شمسی باید قبل یا برابر از '.':fa-date'.' باشد.',
        'jdatetime_before'       => ':attribute'.' تاریخ و زمان شمسی باید قبل از '.':fa-date'.' باشد.',
        'jdatetime_before_equal' => ':attribute'.' تاریخ و زمان شمسی باید قبل یا برابر از '.':fa-date'.' باشد.',
        'iran_phone'             => ':attribute یک شماره تلفن معتبر نیست.',
        'iran_mobile'            => ':attribute یک شماره همراه معتبر نیست.',
        'iran_national_code'     => ':attribute کد ملی معتبر نیست.',
    ];

    public function __construct($translator, $data, $rules, $messages = [], $customAttributes = [])
    {
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
        $this->setCustomMessages($this->validationMessages);
    }

    /**
     * Validate iran mobile.
     *
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @param  \Illuminate\Validation\Validator  $validator
     * @return bool
     */
    public function validateIranMobile($attribute, $value, $parameters, $validator)
    {
        if (isset($parameters[0]) and $parameters[0] == 'true') {
            return preg_match("/^0?9[0-1-2-3-9]\d{8}$/", $value);
        }

        return preg_match("/^09[0-1-2-3-9]\d{8}$/", $value);
    }

    /**
     * Validate iran phone.
     *
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @param  \Illuminate\Validation\Validator  $validator
     * @return bool
     */
    public function validateIranPhone($attribute, $value, $parameters, $validator)
    {
        return preg_match("/^0[1-8]\d{9}$/", $value);
    }

    /**
     * Validate iran national code.
     *
     * @param  string  $attribute
     * @param  string  $value
     * @param  array  $parameters
     * @param  \Illuminate\Validation\Validator  $validator
     */
    public function validateIranNationalCode($attribute, $value, $parameters, $validator)
    {
        if (! preg_match('/^[0-9]{10}$/', $value)) {
            return false;
        }
        for ($i = 0; $i < 10; $i++) {
            if ($value == str_repeat($i, 10)) {
                return false;
            }
        }
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += ((10 - $i) * intval(substr($value, $i, 1)));
        }
        $ret = $sum % 11;
        $parity = intval(substr($value, 9, 1));
        if (($ret < 2 && $ret == $parity) || ($ret >= 2 && $ret == 11 - $parity)) {
            return true;
        }

        return false;
    }
}
