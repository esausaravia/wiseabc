
const evModalShow = new Event('modal.show');
const evModalVisible = new Event('modal.visible');
const evModalHide = new Event('modal.hide');
const evModalHidden = new Event('modal.hidden');

window.addEventListener('DOMContentLoaded', function(){

  (function(modals){
    if (!modals || !modals.length || modals.length<1 )  return false;



    modals.forEach((modal)=>{
      if (!modal || !modal.querySelector)  return false;

      modal.addEventListener('click', (ev)=>{
        console.log('click',ev);
        const _modal = document.getElementById(ev.target.dataset.target);
        if ( _modal && _modal.querySelector )  _modal.dispatchEvent(evModalShow);
      });
    });

  })( document.querySelectorAll('[data-toggle="modal"]') );

  (function(modals){
    if (!modals || !modals.length || modals.length<1 )  return false;

    modals.forEach(function(modal){
      modal.addEventListener('modal.show', function(ev){
        document.body.classList.add('overflow-hidden');
        ev.target.classList.remove('hidden');
      });

      modal.addEventListener('modal.hide',function(ev){
        ev.target.classList.add('hidden');
        ev.target.dispatchEvent(evModalHidden);
      });

      modal.addEventListener('modal.hidden', function(ev){
        document.body.classList.remove('overflow-hidden');
      });

      modal.addEventListener('click', (ev)=>{
        if ( this!=window && this!=ev.target )  return false;
        if ( ev.currentTarget!=ev.target )  return false;

        if ( ev.stopPropagation )  ev.stopPropagation();
        if ( ev.preventDefault )  ev.preventDefault();

        ev.currentTarget.dispatchEvent(evModalHide);
      });
    });

  })( document.querySelectorAll('.modal') );
});