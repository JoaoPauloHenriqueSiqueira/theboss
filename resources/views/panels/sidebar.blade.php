<aside class="{{$configData['sidenavMain']}} @if(!empty($configData['activeMenuType'])) {{$configData['activeMenuType']}} @else {{$configData['activeMenuTypeClass']}}@endif @if(($configData['isMenuDark']) === true) {{'sidenav-dark'}} @elseif(($configData['isMenuDark']) === false){{'sidenav-light'}}  @else {{$configData['sidenavMainColor']}}@endif">
  <div class="brand-sidebar">
    <h1 class="logo-wrapper">
      <a class="brand-logo darken-1" href="{{asset('/')}}">
        @if(!empty($configData['mainLayoutType']) && isset($configData['mainLayoutType']))
        @if($configData['mainLayoutType']=== 'vertical-modern-menu')
        <img class="hide-on-med-and-down" src="{{asset($configData['largeScreenLogo'])}}" alt="materialize logo" />
        <img class="show-on-medium-and-down hide-on-med-and-up" src="{{asset($configData['smallScreenLogo'])}}" alt="materialize logo" />

        @elseif($configData['mainLayoutType']=== 'vertical-menu-nav-dark')
        <img src="{{asset($configData['smallScreenLogo'])}}" alt="materialize logo" />

        @elseif($configData['mainLayoutType']=== 'vertical-gradient-menu')
        <img class="show-on-medium-and-down hide-on-med-and-up" src="{{asset($configData['largeScreenLogo'])}}" alt="materialize logo" />
        <img class="hide-on-med-and-down" src="{{asset($configData['smallScreenLogo'])}}" alt="materialize logo" />

        @elseif($configData['mainLayoutType']=== 'vertical-dark-menu')
        <!-- <img class="show-on-medium-and-down hide-on-med-and-up" src="{{asset($configData['largeScreenLogo'])}}" alt="materialize logo" /> -->
        <!-- <img class="hide-on-med-and-down" src="{{asset($configData['smallScreenLogo'])}}" alt="materialize logo" /> -->
        @endif
        @endif
        <span class="logo-text hide-on-med-and-down">
          <img class="logo" width="140px" src="{{asset('img/logo-text.png')}}" alt="">
        </span>
      </a>
  </div>
  <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">
    <li class="home">
      <a class="tooltipped" href="{{ URL::route('home') }}" data-position='right' data-delay='50' data-tooltip="Início">Início</a>
    </li>
    <li class="sales">
      <a class="tooltipped" href="{{ URL::route('sales') }}" data-position='right' data-delay='50' data-tooltip="Lista todos os Atendimentos">Atendimentos</a>
    </li>
    <li class="users">
      <a class="tooltipped" href="{{ URL::route('users') }}" data-position='right' data-delay='50' data-tooltip="Lista todos os Usuários que terão acesso ao sistema">Usuários</a>
    </li>
    <li class="clients search_clients">
      <a class="tooltipped" href="{{ URL::route('clients') }}" data-position='right' data-delay='50' data-tooltip="Lista todos os Clientes">Clientes</a>
    </li>

    <li class="products search_products">
      <a class="tooltipped" href="{{ URL::route('products') }}" data-position='right' data-delay='50' data-tooltip="Lista todos os Produtos/Serviços">Produtos/Serviços</a>
    </li>

    <li class="sizes search_sizes">
      <a class="tooltipped" href="{{ URL::route('sizes') }}" data-position='right' data-delay='50' data-tooltip="Lista todos os Tamanhos de Produtos">Tamanhos de Produtos</a>
    </li>

    <li class="categories search_categories">
      <a class="tooltipped" href="{{ URL::route('categories') }}" data-position='right' data-delay='50' data-tooltip="Lista todas Categorias de Produtos/Serviços">Categorias</a>
    </li>

    <li class="providers search_status">
      <a class="tooltipped" href="{{ URL::route('status') }}" data-position='right' data-delay='50' data-tooltip="Lista todos Status de Atendimento">Status</a>
    </li>

  </ul>
  <div class="navigation-background"></div>
  <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>

<script src="{{ asset('js/jquery.mask.js') }}"></script>

<script>
  $(".{{\Request::route()->getName()}}").addClass("active");
</script>