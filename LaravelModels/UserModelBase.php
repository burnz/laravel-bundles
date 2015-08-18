<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/24
 * Time: 04:56
 */

namespace Xjtuwangke\LaravelModels;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Schema\Blueprint;
use Xjtuwangke\LaravelModels\Traits\LoginableTrait;
use Zizaco\Entrust\Contracts\EntrustUserInterface;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class UserModelBase extends BaseModel implements EntrustUserInterface , Authenticatable{

    use LoginableTrait;
    use EntrustUserTrait;

    protected $table = 'users';

    public static function _schema_UserModel( Blueprint $table ){
        $table->string( 'email' )->nullable()->label('邮箱');
        $table->string( 'mobile' )->nullable()->label('手机号码');
        $table->string( 'username' )->nullable()->label('姓名');
        $table->string( 'nickname' )->nullable()->label('昵称');
        $table->enum( 'gender' , [ '未填' => '未填' , '男' => '男' , '女' => '女'  ] )->default( '未填' )->label('性别');
        $table->string( 'avatar' )->nullable()->label('头像');
        $table->date( 'birthday' )->nullable()->label('生日');
        return $table;
    }

    public function setPasswordStringAttribute( $password ){
        if( $password ){
            $this->attributes['password'] = \Hash::make( $password );
        }
    }

    public function getPasswordStringAttribute(){
        return '';
    }

}