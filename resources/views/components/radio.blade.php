@if(isset($label))
<label for="{{ $name }}">{{ $label }}</label>
@endif
<div class="form-check form-check-inline">
    @foreach($options as $value => $label)
    <input type="radio" class="form-check-input" name="{{ $name }}" value="{{ $value }}" @if($value == ($checked ?? null)) checked @endif>
    <label class="form-check-label">{{ $label }}</label>
    @endforeach
</div>