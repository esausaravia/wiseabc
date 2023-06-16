import './bootstrap';
import 'lazysizes';

window.app = window.app || {};
window.app.toggleDarkTheme = function(){
  if (document.documentElement.classList.contains('dark') ) {
    localStorage.setItem('theme', 'light');
  }
  else {
    localStorage.setItem('theme', 'dark');
  }
  document.documentElement.classList.toggle('dark');
};

window.app.paypalDateRegex = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])[T,t]([0-1][0-9]|2[0-3]):[0-5][0-9]:([0-5][0-9]|60)([.][0-9]+)?([Zz]|[+-][0-9]{2}:[0-9]{2})$/i


import './modal';

window.addEventListener('DOMContentLoaded',function(){
  console.log('app.js DOMContentLoaded');

  let mydate = new Date(), mydatematch = mydate.toString().match(/([-\+][0-9]+)\s/)

  const strTimezoneOffset = mydatematch && mydatematch.length>0 ? mydatematch[1] : null

  const strTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

  (async function(){
    window.app.ipapi = await (async function() {

      if ( localStorage && localStorage.ipapi)
      {
        let ipapi = JSON.parse( localStorage.ipapi ),
            ttl = ipapi && ipapi.ttl ? new Date( ipapi.ttl ) : null;

        if ( ttl && ttl.getTime && ttl.getTime() > mydate.getTime() )
        {
          return ipapi
        }
      }

      let resp = null
      try
      {
        resp = await axios.get( window.app.home + '/country?tz='+strTimezone)
      }
      catch(e)
      {
        console.log('catch',e)
      }
      resp.data.ttl = ( new Date( mydate.getTime() + ( 60*60*1000 ) ) ).toJSON()

      resp.data.timezone = strTimezoneOffset ? strTimezoneOffset : strTimezone

      if ( localStorage && localStorage.setItem)
      {
        localStorage.setItem('ipapi', JSON.stringify(resp.data));
      }

      return resp.data
    })();
    return window.app.ipapi
  })();


  /*
  //Stripe Helper
  let searchParams = new URLSearchParams(window.location.search);
  if (searchParams.has('session_id')) {
    const session_id = searchParams.get('session_id')

    document.querySelectorAll('[name="session_id"]').forEach(function(_input){
      _input.setAttribute('value', session_id)
      _input.value = session_id
      console.log('input session_id', _input)
    })
  }
  */

  (function(scriptTags){
    if ( !scriptTags || !scriptTags.length )  return false;

    scriptTags.forEach(function(stag){
      stag.src = stag.getAttribute('data-src');
      stag.removeAttribute('data-src');
    });

  })(document.querySelectorAll('script[data-src]'));

  /**
   * Dark Theme toggler
   */
  if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark')
  } else {
    document.documentElement.classList.remove('dark')
  }

  (function(a){
    if (!a || !a.length) {
      return false;
    }
    a.forEach(function(b) {
      if (document.documentElement.classList.contains('dark')) {
        b.ariaChecked = 'true';
        b.setAttribute('checked','checked');
        b.checked = true;
      }
      b.addEventListener('click',function(){
        window.app.toggleDarkTheme();
        return false;
      });
    });
  })( document.querySelectorAll('.darkmode-toggler') );


  /**
   * Global elements
   */
  (function(btns){
    if (!btns || !btns.forEach) return false;

    btns.forEach(function(btn){
      btn.addEventListener('click', function(){
        document.getElementById('MobileMenu').classList.toggle('hidden');
      });
    });
  })(document.querySelectorAll('.btn-toggle-mobilemenu'));

  (function(alertsbtns){
    if (!alertsbtns || !alertsbtns.forEach) return false;

    alertsbtns.forEach(function(btn){
      btn.addEventListener('click', function(){
        let alert = this.parentNode.classList.contains('alert') ? this.parentElement : this.closest('.alert');
        if (!alert || !alert.remove) return false;
        alert.remove();
      });
    });
  })(document.querySelectorAll('.alert .btn-close'));

  (function(elements){
    if (!elements || !elements.forEach)  return false;

    elements.forEach(function(toappend){
      let target = document.getElementById(toappend.dataset.appendTo)

      if (!target || !target.append)  return false;

      target.appendChild( toappend )
    })

  })(document.querySelectorAll('[data-append-to]'));

  const evTabShow = new Event('tabshow');
  const evTabVisible = new Event('tabvisible');
  const evTabHide = new Event('tabhide');
  const evTabHidden = new Event('tabhidden');

  (function(tabsws){
    if (!tabsws || !tabsws.forEach) return false;

    var tabsoptions = {
      'active_classes' : ''
    };

    tabsws.forEach( function(tabsw){

      let tabs = tabsw.querySelectorAll('.tab'),
        tabpanels = tabsw.querySelectorAll('.tabpanel'),
        curtab = tabsw.querySelector('.tab.active');

      if (!curtab || !curtab.tagName) curtab = tabsw.querySelector('.tab'), curtab.classList.add('active');
      console.log('curtab', curtab);

      tabsw.dataset.activeTabClass = curtab.className;
      tabsw.dataset.inactiveTabClass = tabsw.querySelector('.tab:not(.active)').className;

      let curtabpanel = tabsw.querySelector('.tabpanel.active');
      if (!curtabpanel || !curtabpanel.tagName) curtabpanel = tabsw.querySelector('.tabpanel'), curtabpanel.classList.add('active');
      console.log('curtabpanel', curtabpanel);

      tabsw.dataset.activeTabPanelClass = curtabpanel.className;
      tabsw.dataset.inactiveTabPanelClass = tabsw.querySelector('.tabpanel:not(.active)').className;

      tabpanels.forEach(function(tabp, _idx){
        tabp.setAttribute('data-tab', _idx+1);
        tabp.setAttribute('role','tabpanel');
        if (tabp==curtabpanel) return true;
        tabp.setAttribute('aria-expanded', 'false');
        tabp.setAttribute('aria-selected', 'false');
      });

      tabs.forEach(function(tab, _idx){
        tab.setAttribute('data-tab', _idx+1);
        tab.setAttribute('role', 'tab');

        tab.addEventListener('click', function(){
          console.log('tab click', this);
          let ttabpanel = document.getElementById( this.getAttribute('aria-controls') );
          if (!ttabpanel || !ttabpanel.tagName) return false;

          tabpanels.forEach(function(tabp, _idx){
            tabp.className = tabsw.dataset.inactiveTabPanelClass;
            tabp.setAttribute('aria-expanded', 'false');
            tabp.setAttribute('aria-selected', 'false');
          });
          ttabpanel.className = tabsw.dataset.activeTabPanelClass;
          ttabpanel.setAttribute('aria-expanded', 'true');
          ttabpanel.setAttribute('aria-selected', 'true');

          tabs.forEach(function(tab){
            tab.className = tabsw.dataset.inactiveTabClass;
            tab.setAttribute('aria-expanded', 'false');
            tab.setAttribute('aria-selected', 'false');
          });
          this.className = tabsw.dataset.activeTabClass;
          this.setAttribute('aria-expanded', 'true');
          this.setAttribute('aria-selected', 'true');
        });

        if (tab==curtab) return true;
        tab.setAttribute('aria-expanded', 'false');
        tab.setAttribute('aria-selected', 'false');
      });

    });
  })(document.querySelectorAll('.tabs-widget'));

  document.querySelectorAll('.display-timezone').forEach(function(el){
    el.innerText = strTimezone
  });


  /**
   * FORMS ALL FORMS
   */
  const evFormSuccess = new Event('formsuccess');
  const evFormError = new Event('formerror');

  (function(forms){
    if (!forms || !forms.forEach)  return false;

    forms.forEach(function(form){
      /**
       * disable submit btn
       */
      form.addEventListener('submit', function(ev){
        this.querySelectorAll('[type="submit"]').forEach(function(btn){
          btn.disabled = true
          btn.setAttribute('data-submit-disabled','true')
        });
      });
      form.addEventListener('formerror', function(ev){
        this.querySelectorAll('[data-submit-disabled]').forEach(function(btn){
          btn.disabled = false
          btn.removeAttribute('data-submit-disabled')
        })
      })
    })

  })(document.querySelectorAll('form'));

  /**
   * Forms Inputs
   */
  document.querySelectorAll('select[value]').forEach(function(select){

    let selval = select.getAttribute('value'),
    opt = select.querySelector('option[value="'+ selval +'"]');

    if (opt && opt.value) {
      opt.selected = true, opt.setAttribute('selected','selected'), select.removeAttribute('value');
    }
  });

  document.querySelectorAll('input[name="password_confirmation"]').forEach(function(input){

    input.addEventListener('blur', function(){
      let pwd1 = input.form.querySelector('input[name="password"]');
      if ( pwd1.value!==input.value ) {
        input.setCustomValidity('Las contraseñas deben coincidir');
        input.reportValidity()
      }
    });

    input.addEventListener('input', function(ev){
      this.setCustomValidity('');
    });
  });

  document.querySelectorAll('input.with-img-preview[type="file"]').forEach(function(input){
    input.addEventListener('change', function(ev) {
      ([...ev.target.files]).forEach(function(_file){
        if ( /^image\//i.test(_file.type )===false ) return false;

        window.app.previewImgFile(_file, ev.target.parentElement.querySelector('img') );
      });
    });
  });

  /**
   * Validar que siempre hay 1 horario seleccionado
   */
  (function(input){
    document.querySelectorAll('input[name^="horarios\["]').forEach(function(input){
      input.addEventListener('change', function(ev){

        const _first = input.form.querySelector('input[name^="horarios"]')
        const _checked = input.form.querySelector('input[name^="horarios"]:checked')
        _first.setCustomValidity('')

        if ( !_checked || !_checked.value )
        {
          _first.setCustomValidity('Debe elegir al menos un horario disponible')
          input.reportValidity()
        }
      })
    })
  })( document.querySelector('input[name^="horarios\["]') || document.querySelector('input[name^="horarios["]') );

  /**
   * Append timezone input
   */
  (function(forms){
    if (!forms || !forms.forEach)  return false;

    forms.forEach(function(form){
      let datesAndTimes = form.querySelectorAll('input[type="date"], input[type="time"], input[type="datetime"], input[name^="horario"]')

      if (datesAndTimes.length<1){
        return false
      }

      let timeZoneInput = form.querySelector('input[name="timezone"]')
      if (!timeZoneInput || !timeZoneInput.value) {
        timeZoneInput = document.createElement('input')
        timeZoneInput.type="hidden"
        timeZoneInput.name="timezone"
        form.appendChild(timeZoneInput)
      }

      timeZoneInput.value = strTimezoneOffset || strTimezone
    });
  })(document.querySelectorAll('form'));

  /**
   * form.ajx-form
   */
  (function(forms){
    if (!forms || !forms.forEach)  return false;

    forms.forEach( function(form){
      if ( !form || !form.action )  return false;

      form.addEventListener('submit', function(ev) {
        if (ev && ev.preventDefault)  ev.preventDefault();

        let errel = form.querySelector('.alert-error');
        if (errel && errel.classList) {
          errel.classList.add('hidden');
        }

        axios({
          url:form.action,
          method: form.method || 'get',
          data: new FormData(form)
        })
        .then(function(resp){
          console.log('ajx-form success', resp);

          let rmsg = resp.data && resp.data.message ? resp.data.message : null;

          rmsg ? ( alert(rmsg), console.log('response.data.message', rmsg) ) : console.log('response.data', resp.data );

          form.dispatchEvent(evFormSuccess);

          if (resp.data && resp.data.redirect && resp.data.redirect!=="")
          {
            window.location.href = resp.data.redirect;
          }
          else if (form.dataset.redirect && form.dataset.redirect!=="") {
            window.location.href = form.dataset.redirect;
          }
        })
        .catch(function(resp){
          console.log('ajx-form catch', resp);
          form.dispatchEvent(evFormError);

          let respData = resp && resp.response && resp.response.data ? resp.response.data : null,
            errel2 = errel && errel.querySelector ? errel.querySelector(".alert-msg") : null;

          respData.message && errel2 ? (errel2.innerText = respData.message, errel.classList.remove('hidden') )
            : respData.message && errel ? ( errel.innerText = respData.message, errel.classList.remove('hidden') )
              : respData.message ? alert(respData.message)
                : null;

          if (respData && respData.errors) {
            console.log('axios.response.data.errors', respData.errors);
            for(let _key in respData.errors) {
              //console.log('resp.response.data.errors', _key, rerr[_key]);
            }
          }
        });
        return false;
      });
    });
  })(document.querySelectorAll('form.ajx-form'));

  /**
   * Stepped Forms
   */
  (function(forms){
    if (!forms || !forms.forEach)  return false;

    forms.forEach(function(form){
      let c = form.querySelector('.form-step:not(.hidden)');
      let fsteps = form.querySelectorAll('.form-step');
      fsteps.forEach(function(d, _i){
        d.setAttribute('data-step', _i+1);
        if (d!==c) {
          d.classList.add('hidden');
        }
      });
      form.querySelectorAll('.form-indicators .form-indicator').forEach(function(d, _i){
        d.setAttribute('data-step', _i+1);
        d.classList.remove('state-active');
      });

      form.querySelector('.form-indicators .form-indicator[data-step="'+ c.getAttribute('data-step') +'"]').classList.add('state-active');

      form.querySelectorAll('.btn-next').forEach(function(d){
        d.addEventListener('click',function(){
          let c = form.querySelector('.form-step:not(.hidden)'),
            invalidinput = c.querySelector(':invalid');

          if ( invalidinput && invalidinput.reportValidity ) {
            invalidinput.reportValidity();
            invalidinput.focus();
            return false;
          }

          let nn = Number( c.getAttribute('data-step') ) +1;

          c.classList.add('hidden');
          c = form.querySelector('.form-step[data-step="'+ nn.toString() +'"]');
          c.classList.remove('hidden');
          form.querySelector('.form-indicators .form-indicator.state-active').classList.remove('state-active');
          form.querySelector('.form-indicators .form-indicator[data-step="'+ nn +'"]').classList.add('state-active');
        });
      });

      form.querySelectorAll('.btn-back').forEach(function(d){
        d.addEventListener('click',function(){
          let c = form.querySelector('.form-step:not(.hidden)');
          let nn = Number( c.getAttribute('data-step') ) -1;
          let e = form.querySelector('.form-step[data-step="'+ nn.toString() +'"]');
          if (!e || !e.tagName) {
            return false;
          }

          c.classList.add('hidden');
          c = e;
          c.classList.remove('hidden');
          form.querySelector('.form-indicators .form-indicator.state-active').classList.remove('state-active');
          form.querySelector('.form-indicators .form-indicator[data-step="'+ nn +'"]').classList.add('state-active');
        });
      });//btn-back

    });//END foreach
  })(document.querySelectorAll('.form-stepped') );

  /**
   * Registro estudiante
   */
  (function(form){
    if (!form || !form.tagName)  return false;

    /*
    //form.querySelector('[type="submit"]').classList.add('hidden');//reactiva registro

    form.querySelectorAll('[name="horarios[]"]').forEach(function(_input){
      console.log('_input',_input);
      _input.addEventListener('change',function(){
        //form.querySelector('[type="submit"]').classList.add('hidden');//reactiva registro
      });
    });
    */

    document.getElementById('btn-disponibilidad').addEventListener('click', async function(){
      let claseDispEl = document.getElementById('clases-disponibles'),
          claseResultEl = claseDispEl.querySelector('.result'),
          sinClaseEl = document.getElementById('no-clases-disponibles');
      claseDispEl.classList.add('hidden'), sinClaseEl.classList.add('hidden');

      let horarios = [];
      form.querySelectorAll('[name="horarios[]"]:checked').forEach(function(_input){
        _input.checked ? horarios.push(_input.value):null;
      });

      try {
        const result = await axios.get(window.app.home + '/clases/disponibles', {
          params: {
            'html':1, 'precios':1,
            'edad': form.edad.value,
            'nivel': form.nivel.value,
            'horarios': horarios
          }
        });
        claseResultEl.innerHTML = result.data;
        if ( claseResultEl.childElementCount>0 ) {
          claseDispEl.classList.remove('hidden')
          form.querySelector('[type="submit"]').classList.remove('hidden');
        }
        else {
          sinClaseEl.classList.remove('hidden');
        }
      }
      catch(err) {
        console.log('axios catch',err);
        sinClaseEl.classList.remove('hidden');
      }
      console.log('after try-catch');
    });

  })( document.getElementById('frmRegStudent') );

});//DOMContentLoaded END

app.ajxUploadFile = async function(file) {
  let url = 'YOUR URL HERE'
  let formData = new FormData()

  formData.append('file', file)

  fetch(url, {
    method: 'POST',
    body: formData
  })
  .then(() => { /* Done. Inform the user */ })
  .catch(() => { /* Error. Inform the user */ })
}

app.previewImgFile = function(file, imgTarget) {
  if ( !file || !imgTarget || !imgTarget.tagName ) return false;
  let reader = new FileReader();
  reader.readAsDataURL(file);
  console.log('reader', typeof reader, reader);
  reader.onloadend = function() {
    console.log('reader onloadend', imgTarget, reader, arguments);
    imgTarget.src = reader.result, imgTarget.removeAttribute('srcset')
  }
  return imgTarget;
}