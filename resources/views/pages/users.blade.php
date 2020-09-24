@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Usuários')



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
            <table class="table-responsive bordered centered ">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Limpar</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <form class="col s12" method="POST" action="{{ URL::route('search_users') }}">
                        <tr>
                            <td>
                                <input placeholder="Procurar" id="search_name" id="search_name" name="search_name" type="text" class="validate">
                                <label for="procurar_nome">Nome</label>
                            </td>
                            <td>
                                <input placeholder="Procurar" id="search_email" name="search_email" type="text" class="validate">
                                <label for="procurar_codigo">Email</label>
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

<ul class="collapsible collection" data-collapsible="accordion">
    @foreach ($datas as $data)
    <li id="{{$data->id}}">
        <div class="collapsible-header">
            {{$data->name}}
        </div>
        <div class="collapsible-body white">
            <div class="row ">
                <span class="span-body">
                    <span class="green-text">Email:</span>
                    {{ $data->email ==  "" ? '-' : $data->email }}
                </span></br>
            </div>
            <hr>
            <div class="row center">
                <a class="btn-small tooltipped" onclick="editUser({{$data}})" data-position='left' data-delay='50' data-tooltip="Editar Usuário" {{ ($data->id ==  Auth::user()->id) ? 'disabled' : "" }}>
                    <i class="material-icons white-text">
                        edit
                    </i>
                </a>
                <a class="btn-small tooltipped" onclick="askDelete({{$data->id}})" data-position='bottom' data-delay='50' data-tooltip="Deletar Usuário" {{ ($data->id ==  Auth::user()->id) ? 'disabled' : "" }}>
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
    <a class="btn-floating btn-large red  btn tooltipped pulse" data-background-color="red lighten-3" data-position="left" data-delay="50" data-tooltip="Criar Usuário" onclick="openModal()">
        <i class="large material-icons">add</i>
    </a>
</div>


<!-- Modal Structure -->
<div id="modalDelete" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4 class="center red-text">Deletar Usuário?</h4>
        <div class="row center">
            <input type="hidden" id="deleteInput">
            <a class="btn-flat tooltipped" onclick="deleteUser()" data-position='left' data-delay='50' data-tooltip="Sim">
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
        <h4 id="user" class="center red-text">Novo Usuário</h4>
        <form class="col s12" method="POST" action="users" id="formUser">
            <div class="row">
                <div class="input-field col s12">
                    <input id="nameUser" placeholder="Nome" name="name" type="text" class="validate">
                    <label for="disabled">Nome</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input id="emailUser" placeholder="Email" name="email" type="text" class="validate">
                    <label for="disabled">Email</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input id="passwordUser" placeholder="Senha" name="password" type="text" class="validate">
                    <label for="disabled">Senha</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <select name="type_id" id="typeUser" required>
                        <option value="1">Gerente</option>
                        <option value="2">Funcionário</option>
                    </select>
                    <label>Tipo</label>
                </div>
            </div>
    </div>

    <div class="modal-footer">
        <button class="modal-action waves-effect waves-green btn-flat " type="submit">Salvar</button>
        </form>
        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Fechar</a>
    </div>
</div>

</div>
@if( method_exists($datas,'links') )
<h1 class="center">{{$datas->links('vendor.pagination.materializecss')}}</h1>
@endif

@endsection

<script>
    function openModal() {
        this.clean();
        $('#modal').modal('open');
    }

    function closeModal() {
        this.clean();
        $('#modalDelete').modal('close');
    }

    function clean() {
        $('#formUser').get(0).setAttribute('method', 'POST');
        $("#put").remove();
        $("#idUser").remove();
        $("#nameUser").val('');
        $("#emailUser").val('');
        $("#passwordUser").val('');
        $("#passwordUser").attr('disabled', false);
        $("#required").prop('checked', false);
    }

    function editUser(user) {
        $("#idUser").append(user['id']);
        $("#user").html("Editar Usuário");
        $("#nameUser").val(user['name']);
        $("#emailUser").val(user['email']);
        $("#passwordUser").attr('disabled', true);
        $("#typeUser").val(user['type_id']);
        $("#typeUser").formSelect();
        $('<input>').attr({
            type: 'hidden',
            user_id: 'idUser',
            name: 'id',
            value: user['id']
        }).appendTo('#formUser');
        $('<input>').attr({
            id: 'put',
            type: 'hidden',
            name: '_method',
            value: 'PUT'
        }).appendTo('#formUser');
        M.updateTextFields()
        $('#modal').modal('open');
    }

    function askDelete(id) {
        $('#modalDelete').modal('open');
        $("#deleteInput").val(id);
    }


    function clearSearch() {
        $("#search_name").val('');
        $("#search_email").val('');
    }


    function deleteUser() {
        let id = $("#deleteInput").val();
        $.ajax({
            type: 'DELETE',
            url: 'users',
            data: {
                "id": id
            },
            success: function($data) {
                $("#" + id).remove();
                $('.grid').masonry('reloadItems');;
                $('.grid').masonry({
                    itemSelector: '.grid-item',
                    columnWidth: 50
                });
                M.toast({
                    html: $data
                }, 5000);
                $("#modalDelete").modal("close");
                $("#deleteInput").val('');
            }
        });
    }
</script>