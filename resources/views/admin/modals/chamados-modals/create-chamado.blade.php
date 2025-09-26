<!-- Replace the existing modal with this updated version -->
<div class="modal fade" id="createChamadoModal" tabindex="-1" aria-labelledby="createChamadoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="createChamadoForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white" id="createChamadoModalLabel">
                        <i class="fas fa-plus-circle"></i> Criar um novo chamado
                    </h4>
                    <!-- Updated for Bootstrap 5 -->
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
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
                                    @foreach($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}">{{ $departamento->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categoria_id" class="form-label">Categoria</label>
                                <select class="form-control" id="categoria_id" name="categoria_id" required>
                                    <option value="" disabled selected>Selecione a categoria</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                    @endforeach
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

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="anexo">Anexo (Opcional - Múltiplos arquivos permitidos)</label>
                                <div class="drop-zone" id="anexoDropZone">
                                    <span class="drop-zone-text" id="dropZoneText">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        Arraste e solte os arquivos aqui ou clique para selecionar
                                    </span>
                                    <input type="file" class="d-none" id="anexo" name="anexos[]" multiple
                                        accept=".jpg,.jpeg,.png,.pdf,.zip,.rar,.mp4">
                                </div>
                                <div class="invalid-feedback d-block" id="anexo-feedback" style="display: none;"></div>
                                <div id="selectedFilesContainer" class="mt-2" style="display: none;">
                                    <small class="text-muted">Arquivos selecionados:</small>
                                    <div id="selectedFilesList"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
