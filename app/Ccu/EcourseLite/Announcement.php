<?php

namespace App\Ccu\EcourseLite;

class Announcement extends Core
{
    const LIST = self::BASE_URL.'/news/news.php';
    const CONTENT = self::BASE_URL.'/news/content.php';

    public static function lists()
    {
        $_this = new self;

        $response = $_this->client->get(self::LIST, [
            'allow_redirects' => false,
            'cookies' => $_this->jar,
        ]);

        $_this->parseLists($response->getBody()->getContents());
    }

    protected function parseLists($content)
    {
    }
}
