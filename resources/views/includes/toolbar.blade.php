<!--begin::Toolbar-->
<div class="toolbar py-3 py-lg-6 " id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack flex-wrap gap-2  container-fluid">

        <!--begin::Page title-->
        <div class="page-title d-flex flex-column align-items-start me-3 py-2 py-lg-0 gap-2">
            <!--begin::Title-->
            <h1 class="d-flex text-gray-900 fw-bold m-0 fs-3">{{$pageTitle}}
            </h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7">
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-600">
                    <a href="{{'/'}}" class="text-gray-600 text-hover-primary">Dashboard</a>
                </li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-600">{{$pageTitle}}</li>
                <!--end::Item-->
                <!--begin::Item-->
                <li class="breadcrumb-item text-gray-500">{{@$subTitle}}</li>
                <!--end::Item-->
            </ul>
         
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title-->
       
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar-->