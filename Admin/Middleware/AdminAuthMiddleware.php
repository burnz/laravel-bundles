<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/23
 * Time: 18:23
 */

namespace Xjtuwangke\Admin\Middleware;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Xjtuwangke\Admin\Controllers\LockController;
use Xjtuwangke\LaravelModels\UserModel;
use Zizaco\Entrust\Contracts\EntrustUserInterface;

class AdminAuthMiddleware {

    protected $user;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle( Request $request, \Closure $next)
    {
        $result = $this->check();
        $this->onBoot();
        if ( true === $result ) {
            return $next($request);
        }
        else{
            return $result;
        }
    }

    /**
     * @return bool
     */
    public function check(){
        $user = \Auth::user();
        $this->user = $user;
        if( ! $user || ! $user instanceof UserModel || ! $user->hasRole( 'admin' ) ){
            return redirect()->route('admin::login');
        }
        if( LockController::isLocked() ){
            return redirect()->route('admin::lock');
        }
        if( false == $this->checkPermission( \Route::current() , $user ) ){
            return redirect()->route('admin::forbidden');
        }
        return true;
    }

    /**
     * @param Route                $route
     * @param EntrustUserInterface $user
     * @return bool
     */
    public function checkPermission( Route $route , EntrustUserInterface $user ){
        $action = $route->getName()?:$route->getPath();
        return $this->canAccess( $action , $user );
    }

    /**
     * @param                      $action
     * @param EntrustUserInterface $user
     * @return bool
     */
    public static function canAccess( $action , EntrustUserInterface $user ){
        if( $user->hasRole( 'root' , true ) ){
            return true;
        }
        if( false == $user->hasRole( 'admin' ) ){
            return false;
        }
        if( in_array( $action , ['admin::index' , 'admin::forbidden' , 'admin::dashboard' , 'admin::lock' , 'admin::unlock' ] ) ){
            return true;
        }
        return $user->can( $action );
    }

    public function onBoot(){

    }
}