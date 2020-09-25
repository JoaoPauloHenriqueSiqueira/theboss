{{-- layout --}}
@extends('layouts.fullLayoutMaster')

{{-- page title --}}
@section('title','Cadastro')

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/register.css')}}">
@endsection
<link href="{{ asset('css/mstepper.min.css') }}" rel="stylesheet" type="text/css">

{{-- page content --}}

@section('content')

<div id="register-page" class="row">
  <div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 register-card bg-opacity-8">
    <div class="card">
      <div class="card-content pb-0">
        <div class="row">
          <div class="input-field col s12">
            <h5 class="ml-4 center">{{ __('Cadastro') }}</h5>
          </div>
        </div>
        <form class="login-form" method="POST" action="{{ route('register') }}">
          @csrf
          <ul class="stepper linear" id="horizStepper">
            <li class="step done">
              <div class="step-title waves-effect">Passo 1</div>
              <div class="step-content">
                <div class="row">
                  <div class="input-field col s12">
                    <label for="cnpj" class="active">CNPJ</label>
                    <input type="text" id="cnpj" class="validate valid" value="{{ old('cnpj') }}" name="cnpj" onfocusout="consultCNPJ()">
                  </div>
                  <div class="input-field col s12">
                    <label for="company_name" class="active">Nome da empresa: <span class="red-text">*</span></label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" class="validate valid" required="">
                  </div>
                  <div class="input-field col s12">
                    <label for="phone" class="active">Telefone <span class="red-text">*</span></label>
                    <input type="text" id="phone" value="{{ old('phone') }}" class="validate valid" name="phone" required="">
                  </div>
                </div>
                <div class="step-actions">
                  <div class="row">
                    <div class="col m4 s12 mb-3">
                      <button class="red btn btn-reset" type="reset">
                        <i class="material-icons center">clear</i>
                      </button>
                    </div>
                    <div class="col m4 s12 mb-3">
                      <button class="btn btn-light previous-step" disabled="">
                        <i class="material-icons left">arrow_back</i>
                      </button>
                    </div>
                    <div class="col m4 s12 mb-3">
                      <button class="waves-effect waves dark btn btn-primary next-step" type="submit">
                        <i class="material-icons right">arrow_forward</i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li class="step active">
              <div class="step-title waves-effect">Passo 2</div>
              <div class="step-content">
                <div class="row">
                  <div class="input-field col s12">
                    <label for="username" class="active">Nome de usuário: <span class="red-text">*</span></label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" class="validate valid" required="">
                  </div>

                  <div class="input-field col s12">
                    <label for="email" class="active">Email (utilizado para acesso): <span class="red-text">*</span></label>
                    <input type="text" id="email" name="email" value="{{ old('email') }}" class="validate valid" required="">
                  </div>

                  <div class="input-field col m6 s12">
                    <label for="password" class="active">Senha (utilizado p/ acesso): <span class="red-text">*</span></label>
                    <input type="password" class="validate valid" id="password" name="password" required="">
                  </div>

                  <div class="input-field col m6 s12">
                    <label for="confirm_password" class="active">Confirmação de Senha: <span class="red-text">*</span></label>
                    <input type="password" class="validate valid" id="confirm_password" name="password_confirmation" required="">
                  </div>

                </div>
                <div class="step-actions">
                  <div class="row">
                    <div class="col m4 s12 mb-3">
                      <button class="red btn btn-reset" type="reset">
                        <i class="material-icons center">clear</i>Reset
                      </button>
                    </div>
                    <div class="col m4 s12 mb-3">
                      <button class="btn btn-light previous-step">
                        <i class="material-icons left">arrow_back</i>
                      </button>
                    </div>
                    <div class="col m4 s12 mb-3">
                      <button class="waves-effect waves dark btn btn-primary next-step" type="submit">
                        <i class="material-icons right">arrow_forward</i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </li>

            <li class="step">
              <div class="step-title waves-effect waves-dark">Passo 3</div>
              <div class="step-content" style="">
                <div class="row">
                  <div class="input-field col s12">
                    <button type="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">Cadastrar</button>
                  </div>
                </div>

                <div class="step-actions">
                  <div class="row">
                    <div class="col m6 s12 mb-6">
                      <button class="red btn btn-reset" type="reset">
                        <i class="material-icons center">clear</i>Reset
                      </button>
                    </div>
                    <div class="col m6 s12 mb-6">
                      <button class="btn btn-light previous-step">
                        <i class="material-icons left">arrow_back</i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="input-field col s12">
        <p class="margin medium-small"><a href="{{ route('login')}}">Já possui uma conta?</a></p>
      </div>
    </div>
  </div>
</div>


<!-- <div id="register-page" class="row">
  <div class="col s12 m6 l4 z-depth-4 card-panel border-radius-6 register-card bg-opacity-8">
    <form class="login-form" method="POST" action="{{ route('register') }}">
      @csrf
      <div class="row">
        <div class="input-field col s12">
          <h5 class="ml-4 center">Cadastre-se</h5>
        </div>
      </div>

      <div class="row margin">
        <div class="input-field col s12">
          <i class="material-icons prefix pt-2">store</i>
          <input id="name" type="text" class="@error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" required autocomplete="company_name" autofocus>
          <label for="name" class="center-align">Nome da empresa</label>
          @error('company_name')
          <small class="red-text ml-10" role="alert">
            {{ $message }}
          </small>
          @enderror
        </div>
      </div>

      <div class="row margin">
        <div class="input-field col s12">
          <i class="material-icons prefix pt-2">phone</i>
          <input id="phone" type="text" class="@error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" autofocus>
          <label for="phone" class="center-align">Telefone</label>
          @error('phone')
          <small class="red-text ml-10" role="alert">
            {{ $message }}
          </small>
          @enderror
        </div>
      </div>

      <div class="row margin">
        <div class="input-field col s12">
          <i class="material-icons prefix pt-2">mail_outline</i>
          <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
          <label for="email">Email</label>
          @error('email')
          <small class="red-text ml-10" role="alert">
            {{ $message }}
          </small>
          @enderror
        </div>
      </div>
      <div class="row margin">
        <div class="input-field col s12">
          <i class="material-icons prefix pt-2">lock_outline</i>
          <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
          <label for="password">Password</label>
          @error('password')
          <small class="red-text ml-10" role="alert">
            {{ $message }}
          </small>
          @enderror
        </div>
      </div>
      <div class="row margin">
        <div class="input-field col s12">
          <i class="material-icons prefix pt-2">lock_outline</i>
          <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
          <label for="password-confirm">Password again</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <button type="submit" class="btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12">Register</button>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <p class="margin medium-small"><a href="{{ route('login')}}">Already have an account? Login</a></p>
        </div>
      </div>
    </form>
  </div>
</div> -->
@endsection

<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/mstepper.min.js') }}"></script>
<script src="{{ asset('js/materialize.js') }}"></script>


<script>
  $(document).ready(function() {
    maskFields();
    var stepper = document.querySelector('.stepper');
    var stepperInstace = new MStepper(stepper, {
      // options
      firstActive: 0 // this is the default
    })
  });

  function consultCNPJ() {
    let $cnpj = $("#cnpj").val();
    var $num = $cnpj.replace(/[^0-9]/g, '');

    if ($num.length == 14) {
      let $url = `https://www.receitaws.com.br/v1/cnpj/${$num}`;
      $.ajax({
        type: 'GET',
        url: $url,
        dataType: "jsonp",
        success: function(data) {
          $("#company_name").val(data.nome);
          $('#phone').val(data.telefone);
          M.updateTextFields()
        },
        error: function(data) {}
      });
    }
  }

  function maskFields() {
    $('#phone').mask('(00) 00000-0000');
    $('#cnpj').mask('00.000.000/0000-00');

  }
</script>