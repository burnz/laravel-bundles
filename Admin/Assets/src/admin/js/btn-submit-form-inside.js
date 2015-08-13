/**
 * Created by kevin on 14-8-15.
 */

$(function(){
    $('.btn-submit-form-inside').click( function(){
        var $this = $(this),
            $form = $($this).find('form'),
            confirm = $(this).attr('data-attr-confirm');
        if( $form.length ){
            if( confirm ){
                artDialog.confirm({
                    content : confirm,
                    confirmed  : function(){
                        $form.submit();
                    }
                });
            }
            else{
                $form.submit();
            }
        }
    })
});