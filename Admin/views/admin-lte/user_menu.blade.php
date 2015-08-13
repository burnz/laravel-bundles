<?php if( $user ):?>
    <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="glyphicon glyphicon-user"></i>
            <span><?=$user->username?><i class="caret"></i></span>
        </a>
        <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header bg-light-blue">
                <img src="<?=$user->avatar?>" class="img-circle" alt="User Image" />
                <p>
                    <?=$user->username?> - <?=$user->role->title?>
                    <small>登陆时间<?=$user->last_login?></small>
                    <small>登陆ip<?=$user->last_ip?></small>
                </p>
            </li>
            <!-- Menu Body -->
            <!--
            <li class="user-body">
                <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                </div>
                <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                </div>
                <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                </div>
            </li>
            -->
            <!-- Menu Footer-->
            <li class="user-footer">
                <!--
                <div class="pull-left">
                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                -->
                <div class="pull-right">
                    <a href="<?=route('admin::logout')?>" class="btn btn-default btn-flat">登出</a>
                </div>
            </li>
        </ul>
    </li>
<?php else:?>
<?php endif;?>