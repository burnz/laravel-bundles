<?php
$items = $class::where( 'title' , 'like' , "%{$pattern}%" )->orWhere( 'content' , 'like' , "%{$pattern}%" )->paginate( 20 );
foreach( $items as $item ){
    $item->_modal_field  = $field;
}
$table = new KTable();
$table->th( '名称' )
    ->functional( function( $item ){ return e($item->title); } )
    ->th( '图片' )
    ->functional( function( $item ){ return HTML::image( $item->getImage() ); } )
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