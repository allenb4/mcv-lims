<ul class="p-1">
@foreach($package['tests'] as $test)
    <li>
        {{$test['test']['name']}}
    </li>
@endforeach
@foreach($package['cultures'] as $culture)
    <li>
        {{$culture['culture']['name']}}
    </li>
@endforeach
</ul>