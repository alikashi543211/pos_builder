<table class="table table-bordered">
    <tbody>
      <tr>
        <th scope="row">Created Date:</th>
        <td>{{dateFormat($row->created_at,'d-M-Y h:i A')}}</td>
      </tr>
      <tr>
        <th scope="row">Role Name:</th>
        <td>{{$row->role_name}}</td>
      </tr>
    </tbody>
  </table>