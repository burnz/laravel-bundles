<?php if( $item ):?>
    <?php
    $section_action = <<<BTN
<a class="btn btn-sm btn-danger" onclick="javascript:remove_parent_tr(this)" href="javascript:;"><span class="glyphicon glyphicon-minus"></span></a>
<input style="display:none" name="{$field}" type="text" value="{$item->id}">
BTN;
        $table = new KTable();
        $table->th( '姓名' )->setWidth( '10%' )
            ->th( '邮件' )->setWidth( '10%' )
            ->th('手机')->setWidth( '10%' )
            ->th('性别')->setWidth( '10%' )
            ->th( '操作' )->setWidth( '10%' )
            ->td( $item->username )
            ->td( $item->email )
            ->td( $item->mobile )
            ->td( $item->gender )
            ->td( $section_action , [] , false )
            ->tr();
    ?>
    <?=$table->draw()?>
<?php endif;?>