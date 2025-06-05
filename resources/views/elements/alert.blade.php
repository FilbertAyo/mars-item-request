@if (session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $.notify({
                icon: 'bi-check-circle-fill',
                title: 'Done!',
                message: '{{ session('success') }}',
            }, {
                type: 'success',
                placement: {
                    from: "top",
                    align: "right"
                },
                time: 1000,
            });
        });
    </script>
@elseif (session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $.notify({
                icon: 'bi-exclamation-circle-fill',
                title: 'Error!',
                message: "{{ session('error') }}",
            }, {
                type: 'danger',
                placement: {
                    from: "top",
                    align: "right"
                },
                time: 2000,
            });
        });
    </script>
@endif

@if (isset($errors) && $errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach ($errors->all() as $error)
                $.notify({
                    icon: 'bi-exclamation-circle-fill',
                    title: 'Validation Error',
                    message: "{{ $error }}",
                }, {
                    type: 'danger',
                    placement: {
                        from: "top",
                        align: "right"
                    },
                    time: 2000,
                });
            @endforeach
        });
    </script>
@endif


<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.body.addEventListener("click", function(event) {
            if (event.target.matches(".permission-alert")) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "You don't have permission to perform this action!",
                });
            }
        });
    });
</script>



</script>
