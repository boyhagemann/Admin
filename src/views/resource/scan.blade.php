
<h2>New Crud controllers</h2>
<ul>
    @foreach($controllers as $key => $class)
    <li><a href="{{ URL::action('Boyhagemann\Admin\Controller\ResourceController@import', $key) }}">{{ $class->getName() }}</a></li>
    @endforeach
</ul>

<a href="{{ URL::route('admin.resources.index') }}">View used controllers</a>