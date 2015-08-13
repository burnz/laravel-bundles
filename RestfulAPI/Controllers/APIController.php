<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/11
 * Time: 02:24
 */

namespace Xjtuwangke\RestfulAPI\Controllers;

use Xjtuwangke\KForm\FormRequest\KFormRequest;
use Xjtuwangke\L5Controller\L5Controller;
use Xjtuwangke\RestfulAPI\Errors\APIError;
use Xjtuwangke\RestfulAPI\Http\APIJsonResponse;

abstract class APIController extends L5Controller{

    /**
     * @var APIJsonResponse
     */
    protected $response = null;

    public function __construct(){
        $this->response = new APIJsonResponse();
    }

    public function handle( KFormRequest $request ){
        try{
            $this->action( $request );
        }
        catch( APIError $error ){
            $this->response->pushError( $error );
        }
        catch( \Exception $e ){
            $this->response->pushException( $e );
        }
        return $this->response;
    }

    abstract protected function action( KFormRequest $request );

}