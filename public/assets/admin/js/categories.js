"use strict";


//edit drawer
$(document).on("click", ".btn-contact-edit", function (e) {
    $(".card-contact-body").html("");
    
    var id = $(this).data("id");
    console.log('id:', id)
    jQuery.getJSON(
        admin_url + "/workflow/category/edit/" + id,
        function (response) {
            if (response.responseCode == 1) {
                $(".card-contact-body").html(response.html);
                const drawerElement = document.querySelector("#kt_edit_contact_drawer");
                if (drawerElement) {
                    KTDrawer.getInstance(drawerElement).show();
                }
            }
        }
    );
});

$(document).ready(function() {
    $('#add_contact_form').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: admin_url + "/add/workflow/category",
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    text: response.success,
                    icon: 'success',
                    buttonsStyling: false,
                    confirmButtonText: 'Ok',
                    customClass: {
                        confirmButton: 'btn fw-bold btn-primary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
        
                $('#kt_drawer_chat_close').click();
            },
            error: function(response) {
                var errorMessage = 'There was an error adding the contact. Please try again.';
                
                if(response.responseJSON && response.responseJSON.errors && response.responseJSON.errors.status) {
                    errorMessage = response.responseJSON.errors.status[0];
                }
        
                Swal.fire({
                    text: errorMessage,
                    icon: 'warning',
                    buttonsStyling: false,
                    confirmButtonText: 'Ok, got it!',
                    customClass: {
                        confirmButton: 'btn fw-bold btn-primary'
                    }
                });
            }
        });
        
    });

   
});

var KTUsersList = function() {
    var e, o = document.getElementById("kt_table_users");

    // Delete row functionality
    var c = () => {
        o.querySelectorAll('[data-kt-users-table-filter="delete_row"]').forEach((t => {
            t.addEventListener("click", (function(t) {
                t.preventDefault();
                const n = t.target.closest("tr"),
                    r = n.querySelectorAll("td")[0].querySelector("a").innerText,
                    idElement = t.target.closest('[data-kt-users-table-filter="delete_row"]'),
                    id = idElement.getAttribute("data-id");

                console.log('Clicked element:', t.target);
                console.log('Closest delete button element:', idElement);
                console.log('Data ID:', id);

                Swal.fire({
                    text: "Are you sure you want to delete " + r + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/template/category/delete/' + id,
                            type: 'GET',
                            success: function(result) {
                                Swal.fire({
                                    text: "You have deleted " + r + "!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary"
                                    }
                                }).then(() => {
                                    e.row($(n)).remove().draw();
                                });
                            },
                            error: function(xhr, status, error) {
                                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : "Failed to delete " + r + ". Please try again.";
                               
                                Swal.fire({
                                    text: errorMessage,
                                    icon: "warning",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary"
                                    }
                                });
                            }
                        });
                    }
                });
            }));
        }));
    };

    return {
        init: function() {
            if (o) {
                // Initialize DataTable
                e = $(o).DataTable({
                    info: false,
                    order: [],
                    pageLength: 10,
                    lengthChange: false,
                    columnDefs: [{
                        orderable: false,
                        targets: 0
                    }, {
                        orderable: false,
                        targets: 2
                    }]
                }).on("draw", () => {
                    c(); // Reinitialize delete functionality on table draw
                });

                // Initialize delete functionality
                c();
                document.querySelector('[data-kt-user-table-filter="search"]').addEventListener("keyup", (function(t) {
                    e.search(t.target.value).draw();
                }));
            }
        }
    };
}();

// Initialize KTUsersList on DOMContentLoaded
KTUtil.onDOMContentLoaded(function() {
    KTUsersList.init();
});
