/**
 * Created by kevin on 14-8-6.
 */
$(function(){
    $("select[form-role='multi-select']").multiselect({
        selectedList : 6
    }).multiselectfilter();
    $("select[form-role='single-select']").multiselect({
        multiple : false ,
        selectedList : 1
    }).multiselectfilter();
});