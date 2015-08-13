/**
 * Created by kevin on 14-8-15.
 */

var artDialog = {};
artDialog.confirm = function( options  ){
    var defaultOptions = {
            title : '请确认' ,
            content : '确认要执行此操作吗',
            yes : '确定',
            no  : '取消',
            confirmed  : function(){} ,
            canceled : function(){}
        },
        options = $.extend( defaultOptions , options||{}),
        d = dialog({
            title: options.title,
            content: options.content,
            okValue: options.yes,
            ok: options.confirmed ,
            cancelValue: options.no,
            cancel: options.canceled
        });
    d.show();
}
