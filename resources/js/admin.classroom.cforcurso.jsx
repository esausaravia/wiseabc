window.addEventListener('DOMContentLoaded',function(){
  console.log('admin.classroom.cforcurso DOMContentLoaded');

  studentsCollection.forEach(function(item){
    item._htmlEl = document.querySelector('[data-student-id="'+ item.id +'"]')
    item._htmlElements = document.querySelectorAll('[data-student-id="'+ item.id +'"]')
  });

  const mainForm = document.getElementById('frm-createforcurso');

  mainForm.querySelectorAll('[name="ritmo"]').forEach(function(input){
    input.addEventListener('change',function(){
      mainForm.querySelectorAll('input[name^="horarios"]').forEach(function(_in2){
        _in2.checked=false;
      });
    });
  });

  mainForm.querySelectorAll('input[name="teacher_id"]').forEach(function(input){
    input.addEventListener('change',function(){
      const profId = this.value;
      console.log('prof',profId);

      mainForm.querySelectorAll('input[name^="horarios"]').forEach(function(_in2){
        _in2.checked=false;
        _in2.disabled=true;
      });


      mainForm.querySelectorAll('[data-teacher-horario]').forEach(function(div){
        div.classList.add('hidden');

        if ( div.getAttribute('data-teacher-horario')==profId ) {
          div.classList.remove('hidden');

          div.querySelectorAll('input[name^="horarios"]').forEach(function(_in2){
            _in2.checked=false;
            _in2.disabled=false;
          });
        }
      })
    });
  });

  const lockHorarioByRitmo = function(ev){
    let input = ev.currentTarget;
    let frm = input.form;

    let ritmo = frm.querySelector('[name="ritmo"]:checked');
    if (!ritmo || !ritmo.value )  return false;
    ritmo = ritmo.value; //1,2,3,4

    let optsChecked = frm.querySelectorAll('input[name^="horarios"]:checked');
    console.log('change', optsChecked.length,ritmo);
    if (optsChecked.length>ritmo) {
      input.checked = false;
      return false;
    }
    return true;
  }

  mainForm.querySelectorAll('input[name^="horarios"]').forEach(function(input){
    input.addEventListener('change', lockHorarioByRitmo);
    input.addEventListener('click', lockHorarioByRitmo);
  });


  (function(frm,list,fields){

    fields.forEach(function(fname){
      if ( !frm[fname] )  return false;

      frm.querySelectorAll('[name="'+fname+'"]').forEach(function(input){
        input.addEventListener('change',function(){

          list.querySelectorAll('[data-'+fname+']').forEach(function(list_element) {
            let el_value = list_element.getAttribute('data-'+fname);

            list_element.setAttribute('data-filter-'+fname, el_value==input.value );
            list_element.setAttribute('data-filtred',true);

          });

          list.querySelectorAll('[data-filtred]').forEach(function(list_el){
            let isValid = true;
            [...list_el.attributes].forEach(function(attr){

              if( /^data-filter-/i.test(attr.name) && attr.value=='false' ) {
                isValid=false;
              }

            });

            if (isValid) {
              list_el.classList.remove('hidden');
            }
            else {
              list_el.classList.add('hidden');
            }
          });


        });//END onChange
      });//END foreach input
    });//END foreach field

  })( mainForm, document.getElementById('students-list'), ['tipo','ritmo','horarios'] );


  /**
   * trigger options change event
   */
   (function(arrInputs){
    arrInputs.forEach(function(input){
      console.log(input, input.name, input.value);
      if (!input || !input.value)  return false;

      let ev = new Event('change');
      input.dispatchEvent(ev)
    })
  })( mainForm.querySelectorAll('[type="radio"]:checked, [type="checkbox"]:checked') );

  mainForm.addEventListener('submit', function(ev){
    console.log('submit');

    let ritmo = mainForm.querySelector('[name="ritmo"]:checked');
    let arrHorarios = mainForm.querySelectorAll('input[name^="horarios"]:checked');
    if ( !ritmo || !ritmo.value || !arrHorarios || arrHorarios.length<Number(ritmo.value) ) {

      alert('faltan horarios');

      mainForm.querySelectorAll('[type="submit"]').forEach(function(btn){
        btn.disabled = false;
      });

      if (ev && ev.preventDefault) ev.preventDefault();
      return false;
    }
  })

});