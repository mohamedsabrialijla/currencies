@if(isset($label))
<label for="{{ $name }}">{{ $label }}</label>
@endif
<div class="form-check">
    @foreach($options as $value => $label)
    <input type="checkbox" class="form-check-input" name="{{ $name }}[]" value="{{ $value }}" @if(in_array($value, old($name, $checked ?? []))) checked @endif>
    <label class="form-check-label">{{ $label }}</label>
    @endforeach
</div>