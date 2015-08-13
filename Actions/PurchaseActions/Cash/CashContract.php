<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 23:09
 */

namespace Xjtuwangke\Actions\PurchaseActions\Cash;

interface CashContract {

    /**
     * @param $amount float 真实数字 比如 12.3
     * @param $allowLoan bool 是否允许出现负值
     */
    public function __construct( $amount , $allowLoan = false );

    /**
     * 是否允许出现负值
     * @param $bool
     * @return CashContract
     */
    public function setLoanAllowed( $bool );

    /**
     * 是否允许出现负值
     * @return bool
     */
    public function isLoanAllowed();

    /**
     * @param CashContract $cash
     * @return CashContract
     */
    public function add( CashContract $cash );

    /**
     * @param CashContract $cash
     * @return CashContract
     */
    public function minus( CashContract $cash );

    /**
     * @param CashContract $cash
     * @return bool
     */
    public function canPay( CashContract $cash );

    /**
     * 返回现金的单位
     * @return string
     */
    public function getUnit();

    /**
     * 返回整数值
     * @return int
     */
    public function toInt();

    /**
     * @param      $value
     * @return CashContract
     */
    public static function fromInt( $value );

    /**
     * @return string
     */
    public static function getType();

    /**
     * @return float
     */
    public function getAmount();

    /**
     * @return string
     */
    public function getAmountString();

}