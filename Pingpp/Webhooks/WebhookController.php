<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/10/13
 * Time: 22:08
 */

namespace Xjtuwangke\Pingpp\Webhooks;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Xjtuwangke\Pingpp\Exceptions\UnknownWebhookType;
use Xjtuwangke\Pingpp\Exceptions\WebHookVerifyFailed;
use Xjtuwangke\Pingpp\Webhooks\Events\ChargeSucceeded;
use Xjtuwangke\Pingpp\Webhooks\Events\RedEnvelopeReceived;
use Xjtuwangke\Pingpp\Webhooks\Events\RedEnvelopeSent;
use Xjtuwangke\Pingpp\Webhooks\Events\RefundSucceeded;
use Xjtuwangke\Pingpp\Webhooks\Events\SummaryDailyAvailable;
use Xjtuwangke\Pingpp\Webhooks\Events\SummaryMonthlyAvailable;
use Xjtuwangke\Pingpp\Webhooks\Events\SummaryWeeklyAvailable;
use Xjtuwangke\Pingpp\Webhooks\Events\TransferSucceeded;
use Xjtuwangke\Pingpp\Webhooks\Events\AbstractEvent;

class WebhookController extends Controller
{

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws UnknownWebhookType
     * @throws WebHookVerifyFailed
     */
    public function postListen( Request $request ){
        $event = $this->handle( $request );
        event( $event );
        return \Response::make('OK',200);
    }
    /**
     * @link https://pingxx.com/document/api#api-event
     * @param Request $request
     * @return AbstractEvent
     * @throws UnknownWebhookType
     * @throws WebHookVerifyFailed
     */
    protected function handle( Request $request ){
        if( true == $this->verify( $request ) ){
            $event = $request->all();
            switch( strtolower( $request->get('type') ) ){
                case 'charge.succeeded':
                    $event = new ChargeSucceeded( $event );
                    break;
                case 'refund.succeeded':
                    $event = new RefundSucceeded( $event );
                    break;
                case 'transfer.succeeded':
                    $event = new TransferSucceeded( $event );
                    break;
                case 'red_envelope.sent':
                    $event = new RedEnvelopeSent( $event );
                    break;
                case 'red_envelope.received':
                    $event = new RedEnvelopeReceived( $event );
                    break;
                case 'summary.daily.available':
                    $event = new SummaryDailyAvailable( $event );
                    break;
                case 'summary.weekly.available':
                    $event = new SummaryWeeklyAvailable( $event );
                    break;
                case 'summary.monthly.available':
                    $event = new SummaryMonthlyAvailable( $event );
                    break;
                default:
                    throw new UnknownWebhookType('unknown type:' . $request->get('type') );
            }
            return $event;
        }
        else{
            throw new WebHookVerifyFailed;
        }
    }

    /**
     * 验证 webhooks 签名
     * @param Request $request
     * @return bool
     * @throws WebHookVerifyFailed
     */
    protected function verify( Request $request ){
        //POST 原始请求数据是待验签数据，请根据实际情况获取
        //$raw_data = file_get_contents('php://input');
        $raw_data = $request->getContent();
        //签名在头部信息的 x-pingplusplus-signature 字段
        $signature = $request->header('x-pingplusplus-signature');
        $pub_key_contents = file_get_contents( 'cert/pingpp/public.pem');
        $result = openssl_verify($raw_data, base64_decode($signature), $pub_key_contents, OPENSSL_ALGO_SHA256 );
        if( 1 === $result ){
            return true;
        }
        else{
            $e = new WebHookVerifyFailed('openssl_verify result is: ' . $result );
            $e->setExceptionMetaData( 'request' , $request->all() );
            $e->setExceptionMetaData( 'signature' , $signature );
            throw $e;
        }
    }
}