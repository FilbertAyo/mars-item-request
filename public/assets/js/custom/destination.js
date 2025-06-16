$(document).ready(function () {
    $("#destination-input").on("input", function () {
        let query = $(this).val();
        if (query.length < 1) {
            $("#destination-suggestions").hide().empty();
            return;
        }

        $.ajax({
            url: autocompleteRoute, // use the global variable
            data: { term: query },
            success: function (data) {
                let dropdown = $("#destination-suggestions");
                dropdown.empty();

                if (data.length === 0) {
                    dropdown.hide();
                    return;
                }

                data.forEach((item) => {
                    dropdown.append(
                        `<button type="button" class="dropdown-item">${item}</button>`
                    );
                });

                dropdown.show();
            },
        });
    });
    // Suggestion click for static input
    $(document).on(
        "click",
        "#destination-suggestions .dropdown-item",
        function () {
            $("#destination-input").val($(this).text());
            $("#destination-suggestions").hide();
        }
    );

    $(document).on("input", ".destination-input", function () {
        const $input = $(this);
        const query = $input.val();
        const inputId = $input.attr("id");
        const $dropdown = $(`.suggestion-box[data-for="${inputId}"]`);

        if (query.length < 1) {
            $dropdown.hide().empty();
            return;
        }

        $.ajax({
            url: autocompleteRoute, // ✅ use the same valid variable
            data: {
                term: query,
            },
            success: function (data) {
                $dropdown.empty();

                if (data.length === 0) {
                    $dropdown.hide();
                    return;
                }

                data.forEach((item) => {
                    $dropdown.append(
                        `<button type="button" class="dropdown-item">${item}</button>`
                    );
                });

                $dropdown.show();
            },
        });
    });

    // Suggestion click for dynamic input
    $(document).on("click", ".suggestion-box .dropdown-item", function () {
        const selectedText = $(this).text();
        const $dropdown = $(this).closest(".suggestion-box");
        const inputId = $dropdown.data("for");
        $(`#${inputId}`).val(selectedText);
        $dropdown.hide();
    });

    // Hide all dropdowns when clicking outside
    $(document).click(function (e) {
        if (!$(e.target).closest(".form-group, .destination-group").length) {
            $("#destination-suggestions").hide(); // for static
            $(".suggestion-box").hide(); // for dynamic
        }
    });
});

// Add destination (for Route section)
document
    .getElementById("add_destination")
    .addEventListener("click", function () {
        const div = document.createElement("div");
        div.className = "form-group destination-group mt-2 position-relative";

        const uniqueId = "destination-" + Date.now(); // unique ID for suggestion container
        const isTransportOrSalesSelected =
            requestForSelect.value === "Transport" ||
            requestForSelect.value === "Sales Delivery";

        div.innerHTML = `
    <label>To where:</label>
    <div class="input-group">
        <input type="text" name="destinations[]" class="form-control destination-input" id="${uniqueId}" placeholder="Enter destination" ${
            isTransportOrSalesSelected ? "required" : ""
        } autocomplete="off">
        <button type="button" class="btn btn-danger btn-remove-destination">
            <i class="bi bi-trash"></i>
        </button>
    </div>
    <div class="dropdown-menu w-100 suggestion-box" data-for="${uniqueId}" style="display: none;"></div>
`;

        document.getElementById("destination_fields").appendChild(div);
    });

document
    .querySelector("#destination_fields")
    .addEventListener("click", function (e) {
        if (e.target.closest(".btn-remove-destination")) {
            e.target.closest(".destination-group").remove();
        }
    });
