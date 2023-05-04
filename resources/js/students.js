
window.addEventListener('DOMContentLoaded',function(){
  console.log('students.js DOMContentLoaded');
});


window.loadPaypalSDKforSubscription = function ()
{
  const ppsbcontainer = document.getElementById('paypal-subscription-button-container');
  ppsbcontainer.classList.add('loading');
  ppsbcontainer.querySelector('.btn-load').disable = true;

  let s = document.getElementById('paypalsdk');
  if ( !s || !s.src )
  {
    s = document.createElement('script');
  }
  s.setAttribute('data-sdk-integration-source', 'button-factory');
  s.defer=true;
  s.onload = renderPaypalSubscriptionBtns;
  s.src = 'https://www.paypal.com/sdk/js?client-id=AUnc7UCRYV0e9qHJpt9JHmTPo_u8qzwZmu9xtG48fGB6an783RCa_W2Uo4jiUH1IrtBAy0OF8jLYdwSY&vault=true&intent=subscription';
  document.head.appendChild(s);
}
const renderPaypalSubscriptionBtns = function(){

  const ppsbcontainer = document.getElementById('paypal-subscription-button-container');
  ppsbcontainer.querySelector('.btn-load').remove();
  ppsbcontainer.classList.remove('loading');

  const billingPlanApiId = ppsbcontainer.dataset.billingPlanApiId;
  const billingPlanId = ppsbcontainer.dataset.billingPlanId;

  paypal.Buttons({
    style: {
        shape: 'pill',
        color: 'blue',
        layout: 'vertical',
        label: 'subscribe'
    },
    createSubscription: function(data, actions) {
      return actions.subscription.create({
        /* Creates the subscription */
        plan_id: billingPlanApiId
      });
    },
    onApprove: function(respData, actions) {

      console.log('PayPal onApprove',respData);
      /* DATA: {"orderID": "71X44664741365155",
        "subscriptionID": "I-9RC8P1B1PBVJ",
        "facilitatorAccessToken": "FACILITATORACCESSTOKEN",
        "paymentSource": "paypal"}
      */

      //alert('Subscription ID: '+data.subscriptionID); // You can add optional success message for the subscriber here

      (async function(respData){
        try
        {
          const response = await axios.post(window.app.home + '/student/subscriptions', respData);
          console.log('axios resp',response);
          document.location.reload();
          return response;
        }
        catch (err) {
          console.log('axios catch error', err);
          alert("Contáctanos con tu ID de subscripción: " + respData.subscriptionID);
          return false;
        }
      })(respData);

    }
  }).render('#paypal-subscription-button-container'); // Renders the PayPal button
}