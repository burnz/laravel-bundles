<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 18:14
 */

namespace Xjtuwangke\L5Controller;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class L5Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;
}