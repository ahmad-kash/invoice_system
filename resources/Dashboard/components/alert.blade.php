@props(['type'])
@props(['type'])
@if (session()->has($type))
    @push('bodyScripts')
        <script>
            Swal.fire({
                toast: true,
                icon: "{{ $type }}" == 'successMessage' ? 'success' : 'error',
                animation: true,
                title: "{{ session($type) }}",
                position: 'top-right',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        </script>
    @endpush
@endif
