<?php
$pagina_atual = 'documentacao';
include '../includes/header.php';
?>

<div class="backend-layout">

    <?php include '../includes/sidebar.php'; ?>

    <main class="backend-content">

        <div class="backend-topbar">
            <div>
                <h1>Novo Documento</h1>
                <p>Associação de documentação técnica ou administrativa a equipamentos médicos.</p>
            </div>

            <div class="dropdown">
                <button class="backend-user dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span>Administrador</span>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                   
                    <li>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/public/logout.php">
                            <i class="bi bi-box-arrow-right"></i>
                            Terminar sessão
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <section class="backend-box">
            <div class="backend-section-header">
                <div>
                    <h2>Dados do Documento</h2>
                    <p>Preencha os campos principais para simular o registo de um documento.</p>
                </div>

                <a href="index.php" class="btn-backend">
                    <i class="bi bi-arrow-left-circle"></i>
                    Voltar à listagem
                </a>
            </div>

            <form class="form-backend" id="formDocumento">

                <div class="form-grid">
                    <div>
                        <label for="codigoDocumento">Código *</label>
                        <input type="text" id="codigoDocumento" placeholder="Ex: D006">
                    </div>

                    <div>
                        <label for="nomeDocumento">Nome do documento *</label>
                        <input type="text" id="nomeDocumento" placeholder="Ex: Manual de Serviço">
                    </div>

                    <div>
                        <label for="tipoDocumento">Tipo de documento *</label>
                        <select id="tipoDocumento">
                            <option value="">Selecione o tipo</option>
                            <option>Manual de Utilizador</option>
                            <option>Manual de Serviço</option>
                            <option>Certificado de Calibração</option>
                            <option>Contrato / Garantia</option>
                            <option>Fatura ou Guia de Aquisição</option>
                            <option>Declaração de Conformidade</option>
                            <option>Relatório Técnico</option>
                        </select>
                    </div>

                    <div>
                        <label for="equipamentoDocumento">Equipamento associado *</label>
                        <select id="equipamentoDocumento">
                            <option value="">Selecione o equipamento</option>
                            <option>Monitor Multiparamétrico</option>
                            <option>Ventilador Pulmonar</option>
                            <option>Bomba de Infusão</option>
                            <option>Desfibrilhador</option>
                            <option>Ecógrafo Portátil</option>
                        </select>
                    </div>

                    <div>
                        <label for="fornecedorDocumento">Fornecedor associado</label>
                        <select id="fornecedorDocumento">
                            <option value="">Sem fornecedor associado</option>
                            <option>Philips Healthcare</option>
                            <option>MedTech Portugal</option>
                            <option>BioSupport Systems</option>
                            <option>InfuCare Medical</option>
                        </select>
                    </div>

                    <div>
                        <label for="dataDocumento">Data do documento *</label>
                        <input type="date" id="dataDocumento">
                    </div>

                    <div>
                        <label for="validadeDocumento">Data de validade</label>
                        <input type="date" id="validadeDocumento">
                    </div>

                    <div>
                        <label for="estadoDocumento">Estado *</label>
                        <select id="estadoDocumento">
                            <option value="">Selecione o estado</option>
                            <option>Válido</option>
                            <option>Pendente</option>
                            <option>Expirado</option>
                        </select>
                    </div>

                    <div>
                        <label for="ficheiroDocumento">Nome/localização do ficheiro *</label>
                        <input type="text" id="ficheiroDocumento" placeholder="Ex: manual_monitor.pdf">
                    </div>

                    <div>
                        <label for="responsavelDocumento">Responsável pelo registo</label>
                        <input type="text" id="responsavelDocumento" placeholder="Ex: Administrador">
                    </div>
                </div>

                <div class="form-full">
                    <label for="observacoesDocumento">Observações</label>
                    <textarea id="observacoesDocumento" rows="4" placeholder="Observações adicionais sobre o documento"></textarea>
                </div>

                <p id="mensagemDocumento" class="mensagem-login"></p>

                <div class="form-botoes">
                    <button type="submit" class="btn-backend">
                        <i class="bi bi-check-circle"></i>
                        Guardar documento
                    </button>

                    <a href="index.php" class="btn-secundario">
                        Cancelar
                    </a>
                </div>

            </form>
        </section>

    </main>

</div>

<?php include '../includes/footer.php'; ?>