import {library, dom} from '@fortawesome/fontawesome-svg-core';
import {faBars, faTimes, faPlus, faPenToSquare, faUser, faUsers, faTrash, faCalendarDay, faFileInvoiceDollar, faUserGear, faArrowRightFromArc} from '@fortawesome/pro-light-svg-icons';
import {faFacebookF, faTwitter} from '@fortawesome/free-brands-svg-icons';

library.add(faBars, faTimes, faPlus, faPenToSquare, faTrash, faUser, faUsers, faCalendarDay, faFileInvoiceDollar, faUserGear, faArrowRightFromArc, faFacebookF, faTwitter);

window.addEventListener('DOMContentLoaded', function(){
  //const faCss = dom.css();
  //dom.insertCss(faCss);
  dom.watch();
});