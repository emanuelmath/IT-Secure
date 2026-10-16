<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-4" style="z-index: 1056;" data-bs-theme="light">
    @foreach (['success', 'error', 'warning', 'info'] as $msgType)
        @if(session($msgType))
            @php
                $themeClass = '';
                $iconClass = '';
                $title = '';

                switch($msgType) {
                    case 'success':
                        $themeClass = 'dc-toast-success';
                        $iconClass = 'bi bi-check-circle-fill text-success';
                        $title = 'Éxito';
                        break;
                    case 'error':
                        $themeClass = 'dc-toast-error';
                        $iconClass = 'bi bi-x-circle-fill text-danger';
                        $title = 'Error';
                        break;
                    case 'warning':
                        $themeClass = 'dc-toast-warning';
                        $iconClass = 'bi bi-exclamation-triangle-fill text-warning';
                        $title = 'Advertencia';
                        break;
                    case 'info':
                        $themeClass = 'dc-toast-info';
                        $iconClass = 'bi bi-info-circle-fill text-info';
                        $title = 'Información';
                        break;
                }
            @endphp

            <div class="toast {{ $themeClass }} shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header border-bottom-0 pb-0">
                    <i class="{{ $iconClass }} fs-5 me-2"></i>
                    <strong class="me-auto text-light">{{ $title }}</strong>
                    <small class="text-muted">Justo ahora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Cerrar"></button>
                </div>
                <div class="toast-body pt-2 text-light">
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
                    <strong class="me-auto text-light">Error de validación</strong>
                    <small class="text-muted">Justo ahora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Cerrar"></button>
                </div>
                <div class="toast-body pt-2 text-light">
                    {{ $error }}
                </div>
            </div>
        @endforeach
    @endif
</div>
