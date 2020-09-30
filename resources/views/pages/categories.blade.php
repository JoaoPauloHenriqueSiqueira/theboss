@extends('layouts.contentLayoutMaster')


{{-- page title --}}
@section('title','Categorias')

{{-- page content --}}



@section('content')

<style>
    #modal {
        height: 30%;
    }
</style>

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
                        <th>Limpar</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <form class="col s12" method="POST" action="{{ URL::route('search_categories') }}">
                        <tr>
                            <td>
                                <input placeholder="Procurar" id="search_name" name="search_name" type="text" class="validate" value="{{Arr::get($search,'search_name')}}">
                                <label for="procurar_nome">Nome</label>
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
<h3 class="center">Não há Categorias cadastradas</h3>
@endif
<ul class="collapsible collection" data-collapsible="accordion">
    @foreach ($datas as $data)
    <li id="{{$data->id}}">
        <div class="collapsible-header">
            {{$data->name}}
        </div>
        <div class="collapsible-body white">
            <div class="row center">
                <a class="btn-small tooltipped" onclick="editCategory({{$data}})" data-position='left' data-delay='50' data-tooltip="Editar Categoria">
                    <i class="material-icons white-text">
                        edit
                    </i>
                </a>
                <a class="btn-small tooltipped red" onclick="askDelete({{$data->id}})" data-position='right' data-delay='50' data-tooltip="Deletar Categoria">
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
    <a class="btn-floating btn-large green  btn tooltipped pulse" data-background-color="red lighten-3" data-position="left" data-delay="50" data-tooltip="Criar Categoria" onclick="openModal()">
        <i class="large material-icons">add</i>
    </a>
</div>


<!-- Modal Structure -->
<div id="modalDelete" class="modal modal-fixed-footer">
    <div class="modal-content">
        <h4 class="center red-text">Deletar Categoria?</h4>
        <div class="row center">
            <input type="hidden" id="deleteInput">
            <a class="btn-flat tooltipped" onclick="deleteCategory()" data-position='left' data-delay='50' data-tooltip="Sim">
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
        <h4 id="client" class="center red-text">Nova Categoria</h4>
        <form class="col s12" method="POST" action="{{ URL::route('add_categories') }}" id="formCategory">
            <input type="hidden" id="old">
            <div class="row">
                <div class="input-field col s12">
                    <input id="name" placeholder="Nome" pattern=".{1,}" title="1 letras no mínimo" name="name" type="text" class="validate" required value="{{ old('name') }}">
                    <label for="disabled">Nome</label>
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
        M.updateTextFields();
    });

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
    }

    function clean() {
        $('#formCategory').get(0).setAttribute('method', 'POST');
        $("#client").html("Nova Categoria");
        $("#idCategory").remove();
    }

    function editCategory(category) {
        $("#idCategory").append(category['id']);
        $("#category").html("Editar Categoria");
        $("#name").val(category['name']);

        $('<input>').attr({
            type: 'hidden',
            id: 'idCategory',
            name: 'id',
            value: category['id']
        }).appendTo('#formCategory');
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
    }

    function deleteCategory() {
        let id = $("#deleteInput").val();
        let $url = "<?= URL::route('delete_categories') ?>";
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