/**
 * Created by kevin on 14-8-10.
 */

/*
 * for admin tables
 * 排序功能
 * 查找功能
 */

$( function(){

    $("table[data-role='table'] div.table-sort-btn").each( function( index , one ){
        var $one = $(one),
            field = $one.attr('attr-field'),
            $asc = $one.find('button[attr-btn-sort="asc"]'),
            $desc = $one.find('button[attr-btn-sort="desc"]');
        $asc.click( function(){
            cms_table_query( {
                sort : field ,
                order : 'asc'
            });
        });
        $desc.click( function(){
            cms_table_query( {
                sort : field ,
                order : 'desc'
            });
        });
    });

    $("table[data-role='table'] .input-table-search").each( function( index , one ){
        var $one= $(one),
            field = $one.attr('attr-field'),
            $parent = $one.parents("div.input-group"),
            $search = $parent.find("div.table-search-btn"),
            $reset  = $parent.find("div.table-reset-search-btn");
        console.log( $search );
        $search.click( function(){
            var pattern = $(this).parents("div.input-group").find(".input-table-search").val();
            cms_table_query( {
                search : pattern,
                field  : field
            });
        });
        $reset.click( function(){
            cms_table_query( {
                search : null,
                field  : null
            });
        });
    });

    $("table[data-role='table'] .btn-table-groupby").each( function( index , one ){
        var $one = $(one),
            field = $one.attr('attr-field'),
            value = $one.attr('attr-groupby');
        $one.click( function(){
            cms_table_query({
                groupby     : field ,
                group_value : value
            });
        });
    });

    $("table[data-role='table'] .table-role-btn-switch").each( function( index , one ){
        var $one = $(one),
            token = $one.attr( 'data-attr-token' ),
            url   = $one.attr('data-attr-url'),
            id   = $one.attr('data-attr-id'),
            status = $one.html();
        $one.click( function(){
            artDialog.confirm({
                content : '确定要修改状态为' + status + '吗',
                confirmed  : function(){
                    $.ajax({
                        url : url,
                        type : 'POST',
                        data :{
                            'status' : status ,
                            'id' : id ,
                            '_token' : token
                        },
                        success : function( content ){
                            console.log( content );
                            $one.parents('.btn-group').find('.btn-current-status').html( status );
                        }
                    })
                }
            });
        });
    });

})

function cms_table_query( options ){
    /*
     var url = $.getUrl();
     $.each( options , function( i , j ){
     url.get[i] = j;
     });
     $.each( url.get , function( i , j ){
     var $input = $("<input>").attr( "name" , i ).attr( 'value' , j );
     $("#_table-queries").append( $input );
     });
     */
    $.each( options , function( index , element ){
        var selector = '#_table-queries input[name="' + index + '"]';
        $input = $( selector );
        if( $input.length ){
            $input.val( element );
        }
    });

    $("#_table-queries").submit();
}

function remove_parent_tr( one ){
    $(one).parents('tr').remove();
}
