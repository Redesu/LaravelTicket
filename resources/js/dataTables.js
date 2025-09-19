import JSZip from 'jszip';
import pdfMake from 'pdfmake/build/pdfmake';
import vfs_fonts from 'pdfmake/build/vfs_fonts';
import 'datatables.net-responsive-bs4';
import 'datatables.net-buttons-bs4';
import 'datatables.net-buttons/js/buttons.html5.js';
import DataTable from 'datatables.net-bs4';
import { resetModal, getFileColor, refreshTable } from './AppUtils';


pdfMake.vfs = vfs_fonts;
window.pdfMake = pdfMake;
window.JSZip = JSZip;
window.DataTable = DataTable;

// helper functions
window.resetModal = resetModal;
window.getFileColor = getFileColor;
window.refreshTable = refreshTable;