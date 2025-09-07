@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>


<!-- DataTables -->
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.js"></script>

<!-- For Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- For PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>


<script>
    $(document).ready(function () {

        var table = $('#dataTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('api.categorias.data-tables') }}",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function (xhr, error, thrown) {
                    showAlert(`Erro ao obter as categorias`, 'error');
                }
            },
            layout: {
                topStart: {
                    buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5']
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'nome', name: 'nome' },
                {
                    data: 'criado_em',
                    name: 'criado_em',
                    render: function (data, type, row) {
                        if (data && data !== null) {
                            return new Date(data).toLocaleString('pt-BR', {
                                year: 'numeric',
                                month: '2-digit',
                                day: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        }
                        return '';
                    }
                },
                {
                    data: null,
                    name: 'actions',
                    render: function (data, type, row) {
                        let actions = '';
                        actions += `<button class="btn btn-primary btn-sm edit-btn" data-id="${row.id}" data-bs-toggle="modal" data-bs-target="#editCategoriaModal"><i class="fas fa-edit"></i> Editar</button> `;
                        actions += `<button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}"><i class="fas fa-trash"></i> Excluir</button>`;
                        return actions;
                    },
                    orderable: false,
                    searchable: false
                },
            ],
            autoWidth: false,
            pageLength: 15,
            lengthMenu: [[15, 25, 50, -1], [15, 25, 50, "Todos"]],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
                processing: '<i class="fas fa-spinner fa-spin fa-2x"></i><br>Carregando dados...'
            },
        });

        $('#dataTable').on('click', '.delete-btn', function () {
            var id = $(this).data('id');
            if (confirm('Tem certeza que deseja excluir este chamado?')) {
                $.ajax({
                    url: "{{ route('api.categorias.delete') }}",
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { "id": id },
                    success: function (result) {
                        table.ajax.reload();
                        showAlert('Categoria excluída com sucesso', 'success');
                    },
                    error: function (xhr, status, error) {
                        var response = xhr.responseJSON;
                        var errorMessage = 'Erro ao excluir a categoria';

                        if (response && response.message) {
                            errorMessage = response.message;
                        }

                        showAlert(errorMessage, 'error');
                    }
                });
            }
        });

        $(document).on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            const rowData = table.row($(this).closest('tr')).data();

            $('#editCategoriaId').val(rowData.id);
            $('#categoriaNome').val(rowData.nome);
            $('#editCategoriaModal').modal('show');
        });

        $('#createCategoriaForm').on('submit', function (e) {
            e.preventDefault();

            const submitBtn = $('#submitBtn');
            const spinner = $('#spinner');

            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');

            $.ajax({
                url: "{{ route('api.categorias.post') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $(this).serialize(),
                success: function (response) {
                    table.ajax.reload();
                    $('#createCategoriaModal').modal('hide');
                    showAlert('Categoria criada com sucesso', 'success');
                },
                error: function (xhr, status, error) {
                    showAlert('Erro ao criar a categoria', 'error');
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            })
        });

        $('#editCategoriaForm').on('submit', function (e) {
            e.preventDefault();

            const submitBtn = $('#editSubmitBtn');
            const spinner = $('#editSpinner');

            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');

            let updateUrl = "{{ route('api.categorias.put', ':id') }}";
            let id = $('#editCategoriaId').val();
            let finalUrl = updateUrl.replace(':id', id);

            $.ajax({
                url: finalUrl,
                method: "PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: $(this).serialize(),
                success: function (response) {
                    table.ajax.reload();
                    $('#editCategoriaModal').modal('hide');
                    showAlert('Categoria atualizada com sucesso', 'success');
                },
                error: function (xhr, status, error) {
                    showAlert('Erro ao atualizar a categoria', 'error');
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            });
        });

        $('#createCategoriaModal').on('hidden.bs.modal', function () {
            resetModal('#createCategoriaForm');
        });

        $('#editCategoriaModal').on('hidden.bs.modal', function () {
            resetModal('#editCategoriaForm');
        });
    });

    class FloatingActionButton {
        constructor() {
            this.fabContainer = document.getElementById('fabContainer');
            this.fabMain = document.getElementById('fabMain');
            this.fabBackdrop = document.getElementById('fabBackdrop');
            this.isOpen = false;

            this.init();
        }

        init() {
            // Main button click
            this.fabMain.addEventListener('click', () => this.toggle());

            // Backdrop click to close
            this.fabBackdrop.addEventListener('click', () => this.close());

            // Close on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isOpen) {
                    this.close();
                }
            });

            // Remove pulse after first interaction
            this.fabMain.addEventListener('click', () => {
                this.fabMain.classList.remove('pulse');
            }, { once: true });

            // Close when clicking action buttons
            const actionButtons = document.querySelectorAll('.fab-action');
            actionButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    setTimeout(() => this.close(), 100);
                });
            });
        }

        toggle() {
            this.isOpen ? this.close() : this.open();
        }

        open() {
            this.isOpen = true;
            this.fabContainer.classList.add('active');
            this.fabMain.classList.add('active');
            this.fabBackdrop.classList.add('active');

            // Add slight vibration effect (if supported)
            if (navigator.vibrate) {
                navigator.vibrate(50);
            }
        }

        close() {
            this.isOpen = false;
            this.fabContainer.classList.remove('active');
            this.fabMain.classList.remove('active');
            this.fabBackdrop.classList.remove('active');
        }
    }

    // Initialize FAB when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        new FloatingActionButton();
    });

    // Add scroll effect
    let scrollTimeout;
    window.addEventListener('scroll', () => {
        const fab = document.getElementById('fabMain');
        if (fab) {
            fab.style.transform = 'scale(0.9)';

            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                fab.style.transform = '';
            }, 150);
        }
    });

    function refreshTable() {
        // Get existing DataTable instance
        const table = $('#dataTable').DataTable();
        if (table) {
            table.ajax.reload();
            showAlert('Chamados recarregados com sucesso', 'success');
        } else {
            showAlert('Erro ao recarregar tabela', 'error');
        }
    }

    function showAlert(message, type) {
        if (typeof toastr !== 'undefined') {
            const validTypes = ['success', 'error', 'warning', 'info'];
            const toastrType = validTypes.includes(type) ? type : 'info';

            toastr[toastrType](message);
        } else {
            alert(message);
        }
    }

    function resetModal(formSelector) {
        $(formSelector)[0].reset();
        $(formSelector + ' button[type="submit"]').prop('disabled', false);
        $(formSelector + ' .spinner-border').addClass('d-none');
    }

</script>