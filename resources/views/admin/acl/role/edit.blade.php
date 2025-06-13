<form name="fform" enctype="multipart/form-data" id="fform" method="post" action="">
    @csrf
    <input type="hidden" name="act" id="act" value="edit">
    <input type="hidden" name="eid" id="eid" value="{{ $row->ID }}">
    <div class="card-body " style="width: 100% !important" id="kt_activities_body">
        <!--begin::Content-->
        <div id="kt_activities_scroll" class="position-relative scroll-y me-n5 pe-5" data-kt-scroll="true"
            data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_activities_body"
            data-kt-scroll-dependencies="#kt_activities_header, #kt_activities_footer" data-kt-scroll-offset="0px">
            <!--begin::Timeline items-->
            <div class="timeline timeline-border-dashed">
                <div class="fv-row">
                    <label class="required fs-6 fw-semibold mb-2">Role Name</label>
                    <input type="text" name="role_name" placeholder="Role Name" id="role_name"
                        value="{{ $row->role_name }}"class="form-control" required />
                </div>

                <div class="fv-row mt-5">
                    <label class="fs-5 fw-bolder form-label mb-2">Role Permissions</label>
                    <div class="table-responsive">
                        <table class="table  table-row-dashed  gy-5">
                            <tbody class="text-gray-600 fw-bold">
                                @if ($catResult)
                                    @foreach ($catResult as $catRow)
                                        @php
                                            $result = App\Models\Acl\ModuleModel::where(
                                                'module_category_ID',
                                                $catRow->ID,
                                            )
                                                ->orderBy('display_order')
                                                ->get();
                                        @endphp
                                        @if (!$result->isEmpty())
                                            <tr>
                                                <td style="width: 30%" class="text-gray-800"> {{ $catRow->category_name }}</td>
                                                @foreach ($result as $rowModule)
                                                    @php $checked=''; @endphp
                                                    @if ($row->Permissions)
                                                        @foreach ($row->Permissions as $rowPermission)
                                                            @php
                                                                if ($rowPermission->module_ID == $rowModule->ID) {
                                                                    $checked = 'checked';
                                                                }
                                                            @endphp
                                                        @endforeach
                                                    @endif
                                                    <td>
                                                        <div class="d-flex">
                                                            <label
                                                                class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                <input class="form-check-input" type="checkbox"
                                                                    {{ $checked }} value=""
                                                                    name="access[{{ $rowModule->ID }}]">
                                                                <span
                                                                    class="form-check-label">{{ $rowModule->module_name }}</span>
                                                            </label>

                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif

                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Table wrapper-->
                </div>

            </div>
            <!--end::Timeline items-->
        </div>
        <!--end::Content-->
    </div>
    <div class="card-footer pt-4 drawer-footer" id="kt_drawer_chat_messenger_footer">
        <div class="d-flex flex-stack float-end">
            <button class="btn btn-light me-5" id="kt_activities_close" type="button"
                data-kt-element="cancel">Close</button>
            <button type="submit" class="btn btn-primary btn-save">Save</button>
        </div>
    </div>

</form>
