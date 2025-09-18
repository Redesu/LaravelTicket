import './bootstrap';
import './FloatingActionButton.js';
import toastr from 'toastr';
import showAlert from './AppUtils.js';
import { refreshTable, resetModal, getFileColor } from './AppUtils.js';

window.toastr = toastr;
window.showAlert = showAlert;
window.refreshTable = refreshTable;
window.resetModal = resetModal;
window.getFileColor = getFileColor;