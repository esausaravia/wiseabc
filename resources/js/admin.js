
window.addEventListener('DOMContentLoaded', function() {

  const adminAside = document.getElementById('AdminAside');
  const adminMainContainer = document.getElementById('AdminMainContainer');

  document.querySelectorAll('[data-delete-name][data-delete-url]').forEach( (btn)=>{

    btn.addEventListener('click', (ev)=>{
      if ( ev.currentTarget.tagName==="A" )  return;

      const modal = document.getElementById('modal-delete-entity');

      if ( !modal || !modal.querySelector )  return;

      modal.querySelector('form').action = ev.currentTarget.dataset.deleteUrl;

      modal.querySelector('.delete-name').innerText = ev.currentTarget.dataset.deleteName;

      modal.dispatchEvent( new Event('modal.show') );
    })


  });

});