/**
 * Created by kevin on 14-8-21.
 */

$( function(){

    //$('textarea[form-data-role="wysiwyg"]').wysihtml5();
    $('textarea[form-data-role="wysiwyg"]').each( function( index , one ){
        var name = $(one).attr( 'name' );
        CKEDITOR.replace( name );
    });

    //editorInstance.composer.commands.exec("insertImage", { src: "http://url.com/foo.jpg", alt: "this is an image" });

    $('input[form-role="image-upload-richtext"]').each( function( index , one ){
        var $one = $(one),
            id   = $one.attr( 'id'),
            $target = $one.parents('div.form-group').find('.uploadify-result'),
            options = {
                'formData': {
                    '_token': $one.parents('form').find("input[name='_token']").val(),
                    'type': 'default'
                },
                'swf'      : $one.attr('uploadify-swf-path'),
                'uploader' : $one.attr('uploadify-upload-url'),
                'buttonImage' : 'http://labor.qiniudn.com/lib/uploadify/browse-btn.png',
                'cancleImage' : 'http://labor.qiniudn.com/lib/uploadify/uploadify-cancel.png',
                'buttonClass': 'uploadify',
                'onUploadSuccess': function( file , data , response ){
                    console.log('data is:');
                    console.log(data);
                    eval('data=' + data);
                    $target.val( data.file_url );
                }
            };
        $( '#' + id ).uploadify( options );
    });

});
