<?php
/**
 * php artisan migrate:make create_users_table
 * php artisan migrate
 *
 * 创建 tables:
 * users  普通用户信息
 * user_profile 用户profile
 * admins 管理员信息
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Xjtuwangke\LaravelModels\UserModel;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create( UserModel::getTableName() , function( Blueprint $table ){
            UserModel::_schema( $table );
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
        Schema::dropIfExists( UserModel::getTableName() );
	}

}
