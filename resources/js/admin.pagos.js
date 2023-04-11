window.addEventListener('DOMContentLoaded',function(){
  console.log('admin.pagos DOMContentLoaded');

  if ( document.body.classList.contains('pagos-paraprofe') ) {

    function updPaymentConceptAmount(pconcept_id, amount) {

      document.querySelector('[data-pconcept-subtotal="'+pconcept_id+'"]').innerText = amount;
      document.querySelector('.payment-amount').innerText = PaymentAmount;
      document.querySelector('input[name="amount"]').value = PaymentAmount;
      return amount;
    }

    function calcPaymentTotal(){
      let subtotal = 0;
      document.querySelectorAll('[data-pconcept-subtotal]').forEach((elem)=>{
        subtotal += Number(elem.innerText);
      });
      document.querySelector('.payment-amount').innerText = subtotal;
    }

    document.querySelectorAll('input[name^="attendance_pconcept["]').forEach(function(input){
      input.addEventListener('change',function(ev){

        let curCheck = ev.currentTarget;
        const pconcept_id = curCheck.value;
        const is_checked = curCheck.checked;
        const attendanceEl = curCheck.closest('.attendance');
        const attendance_id = attendanceEl.dataset.attendanceId;

        const pconcept_amount = Number(attendanceEl.querySelector('[name="attendance_pconcept_'+attendance_id+'_'+pconcept_id+'"]').value);

        if ( curCheck.checked )
        {
          PaymentAmount = PaymentAmount + pconcept_amount;
          arrPagosPorConcepto[pconcept_id] = arrPagosPorConcepto[pconcept_id] + pconcept_amount;
        }
        else
        {
          PaymentAmount = PaymentAmount - pconcept_amount;
          arrPagosPorConcepto[pconcept_id] = arrPagosPorConcepto[pconcept_id] - pconcept_amount;
        }
        updPaymentConceptAmount( pconcept_id, arrPagosPorConcepto[pconcept_id] );

        if ( pconcept_id==1 ) {
          attendanceEl.querySelectorAll('input[name^="attendance_pconcept["]').forEach(function(_input){
            if (_input==curCheck)  return true;

            _input.disabled = !is_checked;
            _input.checked = is_checked;

            let myev = new Event('change');
            _input.dispatchEvent(myev)

          });
        }
      });
    });

  }//FIN body.pagos-paraprofe
});