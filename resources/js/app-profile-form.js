import intlTelInput from 'intl-tel-input'

const itiErrorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"]

const resetInputState = (input) => {

  if ( input.currentTarget || input.target || input.srcElement ) {
    input = input.currentTarget || input.target || input.srcElement
  }

  if ( input.setCustomValidity ) {
    input.setCustomValidity("")
  }

  input.classList.remove('border-rose-600')
  input.classList.remove('bg-rose-200')
  input.classList.remove('text-rose-600')
}

window.addEventListener('DOMContentLoaded', function() {

  document.querySelectorAll('input[name="phone"], input[type="tel"]').forEach( function(input){

    input.__iti = intlTelInput( input, {
      utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js",
      separateDialCode: true,
      hiddenInput: "tel",
    });

    input.addEventListener('blur', function(ev) {

      let input = ev.currentTarget || ev.target || ev.srcElement;

      resetInputState(input);

      if (input.value.trim() && input.__iti && input.__iti.isValidNumber) {

        if ( input.__iti.isValidNumber()) {
          //validMsg.classList.remove("hide");
        } else {
          //input.classList.add("error");
          const errorCode = input.__iti.getValidationError();
          input.setCustomValidity( itiErrorMap[errorCode] )
        }

        let countryData = input.__iti.getSelectedCountryData();
        if ( countryData && countryData.name ) {
          document.querySelector('input[name="country"]').value = countryData.name
        }
      }
    });

    input.addEventListener('change', resetInputState);
    input.addEventListener('keyup', resetInputState)
  });

  document.querySelectorAll('[data-clase-tipo][data-ritmo]').forEach(function (btn) {
    btn.addEventListener('click', function (ev) {
      if ( !ev.currentTarget || !ev.currentTarget.form )  return;

      ev.currentTarget.form.querySelector('input[name="clase_tipo"]').value = ev.currentTarget.dataset.claseTipo;
      ev.currentTarget.form.querySelector('input[name="ritmo"]').value = ev.currentTarget.dataset.ritmo;

      ev.currentTarget.form.dispatchEvent(new Event('submit'));
      ev.currentTarget.form.submit();
    });
  });
});