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
    <div class="row">
        <h5 class="center purple white-text">Atendimentos 'Sem status'</h5>
        @if(count($datas) == 0)
        <br>
        <h6 class="center">Tudo feito por aqui =)</h6>
        <br>
        @endif

        <form method="POST" action="{{ route('updateStatus') }}">
            <ul class="collapsible collection" id="list" data-collapsible="accordion">
                @foreach ($datas as $data)
                <li id="{{$data->id}}">
                    <div class="collapsible-header">
                        <i class="material-icons green-text">
                            schedule
                        </i>
                        {{$data->date_sale}}
                        <div class="input-field col s7">
                            <select class="select2 browser-default" name="{{$data->id}}">
                                <option value="" selected>Sem status</option>
                                @foreach ($statuses as $status)
                                <option value="{{$status->id}}">
                                    {{$status->name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="collapsible-body white">
                        <div class="row ">
                            <span class="span-body">
                                <span class="green-text">Usuário:</span>
                                {{ $data->user->name }}
                            </span></br>
                            @if($data->client != "")
                            <span class="span-body">
                                <span class="green-text">Cliente:</span>
                                {{ $data->client->name }}
                            </span></br>
                            <span class="span-body">
                                <span class="green-text">Endereço:</span>
                                {{ $data->client->address }}
                            </span><br>
                            <span class="span-body">
                                <span class="green-text">Telefone contato:</span>
                                {{ $data->client->cell_phone  }}
                            </span>
                            @endif
                            <span class="span-body ">
                                @if(count($data->status) > 0)
                                <span class="green-text">Status:
                                </span></br>
                                @foreach ($data->status as $status)
                                {{$status->name}}</br>
                                @endforeach
                                @endif
                            </span><br>
                            <span class="span-body">
                                <span class="green-text">Valor Venda:</span>
                                {{ $data->amount_total ==  "" ? '-' : $data->amount_total_value }}
                            </span></br>
                            <span class="span-body center">
                                <h5 class="purple white-text">Produtos</h5>
                                @if(count($data->products) > 0)
                                <table class="bordered center">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Produto</th>
                                            <th>Valor</th>
                                            <th>Tamanho</th>
                                            <th>Qtde</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->products as $product)
                                        <tr>
                                            <td>
                                                <input placeholder="Id" type="text" readonly disabled value="{{$product->id}}">
                                                <label for="id">Id</label>
                                            </td>
                                            <td>
                                                <input placeholder="Nome" type="text" readonly disabled value="{{$product->name}}">
                                                <label for="name">Nome</label>
                                            </td>
                                            <td>
                                                <input placeholder="Valor" type="text" readonly value="{{$product->product_sale_value}}" readonly>
                                                <label for="value">Valor</label>
                                            </td>
                                            <td>
                                                <select class="browser-default" disabled>
                                                    <option disabled selected>Tamanho</option>
                                                    @foreach ($sizes as $size)
                                                    <option value="{{$size->id}}" {{$size->id == $product->pivot->size_id  ? 'selected' : '' }}>
                                                        {{$size->name}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <label class="active" for="sizes">Tamanho</label>
                                            </td>
                                            <td>
                                                <input placeholder="Qtde" type="text" value="{{$product->pivot->quantity}}" readonly>
                                                <label for="qtde">Qtde</label>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                            </span>
                        </div></br>
                    </div>
                </li>
                @endforeach
            </ul>
            <div class="row">
                <div class="s12 center">
                    <button class="btn-small waves-effect" <?php if (count($datas) == '0'){ ?> disabled <?php   } ?> type="submit">Salvar todos</button>
                </div>
            </div>
        </form>
    </div>
    @if( method_exists($datas,'links') )
    <br>
    <div>
        <h1 class="center" style="display:block">{{$datas->links('vendor.pagination.materializecss')}}</h1>
    </div>
    @endif

</div>


@endsection
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-0RDMX1LEHW"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-0RDMX1LEHW');
</script>
<script>
    $(document).ready(function() {
        $(".select2").select2({
            dropdownAutoWidth: true,
            width: '100%'
        });
    });
</script>