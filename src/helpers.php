<?php

use Illuminate\Support\Arr;

if (! function_exists('en_to_fa')) {
    /**
     * Convert english digits to farsi.
     *
     * @param  string  $text
     * @return string
     */
    function en_to_fa($text)
    {
        $en_num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $fa_num = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

        return str_replace($en_num, $fa_num, $text);
    }
}

if (! function_exists('fa_to_en')) {
    /**
     * Convert farsi/arabic digits to english.
     *
     * @param  string  $text
     * @return string
     */
    function fa_to_en($text)
    {
        $fa_num = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $en_num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($fa_num, $en_num, $text);
    }
}

if (! function_exists('str_to_slug')) {
    /**
     * Returns slug for string.
     *
     * @param  string  $string
     * @param  string  $separator
     * @return string
     */
    function str_to_slug(string $string, string $separator = '-')
    {
        $string = fa_to_en(trim(mb_strtolower($string)));
        $string = preg_replace('!['.preg_quote($separator === '-' ? '_' : '-').']+!u', $separator, $string);

        return preg_replace(
            '/\\'.$separator.'{2,}/',
            $separator,
            preg_replace('/[^A-Za-z0-9\x{0620}-\x{064A}\x{0698}\x{067E}\x{0686}\x{06AF}\x{06CC}\x{06A9}]/ui', $separator, $string)
        );
    }
}

if (! function_exists('find_bank_by_card_number')) {

    /**
     * Find bank info from card number.
     *
     * @param  string  $card
     * @return array
     */
    function find_bank_by_card_number($card)
    {
        static $banks = null;
        if (! $banks) {
            $banks = json_decode(file_get_contents(__DIR__.'/../storage/banks.json'), true);
        }

        return Arr::first(array_filter($banks, function ($bankInfo) use ($card) {
            return preg_match('/^'.$bankInfo['card_prefix'].'\\d*/', $card);
        }));
    }
}
