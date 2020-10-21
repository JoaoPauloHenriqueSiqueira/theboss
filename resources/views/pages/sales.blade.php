@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title',"Atendimentos: $sale_date_format")

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
            <table class="table-responsive bordered  centered">
                <thead>
                    <tr>
                        <th>Nome Cliente</th>
                        <th>De</th>
                        <th>Até</th>
                        <th>Limpar</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <form class="col s12" method="POST" action="{{ URL::route('search_sales') }}">
                        <tr>
                            <td>
                                <div class="input-field  col m12 s12">
                                    <select class="select2 browser-default" id="search_client_id" name="search_client_id">
                                        <option value="" disabled selected>Selecione o cliente</option>
                                        @foreach ($clients as $client)
                                        <option value="{{$client->id}}">{{$client->full_name_value}}</option>
                                        @endforeach
                                    </select>
                                    <label class="active" for="product_id">Cliente</label>
                                </div>
                            </td>
                            <td>
                                <input placeholder="Procurar" id="sale_date_search_start" name="search_sale_date_start" type="date" class="validate" value="{{Arr::get($search,'sale_date_start')}}" required>
                                <label for="procurar_data_venda_inicial">De</label>
                            </td>
                            <td>
                                <input placeholder="Procurar" id="sale_date_search_end" name="search_sale_date_end" type="date" class="validate" value="{{Arr::get($search,'sale_date_end')}}" required>
                                <label for="procurar_data_venda_final">Até</label>
                            </td>
                            <td>
                                <a class="btn red" onclick="clearSearch()">
                                    Limpar
                                </a>

                            </td>
                            <td>
                                <button class="btn waves-effect waves-light" type="submit">Procurar
                                </button>
                            </td>
                        </tr>
                    </form>
                </tbody>
            </table>
        </div>
    </li>
</ul>

<h5>Total: <span class="right">{{$total_sales}}</span></h5>
<ul class="collapsible collection" id="list" data-collapsible="accordion">
    @foreach ($datas as $data)
    <li id="{{$data->id}}">
        <div class="collapsible-header">
            <i class="material-icons green-text">
                schedule
            </i>
            {{$data->date_sale}}

            <div class="second-content">
                <i class="material-icons green-text">
                    attach_money
                </i>
                {{$data->amount_total_value}}
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
                @endif

                <span class="span-body">
                    <span class="green-text">Valor Venda:</span>
                    {{ $data->amount_total ==  "" ? '-' : $data->amount_total_value }}
                </span></br>
                <span class="span-body">
                    <span class="green-text">Valor Pago:</span>
                    {{ $data->amount_paid ==  "" ? '-' : $data->amount_paid_value }}
                </span></br>
                <span class="span-body">
                    <span class="green-text">Valor Débito:</span>
                    {{ $data->amount_paid <  $data->amount_total  ?  $data->getDebtAttribute() : 0}}
                </span></br>
                <span class="span-body center">
                    @if(count($data->products) > 0)
                    <table class="bordered center">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Valor</th>
                                <th>Qtde</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->products as $product)
                            <tr>
                                <td>
                                    <input placeholder="Nome" type="text" readonly disabled value="{{$product->name}}">
                                    <label for="name">Nome</label>
                                </td>
                                <td>
                                    <input placeholder="Valor" type="text" readonly value="{{$product->pivot->sale_value}}" readonly>
                                    <label for="value">Valor</label>
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

            <hr>
            <div class="row center">

                <a class="btn-small tooltipped" onclick="editSale('{{$data->sale_date_format}}','{{$data->sale_time_format}}',{{$data}},{{$data->products}})" data-position='left' data-delay='50' data-tooltip="Editar Atendimento">
                    <i class="material-icons white-text">
                        edit
                    </i>
                </a>
                <a class="btn-small tooltipped red" onclick="askDelete({{$data->id}})" data-position='right' data-delay='50' data-tooltip="Deletar atendimento">
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
    <a class="btn-floating btn-large green  btn tooltipped pulse" data-background-color="red lighten-3" data-position="left" data-delay="50" data-tooltip="Novo atendimento" onclick="openModal()">
        <i class="large material-icons">add</i>
    </a>
</div>

<!-- Modal Structure -->
<div id="modalDelete" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4 class="center red-text">Deletar atendimento?</h4>
        <div class="row center">
            <input type="hidden" id="deleteInput">
            <a class="btn-flat tooltipped" onclick="deleteSale()" data-position='left' data-delay='50' data-tooltip="Sim">
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
<div id="modal" class="modal bottom-sheet">
    <form class="col s12" method="POST" action="{{ URL::route('make_sale') }}" id="formSale">
        <div class="modal-content">
            <div class="row">
                <div class="input-field col m6 s6">
                    <input id="sale_date" name="sale_date" type="date" class="validate">
                    <label for="procurar_data_venda">Data</label>
                </div>

                <div class="input-field col m6 s6">
                    <input id="sale_time" type="time" name="sale_time" class="timepicker">
                </div>
            </div>

            <div class="row">
                <div class="input-field  col m12 s12">
                    <select class="select2 browser-default" id="client_id" name="client_id">
                        <option value="" disabled selected>Selecione o cliente</option>
                        @foreach ($clients as $client)
                        <option value="{{$client->id}}">{{$client->full_name_value}}</option>
                        @endforeach
                    </select>
                    <label class="active" for="product_id">Cliente</label>
                </div>

            </div>

            <div class="row">
                <div class="input-field col m6 s7">
                    <select class="select2 browser-default" onchange="controlQuantity()" id="product_selected">
                        <option value="select" disabled selected>Selecione o produto</option>
                        @foreach ($products as $product)
                        <option data-quantity="{{$product->quantity}}" data-control="{{$product->control_quantity}}" data-name="{{$product->name}}" data-value-number="{{$product->sale_format_value_money}}" data-name="{{$product->name}}" data-value="{{$product->sale_format_value}}" value="{{$product->id}}">
                            {{$product->full_name_value}}
                        </option>
                        @endforeach
                    </select>
                    <label class="active" for="product_id">Produto</label>
                </div>

                <div class="input-field col m4 s3">
                    <input id="quantity" placeholder="Quantidade" type="number" min="0" oninput="validity.valid||(value='');" value="1" class="validate">
                    <label class="active">Quantidade</label>
                </div>

                <div class="input-field col m2 s2">
                    <a class="btn-floating blue  btn tooltipped " data-background-color="red lighten-3" data-position="left" data-delay="50" data-tooltip="Adicionar produto" onclick="addSale()">
                        <i class="large material-icons">add</i>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="input-field col m6 s6">
                    <input id="amount_total" name="amount_total" required placeholder="Valor Total" type="text" class="validate" value="0">
                    <label class="active">Valor Total</label>
                </div>

                <div class="input-field col m6 s6">
                    <input id="amount_paid" name="amount_paid" required placeholder="Valor pago" type="text" class="validate" value="0">
                    <label class="active">Valor pago</label>
                </div>
            </div>

            <div class="row">
                <table id="tableProducts" class="table-responsive bordered hide centered">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Valor</th>
                            <th>Qtde</th>
                            <th></th>
                        </tr>
                    </thead>
                    <form>
                        <tbody id="sale_form">
                        </tbody>
                    </form>
                </table>

            </div>
        </div>

        <div class="modal-footer">
            <button class="modal-action waves-blue btn-flat" type="submit">Salvar</button>
            <a href="#!" onclick="closeSaleModal()" class="modal-action modal-close waves-red btn-flat ">Fechar</a>
        </div>
    </form>
</div>


@if( method_exists($datas,'links') )
<br>
<div>
    <h1 class="center" style="display:block">{{$datas->links('vendor.pagination.materializecss')}}</h1>
</div>
@endif

@endsection


<style>
    .second-content {
        position: absolute;
        right: 20px;
    }
</style>

<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/materialize.js') }}"></script>

<script>
    $(document).ready(function() {
        $(".select2").select2({
            dropdownAutoWidth: true,
            width: '100%'
        });
        cleanFields();
        maskFields();
        $client = "<?= Arr::get($search, 'search_client_id', ""); ?>";

        if ($client && $client != "") {
            selectClient($client, "#search_client_id");
        }
    });

    function clearSearch() {
        $("#sale_date_search_start").val('');
        $("#sale_date_search_end").val('');
        $('#search_client_id').prop('selected', false).find('option:first').prop('selected', true);
        $('#search_client_id').change();
        $('#search_client_id').formSelect();
    }

    function maskFields() {
        $('#amount_total').maskMoney({
            prefix: 'R$ ',
            thousands: '.',
            decimal: ','
        });

        $('#amount_paid').maskMoney({
            prefix: 'R$ ',
            thousands: '.',
            decimal: ','
        });
    }

    function validProductAdd() {
        $product = $("#product_selected").val();
        $quantity = $("#quantity").val();

        if (!$quantity) {
            M.toast({
                html: "Selecione a quantidade"
            }, 5000);
        }

        if (!$product) {
            M.toast({
                html: "Selecione o produto"
            }, 5000);
        }
    }

    function addSale() {
        controlQuantity();
        validProductAdd();
        $product = $("#product_selected").val();
        $quantity = $("#quantity").val();
        $saleValue = $("#product_selected").find(':selected').data('value');
        $saleValueNumber = $("#product_selected").find(':selected').data('value-number');
        $name = $("#product_selected").find(':selected').data('name');

        if ($product && $quantity) {
            let $element = createSaleRow($product, $quantity, $saleValueNumber, $name);
            $("#sale_form").append($element);
            sumTotalValue();
            this.selectProduct(true, $product);
            $("#tableProducts").removeClass('hide');
            $("#quantity").val('1');
        }
    }

    function createSaleRow($product, $quantity, $saleValue, $name) {
        if (String($saleValue).length == 2) {
            $saleValue = parseFloat($saleValue).toFixed(2);
        }
        return $(`<tr id="rowproduct${$product}">
                            <td>
                                <input type="hidden"  name="products[]"  value="${$product}">
                                <input placeholder="Nome" type="text" class="validate" readonly disabled value="${$name}">
                                <label for="name">Nome</label>
                            </td>
                            <td>
                                <input placeholder="Valor" name="value${$product}" type="text" readonly value="${$saleValue}" readonly class="validate">
                                <label for="value">Valor</label>
                            </td>
                            <td>
                                <input placeholder="Qtde" id="qtde${$product}" name="qtde${$product}" type="text" value="${$quantity}" readonly class="validate">
                                <label for="qtde">Qtde</label>
                            </td>
                       
                            <td>
                                <button class="btn-small red" onclick="removeProductSale(${$product},'${$saleValue}')">
                                    <i class="material-icons white-text">
                                        clear
                                    </i>
                                </button>
                            </td>
                        </tr>`);

    }

    function addValueInputs($total) {
        if (String($total).length == 2) {
            $total = parseFloat($total).toFixed(2);
        }

        $("#amount_total").val($total);
        $("#amount_paid").val($total);
        $("#amount_paid").maskMoney('mask', $total);
        $("#amount_total").maskMoney('mask', $total);
    }

    function sumTotalValue() {
        $quantity = Number($("#quantity").val());
        $subtotal = parseFloat($("#amount_total").val().replace(',', '.'));
        $saleValueNumber = ($("#product_selected").find(':selected').data('value-number'));
        $total = parseFloat($subtotal + ($quantity * $saleValueNumber)).toFixed(2);
        addValueInputs($total);
    }

    function removeProductSale($product, $saleValueNumber) {
        $quantityProductRemoved = $("#qtde" + $product).val();
        $subtotal = parseFloat($("#amount_total").val().toString().replace(/[.,\s]/g, ''));
        if ($saleValueNumber.length == 2) {
            $saleValueNumber = parseFloat($saleValueNumber).toFixed(2);
        }
        $total = $subtotal - parseFloat($saleValueNumber.toString().replace(/[.,\s]/g, '') * $quantityProductRemoved);
        this.addValueInputs($total);
        $(`#rowproduct${$product}`).remove();
        let rowCount = $('#tableProducts >tbody >tr').length;

        if (rowCount <= 0) {
            return this.closeSaleModal();
        }

        this.selectProduct(false, $product);
        maskFields();
    }

    function selectProduct($habilitar, $product) {
        $('#product_selected option[value="' + $product + '"]').attr("disabled", $habilitar);
        $('#product_selected').prop('selected', false).find('option:first').prop('selected', true);
        $('#product_selected').change();
        $('#product_selected').formSelect();
    }

    function selectClient($client, $element) {
        $($element).val($client);
        $($element).change();
        $($element).formSelect();
    }

    function openModal() {
        clean();
        cleanFields();
        $('#modal').modal('open');
    }

    function closeSaleModal() {
        $("#sale_form").empty();
        $("#tableProducts").addClass('hide');
        cleanProductField();
        cleanClientField();
        cleanTableProducts();
        this.addValueInputs(0);
    }

    function closeModal() {
        clean();
        $('#modalDelete').modal('close');
    }

    function cleanFields() {
        $("#quantity").val("1");
        $("#sale_date").val("<?= $sale_date_start ?>");
        addTime();
    }

    function addTime() {
        let $hour = String(new Date().getHours()).padStart(2, "0");
        let $minutes = String(new Date().getMinutes()).padStart(2, "0");
        $("#sale_time").val(`${$hour+":"+$minutes}`);
    }

    function cleanTableProducts() {
        $('#tableProducts >tbody>tr').remove();
    }

    function clean() {
        $('#formSale').get(0).setAttribute('method', 'POST');
        $("#idSale").remove();
    }

    function cleanClientField() {
        $('#client_id option').attr("disabled", false);
        $('#client_id').prop('selected', false).find('option:first').prop('selected', true);
        $('#client_id').change();
        $('#client_id').formSelect();
    }

    function cleanProductField() {
        $('#product_selected option').attr("disabled", false);
        $('#product_selected').prop('selected', false).find('option:first').prop('selected', true);
        $('#product_selected').change();
        $('#product_selected').formSelect();
    }

    function editSale(dateSale, timeSale, sale, products) {
        products.forEach(element => {
            let newRow = createSaleRow(element.id, element.pivot.quantity, element.pivot.sale_value, element.name);
            $("#sale_form").append(newRow);
            this.selectProduct(true, element.id);
            $("#tableProducts").removeClass('hide');
        });

        $("#idSale").append(sale['id']);
        $("#sale_date").val(dateSale);
        $("#sale_time").val(timeSale);

        if (String(sale['amount_total']).length == 2) {
            sale['amount_total'] = parseFloat(sale['amount_total']).toFixed(2);
        }

        if (String(sale['amount_paid']).length == 2) {
            sale['amount_paid'] = parseFloat(sale['amount_paid']).toFixed(2);
        }

        $("#amount_total").val(sale['amount_total']);
        $("#amount_paid").val(sale['amount_paid']);
        selectClient(sale['client_id'], '#client_id');
        $('<input>').attr({
            type: 'hidden',
            id: 'idSale',
            name: 'id',
            value: sale['id']
        }).appendTo('#formSale');

        M.updateTextFields()
        maskFields();
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

    function controlQuantity() {
        $quantity = $("#product_selected").find(':selected').data('quantity');
        $control = $("#product_selected").find(':selected').data('control');
        $product = $("#product_selected").val();
        $quantityInput = $("#quantity").val();

        if ($control && $quantity < $quantityInput) {
            $("#quantity").val('');
            M.toast({
                html: "Quantidade maior do que em estoque"
            }, 5000);
        }

        if ($control && $quantity <= 0) {
            this.selectProduct(true, $product);
            M.toast({
                html: "Sem estoque pra esse produto"
            }, 5000);
        }
    }

    function deleteSale() {
        let id = $("#deleteInput").val();
        let $url = "<?= URL::route('delete_sales') ?>";
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