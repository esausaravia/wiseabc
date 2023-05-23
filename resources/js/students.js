
window.addEventListener('DOMContentLoaded',function(){
  console.log('students.js DOMContentLoaded');

  (function(ppl){

    loadPaypalSDKforSubscription(ppl);

  })(document.querySelector('.paypal[data-paypal-plan-id], .paypal-container[data-paypal-plan-id]'))
});


window.loadPaypalSDKforSubscription = function ()
{
  console.log('loadPaypalSDKforSubscription ', arguments)

  let s = document.getElementById('paypalsdk')
  if ( !s || !s.src )
  {
    s = document.createElement('script')
  }
  s.defer=true
  s.onload = renderPaypalSubscriptionBtns
  s.src = 'https://www.paypal.com/sdk/js?client-id=AUnc7UCRYV0e9qHJpt9JHmTPo_u8qzwZmu9xtG48fGB6an783RCa_W2Uo4jiUH1IrtBAy0OF8jLYdwSY&components=buttons&vault=true&intent=subscription'
  document.head.appendChild(s)

  let ppcontainer = arguments[0]
  if ( ppcontainer && ppcontainer.classList && !ppcontainer.classList.contains('paypal-container') )
  {
    ppcontainer = ppcontainer.closest('.paypal-container')
  }
  if ( ppcontainer && ppcontainer.classList )
  {
    ppcontainer.classList.add('loading')
  }
}
const renderPaypalSubscriptionBtns = function(){

  document.querySelectorAll('.paypal[data-paypal-plan-id]').forEach(function(ppsbcontainer){

    const paypalPlanId = ppsbcontainer.dataset.paypalPlanId
    let subscriptionQty = ppsbcontainer.dataset.quantity
    let subscriptionStart = ppsbcontainer.dataset.start || null

    ppsbcontainer.classList.remove('loading')

    if (ppsbcontainer.id !==('paypal-'+paypalPlanId) )
    {
      ppsbcontainer.id = 'paypal-'+paypalPlanId
    }
    if ( isNaN(subscriptionQty) || Number(subscriptionQty)==0 )
    {
      subscriptionQty = 4
    }
    if ( subscriptionStart=="" || window.app.paypalDateRegex.test(subscriptionStart)==false )
    {
      subscriptionStart = null
    }

    paypal.Buttons({
      style: {
          shape: 'pill',
          color: 'blue',
          layout: 'vertical',
          label: 'subscribe'
      },
      createSubscription: function(data, actions) {
        let subscriptionData = {
          plan_id: paypalPlanId,
          quantity: subscriptionQty, // The quantity of the product for a subscription
          start_time: subscriptionStart,
          application_context:{shipping_preference:"NO_SHIPPING"}
        };
        console.log('subscriptionData',subscriptionData);
        return actions.subscription.create(subscriptionData);
      },
      onInit(data,actions){
        console.log('paypal.Buttons onInit data', data)
        console.log('paypal.Buttons onInit actions', actions)
      },
      onApprove: async function(respData, actions) {
        console.log('PayPal onApprove data ',respData);
        console.log('PayPal onApprove actions ',actions);
        /* DATA: {"orderID": "71X44664741365155",
          "subscriptionID": "I-9RC8P1B1PBVJ",
          "facilitatorAccessToken": "FACILITATORACCESSTOKEN",
          "paymentSource": "paypal"}
        */

        //alert('Subscription ID: '+data.subscriptionID); // You can add optional success message for the subscriber here

        respData.api = 'paypal';
        try
        {
          const response = await axios.post(window.app.home + '/student/subscriptions', respData);
          console.log('axios resp',response);
          //document.location.reload();
          return response;
        }
        catch (err) {
          console.log('axios catch error', err);
          alert("Contáctanos con tu ID de subscripción: " + respData.subscriptionID);
          return false;
        }
      }
    }).render('#'+ppsbcontainer.id); // Renders the PayPal button


  });
}