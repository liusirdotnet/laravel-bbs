<?php

namespace App\Handlers;

use GuzzleHttp\Client;
use Overtrue\Pinyin\Pinyin;

class TranslateHandler
{
    /**
     * 将给定的中文字符串翻译成英文字符串。
     *
     * @param string $text
     *
     * @return string
     */
    public function translate(string $text)
    {
        $client = new Client();

        $api = config('services.baidu_translate.api');
        $appid = config('services.baidu_translate.appid');
        $appkey = config('services.baidu_translate.appkey');
        $salt = time();

        if (empty($appid) || empty($appkey)) {
            return $this->pinyin($text);
        }

        $sign = md5($appid . $text . $salt . $appkey);
        $query = http_build_query([
            'q' => $text,
            'from' => 'zh',
            'to' => 'en',
            'appid' => $appid,
            'salt' => $salt,
            'sign' => $sign,
        ]);
        $uri = $api . '?' . $query;
        $response = $client->get($uri);
        $result = json_decode($response->getBody(), true);
        $key = 'trans_result';

        if (isset($result[$key][0]['dst'])) {
            return str_slug($result[$key][0]['dst']);
        }

        return $this->pinyin($text);
    }

    /**
     * 将给定的中文字符串转换为拼音字符串。
     *
     * @param string $text
     *
     * @return string
     */
    public function pinyin(string $text)
    {
        return str_slug(app(Pinyin::class)->permalink($text));
    }
}
