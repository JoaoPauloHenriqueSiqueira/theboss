@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Efetuar pagamento')

@section('content')

<style>

</style>

<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="section">
                    <div class="summary">
                    <h3>Cart</h3>
                    <div class="summary-item"><span class="text">Subtotal</span><span class="price" id="cart-total"></span></div>
                    <button class="btn btn-primary btn-lg btn-block" id="checkout-btn">Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
   
    <script>
//Handle call to backend and generate preference.
document.getElementById("checkout-btn").addEventListener("click", function() {

$('#checkout-btn').attr("disabled", true);

var orderData = {
  quantity: 1,
  description: "Mensalidade",
  price: 150
};
let $url = "<?= URL::route('create-preference') ?>";

    fetch($url, {
            
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(orderData),
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(preference) {
        createCheckoutButton(preference.id);
        $(".shopping-cart").fadeOut(500);
        setTimeout(() => {
            $(".container_payment").show(500).fadeIn();
        }, 500);
    })
    .catch(function() {
        alert("Unexpected error");
        $('#checkout-btn').attr("disabled", false);
    });
});

//Create preference when click on checkout button
function createCheckoutButton(preference) {
var script = document.createElement("script");

// The source domain must be completed according to the site for which you are integrating.
// For example: for Argentina ".com.ar" or for Brazil ".com.br".
script.src = "https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.js";
script.type = "text/javascript";
script.dataset.preferenceId = preference;
document.getElementById("button-checkout").innerHTML = "";
document.querySelector("#button-checkout").appendChild(script);
}
    </script>


                </div>
                <div class="content-overlay"></div>
            </div>
        </div>
    </div>
</div>


@endsection
<script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
