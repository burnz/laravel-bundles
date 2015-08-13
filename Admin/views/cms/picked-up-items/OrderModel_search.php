<?php
$items = $class::where( 'order_no' , 'like' , "%{$pattern}%" )->orWhere( 'mobile' , 'like' , "%{$pattern}%" )->paginate( 20 );
foreach( $items as $item ){
    $item->_modal_field  = $field;
}
$table = new KTable();
$table->th( '订单号' )
    ->functional( function( $order ){ return e($order->orderno); } )
    ->th( '收件人' )
    ->functional( function( $order ){ return e($order->accept_name); } )
    ->th( '手机' )
    ->functional( function( $order ){ return e($order->mobile); } )
    ->th( '地址' )
    ->functional( function( $order ){ return KTable::textInsideTd( $order->fullAddress() ); } )
    ->th( '状态' )
    ->functional( function( $order ){ return e($order->status); } )
    ->th( '选择' )
    ->functional( function( $item ) use($action){
        $url = URL::action( $action );
        $token = Session::getToken();
        $btn= <<<BTN
        <a class="btn btn-sm btn-success" onclick="javascript:modal_select_this_one_btn(this)" data-attr-id="{$item->id}" data-attr-url="{$url}" data-csrf-token="{$token}" href="javascript:;"><span class="glyphicon glyphicon-plus"></span></a>
BTN;
        return $btn;
    })
;
$table->itemsToTbody( $items );
echo $table->draw();
?>