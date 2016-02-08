<?php

namespace App\Ccu\EcourseLite;

use Sunra\PhpSimple\HtmlDomParser;

class Course extends Core
{
    const LIST = self::BASE_URL.'/Courses_Admin/take_course.php?frame=1';

    /**
     * 取得課程列表.
     *
     * @return array
     */
    public static function lists()
    {
        $_this = new self;

        $response = $_this->client->get(self::LIST, [
            'cookies' => $_this->jar,
        ]);

        return $_this->parseLists($response->getBody()->getContents());
    }

    /**
     * 分析課程列表頁面.
     *
     * @param string $content
     * @return array
     */
    protected function parseLists($content)
    {
        static $name = ['department', 'code', 'name', 'professor', 'announcements', 'homework', 'exams'];

        $size = count($name);

        $result = [];

        $rows = HtmlDomParser::str_get_html($content)->find('table table tr');

        array_shift($rows);

        foreach ($rows as $row) {
            $i = 0;

            while ($i < $size) {
                $temp[$name[$i]] = trim($row->children($i + 1)->plaintext);

                if (preg_match('/^\d$/', $temp[$name[$i]])) {
                    $temp[$name[$i]] = intval($temp[$name[$i]]);
                }

                ++$i;
            }

            $result[] = $temp;
        }

        return $result;
    }
}
