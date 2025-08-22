@push('js')

<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
            $('#editChamadosDepartamento').val('{{ $chamado->departamento->id }}');
            $('#editChamadosCategoria').val('{{ $chamado->categoria->id }}');
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
                    $('#solucaoModalErrors').removeClass('d-none').text(xhr.responseJSON.message);
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

            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');

            if (!commentText) {
                showAlert('O comentário não pode estar vazio.', 'error');
                return;
            }
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
                    showAlert(`AJAX Error: ${xhr}, ${error}, ${status}`, 'error');
                },
                complete: function () {
                    spinner.addClass('d-none');
                    submitBtn.prop('disabled', false);
                }
            });
        });

        $('#editChamadosForm').on('submit', function (e) {
            e.preventDefault();

            const submitBtn = $('#editSubmitBtn');
            const status = $('#editChamadosStatus');
            const spinner = $('#editSpinner');
            const errorDiv = $('#editChamadosModalErrors');

            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');
            errorDiv.addClass('d-none');

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
                    showAlert(`AJAX Error: ${xhr}, ${error}, ${status}`, 'error');
                    errorDiv.html(xhr.responseJSON.errors);
                    errorDiv.removeClass('d-none');
                    showAlert('Erro ao atualizar o chamado', 'error');
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

    function resetModal(formSelector, errorDivSelector) {
        $(formSelector)[0].reset();
        $(errorDivSelector).addClass('d-none').html('');
        $(formSelector + ' button[type="submit"]').prop('disabled', false);
        $(formSelector + ' .spinner-border').addClass('d-none');
    }



</script>