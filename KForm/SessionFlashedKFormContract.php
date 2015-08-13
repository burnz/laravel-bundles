<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 23:49
 */

namespace Xjtuwangke\KForm;


interface SessionFlashedKFormContract {

    /**
     * @return array
     */
    public function getFormFields();

    /**
     * @return array
     */
    public function getErrors();

    /**
     * @param array $options
     * @return mixed
     */
    public function render( array $options = array() );
}