<?php

namespace Baloot;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class BalootFakerProvider extends \Faker\Provider\Base
{
    public function customImage($path, $width, $height, $prefix = '')
    {
        $imagePath = sha1(time().'_'.uniqid()).'.jpg';
        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 755, true);
        }
        $ch = curl_init("https://picsum.photos/{$width}/{$height}");
        curl_setopt_array($ch, [
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_BINARYTRANSFER => 1,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => 1,
        ]);
        $raw = curl_exec($ch);
        curl_close($ch);

        $file = fopen(rtrim($path, '\\/').'/'.$imagePath, 'w');
        fwrite($file, $raw);
        fclose($file);

        return $prefix.$imagePath;
    }

    public function customImages($path, $width, $height, $count, $prefix = '')
    {
        $out = [];
        if ($count > 0) {
            foreach (range(1, $count) as $c) {
                $out[] = $this->customImage($path, $width, $height, $prefix);
            }
        }

        return $out;
    }

    public function iranMobile()
    {
        return '09'.rand(10, 39).rand(1000000, 9999999);
    }

    public function iranPhone()
    {
        return '0'.rand(11, 20).rand(1000000, 9999999);
    }

    public function word()
    {
        return Arr::random(explode(' ', $this->generator->realText()));
    }

    public function sentence()
    {
        return $this->generator->realText();
    }

    public function paragraph()
    {
        return $this->generator->realText().' '.$this->generator->realText();
    }
}
