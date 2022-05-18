@foreach(['success', 'info', 'warning', 'error'] as $alert)
@if (session()->has($alert))
<div class="alert alert-{{ $alert == 'error'? 'danger' : $alert }} alert-dismissible fade show" role="alert">
    {{ session()->get($alert) }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@endforeach