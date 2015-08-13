<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/26
 * Time: 01:36
 */

namespace Xjtuwangke\Actions\AbstractAction\Roles;


use Xjtuwangke\Actions\AbstractAction\ActionContract;
use Exception;

interface Listener {

    /**
     * 监听Commit事件
     * @param ActionContract $action
     * @return mixed
     */
    public function onCommit( ActionContract $action );

    /**
     * 监听rollback事件
     * @param ActionContract $action
     * @return mixed
     */
    public function onRollback( ActionContract $action );

    /**
     * 监听Fail事件
     * @param ActionContract $action
     * @param Exception      $exception
     * @return mixed
     */
    public function onFail( ActionContract $action , Exception $exception);

    /**
     * 监听成功事件
     * @param ActionContract $action
     * @return mixed
     */
    public function onSuccess( ActionContract $action );
}