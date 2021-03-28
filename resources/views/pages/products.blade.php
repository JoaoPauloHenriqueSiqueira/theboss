@extends('layouts.contentLayoutMaster')

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
                        <th>Id</th>
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
                    <form class="col s12" action="{{ URL::route('search_products') }}">
                        <tr>
                            <td>
                                <input placeholder="Procurar" id="search_id" name="search_id" type="text" value="{{Arr::get($search,'search_id')}}" class="validate">
                                <label for="procurar_id">Id</label>
                            </td>
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
                <span class="span-body">
                    <span class="green-text">Id:</span>
                    {{ $data->id ==  "" ? '-' : $data->id }}
                </span></br>

                <span class="span-body">
                    @if(count($data->photos) > 0)
                    <a class=" right" onclick="photos({{$data->id}})"><i class="material-icons">add_a_photo</i></a>
                    @endif
                </span>
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
                <span class="span-body ">
                    @if(count($data->categories) > 0)
                    <span class="green-text">Categoria(s):
                    </span></br>
                    @foreach ($data->categories as $category)
                    {{$category->name}}</br>
                    @endforeach
                    @endif
                </span>
                <span class="span-body ">
                    @if(count($data->sizes) > 0)
                    <span class="green-text">Tamanho(s):
                    </span></br>
                    @foreach ($data->sizes as $size)
                    {{$size->name}}</br>
                    @endforeach
                    @endif
                </span>
            </div>
            <hr>
            <div class="row center">
                <a class="btn-small tooltipped" onclick="editProduct({{$data}})" data-position='left' data-delay='50' data-tooltip="Editar produto">
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
    <a class="btn-floating btn-large  btn tooltipped pulse" data-background-color="red lighten-3" data-position="left" data-delay="50" data-tooltip="Criar produto" onclick="openModal()">
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
<div id="modalDeletePhoto" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4 class="center red-text row">Deletar foto?</h4><br>
        <div class="row center">
            <input type="hidden" id="deleteInputPhoto">
            <a class="btn-flat tooltipped" onclick="deleteproductphoto()" data-position='left' data-delay='50' data-tooltip="Sim">
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
        <br>
        <div class="row center">
            <div class=" preloader-wrapper big active center" style="display:none;" id="indeterminate">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div id="modalPhotos" class="modal modal-fixed-footer">
    <div class="modal-content">
        <ul class="collection with-header">
            <li class="collection-header">
                <h6>Fotos cadastradas</h6>
            </li>
            <div id="photosProduct">
            </div>
        </ul>
    </div>
</div>
</div>


<!-- Modal Structure -->
<div id="modal" class="modal bottom-sheet">
    <div class="modal-content">
        <h4 id="product" class="center red-text">Novo Produto</h4>
        <form class="col s12" method="POST" action="{{ URL::route('make_product') }}" id="formProduct" enctype="multipart/form-data">
            <input type="hidden" id="old">
            <div class="row">
                <div class="input-field col s12">
                    <input id="name" placeholder="Nome" name="name" pattern=".{1,}" title="1 letra no mínimo" type="text" class="validate" value="{{ old('name') }}" required>
                    <label for="disabled">Nome*</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input id="description" placeholder="Descrição" name="description" pattern=".{1,}" title="1 letra no mínimo" type="text" class="validate" value="{{ old('description') }}">
                    <label for="disabled">Descrição*</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <div class="file-field input-field">
                        <div class="btn">
                            <span>Fotos</span>
                            <input type="file" multiple name="fotos[]" id="foto" accept="image/*">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" id="foto2">
                        </div>
                    </div>
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
                    <select class="select2 browser-default" id="sizes" name="sizes[]" multiple>
                        @foreach ($sizes as $size)
                        <option value="{{$size->id}}">
                            {{$size->name}}
                        </option>
                        @endforeach
                    </select>
                    <label class="active" for="sizes">Tamanhos</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input id="bar_code" placeholder="Código" name="bar_code" pattern=".{3,}" title="3 letras no mínimo" value="{{ old('bar_code') }}" type="text" class="validate">
                    <label for="disabled">Código</label>
                </div>
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

            <div class="row">
                <div class=" s12 right">
                    <button class="btn-small waves-effect" type="submit">Salvar</button>
                    <a href="#!" class="modal-action modal-close  btn-small red waves-effect waves-red  ">Fechar</a>
                </div>
            </div>
            <br>
        </form>
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
        $('.materialboxed').materialbox();

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

        quantityParam();
        notifyParam();
        $('#modal').modal('open');
    }

    function closeModal() {
        this.clean();
        $('#modalDelete').modal('close');
    }

    function closeModalPhoto() {
        this.clean();
        $('#modalDeletePhoto').modal('close');
    }

    function photos(product) {
        let $url = "<?= URL::route('get_product_photos') ?>";
        $.ajax({
            type: 'POST',
            url: $url,
            data: {
                "id": product
            },
            success: function(photos) {
                $("#photosProduct").empty();
                if (photos !== undefined) {
                    photos.forEach(element => {
                        let row = createRowPhoto(element);
                        $("#photosProduct").append(row);
                    });
                }
                $('.materialboxed').materialbox();
                $("#modalPhotos").modal('open');
            },
            error: function(data) {
                closeCleanPhotoModal(id, data.responseText);
            }
        });
    }

    function cleanFields() {
        $("#name").val('');
        $("#description").val('');
        $("#bar_code").val('');
        $("#cost_value").val('0');
        $("#sale_value").val('');
        $("#quantity").val('');
        $("#days_notify").val('');
        $("#foto").val('');
        $("#foto2").val('');

        cleanCategoryField();
        cleanProviderField();
        cleanSizeField();
    }

    function clean() {
        $('#formProduct').get(0).setAttribute('method', 'POST');
        $("#product").html("Novo Produto");
        $("#idProduct").remove();
    }

    function selectCategory($category) {
        $('#categories option[value="' + $category + '"]').attr('selected', true);
        $('#categories').change();
        $('#categories').formSelect();
    }

    function selectProvider($provider) {
        $('#providers option[value="' + $provider + '"]').attr('selected', true);
        $('#providers').change();
        $('#providers').formSelect();
    }

    function selectSizes($size) {
        $('#sizes option[value="' + $size + '"]').attr('selected', true);
        $('#sizes').change();
        $('#sizes').formSelect();
    }

    function cleanCategoryField() {
        $('#categories option').prop('selected', false);
        $('#categories').change();
        $('#categories').formSelect();
    }

    function cleanProviderField() {
        $('#providers option').prop('selected', false);
        $('#providers').change();
        $('#providers').formSelect();
    }

    function cleanSizeField() {
        $('#sizes option').prop('selected', false);
        $('#sizes').change();
        $('#sizes').formSelect();
    }

    function createRowPhoto(photo) {
        let $urls3 = "<?= $urlS3 ?>";
        return $(`
            <li class="collection-item" id="photo${photo.id}">
                <div class="row" >
                    <div class="input-field col s9">
                        <img class="materialboxed responsive-img" width="450" src="${$urls3}${photo.path}"/>
                    </div>
                    <div class="input-field col s3">
                        <a class="secondary-content" onclick="askDeletePhoto(${photo.id})"><i class="red-text material-icons">delete</i></a>
                    </div>
                </div>
            </li>`);
    }

    function editProduct(product) {

        let categories = product.categories;
        if (categories !== undefined) {
            categories.forEach(element => {
                this.selectCategory(element.id);
            });
        }

        let providers = product.providers;
        if (providers !== undefined) {
            providers.forEach(element => {
                this.selectProvider(element.id);
            });
        }

        let sizes = product.sizes;
        if (sizes !== undefined) {
            sizes.forEach(element => {
                this.selectSizes(element.id);
            });
        }

        $("#idProduct").append(product['id']);
        $("#product").html("Editar Produto");
        $("#name").val(product['name']);
        $("#description").val(product['description']);
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

        quantityParam();
        notifyParam();

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

    function askDeletePhoto(id) {
        $('#modalDeletePhoto').modal('open');
        $("#deleteInputPhoto").val(id);
    }

    function closeCleanPhotoModal($data) {
        $("#indeterminate").hide();
        M.toast({
            html: $data
        }, 5000);
        $('#modalDeletePhoto').modal('close');
        $("#deleteInputPhoto").val('');
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

    function deleteproductphoto() {
        $("#indeterminate").show();
        let id = $("#deleteInputPhoto").val();
        let $url = "<?= URL::route('delete_product_photo') ?>";
        $.ajax({
            type: 'DELETE',
            url: $url,
            data: {
                "id": id
            },
            success: function(data) {
                $("#photo" + id).remove();
                closeCleanPhotoModal(data);
            },
            error: function(data) {
                closeCleanPhotoModal(data.responseText);
            }
        });
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