<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 23:44
 */

namespace Xjtuwangke\Actions\PurchaseActions\Cash;


abstract class CentBasedCash implements CashContract{

    /**
     * @param $amount float 真实数字 比如 12.3
     * @param $allowLoan bool 是否允许出现负值
     * @throws CashHandleException
     */
    public function __construct( $amount , $allowLoan = false ){
        if( ! is_numeric( $amount ) ){
            $e = new CashHandleException( '新建Cash时出现非法的值' );
            $e->setExceptionMetaData( 'amount' , $amount );
            throw $e;
        }
        $this->int_val = (int) round( $amount * 100 );
        if( true === $allowLoan ){
            $this->allowLoan = true;
        }
        if( false == $this->allowLoan && $this->int_val < 0 ){
            $e = new CashHandleException( '新建非借贷Cash时出现负值' );
            $e->setExceptionMetaData( 'amount' , $this->int_val );
            throw $e;
        }
    }

    /**
     * 是否允许出现负值
     * @param $bool
     * @return CashContract
     */
    public function setLoanAllowed( $bool ){
        if( true === $bool ){
            $this->allowLoan = true;
        }
        else{
            $this->allowLoan = false;
        }
    }

    /**
     * 是否允许出现负值
     * @return bool
     */
    public function isLoanAllowed(){
        return $this->allowLoan;
    }

    /**
     * @param CashContract $cash
     * @return CashContract
     * @throws CashHandleException
     */
    public function add( CashContract $cash ){
        $attempt = $this->int_val + $cash->toInt();
        if( false == $this->allowLoan && $attempt < 0 ){
            $e = new CashHandleException( '非借贷Cash处理中出现负值' );
            $e->setExceptionMetaData( 'attempt' , $attempt );
            $e->setExceptionMetaData( 'origin' , $this->int_val );
            $e->setExceptionMetaData( 'add' , $cash->toInt() );
            throw $e;
        }
        $this->int_val = $attempt;
        return $this;
    }

    /**
     * @param CashContract $cash
     * @return CashContract
     * @throws CashHandleException
     */
    public function minus( CashContract $cash ){
        $attempt = $this->int_val - $cash->toInt();
        if( false == $this->allowLoan && $attempt < 0 ){
            $e = new CashHandleException( '非借贷Cash处理中出现负值' );
            $e->setExceptionMetaData( 'attempt' , $attempt );
            $e->setExceptionMetaData( 'origin' , $this->int_val );
            $e->setExceptionMetaData( 'minus' , $cash->toInt() );
            throw $e;
        }
        $this->int_val = $attempt;
        return $this;
    }

    /**
     * @param CashContract $cash
     * @return bool
     */
    public function canPay( CashContract $cash ){
        return $this->int_val >= $cash->toInt();
    }

    /**
     * 返回现金的单位
     * @return string
     */
    abstract public function getUnit();

    /**
     * 返回整数值
     * @return int
     */
    public function toInt(){
        return $this->int_val;
    }

    /**
     * @param $value
     * @return static
     * @throws CashHandleException
     */
    public static function fromInt( $value ){
        if( ! is_int( $value ) ){
            $e = new CashHandleException( '调用Cash::fromInt的参数不是整数' );
            $e->setExceptionMetaData( 'value' , $value );
            throw $e;
        }
        return new static( (int) $value / 100 );
    }

    /**
     * @return float
     */
    public function getAmount(){
        return round( $this->int_val / 100 , 2 );
    }

    /**
     * @return string
     */
    public function getAmountString(){
        return sprintf( "%.2f" , $this->getAmount() );
    }

}