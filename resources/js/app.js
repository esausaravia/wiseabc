import './bootstrap';
import 'lazysizes';
import {library, dom} from '@fortawesome/fontawesome-svg-core';
import {faBars, faTimes} from '@fortawesome/pro-regular-svg-icons';
import {faFacebookF, faTwitter} from '@fortawesome/free-brands-svg-icons';

library.add(faBars, faTimes, faFacebookF,faTwitter);

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

window.addEventListener('DOMContentLoaded',function(){
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

  //const faCss = dom.css();
  //dom.insertCss(faCss);
  dom.watch();

  (function(btns){
    if (!btns || !btns.forEach) return false;

    btns.forEach(function(btn){
      btn.addEventListener('click', function(){
        document.getElementById('MobileMenu').classList.toggle('hidden');
      });
    });
  })(document.querySelectorAll('.btn-toggle-mobilemenu'));

  (function(forms){
    if (!forms || !forms.forEach) {
      return false;
    }
    forms.forEach(function(form){
      form.addEventListener('submit',function(ev){
        this.querySelectorAll('[type="submit"]').forEach(function(btn){
          btn.disabled = true;
        });
      });
    });
  })(document.querySelectorAll('form'));

  const evFormSuccess = new Event('formsuccess');
  (function(forms){
    if (!forms || !forms.forEach) {
      return false;
    }
    forms.forEach(function(form){
      if (!form.action ) {
        return false;
      }
      form.addEventListener('submit',function(ev){
        if (ev && ev.preventDefault) {
          ev.preventDefault();
        }
        let errel = form.querySelector('.alert-error');
        if (errel && errel.classList) {
          errel.classList.add('hidden');
        }
        axios({
          url:form.action,
          method: form.method ? form.method : 'get',
          data: new FormData(form)
        })
        .then(function(resp){
          console.log('ajx-form success', resp);

          form.querySelectorAll('[type="submit"]').forEach(function(btn){
            btn.disabled = false;
          });

          let rd = resp && resp.data ? resp.data : null,
          rmsg = rd && rd.message ? rd.message : null;

          rmsg ? ( alert(rmsg), console.log('response.data.message', rmsg) ) : console.log('response.data', rd);

          if ( rd && rd.redirect ) location.href = rd.redirect;

          form.dispatchEvent(evFormSuccess);
        })
        .catch(function(resp){
          console.log('ajx-form catch', resp);

          form.querySelectorAll('[type="submit"]').forEach(function(btn){
            btn.disabled = false;
          });

          let resp2 = resp.response || null,
            rd = resp2 && resp2.data ? resp2.data : null,
            rerr = rd && rd.errors ? rd.errors: null,
            rmsg = rd && rd.message ? rd.message : null,
            errel2 = errel && errel.querySelector ? errel.querySelector(".alert-msg") : null;

          rmsg && errel2 ? (errel2.innerText = rmsg, errel.classList.remove('hidden') )
            : rmsg && errel ? ( errel.innerText=rmsg, errel.classList.remove('hidden') )
              : rmsg ? console.log('response.data.message', rmsg)
                : null;

          if (rerr) {
            for(let _key in rerr) {
              console.log('resp.response.data.errors', _key, rerr[_key]);
            }
          }
        });
        return false;
      });
    });
  })(document.querySelectorAll('form.ajx-form'));

  (function(a){

    if (!a || !a.length) {
      return false;
    }
    a.forEach(function(b){
      let c = b.querySelector('.form-step:not(.hidden)');
      let fsteps = b.querySelectorAll('.form-step');
      fsteps.forEach(function(d, _i){
        d.setAttribute('data-step', _i+1);
        if (d!==c) {
          d.classList.add('hidden');
        }
      });
      b.querySelectorAll('.form-indicators .form-indicator').forEach(function(d, _i){
        d.setAttribute('data-step', _i+1);
        d.classList.remove('state-active');
      });

      b.querySelector('.form-indicators .form-indicator[data-step="'+ c.getAttribute('data-step') +'"]').classList.add('state-active');

      b.querySelectorAll('.btn-next').forEach(function(d){
        d.addEventListener('click',function(){
          let b = this.closest('.form-stepped'),
            c = b.querySelector('.form-step:not(.hidden)'),
            invalidinput = c.querySelector(':invalid');

          if ( invalidinput && invalidinput.reportValidity ) {
            invalidinput.reportValidity();
            invalidinput.focus();
            return false;
          }

          let nn = Number( c.getAttribute('data-step') ) +1;

          c.classList.add('hidden');
          c = b.querySelector('.form-step[data-step="'+ nn.toString() +'"]');
          c.classList.remove('hidden');
          b.querySelector('.form-indicators .form-indicator.state-active').classList.remove('state-active');
          b.querySelector('.form-indicators .form-indicator[data-step="'+ nn +'"]').classList.add('state-active');
        });
      });

      b.querySelectorAll('.btn-back').forEach(function(d){
        d.addEventListener('click',function(){
          let b = this.closest('.form-stepped');
          let c = b.querySelector('.form-step:not(.hidden)');
          let nn = Number( c.getAttribute('data-step') ) -1;
          let e = b.querySelector('.form-step[data-step="'+ nn.toString() +'"]');
          if (!e || !e.tagName) {
            return false;
          }

          c.classList.add('hidden');
          c = e;
          c.classList.remove('hidden');
          b.querySelector('.form-indicators .form-indicator.state-active').classList.remove('state-active');
          b.querySelector('.form-indicators .form-indicator[data-step="'+ nn +'"]').classList.add('state-active');
        });
      });
    });
  })(document.querySelectorAll('.form-stepped') );

  (function(inputs){
    inputs.forEach(function(input){
      input.value = Intl.DateTimeFormat().resolvedOptions().timeZone;
    })
  })(document.querySelectorAll('input[name="jstimezone"]'));

  (function(elements) {
    const myd = new Date();
    let _arr = myd.toTimeString().split(' ');
    _arr.shift();
    let display = _arr.join(' ');

    (function(inputs){
      inputs.forEach(function(input){
        input.value = myd.getTimezoneOffset();
      })
    })(document.querySelectorAll('input[name="jsTimezoneOffset"]'));

    (function(inputs){
      inputs.forEach(function(input){
        input.value = display;
      })
    })(document.querySelectorAll('input[name="date_toTimeString"]'));

    display = Intl.DateTimeFormat().resolvedOptions().timeZone + ' ' +display;

    elements.forEach(function(el){
      el.innerText = display;
    });

  })(document.querySelectorAll('.display-timezone'));

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
});