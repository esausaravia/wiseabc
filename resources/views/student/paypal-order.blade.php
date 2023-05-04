<div class="w-full max-w-3xl lg:w-1/2 px-3">
  <section class="shadow-md mb-8 rounded-xl border-[3px] p-4 border-azul-600 bg-white">
    <h2 class=" md:text-xl font-medium font-accent text-azul-600">¡Paga tu subscripción ahora!</h2>
    <p class="my-4">Ahora que tienes profesor y clase asignados, el siguiente paso es acreditar el pago de tu subscripción.</p>

    <div class="max-w-[400px] mx-auto">
      <div id="paypal-button-container" class="loading"><button class=" leading-8 px-3 bg-azul-700 text-white" type="button" onclick="loadPayPalSDKwithUserToken()">Cargar</button></div>
    </div>
  </section>
</div>
@push('scripts')
<script>
async function loadPayPalSDKwithUserToken(){
  let response;
  try {
    response = await axios.get(window.app.home + '/api/student/paypal/get-user-token');
  }
  catch (err) {
    console.log(err);
    return false;
  }

  let s = document.getElementById('paypalsdk');
  if ( !s || !s.src )
  {
    s = document.createElement('script');
  }
  s.setAttribute('data-user-id-token',response.data.token);
  s.defer=true;
  s.onload = renderPaypalBtns;
  s.src = 'https://www.paypal.com/sdk/js?client-id=AUnc7UCRYV0e9qHJpt9JHmTPo_u8qzwZmu9xtG48fGB6an783RCa_W2Uo4jiUH1IrtBAy0OF8jLYdwSY&vault=true';
  document.head.appendChild(s);
}

function renderPaypalBtns(){

document.getElementById('paypal-button-container').classList.remove('loading');

paypal.Buttons({
  style: {
      shape: 'pill',
      color: 'blue',
      layout: 'vertical',
      label: 'subscribe'
  },
  // Call your server to set up the transaction
  createOrder: function(data, actions) {
    return axios.post(window.app.home + '/api/student/paypal/orders',{
        source: data.paymentSource, //paypal / venmo / etc.
        billing_plan_id:1
    }).then(function(response) {
      console.log('axios then response', response);
      return response.data.id;
    });
  },
  // Authorize or capture the transaction after payer approves
  onApprove: (data, actions) => {
    console.log('onApprove', data);

    return axios.post(window.app.home + '/api/student/paypal/orders/' + data.orderID + '/capture', {
      'order_id':data.orderID
    });


    return fetch(window.app.home + '/api/student/paypal/orders/' + data.orderID + '/capture', {
      method: 'post'
    });
  },
  onCancel(data, actions) {
    console.log(`Order Canceled - ID: ${data.orderID}`);
  },
  onError(err) {
    console.error(err);
  }
}).render('#paypal-button-container');
}
</script>@endpush