
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

        if ( ev.currentTarget.tagName==="A")
        {
          if (ev.preventDefault)  ev.preventDefault();
        }

        const modal_target = document.getElementById(ev.currentTarget.dataset.target);

        if ( modal_target && modal_target.querySelector )  modal_target.dispatchEvent(evModalShow);

        return false;
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
        if ( ev.currentTarget!=ev.target )  return false;

        if ( ev.stopPropagation )  ev.stopPropagation();
        if ( ev.preventDefault )  ev.preventDefault();

        ev.currentTarget.dispatchEvent(evModalHide);
      });

      modal.querySelectorAll('.modal-close').forEach( (btn)=>{
        btn.addEventListener('click', (ev)=>{
          ev.currentTarget.closest('.modal').dispatchEvent(evModalHide);
        } );
      });
    });

  })( document.querySelectorAll('.modal') );
});