<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 16:23
 */

namespace Xjtuwangke\KForm\DataMapping;
use Xjtuwangke\KForm\FormField\FormField;
use Xjtuwangke\KForm\KForm;

interface InstanceMappingWithFormContract {

    /**
     * @param KForm $form
     * @return void
     */
    public function instanceMapTo( FormField $formField , KForm $form );

    /**
     * @param KForm $form
     * @return void
     */
    public function instanceMapFrom( FormField $formField , KForm $form );

}