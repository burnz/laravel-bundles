<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/22
 * Time: 21:49
 */

namespace Xjtuwangke\KForm\FormRequest;


use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;
use Xjtuwangke\Admin\Elements\KMessenger;
use Xjtuwangke\KForm\FormField\FormField;
use Xjtuwangke\KForm\KForm;
use Illuminate\Http\Exception\HttpResponseException;
use Xjtuwangke\RestfulAPI\Errors\FailedAuthError;
use Xjtuwangke\RestfulAPI\Errors\FailedValidtionError;
use Xjtuwangke\RestfulAPI\Http\APIJsonResponse;

abstract class KFormRequest extends FormRequest{

    /**
     * @var KForm
     */
    protected $kform;

    /**
     * @return KForm
     */
    public function getKform(){
        if( is_null( $this->kform ) ){
            $this->kform = $this->generateKform();
        }
        return $this->kform;
    }

    /**
     * @return KForm
     */
    abstract protected function generateKform();


    /**
     * 权限验证
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * 返回验证规则
     * @return array
     */
    public function rules(){
        return $this->getKform()->getRulesKey();
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return $this->getKform()->getRulesMessage();
    }

    /**
     * 验证方法
     * @throws \HttpRequestException
     */
    public function validate(){
        foreach( $this->getKform()->getFormFields() as $formField ){
            if( $formField instanceof FormField ){
                $formField->setValue( Input::get( $formField->getFieldName() ) , $this );
            }
        }
        if( ! $this->getKform()->isPassed() ){
            $errors = $this->getKform()->getErrors();
            if( $this->ajax() || $this->wantsJson() ){
                $response = new APIJsonResponse();
                $error = new FailedValidtionError();
                $error->setErrorContext( $errors );
                $response->pushError( $error );
                throw new HttpResponseException( $response );
            }
            else{
                $messenger = new KMessenger();
                foreach( $this->getKform()->getFormFields() as $formField ){
                    foreach( $formField->getErrors() as $error ){
                        $messenger->push( $error , KMessenger::ERROR );
                    }
                }
                $view = $this->directlyErrorView();
                if( $view instanceof View ){
                    $response = \Response::make( $view );
                }
                else{
                    $response = $this->redirectBackResponse();
                }
                throw new HttpResponseException( $response );
            }
        }
    }

    public function directlyErrorView(){
        return null;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectBackResponse(){
        return $this->redirector->to($this->getRedirectUrl())->with( KForm::$session_flash_key , $this->getKform() );
    }

    /**
     *
     */
    protected function failedAuthorization(){
        if( $this->acceptsJson() ){
            $response = new APIJsonResponse();
            $response->pushError( new FailedAuthError() );
            throw new HttpResponseException( $response );
        }
        else{

        }
    }

}