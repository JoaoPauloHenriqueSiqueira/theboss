@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Produtos/Serviços')

@section('content')
<ul class="collapsible " data-collapsible=" accordion">
    <li>
        <div class="collapsible-header valign-wrapper">
            <div class="center">
                <p>
                    Pesquisar
                </p>
            </div>
            <div class="second-content">
                <i class="material-icons green-text">
                    search
                </i>
            </div>
        </div>
        <div class="collapsible-body white">
            <table class="table-responsive bordered centered">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Código</th>
                        <th>Valor Custo</th>
                        <th>Valor Venda</th>
                        <th>Qtd. Estoque</th>
                        <th>Limpar</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <form class="col s12" method="POST" action="{{ URL::route('search_products') }}">
                        <tr>
                            <td>
                                <input placeholder="Procurar" id="search_name" name="search_name" type="text" value="{{Arr::get($search,'search_name')}}" class="validate">
                                <label for="procurar_nome">Nome</label>
                            </td>
                            <td>
                                <input placeholder="Procurar" id="search_bar_code" name="search_bar_code" type="text" value="{{Arr::get($search,'search_bar_code')}}" class="validate">
                                <label for="procurar_codigo">Código</label>
                            </td>
                            <td>
                                <input placeholder="Procurar" id="search_cost_value" name="search_cost_value" type="text" value="{{Arr::get($search,'search_cost_value')}}" class="validate">
                                <label for="procurar_vlr_custo">Valor Custo</label>
                            </td>
                            <td>
                                <input placeholder="Procurar" id="search_sale_value" name="search_sale_value" type="text" value="{{Arr::get($search,'search_sale_value')}}" class="validate">
                                <label for="procurar_vlr_venda">Valor Venda</label>
                            </td>
                            <td>
                                <input placeholder="Procurar" id="search_quantity" name="search_quantity" type="text" value="{{Arr::get($search,'search_quantity')}}" class="validate">
                                <label for="procurar_qtde">Quantidade</label>
                            </td>
                            <td>
                                <a class="btn red" onclick="clearSearch()">
                                    Limpar
                                </a>

                            </td>
                            <td>
                                <button class="btn waves-effect waves-light" type="submit">Procurar
                                    <i class="material-icons right">send</i>
                                </button>
                            </td>
                        </tr>
                    </form>
                </tbody>
            </table>
        </div>
    </li>
</ul>

@if(count($datas) == 0)
<h3 class="center">Não há produtos cadastrados</h3>
@endif

<ul class="collapsible collection" data-collapsible="accordion">
    @foreach ($datas as $data)
    <li id="{{$data->id}}">
        <div class="collapsible-header">
            {{$data->name}}
        </div>
        <div class="collapsible-body white">
            <div class="row ">
                <!-- <span class="span-body"> -->
                    <!-- <a class="tooltipped right" onclick="photos({{$data->id}})" data-position='right' data-delay='50' data-tooltip="Adicionar fotos"><i class="material-icons">add_a_photo</i></a> -->
                <!-- </span> -->
                <span class="span-body">
                    <span class="green-text">Código:</span>
                    {{ $data->bar_code ==  "" ? '-' : $data->bar_code }}
                </span></br>
                <span class="span-body">
                    <span class="green-text">Valor Custo:</span>
                    {{ $data->cost_value ==  "" ? '-' : $data->cost_format_value }}
                </span></br>
                <span class="span-body">
                    <span class="green-text">Valor Venda:</span>
                    {{ $data->sale_value ==  "" ? '-' : $data->sale_format_value }}
                </span></br>
                <span class="span-body">
                    <span class="green-text">Valor Lucro:</span>
                    {{ $data->profit}}
                </span></br>
                <span class="span-body">
                    <span class="green-text">Valor Lucro:</span>
                    {{ $data->profit_percent}}
                </span></br>
                @if($data->control_quantity == 1)

                <span class="span-body">
                    <span class="green-text">Quantidade em estoque:</span>
                    {{ $data->quantity ==  "" ? '0' : $data->quantity }}
                </span>

                @endif
                <span class="span-body left">
                    @if(count($data->categories) > 0)
                    <span class="green-text">Categoria(s):
                    </span></br>
                    @foreach ($data->categories as $category)
                    {{$category->name}}</br>
                    @endforeach
                    @endif
                </span>
            </div>
            <hr>
            <div class="row center">
                <a class="btn-small tooltipped" onclick="editProduct({{$data}},{{$data->categories}})" data-position='left' data-delay='50' data-tooltip="Editar produto">
                    <i class="material-icons white-text">
                        edit
                    </i>
                </a>
                <a class="btn-small tooltipped red" onclick="askDelete({{$data->id}})" data-position='right' data-delay='50' data-tooltip="Deletar produto">
                    <i class="material-icons white-text">
                        clear
                    </i>
                </a>
            </div>

        </div>
    </li>
    @endforeach
</ul>




<div class="fixed-action-btn">
    <a class="btn-floating btn-large green  btn tooltipped pulse" data-background-color="red lighten-3" data-position="left" data-delay="50" data-tooltip="Criar produto" onclick="openModal()">
        <i class="large material-icons">add</i>
    </a>
</div>


<!-- Modal Structure -->
<div id="modalDelete" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4 class="center red-text">Deletar produto?</h4>
        <div class="row center">
            <input type="hidden" id="deleteInput">
            <a class="btn-flat tooltipped" onclick="deleteproduct()" data-position='left' data-delay='50' data-tooltip="Sim">
                <i class="material-icons blue-text">
                    done
                </i>
            </a>
            <a class="btn-flat tooltipped" onclick="closeModal()" data-position='right' data-delay='50' data-tooltip="Não">
                <i class="material-icons red-text">
                    close
                </i>
            </a>
        </div>
    </div>
</div>


<!-- Modal Structure -->
<div id="modalPhotos" class="modal modal-fixed-footer">
    <div class="modal-content">
        <div class="carousel">
            <a class="carousel-item" href="#one!"><img src="https://lorempixel.com/250/250/nature/7"></a>
            <a class="carousel-item" href="#two!"><img src="https://lorempixel.com/250/250/nature/2"></a>
            <a class="carousel-item" href="#three!"><img src="https://lorempixel.com/250/250/nature/3"></a>
            <a class="carousel-item" href="#four!"><img src="https://lorempixel.com/250/250/nature/4"></a>
            <a class="carousel-item" href="#five!"><img src="https://lorempixel.com/250/250/nature/5"></a>
        </div>
    </div>
</div>




<!-- Modal Structure -->
<div id="modal" class="modal bottom-sheet">
    <div class="modal-content">
        <h4 id="product" class="center red-text">Novo Produto</h4>
        <form class="col s12" method="POST" action="products" id="formProduct">
            <input type="hidden" id="old">
            <div class="row">
                <div class="input-field col s12">
                    <input id="name" placeholder="Nome" name="name" pattern=".{1,}" title="1 letras no mínimo" type="text" class="validate" value="{{ old('name') }}" required>
                    <label for="disabled">Nome*</label>
                </div>
            </div>
            <div class="row">

                <div class="input-field col s12">
                    <select class="select2 browser-default" id="categories" name="categories[]" multiple>
                        @foreach ($categories as $category)
                        <option value="{{$category->id}}">
                            {{$category->name}}
                        </option>
                        @endforeach
                    </select>
                    <label class="active" for="categories">Categorias</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input id="bar_code" placeholder="Código" name="bar_code" pattern=".{3,}" title="3 letras no mínimo" value="{{ old('bar_code') }}" type="text" class="validate">
                    <label for="disabled">Código</label>
                </div>
            </div>

            <div class="row">
                <p>
                    <label>

                        <input type="checkbox" placeholder="Notificar próximos atendimentos" id="notifiable" name="notify" checked onclick="notifyParam()">
                        <span for="notifiable">Notificar próximos atendimentos</span>
                    </label>
                </p>
            </div>

            <div class="row " id="notify_param">
                <div class="input-field col s12">
                    <input id="days_notify" placeholder="Dias para notificação de atendimento" name="days_notify" value="30" min="1" oninput="validity.valid||(value='');" type="number" value="{{ old('days_notify') }}" class="validate">
                    <label for="disabled">Dias para notificação de atendimento</label>
                </div>

            </div>

            <div class="row">
                <p>
                    <label>
                        <input type="checkbox" id="control_quantity" name="control_quantity" checked onclick="quantityParam()">
                        <span> Controlar estoque?</span>
                    </label>
                </p>
            </div>

            <div class="row " id="quantity_param">
                <div class="input-field col s12">
                    <input id="quantity" placeholder="Quantidade em estoque" name="quantity" type="number" min="0" oninput="validity.valid||(value='');" value="{{ old('quantity') }}" class="validate">
                    <label for="disabled">Quantidade em estoque</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input placeholder="Valor de venda" id="sale_value" name="sale_value" type="text" class="validate" value="{{ old('sale_value') }}" required>
                    <label for="disabled">Valor de venda*</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input placeholder="Valor de custo" id="cost_value" name="cost_value" type="text" class="validate" value="{{ old('cost_value') }}" required>
                    <label for="disabled">Valor de custo*</label>
                </div>
            </div>
    </div>

    <div class="modal-footer">
        <button class="modal-action waves-effect waves-green btn-flat " type="submit">Salvar</button>
        </form>
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Fechar</a>
    </div>
</div>



@if( method_exists($datas,'links') )
<br>
<div>
    <h1 class="center" style="display:block">{{$datas->links('vendor.pagination.materializecss')}}</h1>
</div>
@endif

@endsection

<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.carousel').carousel();
        maskFields();
        $(".select2").select2({
            dropdownAutoWidth: true,
            width: '100%',
            minimumResultsForSearch: Infinity,
            escapeMarkup: function(es) {
                return es;
            }
        });

        $notify = "<?= old('notify') ?>";
        if ($notify == "on") {
            $("#notifiable").prop('checked', true);
            $("#notify_param").show();
        } else {
            $("#notifiable").prop('checked', false);
            $("#notify_param").hide();
        }

        $control_quantity = "<?= old('control_quantity') ?>";
        if ($control_quantity == "on") {
            $("#control_quantity").prop('checked', true);
            $("#notify_param").show();
        } else {
            $("#control_quantity").prop('checked', false);
            $("#notify_param").hide();
        }

        $old = "<?= old('name') ?>";
        if ($old != "") {
            $("#old").val(1);
            openModal();
        }
    });


    function quantityParam() {
        if ($("#control_quantity").is(":checked")) {
            $("#quantity_param").show();
        } else {
            $("#quantity_param").hide();
        }
    }

    function notifyParam() {
        if ($("#notifiable").is(":checked")) {
            $("#notify_param").show();
        } else {
            $("#notify_param").hide();
        }
    }

    function maskFields() {
        $('#cost_value').mask('000.000.000.000.000,00', {
            reverse: true
        });
        $('#sale_value').mask('000.000.000.000.000,00', {
            reverse: true
        });
    }

    function openModal() {

        if ($("#old").val() != 1) {
            this.clean();
            this.cleanFields();
        } else {
            $("#old").val(0);
        }

        $("#quantity_param").hide();
        $("#notify_param").hide();
        $('#modal').modal('open');
    }

    function closeModal() {
        this.clean();
        $('#modalDelete').modal('close');
    }

    function photos() {
        $("#modalPhotos").modal('open');
    }


    function cleanFields() {
        $("#name").val('');
        $("#bar_code").val('');
        $("#cost_value").val('0');
        $("#sale_value").val('');
        $("#quantity").val('');
        $("#days_notify").val('');
        cleanCategoryField();
    }

    function clean() {
        $('#formProduct').get(0).setAttribute('method', 'POST');
        $("#product").html("Novo Produto");
        $("#idProduct").remove();
    }

    function selectCategory($category) {
        console.log($category);
        $('#categories option[value="' + $category + '"]').attr('selected', true);
        $('#categories').change();
        $('#categories').formSelect();
    }

    function cleanCategoryField() {
        $('#categories option').prop('selected', false);
        $('#categories').change();
        $('#categories').formSelect();
    }

    function editProduct(product, categories) {
        categories.forEach(element => {
            this.selectCategory(element.id);
        });

        $("#idProduct").append(product['id']);
        $("#product").html("Editar Produto");
        $("#name").val(product['name']);
        $("#bar_code").val(product['bar_code']);
        $("#cost_value").val(product['cost_value']);
        $("#sale_value").val(product['sale_value']);
        $("#days_notify").val(product['days_notify']);
        $("#quantity").val(product['quantity']);

        notifiable = product['notifiable'];
        if (notifiable) {
            $("#notifiable").prop('checked', true);
        } else {
            $("#notifiable").prop('checked', false);
        }

        control = product['control_quantity'];
        if (control) {
            $("#control_quantity").prop('checked', true);
        } else {
            $("#control_quantity").prop('checked', false);
        }



        $('<input>').attr({
            type: 'hidden',
            id: 'idProduct',
            name: 'id',
            value: product['id']
        }).appendTo('#formProduct');
        M.updateTextFields()
        maskFields();
        notifyParam();
        $('#modal').modal('open');
    }

    function askDelete(id) {
        $('#modalDelete').modal('open');
        $("#deleteInput").val(id);
    }

    function closeCleanModal(id, $data) {
        $("#" + id).remove();
        M.toast({
            html: $data
        }, 5000);
        $("#modalDelete").modal("close");
        $("#deleteInput").val('');
    }

    function clearSearch() {
        $("#search_name").val('');
        $("#search_bar_code").val('');
        $("#search_cost_value").val('');
        $("#search_sale_value").val('');
        $("#search_quantity").val('');
    }

    function deleteproduct() {
        let id = $("#deleteInput").val();
        let $url = "<?= URL::route('delete_products') ?>";
        $.ajax({
            type: 'DELETE',
            url: $url,
            data: {
                "id": id
            },
            success: function(data) {
                closeCleanModal(id, data);
            },
            error: function(data) {
                closeCleanModal(id, data.responseText);
            }
        });
    }
</script>