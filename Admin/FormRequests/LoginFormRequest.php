<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 21:55
 */

namespace Xjtuwangke\Admin\FormRequests;


use Xjtuwangke\Admin\Controllers\LoginController;
use Xjtuwangke\KForm\FormRequest\KFormRequest;

class LoginFormRequest extends KFormRequest{

    /**
     * @return \Xjtuwangke\KForm\KForm
     */
    protected function generateKform(){
        return LoginController::getLoginForm();
    }

    /**
     * @return View
     */
    public function directlyErrorView(){
        return null;
        $controller = new LoginController();
        return $controller->login( $this->getKform() );
    }

}