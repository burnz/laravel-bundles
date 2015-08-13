<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 21:09
 */

namespace Xjtuwangke\Admin\Controllers;

class DashboardController extends AdminController{

    public function index(){
        return $this->getLayout();
    }
}