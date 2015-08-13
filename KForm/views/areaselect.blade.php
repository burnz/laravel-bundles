@if( $field instanceof \Xjtuwangke\KForm\FormField\Types\AreaSelect )
<div <?=$field->formgroup()?>>
    <label for="<?=$field->getFieldName()?>" class="control-label"><?=$field->getLabel()?></label>
        <div class="area-selection">
            <select id="s_province" name="{{ $field->getFieldName() }}[]">
            </select>
            <select id="s_city" name="{{ $field->getFieldName() }}[]">
            </select>
            <select id="s_county" name="{{ $field->getFieldName() }}[]">
            </select>
        </div>
    @if( $field->hasError() )
        <div class="text-danger">
            @foreach( $field->getErrors() as $error )
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
</div>
<script>
    $(function(){
        area_selection.s=["s_province","s_city","s_county"];//三个select的name
        area_selection.opt0 = ["省","市","区/县"];//初始值
        for (i = 0; i < area_selection.s.length - 1; i++) {
            document.getElementById(area_selection.s[i]).onchange = new Function("area_selection.change(" + (i + 1) + ")");
        }
        area_selection.change(0);
        $("#s_province").children("option[value='<?=$field->getAreaProvince()?>']").attr('selected' , 'selected');
        area_selection.change(1);
        $("#s_city").children("option[value='<?=$field->getAreaCity()?>']").attr('selected' , 'selected');
        area_selection.change(2);
        $("#s_county").children("option[value='<?=$field->getAreaDistinct()?>']").attr('selected' , 'selected');
    })
</script>
@else
    <div>内部脚本错误</div>
@endif