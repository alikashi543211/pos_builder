<form class="form" action="" method="POST" id="teamform">
    @csrf
    <input type="hidden" name="act" id="act" value="add">
    <input type="hidden" name="eid" id="eid" value="">
    <div class="card-body" id="kt_drawer_chat_messenger_body">
        <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true"
            data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
            data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
          
            <div class="fv-row mb-8">
                <label class="required fs-6 fw-semibold mb-2">Choose Type</label>
                <select class="form-select form-select-solid" data-control="select2" data-hide-search="false"
                    data-placeholder="Choose Type" name="team_type" id="team_type">
                    <option value="">-- Choose Responsible --</option>
                    <option value="new">New</option>
                    <option value="existing">Existing Team</option>
                </select>
            </div>
            <div class="fv-row mb-8" id="team_name_div">
                <label class="required fs-6 fw-semibold mb-2">Team Name</label>
                <input type="text" class="form-control form-control-solid" placeholder="Enter Team Name"
                    name="team_name" />
            </div>
            <div class="fv-row mb-8" id="choose_team_div">
                <label class="required fs-6 fw-semibold mb-2">Choose Team</label>
                <select class="form-select form-select-solid" data-control="select2" data-hide-search="false"
                    data-placeholder="Choose Team" name="team">
                    <option value="">-- Choose Team --</option>
                    @if ($teams)
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}">
                                {{ $team->team_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
           
        </div>
    </div>
    <div class="card-footer drawer-footer">
        <div class="d-flex flex-stack float-end">
            <button class="btn btn-light me-5" id="kt_drawer_chat_close" type="button" data-kt-element="cancel">Close</button>
            <button class="btn btn-primary btn-save-team" type="submit">Save</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Initialize select2
        $('select[data-control="select2"]').select2();

        // Hide/show team name and choose team fields based on team type
        $('#team_name_div').hide();
        $('#choose_team_div').hide();

        $('#team_type').change(function() {
            var teamType = $(this).val();
            if (teamType === 'new') {
                $('#team_name_div').show();
                $('#choose_team_div').hide();
            } else if (teamType === 'existing') {
                $('#team_name_div').hide();
                $('#choose_team_div').show();
            } else {
                $('#team_name_div').hide();
                $('#choose_team_div').hide();
            }
        });
    });
</script>
