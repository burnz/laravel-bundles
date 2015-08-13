<?php
$items = $class::where( 'username' , 'like' , "%{$pattern}%" )->orWhere( 'mobile' , 'like' , "%{$pattern}%" )->orWhere( 'email' , 'like' , "%{$pattern}%" )->paginate( 20 );
foreach( $items as $item ){
    $item->_modal_field  = $field;
}
$table = new KTable();
$table->th( '姓名' )
    ->functional( function( $user ){ return e($user->username); } )
    ->th( '邮件' )
    ->functional( function( $user ){ return e($user->email); } )
    ->th( '手机' )
    ->functional( function( $user ){ return e($user->mobile); } )
    ->th( '选择' )
    ->functional( function( $user ) use($action){
        $url = URL::action( $action );
        $token = Session::getToken();
        $btn= <<<BTN
        <a class="btn btn-sm btn-success" onclick="javascript:modal_select_this_one_btn(this)" data-attr-id="{$user->id}" data-attr-url="{$url}" data-csrf-token="{$token}" href="javascript:;"><span class="glyphicon glyphicon-plus"></span></a>
BTN;
        return $btn;
    })
;
$table->itemsToTbody( $items );
echo $table->draw();
?>