@extends('layouts.contentLayoutMaster')

@section('content')


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
                <a class="btn-small tooltipped red" onclick="askDelete({{$data->id}})" data-position='bottom' data-delay='50' data-tooltip="Deletar Usuário" {{ ($data->id ==  Auth::user()->id) ? 'disabled' : "" }}>
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
    <a class="btn-floating btn-large green  btn tooltipped pulse" data-background-color="red lighten-3" data-position="left" data-delay="50" data-tooltip="Criar Usuário" onclick="openModal()">
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
            <input type="hidden" id="old">
            <div class="row">
                <div class="input-field col s12">
                    <input id="nameUser" placeholder="Nome" pattern=".{1,}" title="1 letra no mínimo" name="name" type="text" class="validate" required value="{{ old('name') }}">
                    <label for="disabled">Nome</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input id="emailUser" placeholder="Email" pattern=".{7,}" title="7 letras no mínimo" name="email" type="text" class="validate" required value="{{ old('email') }}">
                    <label for="disabled">Email (utilizado para acessar</label>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input id="passwordUser" placeholder="Senha" pattern=".{8,}" title="8 letras no mínimo" name="password" type="text" class="validate">
                    <label for="disabled">Senha</label>
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
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $old = "<?= old('name') ?>";
        if ($old != "") {
            $("#old").val(1);
            openModal();
        }
    });

    function openModal() {
        if ($("#old").val() != 1) {
            this.clean();
        } else {
            $("#old").val(0);
        }
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
        $('<input>').attr({
            type: 'hidden',
            id: 'idUser',
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

    function closeCleanModal(id, $data) {
        $("#" + id).remove();
        M.toast({
            html: $data
        }, 5000);
        $("#modalDelete").modal("close");
        $("#deleteInput").val('');
    }

    function deleteUser() {
        let id = $("#deleteInput").val();
        $.ajax({
            type: 'DELETE',
            url: 'users',
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