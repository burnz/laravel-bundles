<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/7/4
 * Time: 22:51
 */

namespace Xjtuwangke\KForm\Dumper;

use Xjtuwangke\LaravelModels\BaseModel;

class FormDumper {

    /**
     * @param BaseModel $model
     * @return mixed
     */
    public static function dump( BaseModel $model ){
        $blueprint = new FormBluePrint();
        $blueprint = $model->_schema( $blueprint );
        return $blueprint->dump();
    }

}