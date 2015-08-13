<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 16:26
 */

namespace Xjtuwangke\KForm\DataMapping;


use Xjtuwangke\KForm\FormField\FormField;
use Xjtuwangke\KForm\KForm;

class BlackHoleInstance implements InstanceMappingWithFormContract{

    /**
     * @param KForm $form
     * @return void
     */
    public function instanceMapTo( FormField $formField , KForm $form ){
        $formField->setDefault( null );
    }

    /**
     * @param KForm $form
     * @return void
     */
    public function instanceMapFrom( FormField $formField , KForm $form ){
        //do nothing
    }
}