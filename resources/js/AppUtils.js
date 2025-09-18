export default function showAlert(message, type) {
    if (typeof toastr !== 'undefined') {
        const validTypes = ['success', 'error', 'warning', 'info'];
        const toastrType = validTypes.includes(type) ? type : 'info';

        toastr[toastrType](message);
    } else {
        alert(message);
    }
}

export function refreshTable() {
    // Get existing DataTable instance
    const table = $('#dataTable').DataTable();
    if (table) {
        table.ajax.reload();
        showAlert('Chamados recarregados com sucesso', 'success');
    } else {
        showAlert('Erro ao recarregar tabela', 'error');
    }
}

export function resetModal(formSelector) {
    $(formSelector)[0].reset();
    $(formSelector + ' button[type="submit"]').prop('disabled', false);
    $(formSelector + ' .spinner-border').addClass('d-none');
}

export function getFileColor(fileType) {
    switch (fileType) {
        case 'application/pdf':
            return '#dc3545';
        case 'image/jpeg':
        case 'image/png':
            return '#28a745';
        case 'application/zip':
        case 'application/x-rar-compressed':
            return '#ffc107';
        case 'video/mp4':
            return '#6f42c1';
        default:
            return '#6c757d';
    }
}