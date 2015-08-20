<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/8/20
 * Time: 14:38
 */

namespace Xjtuwangke\Aliyun\SLS;

use Xjtuwangke\BugSnag\Exception as BaseException;

/**
 * The Exception of the sls request & response.
 *
 * @author sls_dev
 */
class Exception extends BaseException{
    /**
     * @var string
     */
    private $requestId;

    /**
     * Exception constructor
     *
     * @param string $code
     *            SLS error code.
     * @param string $message
     *            detailed information for the exception.
     * @param string $requestId
     *            the request id of the response, '' is set if client error.
     */
    public function __construct($code, $message, $requestId='') {
        parent::__construct($message);
        $this->code = $code;
        $this->message = $message;
        $this->requestId = $requestId;
    }

    /**
     * The __toString() method allows a class to decide how it will react when
     * it is treated like a string.
     *
     * @return string
     */
    public function __toString() {
        return "Exception: \n{\n    ErrorCode: $this->code,\n    ErrorMessage: $this->message\n    RequestId: $this->requestId\n}\n";
    }

    /**
     * Get Exception error code.
     *
     * @return string
     */
    public function getErrorCode() {
        return $this->code;
    }

    /**
     * Get Exception error message.
     *
     * @return string
     */
    public function getErrorMessage() {
        return $this->message;
    }

    /**
     * Get Sls sever requestid, '' is set if client or Http error.
     *
     * @return string
     */
    public function getRequestId() {
        return $this->requestId;
    }
}