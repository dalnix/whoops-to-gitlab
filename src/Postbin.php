<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 2017-09-26
 * Time: 13:46
 */

namespace Dalnix\WhoopsToGitlab;


use Camspiers\JsonPretty\JsonPretty;

class Postbin
{
    public static function sendDataToBin($data)
    {
        if (config('gitlab.selected_bin') == 'pastebin') {
            return self::sendDataToPastebin($data);
        } else {
            if (config('gitlab.selected_bin') == 'dalnix') {
                return self::sendDataToDalnixPastebin($data);
            }
        }
    }

    public static function sendDataToDalnixPastebin($data)
    {
        $jsonPretty = new JsonPretty();
        $options = config('gitlab.bins')['dalnix'];
        $url = $options['url'];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
           'data="' . $jsonPretty->prettify($data['data'],
               JSON_PRETTY_PRINT, "\t", true) . '"&expire=7&formatter=2&burnafterreading=0&opendiscussion=0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
         $response = curl_exec($ch);
        dd($response);
        echo $response;
    }

    public static function sendDataToPastebin($data)
    {


        $options = config('gitlab.bins')['pastebin'];
        $jsonPretty = new JsonPretty();

        $url = 'https://pastebin.com/api/api_post.php';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            'api_option=paste&api_paste_private=' . $options['api_paste_private'] . '&api_paste_name=' . urlencode($data['title']) . '&api_paste_expire_date=' . $options['api_paste_expire_date'] . '&api_paste_format=' . $options['api_paste_format'] . '&api_dev_key=' . $options['api_dev_key'] . '&api_paste_code=' . urlencode($jsonPretty->prettify($data['data'],
                JSON_PRETTY_PRINT, "\t", true)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        $response = curl_exec($ch);

        return $response;

    }
}