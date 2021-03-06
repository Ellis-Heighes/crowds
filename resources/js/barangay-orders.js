import Swal from "sweetalert2";

const confirmAlert = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-sm ml-2 btn-primary",
        cancelButton: "btn btn-sm ml-2 btn-secondary",
    },
    buttonsStyling: false,
    showClass: {
        popup: "swal2-noanimation",
        backdrop: "swal2-noanimation",
    },
    title: ``,
    icon: "warning",
    showCancelButton: true,
    reverseButtons: true,
    confirmButtonText: "Yes",
});

$(".fulfill-order").click(function() {
    const self = $(this);
    const user = self.data("user");

    confirmAlert
        .fire({
            text: `Are you sure you want to fulfill order by ${user}?`,
        })
        .then((result) => {
            if (result.value) {
                self.parent("form").submit();
            }
        });
});

$("#order_status").change(function() {
    const order_status = $(this).val();
    $("#filter-order").prop("href", `?status=${order_status}`);
});

$(".cancel-order").click(function() {
    const self = $(this);
    confirmAlert
        .fire({
            text: "Are you sure you want to cancel your request?",
        })
        .then((result) => {
            if (result.value) {
                self.parent("form").submit();
            }
        });
});
