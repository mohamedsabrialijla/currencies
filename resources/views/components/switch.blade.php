<div class="form-check form-switch">
    <input type="hidden" name="{{ $name }}" value="0">
  <input class="form-check-input" type="checkbox" name="{{ $name }}" value="1" @if(1 == $checked) checked @endif>
  <label class="form-check-label" for="flexSwitchCheckDefault">{{ $label ?? '' }}</label>
</div>