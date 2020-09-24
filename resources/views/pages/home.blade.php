@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Produtos')

@section('content')

<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">
                    <!-- card stats start -->
                    <div id="card-stats" class="pt-0">
                        <div class="row">
                            <div class="col s12 m6 l6">
                                <div class="card animate fadeLeft">
                                    <div class="card-content cyan white-text">
                                        <p class="card-stats-title"><i class="material-icons">person_outline</i> Clientes</p>
                                        <h4 class="card-stats-number white-text"><?= Arr::get($metrics, 'clients.count') ?></h4>

                                        <p class="card-stats-compare">
                                            @if( Arr::get($metrics,'clients.porcent'))
                                            <?= Arr::get($metrics, 'clients.porcent') ?>
                                            <span class="cyan text text-lighten-5">a + último mês</span>
                                            @endif

                                        </p>
                                    </div>

                                </div>
                            </div>
                            <div class="col s12 m6 l6">
                                <div class="card animate fadeLeft">
                                    <div class="card-content red accent-2 white-text">
                                        <p class="card-stats-title"><i class="material-icons">attach_money</i>Total Vendas (mês atual)</p>
                                        <h4 class="card-stats-number white-text"><?= Arr::get($metrics, 'sales_month.count') ?></h4>
                                        @if( Arr::get($metrics,'sales_month.porcent'))
                                        <p class="card-stats-compare">
                                            <?= Arr::get($metrics, 'sales_month.porcent') ?>
                                            <span class="red-text text-lighten-5">a + último mês</span>
                                        </p>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6 l6">
                                <div class="card animate fadeRight">
                                    <div class="card-content orange lighten-1 white-text">
                                        <p class="card-stats-title"><i class="material-icons">trending_up</i> Total vendas (hoje) </p>
                                        <h4 class="card-stats-number white-text"><?= Arr::get($metrics, 'sales_today.count') ?></h4>
                                        @if( Arr::get($metrics,'sales_today.porcent'))
                                        <p class="card-stats-compare">
                                            <?= Arr::get($metrics, 'sales_today.porcent') ?>
                                            <span class="orange-text text-lighten-5">a + ontem</span>
                                        </p>
                                        @endif

                                    </div>

                                </div>
                            </div>
                            <div class="col s12 m6 l6">
                                <div class="card animate fadeRight">
                                    <div class="card-content green lighten-1 white-text">
                                        <p class="card-stats-title"><i class="material-icons">trending_up</i> Total lucros (hoje)</p>
                                        <h4 class="card-stats-number white-text"><?= Arr::get($metrics, 'profit_today.count') ?></h4>
                                        @if( Arr::get($metrics,'profit_today.porcent'))
                                        <p class="card-stats-compare">
                                            <?= Arr::get($metrics, 'profit_today.porcent') ?>
                                            <span class="green-text text-lighten-5">a + ontem</span>
                                        </p>
                                        @endif

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-overlay"></div>
            </div>
        </div>
    </div>



</div>


@endsection
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/materialize.min.js') }}"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    $(document).ready(function() {});
</script>