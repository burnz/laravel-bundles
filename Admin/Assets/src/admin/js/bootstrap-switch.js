/**
 * Created by kevin on 14-8-16.
 */

$(function(){
    $('input[type="checkbox"].gofarms-admin-switch').each( function( i , one ){
        var $one = $(one) ,
            checked = $one.checked;
        $one.bootstrapSwitch({
            state : checked
        });
        $one.on('switchChange.bootstrapSwitch', function(event, state) {
            console.log(this); // DOM element
            console.log(event); // jQuery event
            console.log(state); // true | false
            var actionON = $(this).attr('data-action-on'),
                actionOFF = $(this).attr('data-action-off'),
                id = $(this).attr('data-item-id'),
                token = $(this).attr('data-token');
            if( state ){ //启用
                console.log({
                    url : actionON ,
                    type : 'post',
                    data : {
                        _token : token ,
                        'id[]'   : id
                    }
                });
                $.ajax({
                    url : actionON ,
                    type : 'post',
                    data : {
                        _token : token ,
                        'id[]'   : id
                    }
                });
            }
            else{
                console.log({
                    url : actionOFF ,
                    type : 'post',
                    data : {
                        _token : token ,
                        'id[]'   : id
                    }
                });
                $.ajax({
                    url : actionOFF ,
                    type : 'post',
                    data : {
                        _token : token ,
                        'id[]'   : id
                    }
                });
            }
        });
    });
})
