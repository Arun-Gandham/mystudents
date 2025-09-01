<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    @if(session('success'))
        <x-toast type="success" title="Success" :message="session('success')" />
    @endif

    @if(session('error'))
        <x-toast type="error" title="Error" :message="session('error')" />
    @endif

    @if(session('info'))
        <x-toast type="info" title="Info" :message="session('info')" />
    @endif
</div>
