<!doctype html>
<html class="no-js" lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{ $title or '' }}_管理页面_{{ Config::get('admin.site.name') or '未命名站点' }}</title>
    <meta name="description" content="{{ $description or '' }}">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="renderer" content="webkit|ie-stand|ie-comp">
    @if( isset( $_controller ) && $_controller instanceof \Xjtuwangke\L5Controller\L5ViewController )
    {!! $_controller->getAssets()->renderAll() !!}
    @endif
</head>
<body class="skin-blue">
<header class="header">
<a href="<?=route('admin::dashboard')?>" class="logo">
    <span>{{ Config::get('admin.site.name') }}</span>
</a>
<nav class="navbar navbar-static-top" role="navigation">
<a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
</a>
<div class="navbar-left">
    @if( isset( $shortcuts ) && is_array( $shortcuts ) )
        @foreach( $shortcuts as $one )
        <ul class="nav navbar-nav">
            <li class="dropdown lock-screen-menu">
                <a href="{{ $one->url or 'javascript:;' }}" target="_blank">
                    <i class="fa fa-spin"></i>{{ $one->title or '' }}
                </a>
            </li>
        </ul>
        @endforeach
    @endif
</div>
<div class="navbar-right">
    <ul class="nav navbar-nav">
        <li class="dropdown report-bug-menu">
            <a href="mailto:{{ Config::get('admin.site.email') }}">
                <i class="fa fa-bug"></i>后台使用说明
            </a>
        </li>
        <li class="dropdown report-bug-menu">
            <a href="mailto:{{ Config::get('admin.site.email') }}">
                <i class="fa fa-bug"></i>上报Bug(版本:{{ Xjtuwangke\DebugInfo\ServiceProvider::appVersion() }})
            </a>
        </li>
        <li class="dropdown lock-screen-menu">
            <a href="<?=route('admin::lock')?>">
                <i class="fa fa-lock"></i>锁屏
            </a>
        </li>
        <?php $user = Auth::getUser();?>
        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="glyphicon glyphicon-user"></i>
                <span>{{ $user->username }}<i class="caret"></i></span>
            </a>
            <ul class="dropdown-menu">
                <li class="user-header bg-light-blue">
                    <img src="" class="img-circle" alt="User Image">
                    <p>
                        {{ $user->username }} <small>上次登陆时间{{ $user->last_login }}</small>
                    </p>
                </li>
                <li class="user-footer">
                    <div class="pull-right">
                        <a href="{{ url('admin/logout') }}" class="btn btn-danger">登出</a>
                    </div>
                    <div class="pull-left">
                        <a href="{{ url('admin/password') }}" class="btn btn-warning">修改密码</a>
                    </div>
                    <div class="pull-left">
                        <a href="{{ url('admin/profile') }}" class="btn btn-info">个人信息</a>
                    </div>
                </li>
            </ul>
        </li>
    </ul>
</div>
</nav>
</header>
<div class="wrapper row-offcanvas row-offcanvas-left">
<aside class="left-side sidebar-offcanvas">
    <section class="sidebar">
        {!! $navbar or '' !!}
    </section>
</aside>

<aside class="right-side">
<section class="content-header">
    <h1>
        {!! $title or '' !!}
        <small>{!! $small_title or '' !!}</small>
    </h1>
</section>
<!-- Main content -->
<section class="content">
    <?php
        $messenger = new \Xjtuwangke\Admin\Elements\KMessenger();
        echo $messenger->show();
    ?>
    {!! $content or '' !!}
</section><!-- /.content -->
</aside><!-- /.right-side -->
</div><!-- ./wrapper -->
{!! $analytics or '' !!}
</body>
</html>
