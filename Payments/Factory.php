<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/23
 * Time: 18:23
 */

namespace Xjtuwangke\Payments;


use Omnipay\Alipay\ExpressGateway as AlipayExpressGateway;
use Config;
use Omnipay\WeiboPay\WeiboExpressPayGateway;
use Omnipay\Wechat\ExpressGateway as WechatExpressGateway;

class Factory
{

    /**
     * @param array|null $config
     * @return AlipayExpressGateway
     */
    public function alipay( array $config = null ){
        $returnUrl = url('pay/alipay/return');
        $notifyUrl = url('pay/alipay/notify');
        $gateway = new AlipayExpressGateway();
        if( is_null( $config ) ){
            $config = Config::get('payments.alipay');
        }
        $gateway->setPartner( array_get( $config , 'id' ) );
        $gateway->setKey( array_get( $config , 'key' ));
        $gateway->setSellerEmail( array_get( $config , 'email' ) );
        $gateway->setNotifyUrl($notifyUrl);
        $gateway->setReturnUrl($returnUrl);
        return $gateway;
    }

    /**
     * @param array|null $config
     * @return WeiboExpressPayGateway
     */
    public function weibo( array $config = null ){
        $returnUrl = url('pay/weibo/return');
        $notifyUrl = url('pay/weibo/notify');
        $gateway = new WeiboExpressPayGateway();
        if( is_null( $config ) ){
            $config = Config::get('payments.weibo');
        }
        $gateway->setPartner( array_get( $config , 'partner' ) );
        $gateway->setKey( array_get( $config , 'key' ));
        $gateway->setNotifyUrl($notifyUrl);
        $gateway->setShowUrl($returnUrl);
        return $gateway;
    }

    public function wechat( array $config = null ){
        $returnUrl = url('pay/wechat/return');
        $notifyUrl = url('pay/wechat/notify');
        $cancelUrl = url('pay/wechat/cancel');
        $gateway = new WechatExpressGateway();
        if( is_null( $config ) ){
            $config = Config::get('payments.weichat');
        }
        $gateway->setAppId(array_get( $config , 'app_id' ) );
        $gateway->setKey(array_get( $config , 'pay_sign_key' ));
        $gateway->setPartner(array_get( $config , 'partner' ));
        $gateway->setPartnerKey(array_get( $config , 'partner_key' ));

        $gateway->setNotifyUrl($notifyUrl);
        $gateway->setReturnUrl($returnUrl);
        $gateway->setReturnUrl($cancelUrl);

        $gateway->setCertPath(array_get( $config , 'cert_path' ));
        $gateway->setCertKeyPath(array_get( $config , 'cert_key_path' ));
        return $gateway;
    }


}