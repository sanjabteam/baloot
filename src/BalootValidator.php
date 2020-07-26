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
    ];

    public function __construct($translator, $data, $rules, $messages = [], $customAttributes = [])
    {
        parent::__construct($translator, $data, $rules, $messages, $customAttributes);
        $this->setCustomMessages($this->validationMessages);
    }

    /**
     * Validate iran mobile.
     *
     * @param string                           $attribute
     * @param string                           $value
     * @param array                            $parameters
     * @param \Illuminate\Validation\Validator $validator
     */
    public function validateIranMobile($attribute, $value, $parameters, $validator)
    {
        return preg_match("/^09([0-3]|9)\d{8}$/", $value);
    }

    /**
     * Validate iran phone.
     *
     * @param string                           $attribute
     * @param string                           $value
     * @param array                            $parameters
     * @param \Illuminate\Validation\Validator $validator
     */
    public function validateIranPhone($attribute, $value, $parameters, $validator)
    {
        return preg_match("/^0[1-8]\d{9}$/", $value);
    }
}
