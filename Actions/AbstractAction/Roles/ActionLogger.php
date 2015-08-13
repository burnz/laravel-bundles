<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/26
 * Time: 02:01
 */

namespace Xjtuwangke\Actions\AbstractAction\Roles;


use Illuminate\Contracts\Logging\Log;
use Xjtuwangke\Actions\AbstractAction\ActionContract;
use Exception;

class ActionLogger implements  Listener{

    /**
     * @var Log
     */
    protected $log;

    /**
     * Log::debug($error);
    Log::info($error);
    Log::notice($error);
    Log::warning($error);
    Log::error($error);
    Log::critical($error);
    Log::alert($error);
     */

    protected $log_commit   = 'debug';

    protected $log_rollback = 'info';

    protected $log_fail     = 'error';

    protected $log_success  = 'debug';

    /**
     * @param Log $log
     */
    public function __construct( Log $log ){
        $this->log = $log;
    }

    /**
     * @inheritdoc
     */
    public function onCommit( ActionContract $action ){
        if( $level = $this->log_commit ){
            $context = array(
                'name'    => $action->name() ,
                'creator' => $action->getCreator()->getFingerprint() ,
                'action'  => $action->getContext() ,
            );
            $this->log->log( $level , 'Action commit event.' , $context );
        }
    }

    /**
     * @inheritdoc
     */
    public function onRollback( ActionContract $action ){
        if( $level = $this->log_rollback ){
            $context = array(
                'name'    => $action->name() ,
                'creator' => $action->getCreator()->getFingerprint() ,
                'action'  => $action->getContext() ,
            );
            $this->log->log( $level , 'Action rollback event.' , $context );
        }
    }

    /**
     * @inheritdoc
     */
    public function onFail( ActionContract $action , Exception $exception){
        if( $level = $this->log_fail ){
            $context = array(
                'name'    => $action->name() ,
                'creator' => $action->getCreator()->getFingerprint() ,
                'action'  => $action->getContext() ,
                'exception' => $exception->getMessage() ,
            );
            $this->log->log( $level , 'Action failed event.' , $context );
        }
    }

    /**
     * @inheritdoc
     */
    public function onSuccess( ActionContract $action ){
        if( $level = $this->log_success ){
            $context = array(
                'name'    => $action->name() ,
                'creator' => $action->getCreator()->getFingerprint() ,
                'action'  => $action->getContext() ,
            );
            $this->log->log( $level , 'Action success event.' , $context );
        }
    }
}