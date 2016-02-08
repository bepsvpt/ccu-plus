<?php

namespace App\Http\Controllers\Api\V1;

use App\Ccu\General\Category;
use Cache;

class ResourceController extends ApiController
{
    /**
     * 取得學院的系所.
     *
     * @param string $name
     * @return \Illuminate\Http\JsonResponse
     */
    public function colleges($name = '')
    {
        $college = Category::getCategories('college', $name);

        if (is_null($college)) {
            return $this->responseNotFound();
        } elseif (empty($name)) {
            return $this->setData($college)->responseOk();
        }

        $key = 'department-'.$college->getAttribute('id');

        $departments = Cache::tags('resource')->remember($key, Category::MINUTES_PER_MONTH, function () use ($college) {
            return Category::whereIn('id', explode(',', $college->getAttribute('remark')))->get();
        });

        return $this->setData($departments)->responseOk();
    }
}
