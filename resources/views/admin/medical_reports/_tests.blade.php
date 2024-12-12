<ul class="pl-3">
@foreach($group['all_tests'] as $test)
    <li class="@if($test['done']) text-success @endif">{{$test['test']['name']}}</li>
@endforeach
@foreach($group['all_cultures'] as $culture)
    <li class="@if($culture['done']) text-success @endif">{{$culture['culture']['name']}}</li>
@endforeach
</ul>