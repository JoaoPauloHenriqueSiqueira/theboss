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
          @if(!empty ($configData['templateTitle']) && isset($configData['templateTitle']))
          {{$configData['templateTitle']}}
          @else
          Materialize
          @endif
        </span>
      </a>
  </div>
  <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">
    <li class="home">
      <a class="tooltipped" href="{{ URL::route('home') }}" data-position='right' data-delay='50' data-tooltip="Início">Início</a>
    </li>
    <li class="sales">
      <a class="tooltipped" href="{{ URL::route('sales') }}" data-position='right' data-delay='50' data-tooltip="CRUD para as ATENDIMENTOS">Atendimentos</a>
    </li>
    <!-- <li class="users">
      <a class="tooltipped" href="" data-position='right' data-delay='50' data-tooltip="CRUD para as USUÁRIOS">Usuários</a>
    </li> -->
    <li class="clients search_clients">
      <a class="tooltipped" href="{{ URL::route('clients') }}" data-position='right' data-delay='50' data-tooltip="CRUD para as CLIENTES">Clientes</a>
    </li>

    <li class="products search_products">
      <a class="tooltipped" href="{{ URL::route('products') }}" data-position='right' data-delay='50' data-tooltip="CRUD para as PRODUTOS">Produtos</a>
    </li>

    <li class="categories search_categories">
      <a class="tooltipped" href="{{ URL::route('categories') }}" data-position='right' data-delay='50' data-tooltip="CRUD para as CATEGORIAS">Categorias</a>
    </li>

  </ul>
  <div class="navigation-background"></div>
  <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>

<script src="{{ asset('js/jquery.mask.js') }}"></script>

<script>
  $(".{{\Request::route()->getName()}}").addClass("active");
</script>