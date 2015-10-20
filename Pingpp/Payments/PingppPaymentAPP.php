<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/10/12
 * Time: 23:14
 */

namespace Xjtuwangke\Pingpp\Payments;


class PingppPaymentAPP
{

    /**
     * alipay 适用于 App 支付，需要开通支付宝手机支付服务。
     */
    const ALIPAY = 'alipay';

    /**
     * wx 适用于 App 支付，需要开通微信 App 支付服务。
     */
    const WECHAT = 'wx';

    /**
     * upacp 适用于 App 支付，限 2015 年元旦后的银联新商户使用。需要开通银联全渠道支付服务。
    安卓平台需要到 http://mobile.unionpay.com/getclient?platform=android&type=securepayplugin 下载银联支付的插件，iOS 不需要下载银联安全支付 App。
     */
    const UPACP  = 'upacp';

    /**
     * upmp 适用于 App 支付。限 2015 年元旦之前的银联老客户使用，需开通银联手机支付服务（银联于 2015 年元旦正式将该服务整合为新的银联全渠道支付）。
    安卓平台需要到 http://mobile.unionpay.com/getclient?platform=android&type=securepayplugin 下载银联支付的插件，iOS 不需要下载银联安全支付 App。
     */
    const UPMP   = 'upmp';

    /**
     * bfb 适用于 App 支付，需开通百度钱包移动快捷支付服务。
     */
    const BFB    = 'bfb';

    /**
     * apple_pay 只适用于 iOS，且仅限 iPhone6 和 iPhone6 plus 能使用。
     * 使用 Ping++ 发起 apple_pay 请求时需要额外的参数 payment_token，
     * 该参数由 Client 发起交易请求时从支付渠道获得并且传递给 Server ，
     * Server 调用时需要接收该参数并且在向 Ping++ 发起请求时填入。
     * 每次交易的 payment_token 值都不一样，Ping++ 把这个参数放在了 Charge 对象的 extra 字段里，
     * 在发起交易请求的时候需要在 extra 里填写 payment_token。
     */
    const APPLE  = 'apple_pay';
}