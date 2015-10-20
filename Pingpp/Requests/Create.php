<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/10/12
 * Time: 22:48
 */

namespace Xjtuwangke\Pingpp\Requests;

use Pingpp\Charge;

class Create
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * @param array $config
     */
    public function setConfig( array $config ){
        $this->config = $config;
    }

    /**
     * @param $channel
     * @param $orderno
     * @param $amount
     * @param array $others
     * @param array|null $extra
     * @return \Illuminate\Http\Response
     */
    public function response( $channel , $orderno , $amount , array $others = array() , array $extra = null ){
        $charge = $this->create( $channel , $orderno , $amount , $others , $extra );
        return \Response::make( $charge );
    }

    /**
     * @param $channel
     * @param $orderno
     * @param $amount
     * @param array $others
     * @param array|null $extra
     * @return Charge
     */
    public function create( $channel , $orderno , $amount , array $others = array() , array $extra = null ){
        $config = array(
            'channel' => $channel ,
            'order_no' => $orderno ,
            'amount' => $amount ,
        );
        if( $extra ){
            $config['extra'] = $extra;
        }
        $config = array_merge( $config , $others , $this->config );
        $charge = Charge::create( $config );
        return $charge;
    }
}