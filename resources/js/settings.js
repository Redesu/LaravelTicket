import $ from 'jquery';
import 'bootstrap';

window.$ = window.jQuery = $;

$(function () {
    $('#updateUserForm').on('submit', function (e) {
        e.preventDefault();

        const submitBtn = $('#updateSubmitBtn');
        const spinner = $('#updateSpinner');
        const password = $('#password');
        const password_confirmation = $('#password_confirmation');

        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        const formData = new FormData(this);
        formData.delete('id');

        let updateUrl = window.routes.updateUser
        let id = $('#updateUserId').val();
        let finalUrl = updateUrl.replace(':id', id);

        // Check if both password fields are equal
        if (password.val() !== password_confirmation.val()) {
            spinner.addClass('d-none');
            submitBtn.prop('disabled', false);
            showAlert('As senhas precisam ser iguais', 'error');
            return;
        }

        formData.delete('password_confirmation');

        if (password.val() === '' || password.val().trim() === '') {
            formData.delete('password');
        }

        $.ajax({
            url: finalUrl,
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                spinner.addClass('d-none');
                submitBtn.prop('disabled', false);
                showAlert('Usuário atualizado com sucesso', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function (xhr, status, error) {
                spinner.addClass('d-none');
                submitBtn.prop('disabled', false);

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function (field) {
                        $(`#${field}`).addClass('is-invalid');
                        $(`#${field}`).siblings('.invalid-feedback').text(errors[field][0]);
                    });
                    showAlert('Por favor, corrija os erros do formulário', 'error');
                } else {
                    showAlert('Erro ao atualizar usuário', 'error');
                }

            }
        });
    });


    $('#avatar').on('change', function () {
        const file = this.files[0];
        const avatarPreview = $('#avatarPreview');
        const avatarPlaceholder = $('#avatarPlaceholder');

        if (file) {
            if (!file.type.startsWith('image/')) {
                $('#avatar').val('');
                showAlert('O arquivo selecionado precisa ser uma imagem', 'error');
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                $('#avatar').val('');
                showAlert('O arquivo selecionado precisa ser menor que 2MB', 'error');
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                avatarPreview.attr('src', e.target.result);
                avatarPreview.show();
                avatarPlaceholder.removeClass('d-flex').addClass('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            avatarPreview.hide();
            avatarPlaceholder.show();
            avatarPlaceholder.removeClass('d-none').addClass('d-flex');
        }
    });
})
