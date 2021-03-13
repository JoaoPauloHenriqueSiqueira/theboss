@extends('layouts.contentLayoutMaster')


{{-- page content --}}
@section('content')


<ul class="collapsible" data-collapsible=" accordion">
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
            <table class="table-responsive bordered centered ">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Tel</th>
                        <th>Celular</th>
                        <th>Email</th>
                        <th>CNPJ/CPF</th>
                        <th>Endereço</th>
                        <th>Limpar</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <form class="col s12"  action="{{ URL::route('search_clients') }}">
                        <tr>
                            <td>
                                <input placeholder="Procurar" id="search_name" name="search_name" type="text" class="validate" value="{{Arr::get($search,'search_name')}}">
                                <label for="procurar_nome">Nome</label>
                            </td>
                            <td>
                                <input placeholder="Procurar" id="search_phone" name="search_phone" type="text" class="validate" value="{{Arr::get($search,'search_phone')}}">
                                <label for="procurar_telefone">Telefone</label>
                            </td>
                            <td>
                                <input placeholder="Procurar" id="search_cell_phone" name="search_cell_phone" type="text" class="validate" value="{{Arr::get($search,'search_cell_phone')}}">
                                <label for="procurar_celular">Celular</label>
                            </td>
                            <td>
                                <input placeholder="Procurar" id="search_email" name="search_email" type="text" class="validate" value="{{Arr::get($search,'search_email')}}">
                                <label for="procurar_email">Email</label>
                            </td>
                            <td>
                                <input placeholder="Procurar" id="search_cpf_cnpj" name="search_cpf_cnpj" type="text" class="validate" value="{{Arr::get($search,'search_cpf_cnpj')}}">
                                <label for="procurar_cnpj_cpf">CNPJ/CPF</label>
                            </td>
                            <td>
                                <input placeholder="Procurar" id="search_address" name="search_address" type="text" class="validate" value="{{Arr::get($search,'search_address')}}">
                                <label for="procurar_endereco">Endereço</label>
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
<h3 class="center">Não há clientes cadastrados</h3>
@endif
<ul class="collapsible collection" data-collapsible="accordion">
    @foreach ($datas as $data)
    <li id="{{$data->id}}">
        <div class="collapsible-header">
            <i class="material-icons">
                {{ $data->type_id ==  1 ? 'store' : 'account_circle' }}
            </i>
            {{$data->name}}
        </div>
        <div class="collapsible-body white">
            <div class="row ">
                <span class="span-body">
                    <span class="green-text">Telefone</span>
                    {{ $data->phone ==  "" ? '-' : $data->phone }}
                </span></br>
                <span class="span-body">
                    <span class="green-text">Celular</span>
                    {{ $data->cell_phone ==  "" ? '-' : $data->cell_phone }}
                </span></br>
                <span class="span-body">
                    <span class="green-text">Email</span>
                    {{ $data->email ==  "" ? '-' : $data->email }}
                </span></br>
                <span class="span-body">
                    <span class="green-text">Endereço</span>
                    {{ $data->address ==  "" ? '-' : $data->address }}
                </span></br>
                <span class="span-body">
                    <span class="green-text">CNPJ/CPF</span>
                    {{ $data->cpf_cnpj ==  "" ? '-' : $data->cpf_cnpj }}
                </span></br>
                <span class="span-body">
                    <span class="green-text">Permite notificar futuros atendimentos?</span>
                    {{ $data->notifiable ==  1 ? 'Sim' : 'Não' }}
                </span>
            </div>
            <hr>
            <div class="row center">
                <a class="btn-small tooltipped" onclick="editClient({{$data}})" data-position='left' data-delay='50' data-tooltip="Editar Cliente">
                    <i class="material-icons white-text">
                        edit
                    </i>
                </a>
                <a class="btn-small tooltipped red" onclick="askDelete({{$data->id}})" data-position='right' data-delay='50' data-tooltip="Deletar Cliente">
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
    <a class="btn-floating btn-large green  btn tooltipped pulse" data-background-color="red lighten-3" data-position="left" data-delay="50" data-tooltip="Criar Cliente" onclick="openModal()">
        <i class="large material-icons">add</i>
    </a>
</div>


<!-- Modal Structure -->
<div id="modalDelete" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4 class="center red-text">Deletar Cliente?</h4>
        <div class="row center">
            <input type="hidden" id="deleteInput">
            <a class="btn-flat tooltipped" onclick="deleteClient()" data-position='left' data-delay='50' data-tooltip="Sim">
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
    <div class="modal-content">
        <h4 id="client" class="center red-text">Novo Cliente</h4>
        <form class="col s12" method="POST" action="{{ URL::route('make_client') }}" id="formClient">
            <input type="hidden" id="old">
            <div class="row">
                <div class="input-field col s12">
                    <input id="name" placeholder="Nome" pattern=".{3,}" title="3 letras no mínimo" name="name" type="text" class="validate" required value="{{ old('name') }}">
                    <label for="disabled">Nome</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input id="cpf_cnpj" value="{{ old('cpf_cnpj') }}" placeholder="CNPJ/CPF" pattern=".{14,19}" title="11 dígitos no mínimo e 14 dígitos no máximo" name="cpf_cnpj" type="text" class="validate" onkeypress="validCnpjCpf()" min="10">
                    <label for=" disabled">CNPJ/CPF</label>
                </div>
            </div>

            <div class="row">
                <p>
                    <label>
                        <input type="checkbox" id="notifiable" name="notify" checked>
                        <span> Notificar próximos atendimentos</span>
                    </label>
                </p>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input id="cell_phone" value="{{ old('cell_phone') }}" placeholder="Celular" pattern=".{15,17}" title="11 dígitos requeridos" name="cell_phone" type="text" class="validate">
                    <label for="disabled">Celular</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input id="phone" value="{{ old('phone') }}" placeholder="Telefone Fixo" name="phone" type="text" pattern=".{14,17}" title="10 dígitos requeridos" class="validate">
                    <label for="disabled">Telefone Fixo</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input id="address" value="{{ old('address') }}" placeholder="Endereço" name="address" pattern=".{5,100}" title="5 dígitos requeridos" type="text" class="validate">
                    <label for="disabled">Endereço</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input id="email" value="{{ old('email') }}" placeholder="Email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" type="text" class="validate">
                    <label for="disabled">Email</label>
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
    <h1 class="center">{{$datas->links('vendor.pagination.materializecss')}}</h1>
</div>

@endif
@endsection

<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script>
    $(document).ready(function() {
        maskFields();
        M.updateTextFields();

        $notify = "<?= old('notify') ?>";
        if ($notify == "on") {
            $("#notifiable").prop('checked', true);
        } else {
            $("#notifiable").prop('checked', false);
        }

        $old = "<?= old('name') ?>";
        if ($old != "") {
            $("#old").val(1);
            openModal();
        }
    });

    function maskFields() {
        $('#phone').mask('(00) 0000-0000');
        $('#cell_phone').mask('(00) 00000-0000');
    }

    function validCnpjCpf() {
        let cpf = $('#cpf_cnpj').val()
        var masks = ['000.000.000-000', '00.000.000/0000-00'];
        $('#cpf_cnpj').mask((cpf.length > 14) ? masks[1] : masks[0]);
    }

    function openModal() {
        if ($("#old").val() != 1) {
            this.clean();
            this.cleanFields();
        } else {
            $("#old").val(0);
        }


        $('#modal').modal('open');
    }

    function closeModal() {
        this.clean();
        $('#modalDelete').modal('close');
    }

    function cleanFields() {
        $("#name").val('');
        $("#cpf_cnpj").val('');
        $("#phone").val('');
        $("#cell_phone").val('');
        $("#address").val('');
        $("#email").val('');
        $("#notifiable").prop('checked', true);
    }

    function clean() {
        $('#formClient').get(0).setAttribute('method', 'POST');
        $("#client").html("Novo Cliente");
        $("#idClient").remove();
    }

    function editClient(client) {
        $("#idClient").append(client['id']);
        $("#client").html("Editar Cliente");
        $("#name").val(client['name']);
        $("#cpf_cnpj").val(client['cpf_cnpj']);
        $("#phone").val(client['phone']);
        $("#cell_phone").val(client['cell_phone']);
        $("#address").val(client['address']);
        $("#email").val(client['email']);
        maskFields();
        validCnpjCpf();
        notifiable = client['notifiable'];
        if (notifiable) {
            $("#notifiable").prop('checked', true);
        } else {
            $("#notifiable").prop('checked', false);
        }
        $('<input>').attr({
            type: 'hidden',
            id: 'idClient',
            name: 'id',
            value: client['id']
        }).appendTo('#formClient');
        M.updateTextFields();
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
        $("#search_phone").val('');
        $("#search_cell_phone").val('');
        $("#search_email").val('');
        $("#search_cpf_cnpj").val('');
        $("#search_address").val('');
    }

    function deleteClient() {
        let id = $("#deleteInput").val();
        let $url = "<?= URL::route('delete_clients') ?>";
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