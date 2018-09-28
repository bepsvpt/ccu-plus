<?php

namespace App\Ccu\EcourseLite;

use GuzzleHttp\Promise;
use Sunra\PhpSimple\HtmlDomParser;

class Attachment extends Core
{
    const LISTS = self::BASE_URL.'/textbook/course_menu.php?list=1';
    const CONTENT = self::BASE_URL.'/textbook/';

    /**
     * 分析授課教材列表.
     *
     * @param $content
     * @return array
     */
    public function parseLists($content)
    {
        $rows = HtmlDomParser::str_get_html($content)->find('a[href]');

        $result = $promises = [];

        foreach ($rows as $row) {
            $promises[] = $this->client->getAsync(self::CONTENT.$row->href, [
                'http_errors' => false,
                'cookies' => $this->jar,
                'verify' => false,
            ]);
        }

        $responses = Promise\unwrap($promises);

        foreach ($responses as $response) {
            if (200 === $response->getStatusCode()) {
                $result = array_merge($result, $this->parseContent($response->getBody()->getContents()));
            }
        }

        return $result;
    }

    /**
     * 分析連結內容.
     *
     * @param string $content
     * @return array
     */
    protected function parseContent($content)
    {
        static $excepts = ['#', 'FILE_LINK'];

        $rows = HtmlDomParser::str_get_html($content)->find('a[href]');

        $result = [];

        foreach ($rows as $row) {
            if (! str_contains($row->href, $excepts)) {
                if (false === json_encode([trim($row->plaintext)])) {
                    $row->plaintext = utf8_encode(trim($row->plaintext));
                }

                $result[] = [
                    'name' => trim($row->plaintext),
                    'link' => self::BASE_URL.'/'.$row->href,
                ];
            }
        }

        return $result;
    }
}
