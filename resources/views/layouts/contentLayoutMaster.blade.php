{{-- pageConfigs variable pass to Helper's updatePageConfig function to update page configuration  --}}
@isset($pageConfigs)
{!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

<!DOCTYPE html>
@php
// confiData variable layoutClasses array in Helper.php file.
$configData = Helper::applClasses();
@endphp
<!--
Template Name: Materialize - Material Design Admin Template
Author: PixInvent
Website: http://www.pixinvent.com/
Contact: hello@pixinvent.com
Follow: www.twitter.com/pixinvents
Like: www.facebook.com/pixinvents
Purchase: https://themeforest.net/item/materialize-material-design-admin-template/11446068?ref=pixinvent
Renew Support: https://themeforest.net/item/materialize-material-design-admin-template/11446068?ref=pixinvent
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.

-->
<html class="loading" lang="@if(session()->has('locale')){{session()->get('locale')}}@else{{$configData['defaultLanguage']}}@endif" data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}">
<!-- BEGIN: Head-->

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title')</title>
  <link rel="apple-touch-icon" href="../../images/favicon/apple-touch-icon-152x152.png">
  <link rel="shortcut icon" type="image/x-icon" href="../../images/favicon/favicon-32x32.png">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet" type="text/css">
  <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('css/select2-materialize.css') }}" rel="stylesheet" type="text/css">

  <style>
    #main {
      background-color: #eceff1;
    }

    #modalDelete {
      max-height: 30%;
    }

    #modal {
      max-height: 70%;

    }

    .table-responsive {
      display: block;
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      -ms-overflow-style: -ms-autohiding-scrollbar;
    }

    .nav-extended {
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
    }

    .grid-item {
      width: 290px;
      margin: 5px;
    }

    .modal-content {
      height: 100% !important;
    }

    .material-tooltip {
      background-color: purple;
    }

    .caret {
      display: none;
    }

    .modal-flow {
      height: 55% !important;
    }

    #modalDelete {
      height: 25% !important;
    }

    .material-icons {
      display: inline-flex;
      vertical-align: top;
    }

    .span-body {
      margin-top: 7px;
      display: block;
    }
  </style>
  {{-- Include core + vendor Styles --}}
  @include('panels.styles')

</head>
<!-- END: Head-->

{{-- @isset(config('custom.custom.mainLayoutType'))
@endisset --}}
@if(!empty($configData['mainLayoutType']) && isset($configData['mainLayoutType']))
@include(($configData['mainLayoutType'] === 'horizontal-menu') ? 'layouts.horizontalLayoutMaster':
'layouts.verticalLayoutMaster')
@else
{{-- if mainLaoutType is empty or not set then its print below line  --}}
<h1>{{'mainLayoutType Option is empty in config custom.php file.'}}</h1>
@endif

@if ($errors->any())
<div class="alert alert-danger">
  <ul>
    @foreach ($errors->all() as $error)
    <script>
      M.toast({
        html: '{{$error}}'
      }, 5000);
    </script>
    @endforeach
  </ul>
</div>
@endif

@if(session()->has('message'))
<div class="alert alert-success">
  <script>
    M.toast({
      html: '{{ session()->get("message")}}'
    }, 5000);
  </script>
</div>
@endif

<script src="{{ asset('js/jquery.mask.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/jquery.maskMoney.js') }}"></script>

<script>
  $(document).ready(function() {
    $('.modal1').modal();
    $('#modalDelete').modal();
    $('#modalPhotos').modal();

    document.querySelectorAll('.select-wrapper').forEach(t => t.addEventListener('click', e => e.stopPropagation()))
  });

  $(".{{\Request::route()->getName()}}").addClass("active");

  $('#modal').modal({
    dismissible: false, // Modal can be dismissed by clicking outside of the modal
  });
</script>

<script>
  (function(document, $, undefined) {
    $.fn.sm_select = function(options) {
      var defaults = $.extend({
        input_text: 'Select option...',
        duration: 200,
        show_placeholder: false
      }, options);
      return this.each(function(e) {
        $(this).select2(options);
        var select_state;
        var drop_down;
        var obj = $(this);
        $(this).on('select2:open', function(e) {
          drop_down = $('body>.select2-container .select2-dropdown');
          drop_down.find('.select2-search__field').attr('placeholder', (($(this).attr('placeholder') != undefined) ?
            $(this).attr('placeholder') : defaults.input_text));
          drop_down.hide();
          setTimeout(function() {
            if (defaults.show_placeholder == false) {
              var out_p = obj.find('option[placeholder]');
              out_p.each(function() {
                drop_down.find('li:contains("' + $(this).text() + '")').css('display', 'none');
              });
            }
            drop_down.css('opacity', 0).stop(true, true).slideDown(defaults.duration, 'easeOutCubic', function() {
              drop_down.find('.select2-search__field').focus();
            }).animate({
              opacity: 1
            }, {
              queue: false,
              duration: defaults.duration
            })
          }, 10);
          select_state = true;
        });
        $(this).on('select2:closing', function(e) {
          if (select_state) {
            e.preventDefault();
            drop_down = $('body>.select2-container .select2-dropdown');
            drop_down.slideUp(defaults.duration, 'easeOutCubic', function() {
              obj.select2('close');
            }).animate({
              opacity: 0
            }, {
              queue: false,
              duration: defaults.duration,
              easing: 'easeOutSine'
            });
            select_state = false;
          }
        });
      });
    };
  })(document, jQuery);
</script>

</html>