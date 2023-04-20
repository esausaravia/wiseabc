window.addEventListener('DOMContentLoaded',function(){
  console.log('admin.pagos DOMContentLoaded')

  if ( document.body.classList.contains('pagos-paraprofe') ) {

    function updPaymentConceptAmount(pconcept_id, amount) {

      arrPagosPorConcepto[pconcept_id] = arrPagosPorConcepto[pconcept_id] + amount

      PaymentAmount = PaymentAmount + amount;

      document.querySelector('[data-pconcept-subtotal="'+pconcept_id+'"]').innerText = arrPagosPorConcepto[pconcept_id]
      document.querySelector('.payment-amount').innerText = PaymentAmount
      document.querySelector('input[name="amount"]').value = PaymentAmount
      return amount
    }

    function updAllPaymentConceptsAmount(){

      PaymentAmount = 0

      for( let _key in arrPagosPorConcepto )
      {
        arrPagosPorConcepto[_key] = 0;
      }

      document.querySelectorAll('[data-recibo-amount]').forEach(function(_input){
        if ( _input.disabled || ( _input.type=='checkbox' && !_input.checked ) )  return;

        let _amount = Number(_input.dataset.reciboAmount)

        arrPagosPorConcepto[( _input.dataset.pconceptId )] += _amount
        PaymentAmount += _amount
      })
      updAllPaymentConceptsHtml()
    }

    function updAllPaymentConceptsHtml(){

      for( let _key in arrPagosPorConcepto )
      {
        document.querySelector('[data-pconcept-subtotal="'+_key+'"]').innerText = arrPagosPorConcepto[_key]
      }
      document.querySelector('.payment-amount').innerText = PaymentAmount
      document.querySelector('input[name="amount"]').value = PaymentAmount

    }

    document.querySelectorAll('input[name^="attendance_pconcept["]').forEach(function(input){
      input.addEventListener('change',function(ev){

        let curCheck = ev.currentTarget
        const pconcept_id = curCheck.value
        const attendanceEl = curCheck.closest('.attendance')
        const recibo_amount = Number(curCheck.dataset.reciboAmount)

        if ( pconcept_id!=1 )
        {
          updPaymentConceptAmount( pconcept_id, ( curCheck.checked ? recibo_amount : (recibo_amount *-1) ) )
        }
        else
        {
          attendanceEl.querySelectorAll('input[name^="attendance_pconcept["]').forEach(function(_input){
            if (_input==curCheck)  return;
            _input.disabled = !curCheck.checked
          });
          updAllPaymentConceptsAmount();
        }
      });
    });

  }//FIN body.pagos-paraprofe
});