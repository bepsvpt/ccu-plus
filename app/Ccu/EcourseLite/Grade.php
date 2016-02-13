<?php

namespace App\Ccu\EcourseLite;

use Sunra\PhpSimple\HtmlDomParser;

class Grade extends Core
{
    const LISTS = self::BASE_URL.'/Trackin/SGQueryFrame1.php';

    /**
     * 分析成績列表.
     *
     * @param string $content
     * @return array
     */
    protected function parseLists($content)
    {
        $rows = HtmlDomParser::str_get_html($content)->find('table table', 0)->find('tr[bgcolor!="#4d6eb2"]');

        $result = [];

        foreach ($rows as $row) {
            $result[] = [
                'name' => trim($row->children(0)->plaintext),
                'value' => trim($row->children((4 === count($row->children) ? 2 : 1))->plaintext),
            ];
        }

        return $result;
    }
}
