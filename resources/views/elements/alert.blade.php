


@if (session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        });
    </script>
@elseif (session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 2000
            });
        });
    </script>
@endif




<script>
    // Show the spinner before the page is refreshed
    window.addEventListener('beforeunload', function() {
        // Display the spinner overlay
        document.getElementById('spinner-overlay').style.display = 'flex';
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.body.addEventListener("click", function (event) {
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

