<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/6
 * Time: 19:12
 */

namespace Xjtuwangke\Utils;


use Carbon\Carbon;

class DataWithExpiration {

    /**
     * @var null
     */
    protected $data = null;

    /**
     * @var Carbon
     */
    protected $expires_at;

    /**
     * @param        $data
     * @param Carbon $carbon
     */
    public function __construct( $data , Carbon $carbon = null ){
        $this->data = $data;
        $this->expires_at = $carbon;
    }

    /**
     * @return bool
     */
    public function isExpired(){
        if( is_null( $this->expires_at ) ){
            return false;
        }
        if( $this->expires_at->isFuture() ){
            return false;
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function getData(){
        return $this->data;
    }

    /**
     * @param int $seconds
     */
    public function setExpireInSeconds( $seconds ){
        $this->expires_at = Carbon::now()->addSeconds( (int) $seconds );
    }

    /**
     * @param int $minutes
     */
    public function setExpireInMinutes( $minutes ){
        $this->expires_at = Carbon::now()->addMinutes( (int) $minutes );
    }

}