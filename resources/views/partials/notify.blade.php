<div id="notifier" class="position-fixed bottom-1 end-1 z-index-2">
    @include('partials.notification-card', ['type' => 'success', 'message' => session('success')])
    @include('partials.notification-card', ['type' => 'info', 'message' => session('info')])
    @include('partials.notification-card', ['type' => 'danger', 'message' => session('error')])
    @include('partials.notification-card', ['type' => 'warning', 'message' => session('warning')])
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @include('partials.notification-card', ['type' => 'danger', 'message' => $error])
        @endforeach
    @endif
</div>
