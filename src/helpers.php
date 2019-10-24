<?php

use Illuminate\Support\Arr;

if (!function_exists("en_to_fa")) {
    /**
     * Convert english digits to farsi.
     *
     * @param string $text
     * @return string
     */
    function en_to_fa($text)
    {
        $en_num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $fa_num = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        return str_replace($en_num, $fa_num, $text);
    }
}

if (!function_exists("fa_to_en")) {
    /**
     * Convert farsi/arabic digits to english.
     *
     * @param string $text
     * @return string
     */
    function fa_to_en($text)
    {
        $fa_num = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $en_num = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($fa_num, $en_num, $text);
    }
}

if (!function_exists("str_to_slug")) {
    /**
     * Returns slug for string.
     *
     * @param string $string
     * @param string $separator
     * @return string
     */
    function str_to_slug(string $string, string $separator = '-')
    {
        $string = trim(mb_strtolower($string));
        $string = preg_replace('!['.preg_quote($separator === '-' ? '_' : '-').']+!u', $separator, $string);
        return preg_replace("/\\" . $separator . "{2,}/", $separator, preg_replace('/[^A-Za-z0-9\x{0600}-\x{06FF}]/ui', $separator, $string));
    }
}

if (!function_exists("aparat_info")) {
    /**
     * Get information about videos from aparat api.
     *
     * @param array $addresses
     * @param bool $cache
     * @return array
     */
    function aparat_info(array $addresses, bool $cache = true)
    {
        $videoInfos = [];
        foreach ($addresses as $address) {
            if (!empty($address) && preg_match_all("/https:\/\/www.aparat.com\/v\/(.+)(\/?)/", $address, $matches)) {
                $aparatVideo = \Baloot\Models\AparatVideo::where('uid', $matches[1][0])->first();

                if ($aparatVideo == null || $cache == false) {
                    try {
                        $curl = curl_init();
                        curl_setopt_array($curl, [
                            CURLOPT_URL => 'https://www.aparat.com/etc/api/video/videohash/' . $matches[1][0],
                            CURLOPT_RETURNTRANSFER => true,
                        ]);
                        $responseBody = curl_exec($curl);
                        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200 && is_string($responseBody)) {
                            $responseBody = json_decode($responseBody, true)['video'];
                            if (isset($responseBody['duration'])) {
                                $responseBody['is_360d'] = $responseBody['360d'];
                                if (is_array($responseBody['tags'])) {
                                    $responseBody['tags'] = array_map(function ($tag) {
                                        return $tag['name'];
                                    }, $responseBody['tags']);
                                }
                                if ($cache) {
                                    $videoInfos[] = \Baloot\Models\AparatVideo::create($responseBody);
                                } else {
                                    $videoInfos[] = $responseBody;
                                }
                            }
                        }
                        curl_close($curl);
                    } catch (\Exception $e) {
                    }
                } else {
                    $videoInfos[] = $aparatVideo;
                }
            }
        }
        return $videoInfos;
    }
}

if (! function_exists("find_bank_by_card_number")) {

    /**
     * Find bank info from card number.
     *
     * @param string $card
     * @return array
     */
    function find_bank_by_card_number($card)
    {
        static $banks = null;
        if (!$banks) {
            $banks = json_decode(file_get_contents(__DIR__."/../storage/banks.json"), true);
        }
        return Arr::first(array_filter($banks, function ($bankInfo) use ($card) {
            return preg_match("/^".$bankInfo['card_prefix']."\\d*/", $card);
        }));
    }
}
