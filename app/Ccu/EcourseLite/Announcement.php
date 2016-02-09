<?php

namespace App\Ccu\EcourseLite;

use GuzzleHttp\Promise;
use Sunra\PhpSimple\HtmlDomParser;

class Announcement extends Core
{
    const LIST = self::BASE_URL.'/news/news.php';
    const CONTENT = self::BASE_URL.'/news/content.php';

    /**
     * 取得公告列表.
     *
     * @param string $courseId
     * @return array
     */
    public static function lists($courseId)
    {
        $_this = new self;

        $_this->setCourseId($courseId)->touchSession();

        $response = $_this->client->get(self::LIST, [
            'cookies' => $_this->jar,
        ]);

        return $_this->parseLists($response->getBody()->getContents());
    }

    /**
     * 分析公告列表.
     *
     * @param string $content
     * @return array
     */
    protected function parseLists($content)
    {
        $rows = array_slice(HtmlDomParser::str_get_html($content)->find('table table[bordercolordark] tr'), 1, 5);

        $result = $promises = [];

        foreach ($rows as $row) {
            $result[] = [
                'date' => trim($row->children(0)->plaintext),
                'title' => html_entity_decode(trim($row->children(2)->plaintext), ENT_QUOTES, 'UTF-8'),
            ];

            $promises[] = $this->client->getAsync(self::CONTENT, [
                'cookies' => $this->jar,
                'query' => [
                    'a_id' => substr(strrchr(strstr($row->find('a', 0)->outertext, '&system', true), '='), 1),
                ],
            ]);
        }

        $response = Promise\unwrap($promises);

        for ($i = 0, $size = count($result); $i < $size; ++$i) {
            $result[$i]['content'] = $this->parseContent($response[$i]->getBody()->getContents());
        }

        return $result;
    }

    /**
     * 分析公告內容.
     *
     * @param string $content
     * @return string
     */
    protected function parseContent($content)
    {
        return html_entity_decode(
            trim(HtmlDomParser::str_get_html($content)->find('table table div[align="left"]', 0)->plaintext),
            ENT_QUOTES,
            'UTF-8'
        );
    }
}
