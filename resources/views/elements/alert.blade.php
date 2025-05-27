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
                    from: "bottom",
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
                    from: "bottom",
                    align: "right"
                },
                time: 2000,
            });
        });
    </script>
@endif

@if ($errors->any())
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
                        from: "bottom",
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


{{-- delete --}}
<script>
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success ml-2", // Added 'ml-2' for spacing
            cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
    });

    function showSweetAlert(event, itemId) {
        event.preventDefault(); // Prevent the form from submitting immediately

        swalWithBootstrapButtons.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form programmatically
                document.getElementById(`deleteForm-${itemId}`).submit();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: "Cancelled",
                    text: "Your application is safe :)",
                    icon: "error"
                });
            }
        });

        return false; // Prevent default form submission behavior
    }
</script>
