<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/11/7
 * Time: 17:59
 */

namespace Xjtuwangke\KForm;


class KDummyForm implements SessionFlashedKFormContract
{
    /**
     * @return array
     */
    public function getFormFields(){
        return array();
    }

    /**
     * @return array
     */
    public function getErrors(){
        return array();
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function render( array $options = array() ){
        return null;
    }
}