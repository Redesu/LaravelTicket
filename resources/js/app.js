import './bootstrap';
import './FloatingActionButton.js';
import $ from 'jquery';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
import showAlert from './AppUtils.js';
import 'bootstrap';



window.$ = window.jQuery = $;
window.toastr = toastr;
window.showAlert = showAlert;
