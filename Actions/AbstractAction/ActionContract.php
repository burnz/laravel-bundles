<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/26
 * Time: 01:34
 */

namespace Xjtuwangke\Actions\AbstractAction;

use Xjtuwangke\Actions\AbstractAction\Roles\Creator;
use Xjtuwangke\Actions\AbstractAction\Roles\Listener;

interface ActionContract {

    const Trans_Succeed      = 1;
    const Trans_Failed       = -1;
    const Trans_Uncommitted  = 0;

    /**
     * 获取Creator
     * @return Creator
     */
    public function getCreator();

    /**
     * @param Listener $listener
     * @return ActionContract
     */
    public function addListener( Listener $listener );

    /**
     * @return void
     */
    public function rollback();

    /**
     * @return int
     */
    public function commit();

    /**
     * @return int
     */
    public function getResult();

    /**
     * 生成array供记录
     * @return array
     */
    public function getContext();

    /**
     * action名称
     * @return string
     */
    public function name();
}