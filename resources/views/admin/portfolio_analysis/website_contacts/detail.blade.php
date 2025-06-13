<div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>Name</th>
                <td>{{ $row->contact_name ?? '-----' }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $row->websiteContactEmail->contact_email ?? '-----' }}</td>
            </tr>
            <tr>
                <th>Subject</th>
                <td>{{ $row->contact_subject ?? '-----' }}</td>
            </tr>
            <tr>
                <th>Service</th>
                <td>{{ $row->contactService->service_name ?? '-----' }}</td>
            </tr>
            <tr>
                <th>Message</th>
                <td style="white-space: pre-wrap;">{!! $row->contact_body ?? '-----' !!}</td>
            </tr>
        </tbody>
    </table>
</div>
