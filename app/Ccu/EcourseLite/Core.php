<?php

namespace App\Ccu\EcourseLite;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Auth\Access\AuthorizationException;
use Session;

abstract class Core
{
    const BASE_URL = 'https://ecourse.ccu.edu.tw/php';
    const SIGN_IN = self::BASE_URL.'/index_login.php';
    const COURSES_LIST = self::BASE_URL.'/Courses_Admin/take_course.php?frame=1';
    const COURSES_SHOW = self::BASE_URL.'/login_s.php';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var CookieJar
     */
    protected $jar;

    /**
     * @var string
     */
    protected $courseId;

    /**
     * Core constructor.
     */
    public function __construct()
    {
        $this->client = new Client;

        $this->jar = Session::has('ccu.ecourse.jar')
            ? unserialize(decrypt(Session::get('ccu.ecourse.jar')))
            : new CookieJar;

        $this->signInIfGuest();
    }

    /**
     * 檢查是否已登入，如未登入則登入之.
     *
     * @return void
     */
    private function signInIfGuest()
    {
        $response = $this->client->get(self::SIGN_IN, [
            'allow_redirects' => false,
            'cookies' => $this->jar,
            'verify' => false,
        ]);

        if (! str_contains($response->getHeaderLine('location'), 'Courses_Admin')) {
            $this->signIn();
        }
    }

    /**
     * 登入 Ecourse.
     *
     * @return void
     * @throws AuthorizationException
     */
    private function signIn()
    {
        $response = $this->client->post(self::SIGN_IN, [
            'allow_redirects' => false,
            'cookies' => $this->jar,
            'form_params' => [
                'id' => decrypt(Session::get('ccu.sso.username')),
                'pass' => decrypt(Session::get('ccu.sso.password')),
                'ver' => 'C',
            ],
            'verify' => false,
        ]);

        if (! str_contains($response->getHeaderLine('location'), 'Courses_Admin')) {
            throw new AuthorizationException;
        }

        Session::put('ccu.ecourse.jar', encrypt(serialize($this->jar)));
    }

    /**
     * 取得分析列表.
     *
     * @param string $courseId
     * @return array
     */
    public static function lists($courseId = '')
    {
        $_this = new static;

        if (! empty($courseId)) {
            $_this->setCourseId($courseId)->touchSession();
        }

        $response = $_this->client->get(static::LISTS, [
            'cookies' => $_this->jar,
            'verify' => false,
        ]);

        return $_this->parseLists($response->getBody()->getContents());
    }

    /**
     * 更改 ecourse session 中 course id 的值.
     *
     * @return $this
     */
    protected function touchSession()
    {
        if ($this->courseId !== Session::get('ccu.ecourse.courseId')) {
            $this->client->get(self::COURSES_SHOW, [
                'cookies' => $this->jar,
                'query' => [
                    'courseid' => $this->courseId,
                ],
                'verify' => false,
            ]);

            Session::put('ccu.ecourse.courseId', $this->courseId);
        }

        return $this;
    }

    /**
     * @param string $courseId
     * @return $this
     */
    public function setCourseId($courseId)
    {
        $this->courseId = $courseId;

        return $this;
    }

    abstract protected function parseLists($content);
}
