<table class="table table-bordered">
    <tbody>
      <tr>
        <th scope="row">Created Date:</th>
        <td>{{dateFormat($row->created_at,'d-M-Y h:i A')}}</td>
      </tr>
      <tr>
        <th scope="row">Module Name:</th>
        <td>{{$row->module_name}}</td>
      </tr>
      <tr>
        <th scope="row">Category Name:</th>
        <td>{{$row->category->category_name}}</td>
      </tr>
      <tr>
        <th scope="row">Slug:</th>
        <td>{{$row->route}}</td>
      </tr>
     
      <tr>
        <th scope="row">CSS Class:</th>
        <td>{{$row->css_class}} <i class={{$row->css_class}}></i></td>
      </tr>
    </tbody>
  </table>