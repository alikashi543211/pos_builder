@if ($result)
    @foreach ($result as $row)
        <div class="col-md-3">
            <!--begin::Card-->
            <div class="card card-flush h-md-100">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2>{{ $row->category_name }}</h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-1">
                    <!--begin::Users-->
                    <div class="fw-bolder text-gray-600 mb-5">Total Modules in this Category:
                        {{ @$row->modules->count() }}</div>
                    <!--end::Users-->
                    <!--begin::Permissions-->
                    <div class="d-flex flex-column text-gray-600">

                        @if ($row->modules)
                            @foreach ($row->modules->take(4) as $modules)
                                <div class="d-flex align-items-center py-2">
                                    <span class="bullet bg-primary me-3"></span>{{ $modules->module_name }}
                                </div>
                            @endforeach
                        @endif

                    </div>
                    <!--end::Permissions-->
                </div>
                <!--end::Card body-->
                <!--begin::Card footer-->
                <div class="card-footer flex-wrap pt-0">
                    <a href="javascript:void(0)">
                        <button type="button" data-id="{{ $row->ID }}"
                            class="btn btn-light btn-active-light-primary my-1  btn-edit">Edit
                        </button>
                    </a>

                    <button type="button" class="btn btn-light btn-active-light-primary my-1 btn-del"
                        data-id="{{ $row->ID }}">Delete </button>

                </div>
                <!--end::Card footer-->
            </div>
            <!--end::Card-->
        </div>
    @endforeach
@endif
