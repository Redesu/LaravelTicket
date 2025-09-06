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
                url: "{{ route('api.departamentos.data-tables') }}",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function (xhr, error, thrown) {
                    showAlert(`Erro ao obter os departamentos`, 'error');
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
                { data: 'descricao', name: 'descricao' },
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
                        actions += `<button class="btn btn-primary btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editChamadosModal"><i class="fas fa-edit"></i> Editar</button> `;
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
</script>