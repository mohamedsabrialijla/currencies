@if(isset($label))
<label for="{{ $name }}">{{ $label }}</label>
@endif
<select class="form-control {{ $class ?? '' }} @error($name) is-invalid @enderror" id="{{ $name }}" name="{{ $name }}">
    @if($empty ?? false)
    <option value="">{{ __('Select') }}</option>
    @endif
    @foreach($options ?? [] as $value => $text)
    <option value="{{ $value }}" @if($value == $selected) selected @endif>{{ $text }}</option>
    @endforeach
</select>
@error($name)
<div class="invalid-feedback">
    {{ $message }}
</div>
@enderror