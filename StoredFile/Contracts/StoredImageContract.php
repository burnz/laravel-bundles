<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 22:03
 */

namespace Xjtuwangke\StoredFile\Contracts;


interface StoredImageContract extends StoredFileContract{

    /**
     * @return mixed
     */
    public function getWidth();

    /**
     * @return mixed
     */
    public function getHeight();

    /**
     * @return mixed
     */
    public function getSize();
}