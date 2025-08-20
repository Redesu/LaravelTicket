<div class="modal fade" id="createChamadoModal" tabindex="-1" aria-labelledby="createChamadoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="createChamadoForm">
                @csrf
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white" id="createChamadoModalLabel">
                        <i class="fas fa-plus-circle"></i> Criar um novo chamado
                    </h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modalErrors" class="alert alert-danger d-none"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prioridade" class="form-label">Prioridade</label>
                                <select class="form-control" id="prioridade" name="prioridade" required>
                                    <option value="" disabled selected>Selecione a prioridade</option>
                                    <option value="baixa">Baixa</option>
                                    <option value="media">Média</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="descricao" class="form-label">Descrição</label>
                                <input type="text" class="form-control" id="descricao" name="descricao" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="departamento_id" class="form-label">Departamento</label>
                                <select class="form-control" id="departamento_id" name="departamento_id" required>
                                    <option value="" disabled selected>Selecione o departamento</option>
                                    <option value="1">Suporte</option>
                                    <option value="2">Desenvolvimento</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categoria_id" class="form-label">Categoria</label>
                                <select class="form-control" id="categoria_id" name="categoria_id" required>
                                    <option value="" disabled selected>Selecione a categoria</option>
                                    <option value="1">Suporte</option>
                                    <option value="2">Correção</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="createChamadosUsuario" class="form-label">Usuário Responsável</label>
                                <select class="form-control" id="createChamadosUsuario" name="user_id" required>
                                    <option value="" disabled selected>Selecione o usuário responsável</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Fechar
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="spinner"></span>
                        <i class="fas fa-plus-circle"></i> Criar chamado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>