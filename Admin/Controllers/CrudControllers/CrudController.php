<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/30
 * Time: 17:53
 */

namespace Xjtuwangke\Admin\Controllers\CrudControllers;

use Xjtuwangke\Admin\Controllers\AdminController;
use Xjtuwangke\Admin\Elements\KTable\KTable;
use Xjtuwangke\KForm\FormRequest\KFormRequest;

abstract class CrudController extends AdminController{

    use CrudControllerTrait;

    /**
     * @var string
     */
    protected static $name = '未知item';

    /**
     * @var string
     */
    protected static $class;

    /**
     * @var array
     */
    protected $navbar = [];

    /**
     * @var int
     */
    protected $paginate = 15;

    /**
     * @return KTable
     */
    abstract public function listTable();

    /**
     * @return KFormRequest
     */
    abstract protected function formRequest();

}