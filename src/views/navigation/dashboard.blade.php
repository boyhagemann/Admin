
<ul class="dashboard media-list row">
	@foreach($nodes as $key => $node)
  <li class="dashboard__item media col-lg-3">

    <div class="pull-left dashboard__icon {{ $node->icon_class }} circle" style="background-color: {{ $node->color }}"></div>
    <div class="dashboard__body media-body">
      <h4 class="dashboard__title media-heading">
          <a class="dashboard__link" href="{{ URL::route($node->page->alias, $node->params) }}">
            {{{ $node['title'] }}}              
          </a>
      </h4>
      {{{ $node['description'] }}}
    </div>
  </li>
	@endforeach
</ul>
