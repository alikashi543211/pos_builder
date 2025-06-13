$(document).ready(function () {

    var table = $('#product-listing-table').DataTable({
        info: false,
        order: [],
        pageLength: 50,
        lengthChange: false,
        columnDefs: [{
            orderable: false
        }, {
            orderable: false
        }]
    });

    document.querySelector('[data-product-listing-table-filter="search"]').addEventListener("keyup", function (t) {
        table.search(t.target.value).draw();
    });

});

//Product Status Change
$(document).on("click", ".btn-supplier-product-status-change", function (e) {
    console.log("e:", e);
    $(".modal-body").html("");
    $(".drawer-title").html("Change Status");
    var data_url = $(this).attr("data-url");
    jQuery.getJSON(admin_url + data_url, function (response) {
        if (response.responseCode == 1) {
            $(".drawer-body").html(response.html);
            const drawerElement = document.querySelector("#kt_drawer_chat");
            if (drawerElement) {
                KTDrawer.getInstance(drawerElement).show();
            }
        }else if (response.responseCode == 0) {
            Swal.fire("Error", response.msg, "error");
        }
    });
});

// Change status form handler
$(document).on("submit", "#changeStatusForm", function (e) {
    e.preventDefault();
    submitUrl = $(this).attr('action');
    $("#msg_box").html("");
    $(".change_status_btn").attr("disabled", "disabled");

    var url = submitUrl;

    //loadSpinner();
    $.ajax({
        url: url,
        type: "POST",
        data: new FormData(this),

        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function(){
            $(".change_status_btn").html("Please wait...");
        },
        success: function (data) {
            try {
                $(".change_status_btn").removeAttr("disabled");
                $(".change_status_btn").html("Save");
                var obj = data;
                var obj = JSON.parse(data);
                if (obj.responseCode == 1) {
                    console.log('obj:', obj)
                    $(".modal-body").html("");
                    setTimeout(function () {
                        closeAllDrawers();
                        Swal.fire("Success", obj.msg, "success").then(() => {
                            location.reload();
                        });
                    }, 200);
                    return null;
                } else {
                    var errMsg = "";
                    if (typeof obj.msg === "object") {
                        $.each(obj.msg, function (index, value) {
                            errMsg = errMsg + value + "\r\n";
                        });
                    } else {
                        errMsg = obj.msg;
                    }
                    Swal.fire("Error", errMsg, "error");
                    return;
                }
            } catch (err) {
                hideSpinner();
                Swal.fire(
                    "Error",
                    "Oops! Something went wrong. Please refresh the page and try again. Or contact IT department." +
                        err,
                    "error"
                );
            }
        },
    });
    return;
});

// Close The Drawer After Operation Performed
function closeAllDrawers()
{
    $("#kt_drawer_chat_close").click();
    $("#kt_activities_close").click();
}
// Product Detail Popup Handling
$(document).on('click', '.product-detail-tab', function(){
    $(".product-detail-tab").removeClass('active');
    $(this).addClass('active');
    // Content hide and display detail
    $(".product-detail-content").hide();
    $(".product-detail-content-please-wait").removeClass('d-none');

    var url = $(this).attr('data-url');
    jQuery.getJSON(url, function (response) {

        if (response.responseCode == 1) {
            $(".product-detail-content").html(response.html).fadeIn('slow')
            $(".product-detail-content-please-wait").addClass('d-none');
        }
    });
});

$(document).on('submit', '.product_filter_form', function(e){
    e.preventDefault();
    var data = $(this).serialize();
    $('.datatable-listing').DataTable().destroy();
    renderProductListing(data);
});

$(document).on('click', '.product_reset_button', function(e){
    e.preventDefault();
    $('.product_filter_form').find('select').val('0').change()
    $('.product_filter_form').find('input').val('')
    $('input[name="search"]').val('')
    $('.datatable-listing').DataTable().destroy();
    renderProductListing('');
});



$(document).on('keyup', 'input[name="search"]', function(){
    $(".product_filter_form").find('input[name="keyword"]').val($(this).val())
    $(".product_filter_form").submit();
});

//Detail Of Product
$(document).on("click", ".btn-product-detail", function (e) {
    console.log("e:", e);
    $(".modal-body").html("");
    $(".drawer-title").html("Product Detail");
    var data_url = $(this).attr("data-url");
    jQuery.getJSON(admin_url + data_url, function (response) {
        if (response.responseCode == 1) {
            $(".drawer-body").html(response.html);
            const drawerElement = document.querySelector("#kt_activities");
            if (drawerElement) {
                KTDrawer.getInstance(drawerElement).show();
                $(".product_basics_tab").click();
            }
        }
    });
});
