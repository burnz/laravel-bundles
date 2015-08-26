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
use Xjtuwangke\BugSnag\Exception;
use Xjtuwangke\QRCode\QRFactory;

class Factory
{

    /**
     * @param array|null $config
     * @return \Omnipay\Alipay\ExpressGateway
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
     * @return \Omnipay\WeiboPay\WeiboExpressPayGateway
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

    /**
     * @param array|null $config
     * @return \Omnipay\Wechat\ExpressGateway
     */
    public function wechat( array $config = null ){
        $returnUrl = url('pay/wechat/return');
        $notifyUrl = url('pay/wechat/notify');
        $cancelUrl = url('pay/wechat/cancel');
        $gateway = new WechatExpressGateway();
        if( is_null( $config ) ){
            $config = Config::get('payments.wechat');
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

    /**
     * @param $out_trade_no string 交易号
     * @param $total_fee float 总金额
     * @param $subject string
     * @param null|string $trade_type
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws Exception
     */
    public function wechat_qrcode( $out_trade_no , $total_fee , $subject , $trade_type = null ){
        $config = Config::get('payments.wechat',array());
        if( is_null( $trade_type ) || WechatExpressGateway::Trade_Type_JSAPI === $trade_type ){
            //JS API方式支付
            //初始化wechat js api
            $jsApi = new \Omnipay\Wechat\Sdk\JsApi();
            $jsApi->init(array(
                'app_id' => $config['app_id'],
                'mch_id' => $config['mch_id'],
                'app_secret' => $config['app_secret'],
                'pay_sign_key' => $config['pay_sign_key'],
                'cert_path' => $config['cert_path'],
                'cert_key_path' => $config['cert_key_path'],
            ));
            //回调parameters,GET方式传给回调url
            $parameters = array(
                'out_trade_no' => $out_trade_no ,
                'total_fee' => $total_fee ,
                'subject' => $subject ,
            );
            $r_link = url( 'pay/wechat/?' . http_build_query( $parameters ) );
            $url = $jsApi->createOauthUrlForCode(urlencode($r_link));
            //造二维码
            $qrFactory = new QRFactory();
            return $qrFactory->make( $url )->response();
        }
        if( WechatExpressGateway::Trade_Type_NATIVE === $trade_type ){
            //native方式
            $gateway = $this->wechat();
            $parameters = array(
                'out_trade_no' => $out_trade_no ,
                'total_fee' => $total_fee ,
                'subject' => $subject ,
                'mch_id' => array_get( $config , 'mch_id' ) ,
            );
            $result = $gateway->prePurchase($parameters,WechatExpressGateway::Trade_Type_NATIVE )->send();
            $result = $result->getTransactionReference();
            if( is_array( $result ) && array_get( $result , 'return_code' ) === 'SUCCESS' ){
                $qrFactory = new QRFactory();
                return $qrFactory->make( array_get( $result , 'code_url' ) )->response();
            }
            else{
                $e = new Exception();
                $e->setExceptionMetaData('wechat-qrcode-native-api-fail',$result );
                throw $e;
            }
        }
    }


}