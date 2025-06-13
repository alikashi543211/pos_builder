"use strict";


//edit drawer
$(document).on("click", ".btn-edit", function (e) {
   $(".drawer-body").html("");
   $(".drawer-title").html("Edit Group");
   var id = $(this).data("id");
   console.log('id:', id)
   jQuery.getJSON(
       admin_url + "/edit/users/team/" + id,
       function (response) {
           if (response.responseCode == 1) {
            $(".drawer-body").html(response.html);
               const drawerElement = document.querySelector("#kt_drawer_chat");
               if (drawerElement) {
                   KTDrawer.getInstance(drawerElement).show();
               }
           }
       }
   );
});

 // Add New contact group
 $(document).on("click", ".btn-add", function (e) {
   
   $(".drawer-body").html("");
   $(".drawer-title").html("Create New Team");
   jQuery.getJSON(admin_url + "/add/users/team", function (response) {
       if (response.responseCode == 1) {
      
           $(".drawer-body").html(response.html);
           const drawerElement = document.querySelector("#kt_drawer_chat");
           if (drawerElement) {
               KTDrawer.getInstance(drawerElement).show();
           }
       }
   });
});


var KTUsersList = function() {
   var e, t, n, r, o = document.getElementById("kt_table_users"),
      c = () => {
         o.querySelectorAll('[data-kt-users-table-filter="delete_row"]').forEach((t => {
            t.addEventListener("click", (function(t) {
               t.preventDefault();
               const n = t.target.closest("tr"),
                 
                  r = n.querySelectorAll("td")[0].querySelector("a").innerText;

                  const idElement = t.target.closest('[data-kt-users-table-filter="delete_row"]');
            const id = idElement.getAttribute("data-id");

            
              console.log('Data ID:', id);
  

               Swal.fire({
                  text: "Are you sure you want to delete " + r + "?",
                  icon: "warning",
                  showCancelButton: !0,
                  buttonsStyling: !1,
                  confirmButtonText: "Yes, delete!",
                  cancelButtonText: "No, cancel",
                  customClass: {
                     confirmButton: "btn fw-bold btn-danger",
                     cancelButton: "btn fw-bold btn-active-light-primary"
                  }
               }).then((function(t) {
                if (t.value) {
                    $.ajax({
                        url: '/contact/group/delete/' + id,
                        type: 'GET',
                        success: function(result) {
                            Swal.fire({
                                text: "You have deleted " + r + "!",
                                icon: "success",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary"
                                }
                            }).then((function() {
                                e.row($(n)).remove().draw()
                            })).then((function() {
                                a()
                            }));
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                text: "Failed to delete " + r + ". Please try again.",
                                icon: "error",
                                buttonsStyling: !1,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary"
                                }
                            });
                        }
                    });
                } 
            }))
            }))
         }))
      },
      l = () => {
         const c = o.querySelectorAll('[type="checkbox"]');
         t = document.querySelector('[data-kt-user-table-toolbar="base"]'), n = document.querySelector('[data-kt-user-table-toolbar="selected"]'), r = document.querySelector('[data-kt-user-table-select="selected_count"]');
         const s = document.querySelector('[data-kt-user-table-select="delete_selected"]');
         c.forEach((e => {
            e.addEventListener("click", (function() {
               setTimeout((function() {
                  a()
               }), 50)
            }))
         })), s.addEventListener("click", (function() {
            Swal.fire({
               text: "Are you sure you want to delete selected customers?",
               icon: "warning",
               showCancelButton: !0,
               buttonsStyling: !1,
               confirmButtonText: "Yes, delete!",
               cancelButtonText: "No, cancel",
               customClass: {
                  confirmButton: "btn fw-bold btn-danger",
                  cancelButton: "btn fw-bold btn-active-light-primary"
               }
            }).then((function(t) {
               t.value ? Swal.fire({
                  text: "You have deleted all selected customers!.",
                  icon: "success",
                  buttonsStyling: !1,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                     confirmButton: "btn fw-bold btn-primary"
                  }
               }).then((function() {
                  c.forEach((t => {
                     t.checked && e.row($(t.closest("tbody tr"))).remove().draw()
                  }));
                  o.querySelectorAll('[type="checkbox"]')[0].checked = !1
               })).then((function() {
                  a(), l()
               })) : "cancel" === t.dismiss && Swal.fire({
                  text: "Selected customers was not deleted.",
                  icon: "error",
                  buttonsStyling: !1,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                     confirmButton: "btn fw-bold btn-primary"
                  }
               })
            }))
         }))
      };
   const a = () => {
      const e = o.querySelectorAll('tbody [type="checkbox"]');
      let c = !1,
         l = 0;
      e.forEach((e => {
         e.checked && (c = !0, l++)
      })), c ? (r.innerHTML = l, t.classList.add("d-none"), n.classList.remove("d-none")) : (t.classList.remove("d-none"), n.classList.add("d-none"))
   };
   return {
      init: function() {
         o && (o.querySelectorAll("tbody tr").forEach((e => {
            
         })), (e = $(o).DataTable({
            info: !1,
            order: [],
            pageLength: 10,
            lengthChange: !1,
            columnDefs: [{
               orderable: false,
               targets: 0
            }, {
               orderable: false,
               targets: 2
            }]
         })).on("draw", (function() {
            l(), c(), a()
         })), l(), document.querySelector('[data-kt-user-table-filter="search"]').addEventListener("keyup", (function(t) {
            e.search(t.target.value).draw()
         })),  c(), (() => {
            
         })())
      }
   }
}();
KTUtil.onDOMContentLoaded((function() {
   KTUsersList.init()
}));


 