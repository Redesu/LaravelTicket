@push('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

        $('#solucaoChamadoForm').on('submit', function (e) {
            e.preventDefault();
            const descricao = $('#solucaoDescricao').val().trim();

            if (!descricao) {
                showAlert('A descrição não pode estar vazia.', 'error');
                return; // Explicitly stop submission
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
                    chamado_id: {{ $chamado->id }}
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

    $('#add-comment-form').on('submit', function (e) {
        e.preventDefault();
        const commentText = $('#comment-text').val().trim();

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
                chamado_id: {{ $chamado->id }}
             },
            success: function (response) {
                $('#comment-text').val('');
                $('#add-comment-card').CardWidget('collapse');

                showAlert('Comentário adicionado com sucesso!', 'success');

                location.reload();
            },
            error: function (xhr) {
                showAlert('Erro ao adicionar comentário: ' + xhr.responseJSON.message, 'error');
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        $('#editChamadoModal').modal('show');
        // $('#editChamadoForm').off('submit');
    });

    $(document).on('click', '.solucao-btn', function () {
        $('#solucaoChamadoModal').modal('show');
        // $('#solucaoChamadoForm').off('submit');
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



</script>