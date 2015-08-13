/**
 * Created by kevin on 14-8-28.
 */

$(function(){
    $(".btn-search-modal-search").click(function(){
        var $this = $(this),
            $results = $this.parents( '.modal-content' ).find('.modal-body-results'),
            $search = $this.parents( '.modal-content' ).find('.modal-body-search'),
            $reset_btn = $this.parents( '.modal-content').find('.btn-search-modal-reset'),
            pattern = $this.parents('.modal-content').find(".modal-search-pattern").val(),
            url = $this.attr('data-attr-ajax-url'),
            options ={
                'pattern' : pattern ,
                'field' :$this.parents('.modal').attr('field') ,
                '_token' : $this.attr('data-csrf-token')
            };
        console.log( url );
        console.log( options );
        console.log( $results );
        $results.show();
        $search.hide();
        $this.hide();
        $results.html( 'loading...' );
        $results.load( url , options , function( response , status , jqXHR ){
            console.log( response );
            console.log( status );
            $reset_btn.show();
        });
    })

    $(".btn-search-modal-reset").click(function(){
        var $this = $(this),
            $results = $this.parents( '.modal-content' ).find('.modal-body-results'),
            $search = $this.parents( '.modal-content' ).find('.modal-body-search'),
            $search_btn = $this.parents( '.modal-content').find('.btn-search-modal-search');
        $results.hide();
        $search.show();
        $search_btn.show();
        $(this).hide();
    })
})

function modal_select_this_one_btn( one ){
    var $one = $(one),
        $modal = $one.parents('.modal'),
        $target = $one.parents('.form-group').find('.picked-item-span'),
        url = $one.attr('data-attr-url'),
        options = {
            'field' :$one.parents('.modal').attr('data-attr-field') ,
            'id'  : $one.attr('data-attr-id'),
            '_token' : $one.attr('data-csrf-token')
        }
    console.log( options );
    $modal.modal( 'hide' );
    $target.load( url , options );
}
