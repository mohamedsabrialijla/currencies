@if(isset($label))
<label for="{{ $name }}">{{ $label }}</label>
@endif
<label class="checkbox">
    <input type="checkbox" name="{{ $name }}" value="{{ $value ?? 1 }}" @if(($value ?? 1) == ($checked ?? null)) checked @endif {{ $attributes }}>
    <span></span>
    {{ $label ?? '' }}
</label>