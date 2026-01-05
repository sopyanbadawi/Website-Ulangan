@if (session('success') || session('error') || session('info') || session('warning'))
<script>
    Swal.fire({
        icon: '{{ session('success') ? 'success' : (session('error') ? 'error' : (session('info') ? 'info' : 'warning')) }}',
        title: '{{ session('success') ? 'Berhasil!' : (session('error') ? 'Gagal!' : (session('info') ? 'Info' : 'Peringatan')) }}',
        text: '{{ session('success') ?? session('error') ?? session('info') ?? session('warning') }}',
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        },
        timer: '{{ session('success') ? 2000 : 4000 }}',  // Auto close in 2 seconds for success, 4 seconds for others
        timerProgressBar: true,  // Optional: Shows a progress bar while the alert is visible
        showConfirmButton: false  // Disable the "OK" button
    });
</script>
@endif
