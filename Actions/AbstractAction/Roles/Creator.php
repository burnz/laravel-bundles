<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/26
 * Time: 01:35
 */

namespace Xjtuwangke\Actions\AbstractAction\Roles;


interface Creator {

    /**
     * 获取独一无二的指纹
     * @return string
     */
    public function getFingerprint();
}