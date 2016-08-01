@section('leftAdmin')
      <li class="active has-dropdown"><a href="{{URL::to('/admin')}}">Admin</a>
      <ul class="dropdown">
          <li><a href="{{URL::to('/admin/user')}}">Users</a></li>
          <li><a href="{{URL::to('/admin/role')}}">Roles</a></li>
        </ul>
      </li>
@endsection

@section('leftAdmin')
      <li class="divider"></li>
      <li class="heading">Admin</li>
      <li><a href="{{URL::to('/admin')}}">Admin Menu</a></li>
      <li><a href="{{URL::to('/admin/user')}}">Users</a></li>
      <li><a href="{{URL::to('/admin/role')}}">Roles</a></li>
@endsection
