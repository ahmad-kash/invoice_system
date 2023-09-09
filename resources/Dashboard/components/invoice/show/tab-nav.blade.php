 @props(['watch', 'active' => false])
 <li class="nav-item">
     <a id="{{ $watch }}-tab" data-toggle="pill" href="#{{ $watch }}" role="tab"
         aria-controls="{{ $watch }}" aria-selected="true" @class(['nav-link', 'active' => $active])>{{ $slot }}</a>
 </li>
