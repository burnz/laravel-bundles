/**
 * Created by kevin on 14-8-6.
 */
$(function(){

    /**
     * 单张图片uploadify上传
     */
    $("div[form-role='image-upload']").each( function( index , div ){
        var $div  = $(div);
        var options = {
            'formData'     : {
                '_token' : $div.parents('form').find("input[name='_token']").val(),
                'type'   : $div.attr('uploadify-image-type')
            },
            'swf'      : $div.attr('uploadify-swf-path'),
            'uploader' : $div.attr('uploadify-upload-url'),
            'buttonImage' : 'http://labor.qiniudn.com/lib/uploadify/browse-btn.png',
            'cancleImage' : 'http://labor.qiniudn.com/lib/uploadify/uploadify-cancel.png',
            'buttonClass': 'uploadify',
            'onUploadSuccess': function( file , data , response ){
                console.log('data is:');
                console.log(data);
                eval('data=' + data);
                var $img = $div.find('img.uploadify-image');
                var $input = $div.find('input.uploadify-input');
                if(data.file_url !== undefined){
                    $img.attr( 'src' , data.file_url );
                    $input.attr( 'value' , data.file_url);
                }
            }
        };
        var id = $div.find("input[type='file']").attr('id'); //必须用id初始化uploadify
        $( '#' + id ).uploadify( options );
    });

    /**
     * 多张图片uploadify上传
     */
    $("div[form-role='multi-image-upload']").each( function( index , div ) {
        var $div = $(div);
        var $container = $div.find( 'div.uploadify-multi-image-container' );
        var name = $div.attr('uploadify-field-name');
        var options = {
            'formData'     : {
                '_token' : $div.parents('form').find("input[name='_token']").val(),
                'type'   : $div.attr('uploadify-image-type')
            },
            'swf'      : $div.attr('uploadify-swf-path'),
            'uploader' : $div.attr('uploadify-upload-url'),
            'buttonImage' : 'http://labor.qiniudn.com/lib/uploadify/browse-btn.png',
            'cancleImage' : 'http://labor.qiniudn.com/lib/uploadify/uploadify-cancel.png',
            'buttonClass': 'uploadify',
            'onUploadSuccess': function( file , data , response ){
                console.log('data is:');
                console.log(data);
                eval('data=' + data);
                if(data.file_url !== undefined){
                    var html = '<div class="uploadify-image">\
<img src="{$image}" class="img-responsive uploadify-image">\
<input value="{$image}" name="{$name}" style="display:none">\
<a href="javascript:;" class="uploadify-remove-image" onclick="$(this).parents(\'div.uploadify-image\').remove();">x</a>\
</div>';
                    html = html.replace( /\{\$image\}/g , data.file_url );
                    html = html.replace( /\{\$name\}/g , name );
                    console.log( html );
                    $container.append( html );
                }
            }
        };
        var id = $div.find("input[type='file']").attr('id'); //必须用id初始化uploadify
        $( '#' + id ).uploadify( options );
    });

});
