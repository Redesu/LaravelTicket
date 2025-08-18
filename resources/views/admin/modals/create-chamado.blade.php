<div class="modal fade" id="createChamadoModal" tabindex="-1" aria-labelledby="createChamadoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createChamadoForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createChamadoModalLabel">Criar um novo chamado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div id="modalErrors" class="alert alert-danger d-none"></div>

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Titulo</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" required>
                    </div>

                    <div class="mb-3">
                        <label for="prioridade" class="form-label">Prioridade</label>
                        <select class="form-select" id="prioridade" name="prioridade" required>
                            <option value="" disabled selected>Selecione a prioridade</option>
                            <option value="baixa">Baixa</option>
                            <option value="media">Média</option>
                            <option value="alta">Alta</option>
                            <option value="urgente">Urgente</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="departamento" class="form-label">Departamento</label>
                        <select class="form-select" id="departamento_id" name="departamento_id" required>
                            <option value="" disabled selected>Selecione o departamento</option>
                            <option value="1">Suporte</option>
                            <option value="2">Desenvolvimento</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoria</label>
                        <select class="form-select" id="categoria_id" name="categoria_id" required>
                            <option value="" disabled selected>Selecione a categoria</option>
                            <option value="1">Suporte</option>
                            <option value="2">Correção</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="createChamadosUsuario" class="form-label">Usuário Responsável</label>
                        <select class="form-select" id="createChamadosUsuario" name="user_id" required>
                            <option value="" disabled selected>Selecione o usuário responsável</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="spinner"></span>
                        Criar chamado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>