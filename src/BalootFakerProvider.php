<?php

namespace Baloot;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
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
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
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

    public function aparatVideo()
    {
        $videos = Cache::remember('sanjab_baloot_aparat_videos', now()->addHour(), function () {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://www.aparat.com/etc/api/categoryVideos/cat/7/perpage/50',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10
            ]);
            $responseBody = curl_exec($curl);
            if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200 && is_string($responseBody)) {
                $responseBody = json_decode($responseBody, true);
                if (is_array($responseBody)) {
                    $responseBody = $responseBody['categoryvideos'];
                    if (is_array($responseBody)) {
                        curl_close($curl);

                        return array_map(function ($videoDetail) {
                            return $videoDetail['uid'];
                        }, $responseBody);
                    }
                }
            }
        });
        if (is_array($videos)) {
            return 'https://www.aparat.com/v/'.Arr::random($videos);
        }
        // @codeCoverageIgnoreStart
        return 'https://www.aparat.com/v/'.Arr::random([
            'IAN6z', 'xrAb8', 'w7NMS', '0fFhg', 'uCgQd',
            'hK5fF', 'arsHC', '43aZ8', 'syI7N', 'XaN3o',
            'YJpM1', 'TSAz1', 'sQBq4', 'Y7AZF', 'dNn3M',
            'uR7DI', 'TZ63C', '8T3hA', 'NdqEn', 'xCFnE',
            'mL21e', 'ZkGp8', 'MV9RW', 'jicTW', 'Ikan1',
            'Ylj9x', '3Qmhd', 'jWqud', '6VeK8', 'fic92',
            'fdQXx', 'UQ2jS', 'RrctN', 'EutTQ', 'evc7o',
            'CugP3', '1T08s', 'eTFxk', 'UF8xV', 'Qn8CF',
            'xtKbg', 'hTWBg', 'wWBU7', 'zt90l', 'fjyRd',
            'VPvu3', 'iaCV3', '4r9IK', 'm1gxT', 'XonZx',
            'rUC8l', '6yoBO', 'aoE2p', 'slKDV', '2Dun4',
            'L4J1F', 'dwWTy', 'a6PqK', 'bDaPX', 'pRPSt',
        ]);
        // @codeCoverageIgnoreEnd
    }

    public function aparatVideos($count)
    {
        $out = [];
        foreach (range(1, $count) as $c) {
            $out[] = $this->aparatVideo();
        }

        return $out;
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
