@push('js')

<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('js/FloatingActionButton.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jdenticon@3.3.0/dist/jdenticon.min.js" async
    integrity="sha384-LfouGM03m83ArVtne1JPk926e3SGD0Tz8XHtW2OKGsgeBU/UfR0Fa8eX+UlwSSAZ" crossorigin="anonymous">
    </script>



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
                $priorityBadge.addClass('badge-info');
                break;
            case 'baixa':
                $priorityBadge.addClass('badge-secondary');
                break;
            default:
                $priorityBadge.addClass('badge-secondary');
        }

        const $statusBadge = $('#status-badge');
        const status = $statusBadge.text().trim().toLowerCase();

        $statusBadge.removeClass().addClass('badge'); // Reset classes
        switch (status) {
            case 'aberto':
                $statusBadge.addClass('badge-success');
                break;

            case 'em andamento':
                $statusBadge.addClass('badge-warning');
                break;

            case 'finalizado':
                $statusBadge.addClass('badge-secondary');
                break;
        }

        $(document).on('click', '.reply-btn', function () {
            showAddComment();
        });


        $(document).on('click', '.edit-btn', function () {
            $('#editChamadosModal').modal('show');
            console.log('{{ $chamado->status }}')

            $('#editChamadoId').val({{ $chamado->id }});
            $('#editChamadosTitulo').val('{{ $chamado->titulo }}');
            $('#editChamadosDescricao').val('{{ $chamado->descricao }}');
            $('#editChamadosStatus').val('{{ $chamado->status }}');
            $('#editChamadosPrioridade').val('{{ $chamado->prioridade }}');
            $('#editChamadosDepartamento').val('{{ $chamado->departamento->nome }}');
            $('#editChamadosCategoria').val('{{ $chamado->categoria->nome }}');
            $('#editChamadosUsuario').val('{{ $chamado->usuario->id }}');
            $('#editChamadoForm').off('submit');
        });

        $(document).on('click', '.solucao-btn', function () {
            $('#solucaoChamadoModal').modal('show');
            // $('#solucaoChamadoForm').off('submit');
        });

        $(document).on('click', '.voltar-btn', function () {
            $('#voltarSpinner').removeClass('d-none');
        });

        $('#solucaoChamadoForm').on('submit', function (e) {
            e.preventDefault();
            const descricao = $('#solucaoDescricao').val().trim();

            if (!descricao) {
                showAlert('A descrição não pode estar vazia.', 'error');
                return;
            }

            $('#solucaoSpinner').removeClass('d-none');
            $('#solucaoSubmitBtn').prop('disabled', true);

            $.ajax({
                url: '{{ route("api.chamados.addSolution", $chamado->id) }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    descricao: descricao,
                    tipo: 'solution',
                },
                success: function (response) {
                    $('#solucaoChamadoModal').modal('hide');
                    showAlert('Solução adicionada com sucesso!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                },
                error: function (xhr) {
                    showAlert('Ocorreu um erro ao adicionar a solução.', 'error');
                },
                complete: function () {
                    $('#solucaoSpinner').addClass('d-none');
                    $('#solucaoSubmitBtn').prop('disabled', false);
                }
            });
        });

        $(document).on('submit', '#add-comment-form', function (e) {
            e.preventDefault();

            const commentText = $('#comment-text').val().trim();
            const spinner = $('#comentarioSpinner');
            const submitBtn = $('#addCommentBtn');

            if (!commentText) {
                showAlert('O comentário não pode estar vazio.', 'error');
                return;
            }

            // Show loading state
            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');

            const originalBtnText = submitBtn.html();
            submitBtn.html('<span class="spinner-border spinner-border-sm" id="comentarioSpinner"></span> Enviando...');

            $.ajax({
                url: '{{ route("api.chamados.addComentario", $chamado->id) }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    descricao: commentText,
                    tipo: 'comment',
                },
                success: function (response) {
                    cancelComment();
                    showAlert('Comentário adicionado com sucesso!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                },
                error: function (xhr, status, error) {
                    spinner.addClass('d-none');
                    showAlert('Erro ao adicionar comentário', 'error');
                },
                complete: function () {
                    spinner.addClass('d-none');
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalBtnText);
                }
            });
        });

        $('#editChamadosForm').on('submit', function (e) {
            e.preventDefault();

            const submitBtn = $('#editSubmitBtn');
            const status = $('#editChamadosStatus');
            const spinner = $('#editSpinner');

            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');

            const formData = $(this).serialize();

            let updateUrl = "{{ route('api.chamados.put', ':id') }}";
            let id = {{ $chamado->id }};
            let finalUrl = updateUrl.replace(':id', id);

            $.ajax({
                url: finalUrl,
                method: "PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: formData,
                success: function (response) {
                    if (typeof table !== 'undefined') {
                        table.ajax.reload();
                    }
                    $('#editChamadosModal').modal('hide');
                    showAlert('Chamado atualizado com sucesso', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                },
                error: function (xhr, status, error) {
                    showAlert(`Ocorreu um erro ao atualizar o chamado`, 'error');
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            });
        });

        $('#solucaoChamadoModal').on('hidden.bs.modal', function () {
            resetModal('#solucaoChamadoForm', '#solucaoModalErrors');
        });

        $('#editChamadosModal').on('hidden.bs.modal', function () {
            resetModal('#editChamadosForm', '#editChamadosModalErrors');
        });
    });

    function showAddComment() {
        const cardBody = $('#add-comment-card .card-body');
        if (cardBody.is(':hidden')) {
            cardBody.slideDown();
        }
        $('#comment-text').focus();
    }

    function cancelComment() {
        $('#comment-text').val('');
        $('#add-comment-card .card-body').slideUp();
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

    function goBack() {
        window.history.back();
    }

    function getFileIcon(fileExtension) {
        const iconMap = {
            'pdf': 'fa-file-pdf text-danger',
            'doc': 'fa-file-word text-primary',
            'docx': 'fa-file-word text-primary',
            'xls': 'fa-file-excel text-success',
            'xlsx': 'fa-file-excel text-success',
            'ppt': 'fa-file-powerpoint text-warning',
            'pptx': 'fa-file-powerpoint text-warning',
            'jpg': 'fa-file-image text-info',
            'jpeg': 'fa-file-image text-info',
            'png': 'fa-file-image text-info',
            'gif': 'fa-file-image text-info',
            'bmp': 'fa-file-image text-info',
            'svg': 'fa-file-image text-info',
            'txt': 'fa-file-alt text-secondary',
            'zip': 'fa-file-archive text-warning',
            'rar': 'fa-file-archive text-warning',
            '7z': 'fa-file-archive text-warning',
            'tar': 'fa-file-archive text-warning',
            'mp4': 'fa-file-video text-info',
            'avi': 'fa-file-video text-info',
            'mov': 'fa-file-video text-info',
            'mp3': 'fa-file-audio text-success',
            'wav': 'fa-file-audio text-success',
            'css': 'fa-file-code text-info',
            'js': 'fa-file-code text-warning',
            'html': 'fa-file-code text-danger',
            'php': 'fa-file-code text-purple',
            'json': 'fa-file-code text-success'
        };

        return iconMap[fileExtension.toLowerCase()] || 'fa-file text-secondary';
    }

    function formatFileSize(bytes) {
        if (bytes == 0) return '0 Bytes';

        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }


    function initializeFileDisplay() {
        document.querySelectorAll('.file-icon').forEach(function (icon) {
            const fileType = icon.getAttribute('data-file-type');
            const iconClasses = getFileIcon(fileType);

            icon.className = icon.className.replace(/fa-file[\w-]*/g, '').trim();
            icon.className += ' ' + iconClasses;
        });

        // Update file sizes
        document.querySelectorAll('.file-size').forEach(function (sizeElement) {
            const bytes = parseInt(sizeElement.getAttribute('data-size'));
            sizeElement.textContent = formatFileSize(bytes);
        });
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function () {
        initializeFileDisplay();
    });

    // Also initialize when new comments are loaded via AJAX (if applicable)
    function reinitializeFileDisplay() {
        initializeFileDisplay();
    }


</script>