<?php

namespace App\Ccu\EcourseLite;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Auth\Access\AuthorizationException;
use Session;

class Core
{
    const BASE_URL = 'https://ecourse.ccu.edu.tw/php';
    const SIGN_IN = self::BASE_URL.'/index_login.php';
    const COURSES_LIST = self::BASE_URL.'/Courses_Admin/take_course.php?frame=1';
    const COURSES_SHOW = self::BASE_URL.'/login_s.php';
    const COURSES_GRADES = self::BASE_URL.'/Trackin/SGQueryFrame1.php';
    const COURSES_FILES = self::BASE_URL.'/textbook/course_menu.php?list=1';
    const COURSES_TEXTBOOK = self::BASE_URL.'/php/textbook/';

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
        ]);

        if (! str_contains($response->getHeaderLine('location'), 'Courses_Admin')) {
            throw new AuthorizationException;
        }

        Session::put('ccu.ecourse', [
            'jar' => encrypt(serialize($this->jar)),
        ]);
    }

    /**
     * 更改 ecourse session 中 course id 的值.
     *
     * @return $this
     */
    protected function touchSession()
    {
        $this->client->get(self::COURSES_SHOW, [
            'cookies' => $this->jar,
            'query' => [
                'courseid' => $this->courseId,
            ],
        ]);

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
}
