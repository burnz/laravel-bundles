/**
 * Created by kevin on 14-8-21.
 */

$(function(){

    //tags中移除tag条

    $('a[form-role="tag-remove"]').click(function(){
        $(this).parents('span.label').remove();
    });

    $('.btn[form-role="add-tag"]').click(function(){
        var tag = $.trim( $(this).parents('.input-group').find("input").val()),
            field = $.trim( $(this).attr('form-attr-field')),
            new_span = '<span class="label label-info" style="display:inline-block;margin-right:5px;">' +
                tag + '<a href="javascript:;" form-role="tag-remove" style="margin-left:10px;">x</a>' +
                '<input style="display:none;" name="' + field + '" value="' + tag + '">' +
                '</span>';
        $(this).parents('.form-group').find('.tags-span').append( new_span );
        $('a[form-role="tag-remove"]').unbind( 'click' );
        $('a[form-role="tag-remove"]').click(function(){
            $(this).parents('span.label').remove();
        });
    });

    $("input[data-form-date-role='date']").each( function( index , one ){
        $(one).datepicker();
    });

    $("input[data-form-date-role='daterange']").each( function( index , one ){
        $(one).daterangepicker({
            format: 'YYYY-MM-DD hh:mm:ss',
            timePicker : true ,
            separator : '到'
        });
    });

    //appendable formfiled
    $('.btn[form-role="appendable"]').click(function(){
        var $this = $(this),
            url   = $this.attr('data-attr-append-url'),
            $target = $this.parents('.form-group').find('.appendable-inputs');
        $.ajax({
            url : url ,
            success : function( data ){
                $target.append( data );
            }
        });
    });

    //表格批量操作
    $('a[data-role="table-selecte-all"]').click(function(){
            $('input[data-role="form-selection"]').iCheck( 'check' );
        }
    );

    $('a[data-role="table-selecte-none"]').click(function(){
            $('input[data-role="form-selection"]').iCheck( 'uncheck' );
        }
    );

    $('a[data-role="table-group-action"]').click(function(){
        var $this = $(this),
            token = $this.attr( 'data-attr-token'),
            url   = $this.attr( 'data-attr-url'),
            $form = $("<form>"),
            $token = $( "<input>" ).attr( "name" , '_token' ).val( token );
        $form.attr('method' , 'POST');
        $form.attr( 'action' , url );
        $form.attr( 'target' , '_blank' );
        $form.css('display','none');
        $form.append( $token );
        $('input[data-role="form-selection"]').each( function( index , one ){
            var $one = $(one),
                checked = $one.is(':checked'),
                id = $one.attr( 'data-attr-id' );
            if( checked ){
                $form.append( $( "<input>" ).attr( "name" , 'id[]' ).val( id ) );
            }
        })
         console.log( $form );
         $('body').append( $form );
         $form.submit();
    });

})

function appendable_form_remove_btn( btn ){
    var $btn = $(btn);
    $btn.parents('.appendable').remove();
}
