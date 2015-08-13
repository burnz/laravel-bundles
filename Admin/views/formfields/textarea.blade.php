
<div {{ $field->formgroup() }} >
    <label for="{{{ $field->name() }}}" class="control-label">{{ $field->label() }}</label>
    <textarea class="form-control" name="{{{ $field->name() }}}" placeholder="{{{ $field->placeholder() }}}" rows="{{{ $field->rows() }}}" {{ $field->isReadonly()?'readonly':''; }}>{{{ $field->value() }}}</textarea>
    <div class="text-danger">
    {{ $field->errors() }}
    </div>
</div>