@if(isset($label))
<label for="{{ $name }}">{{ $label }}</label>
@endif
<textarea id="{{ $name }}" rows="{{ $rows ?? '3' }}" name="{{ $name }}" class="form-control {{ $class ?? '' }} @error($name) is-invalid @enderror" {{ $attributes }}>{{ old($name, $value ?? '') }}</textarea>
@error($name)
<div class="invalid-feedback">
    {{ $message }}
</div>
@enderror