<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/5/12
 * Time: 06:56
 */

namespace Xjtuwangke\HttpClient;

use Symfony\Component\HttpFoundation\Response as Response;
use Xjtuwangke\HttpClient\ClientRequests\ClientRequest;

interface Contract {

    /**
     * @param ClientRequest $clientRequest
     * @return Response
     */
    public function getResponse( ClientRequest $clientRequest );

}