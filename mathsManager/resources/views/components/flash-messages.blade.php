{{-- Composant global pour l'affichage des messages flash --}}
{{-- Usage: <x-flash-messages /> --}}

@if(session('success') || session('error') || session('warning') || session('info'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-blue-700">{{ session('info') }}</p>
            </div>
        @endif
    </div>
@endif

{{-- Warnings via SweetAlert2 pour plus de visibilité --}}
@if(session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                html: {!! json_encode(session('warning')) !!},
                confirmButtonText: 'OK',
                confirmButtonColor: '#f59e0b',
                customClass: {
                    popup: 'swal-warning-custom'
                }
            });
        });
    </script>
@endif
