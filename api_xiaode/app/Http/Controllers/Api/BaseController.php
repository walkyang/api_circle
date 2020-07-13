<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Traits\ReturnAjaxTraits;

// 定义1个基类
class BaseController extends Controller
{
    use ReturnAjaxTraits;
}
