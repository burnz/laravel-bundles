<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/10/13
 * Time: 22:28
 */

namespace Xjtuwangke\Pingpp\Webhooks\Events;


use Carbon\Carbon;
use Xjtuwangke\Pingpp\Exceptions\PingppException;

abstract class AbstractEvent
{
    /**
     * @var array
     */
    protected $event;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $livemode = false;

    /**
     * @var Carbon
     */
    protected $created = null;

    /**
     * @var array
     */
    protected $data = null;

    /**
     * @var int
     */
    protected $pending_webhooks = 0;

    /**
     * @var null|string
     */
    protected $requestID;

    /**
     * @var string
     */
    protected $id;

    public function __construct( array $event ){
        $this->livemode = $this->getRequired( $event , 'livemode' );
        $this->id = $this->getRequired( $event , 'id' );
        $created = $this->getRequired( $event , 'created' );
        $this->created = new Carbon( $created );
        $this->type = $this->getRequired( $event , 'type' );
        $this->data = $this->getRequired( $event , 'data' );
        $this->pending_webhooks = $this->getRequired( $event , 'pending_webhooks' );
        $this->requestID = array_get( $event , 'request' );
        $this->event = $event;
    }

    protected function getRequired( array $array , $key ){
        $val = array_get( $array , $key );
        if( null === $val ){
            throw new PingppException('event missing key: ' . $key );
        }
        return $val;
    }

    /**
     * @return string
     */
    public function getType(){
        return $this->type;
    }

    /**
     * @return array
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return boolean
     */
    public function isLivemode()
    {
        return $this->livemode;
    }

    /**
     * @return Carbon
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getPendingWebhooks()
    {
        return $this->pending_webhooks;
    }

    /**
     * @return null|string
     */
    public function getRequestID()
    {
        return $this->requestID;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}