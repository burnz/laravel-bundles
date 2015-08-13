
<div {{ $field->formgroup() }} >
    <label for="{{{ $field->name() }}}" class="control-label">{{ $field->label() }}</label>
    <input class="form-control" name="{{{ $field->name() }}}" placeholder="{{{ $field->placeholder() }}}" type="text" value="{{{ $field->value() }}}" {{ $field->isReadonly()?'readonly':''; }}>
    <div class="text-danger">
    {{ $field->errors() }}
    </div>
</div>