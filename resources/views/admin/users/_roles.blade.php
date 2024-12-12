<ul class="p-1">
    @foreach($user['roles'] as $role)
        <li>
            @if(isset($role['role']))
                {{$role['role']['name']}}
            @endif
        </li>
    @endforeach
</ul>