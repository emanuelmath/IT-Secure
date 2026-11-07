<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-4 dc-toast-wrapper" data-bs-theme="dark">
    @foreach (['success', 'error', 'warning', 'info'] as $msgType)
        @if(session($msgType))
            @php
                $themeClass = match($msgType) {
                    'success' => 'dc-toast-success',
                    'error' => 'dc-toast-error',
                    'warning' => 'dc-toast-warning',
                    'info' => 'dc-toast-info',
                    default => ''
                };

                $iconClass = match($msgType) {
                    'success' => 'bi bi-check-circle-fill text-success',
                    'error' => 'bi bi-x-circle-fill text-danger',
                    'warning' => 'bi bi-exclamation-triangle-fill text-warning',
                    'info' => 'bi bi-info-circle-fill text-info',
                    default => ''
                };

                $title = match($msgType) {
                    'success' => 'Éxito',
                    'error' => 'Error',
                    'warning' => 'Advertencia',
                    'info' => 'Información',
                    default => ''
                };
            @endphp

            <div class="toast {{ $themeClass }} shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header border-bottom-0 pb-0">
                    <i class="{{ $iconClass }} fs-5 me-2"></i>
                    <strong class="me-auto text-light outfit-bold">{{ $title }}</strong>
                    <small class="text-muted outfit-light">Justo ahora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Cerrar"></button>
                </div>
                <div class="toast-body pt-2 text-light outfit-regular">
                    {{ session($msgType) }}
                </div>
            </div>
        @endif
    @endforeach

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="toast dc-toast-error shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header border-bottom-0 pb-0">
                    <i class="bi bi-x-circle-fill text-danger fs-5 me-2"></i>
                    <strong class="me-auto text-light outfit-bold">Error de validación</strong>
                    <small class="text-muted outfit-light">Justo ahora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Cerrar"></button>
                </div>
                <div class="toast-body pt-2 text-light outfit-regular">
                    {{ $error }}
                </div>
            </div>
        @endforeach
    @endif
</div>
