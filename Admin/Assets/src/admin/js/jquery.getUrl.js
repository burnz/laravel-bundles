/**
 * Created by kevin on 14-8-16.
 */

;(function($)
{
    $.extend(
        {
            getUrl: function(){
                var aQuery = window.location.href.split("?"),
                    aGET = {},
                    result = {
                        url : '',
                        get : {}
                    };
                if(aQuery.length > 1){
                    var aBuf = aQuery[1].split("&"),
                        iLoop = aBuf.length;
                    for( var i=0 ; i<iLoop ; i++){
                        var aTmp = aBuf[i].split("=");//分离key与Value
                        if( ! aTmp[0] ){
                            continue;
                        }
                        aGET[aTmp[0]] = aTmp[1];
                    }
                    result.url = aQuery[0];
                    result.get = aGET;
                }
                else{
                    result.url = window.location.href;
                }
                return result;
            } ,
            createUrl: function( options ){
                var result = options.url,
                    first = true;
                $.each( options.get , function( i , j ){
                    if( first ){
                        first = false;
                        result = result + "?" + i + "=" + j;
                    }
                    else{
                        result = result + "&" + i + "=" + j;
                    }

                });
                return result;
            }
        });
})(jQuery);
