<?php

namespace App\Ccu\EcourseLite;

use GuzzleHttp\Promise;
use Sunra\PhpSimple\HtmlDomParser;

class Homework extends Core
{
    const LISTS = self::BASE_URL.'/Testing_Assessment/show_allwork.php';

    /**
     * 分析作業列表.
     *
     * @param string $content
     * @return array
     */
    protected function parseLists($content)
    {
        $rows = array_slice(HtmlDomParser::str_get_html($content)->find('table table tr'), 1);

        $result = $promises['content'] = $promises['submitted'] = [];

        foreach ($rows as $row) {
            $id = substr(strrchr(strstr($row->children(2)->innertext, '&action', true), '='), 1);

            $result[] = [
                'name' => trim($row->children(1)->plaintext),
                'date' => trim($row->children(3)->plaintext),
            ];

            $promises['content'][] = $this->client->getAsync(self::LISTS, [
                'allow_redirects' => false,
                'cookies' => $this->jar,
                'query' => [
                    'work_id' => $id,
                    'action' => 'showwork',
                ],
                'verify' => false,
            ]);
            $promises['submitted'][] = $this->client->getAsync(self::LISTS, [
                'cookies' => $this->jar,
                'query' => [
                    'work_id' => $id,
                    'action' => 'seemywork',
                ],
                'verify' => false,
            ]);
        }

        $response['content'] = Promise\unwrap($promises['content']);
        $response['submitted'] = Promise\unwrap($promises['submitted']);

        for ($i = 0, $size = count($result); $i < $size; ++$i) {
            $result[$i]['submitted'] = $this->parseSubmitted($response['submitted'][$i]->getBody()->getContents());

            if ($response['content'][$i]->hasHeader('location')) {
                $result[$i]['link'] = self::BASE_URL.'/'.$response['content'][$i]->getHeaderLine('location');
            } else {
                $result[$i]['content'] = $this->parseContent($response['content'][$i]->getBody()->getContents());
            }
        }

        return array_reverse($result);
    }

    /**
     * 判斷是否已繳交作業.
     *
     * @param string $content
     * @return bool
     */
    protected function parseSubmitted($content)
    {
        return null !== HtmlDomParser::str_get_html($content)->find('a', 0);
    }

    /**
     * 取得作業內容.
     *
     * @param string $content
     * @return string
     */
    protected function parseContent($content)
    {
        return html_entity_decode(
            trim(HtmlDomParser::str_get_html($content)->find('font', 0)->plaintext),
            ENT_QUOTES,
            'UTF-8'
        );
    }
}
