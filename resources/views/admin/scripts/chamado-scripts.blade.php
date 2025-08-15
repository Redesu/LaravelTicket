@push('js')

<script>
    $(document).ready(function () {
        const $priorityBadge = $('#priority-badge');
        const priority = $priorityBadge.text().trim().toLowerCase();

        $priorityBadge.removeClass().addClass('badge'); // Reset classes
        switch (priority.toLowerCase()) {
            case 'urgente':
                $priorityBadge.addClass('badge-danger');
                break;
            case 'alta':
                $priorityBadge.addClass('badge-warning');
                break;
            case 'média':
                $priorityBadge.addClass('badge-warning');
                break;
            case 'baixa':
                $priorityBadge.addClass('badge-secondary');
                break;
            default:
                $priorityBadge.addClass('badge-secondary');
        }

        $(document).on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            // const rowData = table.row($(this).closest('tr')).data();
            console.log('Edit button clicked for Chamado');
            $('#editChamadoModal').modal('show');
            $('#editChamadoForm').off('submit'); // Remove previous submit handler
        });
    });
</script>