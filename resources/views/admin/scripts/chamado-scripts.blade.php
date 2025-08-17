@push('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jdenticon@3.3.0/dist/jdenticon.min.js" async
    integrity="sha384-LfouGM03m83ArVtne1JPk926e3SGD0Tz8XHtW2OKGsgeBU/UfR0Fa8eX+UlwSSAZ" crossorigin="anonymous">
    </script>

<script>
    $(document).ready(function () {
        const $priorityBadge = $('#priority-badge');
        const priority = $priorityBadge.text().trim().toLowerCase();

        console.log(`{{ $chamado }}`);

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

        const $statusBadge = $('#status-badge');
        const status = $statusBadge.text().trim().toLowerCase();

        $statusBadge.removeClass().addClass('badge'); // Reset classes
        switch (status) {
            case 'aberto':
                $statusBadge.addClass('badge-success');
                break;

            case 'em andamento':
                $statusBadge.addClass('badge-info');
                break;

            case 'finalizado':
                $statusBadge.addClass('badge-secondary');
                break;
        }

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
                    location.reload();
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

            console.log('Submitting comment form');
            console.log("Submitting with text: " + $('#comment-text').val());

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
                    $('#comment-text').val('');
                    $('#add-comment-card').CardWidget('collapse');

                    showAlert('Comentário adicionado com sucesso!', 'success');
                    location.reload();
                },
                error: function (xhr) {
                    showAlert('Erro ao adicionar comentário: ' + xhr.responseJSON.message, 'error');
                },
                complete: function () {
                    spinner.addClass('d-none');
                    submitBtn.prop('disabled', false);
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
        const card = $('#add-comment-card');
        if (card.hasClass('collapsed-card')) {
            card.CardWidget('expand');
        }
        $('#comment-text').focus();
    }

    function cancelComment() {
        $('#comment-text').val('');
        $('#add-comment-card').CardWidget('collapse');
    }



    $(document).on('click', '.edit-btn', function () {
        $('#editChamadosModal').modal('show');

        $('#editChamadoId').val({{ $chamado->id }});
        $('#editChamadosTitulo').val('{{ $chamado->titulo }}');
        $('#editChamadosDescricao').val('{{ $chamado->descricao }}');
        $('#editChamadosStatus').val('{{ $chamado->status }}');
        $('#editChamadosPrioridade').val('{{ $chamado->prioridade }}');
        $('#editChamadosDepartamento').val('{{ $chamado->departamento->nome }}');
        $('#editChamadosCategoria').val('{{ $chamado->categoria->nome }}');
        // $('#editChamadoForm').off('submit');
    });

    $(document).on('click', '.solucao-btn', function () {
        $('#solucaoChamadoModal').modal('show');
        // $('#solucaoChamadoForm').off('submit');
    });

    $(document).on('click', '.voltar-btn', function () {
        $('#voltarSpinner').removeClass('d-none');
    });




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