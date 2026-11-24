<?php
session_start();
include_once('inc/conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    header('Location: entrarConta.php');
    exit;
}

$idUsuarioLogado = $_SESSION['usuario_id'];
$nomeUsuarioLogado = $_SESSION['usuario_nome'] ?? 'Usuário';
$emailUsuarioLogado = $_SESSION['usuario_email'] ?? '';
$fotoUsuarioLogado = $_SESSION['usuario_foto'] ?? '';

$partesNome = explode(' ', trim($nomeUsuarioLogado));
$iniciaisUsuario = mb_strtoupper(substr($partesNome[0], 0, 1));
if (count($partesNome) > 1) {
    $iniciaisUsuario .= mb_strtoupper(substr(end($partesNome), 0, 1));
}

$mensagemSucesso = "";
$mensagemErro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'excluir') {
    $idTransacao = (int) ($_POST['id_transacao'] ?? 0);
    $tipoTransacao = $_POST['tipo_transacao'] ?? '';

    try {
        if ($tipoTransacao == 'deposito' && $idTransacao > 0) {
            $stmtDel = $pdo->prepare("DELETE FROM tb_depositos WHERE cd_deposito = ? AND id_usuario = ?");
            $stmtDel->execute([$idTransacao, $idUsuarioLogado]);
            $mensagemSucesso = "Entrada excluída com sucesso!";
        } elseif ($tipoTransacao == 'saque' && $idTransacao > 0) {
            $stmtDel = $pdo->prepare("DELETE FROM tb_saques WHERE cd_saque = ? AND id_usuario = ?");
            $stmtDel->execute([$idTransacao, $idUsuarioLogado]);
            $mensagemSucesso = "Saída excluída com sucesso!";
        }
    } catch (PDOException $e) {
        $mensagemErro = "Erro ao excluir movimentação: " . $e->getMessage();
    }
}

$filtro = $_GET['filtro'] ?? 'todas';

try {
    $stmtDep = $pdo->prepare("
        SELECT 
            d.cd_deposito AS id,
            'deposito' AS tipo,
            'Depósito / Entrada' AS descricao,
            d.ds_valor_deposito AS valor,
            d.dt_deposito AS data,
            d.ds_forma_pagamento AS forma,
            b.nm_banco AS banco,
            'Entrada' AS categoria
        FROM tb_depositos d
        INNER JOIN tb_bancos b ON d.id_banco = b.cd_banco
        WHERE d.id_usuario = ?
    ");
    $stmtDep->execute([$idUsuarioLogado]);
    $depositos = $stmtDep->fetchAll(PDO::FETCH_ASSOC);

    $stmtSaq = $pdo->prepare("
        SELECT 
            s.cd_saque AS id,
            'saque' AS tipo,
            s.nm_saque AS descricao,
            s.ds_valor_saque AS valor,
            s.dt_saque AS data,
            s.ds_tipo_saque AS forma,
            b.nm_banco AS banco,
            c.nm_categoria AS categoria
        FROM tb_saques s
        INNER JOIN tb_bancos b ON s.id_banco = b.cd_banco
        INNER JOIN tb_categoria_contas c ON s.id_categoria = c.cd_categoria
        WHERE s.id_usuario = ?
    ");
    $stmtSaq->execute([$idUsuarioLogado]);
    $saques = $stmtSaq->fetchAll(PDO::FETCH_ASSOC);

    if ($filtro == 'entrada') {
        $transacoes = $depositos;
    } elseif ($filtro == 'saida') {
        $transacoes = $saques;
    } else {
        $transacoes = array_merge($depositos, $saques);
    }

    usort($transacoes, function ($a, $b) {
        $cmp = strcmp($b['data'], $a['data']);
        if ($cmp == 0) {
            return $b['id'] <=> $a['id'];
        }
        return $cmp;
    });

    $totalEntradas = array_sum(array_column($depositos, 'valor'));
    $totalSaidas = array_sum(array_column($saques, 'valor'));
    $balancoFiltrado = $totalEntradas - $totalSaidas;

    $stmtBancos = $pdo->prepare("SELECT cd_banco, nm_banco FROM tb_bancos WHERE id_usuario = ?");
    $stmtBancos->execute([$idUsuarioLogado]);
    $bancosUsuario = $stmtBancos->fetchAll();

    $stmtCat = $pdo->query("SELECT cd_categoria, nm_categoria FROM tb_categoria_contas ORDER BY nm_categoria ASC");
    $categoriasGasto = $stmtCat->fetchAll();

} catch (PDOException $e) {
    $transacoes = [];
    $bancosUsuario = [];
    $categoriasGasto = [];
    $totalEntradas = 0;
    $totalSaidas = 0;
    $balancoFiltrado = 0;
    $mensagemErro = "Erro ao carregar transações: " . $e->getMessage();
}
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transações | GERENCI MONEY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">
    <style>
        .filter-btn {
            background-color: #14161d;
            color: #a0a8b8;
            border: 1px solid #222630;
            border-radius: 8px;
            padding: 6px 16px;
            font-size: 0.9rem;
            text-decoration: none;
            transition: 0.2s;
        }
        .filter-btn:hover, .filter-btn.active {
            background-color: #8fe028;
            color: #0d0e12;
            border-color: #8fe028;
            font-weight: 600;
        }
        .badge-entrada {
            background-color: rgba(143, 224, 40, 0.15);
            color: #8fe028;
            border: 1px solid rgba(143, 224, 40, 0.3);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
        }
        .badge-saida {
            background-color: rgba(229, 62, 62, 0.15);
            color: #fc8181;
            border: 1px solid rgba(229, 62, 62, 0.3);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
        }
        .btn-excluir {
            background: transparent;
            border: 1px solid #2d3342;
            color: #fc8181;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 0.8rem;
            transition: 0.2s;
        }
        .btn-excluir:hover {
            background-color: #e53e3e;
            color: #fff;
            border-color: #e53e3e;
        }
    </style>
</head>

<body>
    <div class="page-glow">
        <main class="dashboard">
            <aside class="sidebar">
                <div class="brand">
                    <div class="brand-mark">₼</div>
                    <span>GERENCI MONEY</span>
                </div>


                <p class="menu-label">PAINEL</p>
                <nav class="menu">
                    <a class="menu-link" href="inicial.php"><span>▦</span> Visão geral</a>
                    <a class="menu-link active" href="transacoes.php"><span>◫</span> Transações</a>
                    <a class="menu-link" href="contas.php"><span>♙</span> Contas</a>
                </nav>

                <p class="menu-label settings-label">CONFIGURAÇÕES</p>
                <nav class="menu">
                    <a class="menu-link" href="configuracoes.php"><span>⚙</span> Perfil</a>
                    <a class="menu-link text-danger" href="logout.php">Sair da conta</a>
                </nav>
            </aside>

            <section class="main-content">
                <header class="topbar">
                    <div class="breadcrumbs">
                        <span>▱</span><span>☆</span>
                        <span class="muted">/</span><span class="muted">Painel</span>
                        <span class="muted">/</span><strong>Transações</strong>
                    </div>

                    <div class="top-actions d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-sm fw-bold px-3 py-2" data-bs-toggle="modal"
                            data-bs-target="#modalEntrada"
                            style="background-color: #8fe028; color: #0d0e12; border: none; border-radius: 8px;">
                            + Entrada
                        </button>

                        <button type="button" class="btn btn-sm fw-bold px-3 py-2" data-bs-toggle="modal"
                            data-bs-target="#modalSaque"
                            style="background-color: #e53e3e; color: #fff; border: none; border-radius: 8px;">
                            - Saída
                        </button>

                        <div class="dropdown">
                            <div class="avatar user-avatar dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false" style="cursor: pointer; overflow: hidden; padding: 0;">
                                <?php if (!empty($fotoUsuarioLogado) && file_exists($fotoUsuarioLogado)): ?>
                                    <img src="<?= htmlspecialchars($fotoUsuarioLogado) ?>" alt="Perfil" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <?= htmlspecialchars($iniciaisUsuario) ?>
                                <?php endif; ?>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow"
                                style="background-color: #14161d; border: 1px solid #222630;">
                                <li><h6 class="dropdown-header text-light"><?= htmlspecialchars($nomeUsuarioLogado) ?></h6></li>
                                <li><small class="dropdown-item-text text-muted text-truncate" style="max-width: 200px;"><?= htmlspecialchars($emailUsuarioLogado) ?></small></li>
                                <li><hr class="dropdown-divider border-secondary"></li>
                                <li><a class="dropdown-item text-light" href="configuracoes.php">⚙ Minhas Configurações</a></li>
                                <li><a class="dropdown-item text-danger" href="logout.php">Sair da Conta</a></li>
                            </ul>
                        </div>
                    </div>
                </header>

                <div class="heading-row">
                    <div>
                        <p class="eyebrow">EXTRATO COMPLETO</p>
                        <h1>Histórico de Transações</h1>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="transacoes.php?filtro=todas" class="filter-btn <?= $filtro === 'todas' ? 'active' : '' ?>">Todas</a>
                        <a href="transacoes.php?filtro=entrada" class="filter-btn <?= $filtro === 'entrada' ? 'active' : '' ?>">Entradas</a>
                        <a href="transacoes.php?filtro=saida" class="filter-btn <?= $filtro === 'saida' ? 'active' : '' ?>">Saídas</a>
                    </div>
                </div>

                <?php if (!empty($mensagemSucesso)): ?>
                    <div class="alert alert-success py-2 text-center" role="alert"><?= htmlspecialchars($mensagemSucesso) ?></div>
                <?php endif; ?>

                <?php if (!empty($mensagemErro)): ?>
                    <div class="alert alert-danger py-2 text-center" role="alert"><?= htmlspecialchars($mensagemErro) ?></div>
                <?php endif; ?>

                <!-- Resumo Rápido -->
                <section class="metric-grid mb-4">
                    <article class="metric-card">
                        <div class="metric-top"><span>Total Entradas</span><span class="metric-icon">↗</span></div>
                        <div class="metric-value text-success">R$ <?= number_format($totalEntradas, 2, ',', '.') ?></div>
                        <div class="metric-note">todos os depósitos</div>
                    </article>
                    <article class="metric-card">
                        <div class="metric-top"><span>Total Saídas</span><span class="metric-icon">↙</span></div>
                        <div class="metric-value text-danger">R$ <?= number_format($totalSaidas, 2, ',', '.') ?></div>
                        <div class="metric-note">todos os saques</div>
                    </article>
                    <article class="metric-card">
                        <div class="metric-top"><span>Balanço Líquido</span><span class="metric-icon">₼</span></div>
                        <div class="metric-value">R$ <?= number_format($balancoFiltrado, 2, ',', '.') ?></div>
                        <div class="metric-note">entradas menos saídas</div>
                    </article>
                </section>

                <!-- Tabela Unificada de Movimentações -->
                <article class="panel customers-panel">
                    <div class="panel-heading">
                        <div>
                            <h2>Movimentações Registradas (<?= count($transacoes) ?>)</h2>
                            <p>Visualize, filtre ou remova entradas e saídas</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table customer-table align-middle">
                            <thead>
                                <tr>
                                    <th>TIPO</th>
                                    <th>DESCRIÇÃO</th>
                                    <th>BANCO / CONTA</th>
                                    <th>CATEGORIA</th>
                                    <th>PAGAMENTO</th>
                                    <th>DATA</th>
                                    <th>VALOR</th>
                                    <th class="text-end">AÇÃO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($transacoes)): ?>
                                    <?php foreach ($transacoes as $t): ?>
                                        <tr>
                                            <td>
                                                <?php if ($t['tipo'] === 'deposito'): ?>
                                                    <span class="badge-entrada">↗ Entrada</span>
                                                <?php else: ?>
                                                    <span class="badge-saida">↙ Saída</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($t['descricao']) ?></strong>
                                            </td>
                                            <td><?= htmlspecialchars($t['banco']) ?></td>
                                            <td><span class="text-muted"><?= htmlspecialchars($t['categoria']) ?></span></td>
                                            <td><?= htmlspecialchars($t['forma']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($t['data'])) ?></td>
                                            <td class="fw-bold <?= $t['tipo'] === 'deposito' ? 'text-success' : 'text-danger' ?>">
                                                <?= $t['tipo'] === 'deposito' ? '+' : '-' ?> R$ <?= number_format($t['valor'], 2, ',', '.') ?>
                                            </td>
                                            <td class="text-end">
                                                <form action="" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta movimentação?');">
                                                    <input type="hidden" name="acao" value="excluir">
                                                    <input type="hidden" name="id_transacao" value="<?= $t['id'] ?>">
                                                    <input type="hidden" name="tipo_transacao" value="<?= $t['tipo'] ?>">
                                                    <button type="submit" class="btn-excluir">Excluir</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            Nenhuma movimentação encontrada para este filtro.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </article>

                <footer class="footer mt-4">© <?= date('Y') ?> GERENCI MONEY. Todos os direitos reservados.</footer>
            </section>
        </main>
    </div>

    <!-- MODAL DE ENTRADA -->
    <div class="modal fade" id="modalEntrada" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: #14161d; color: #fff; border: 1px solid #222630; border-radius: 12px;">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title">Adicionar Entrada (Depósito)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="salvarTransacao.php" method="POST">
                    <input type="hidden" name="tipoMovimentacao" value="deposito">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-white">Valor (R$):</label>
                            <input type="number" step="0.01" name="valorMovimentacao" class="form-control bg-black text-white border-secondary" placeholder="0.00" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Banco / Conta:</label>
                            <select name="idBanco" class="form-control bg-black text-white border-secondary" required>
                                <option value="">Selecione o banco...</option>
                                <?php foreach ($bancosUsuario as $banco): ?>
                                    <option value="<?= $banco['cd_banco']; ?>"><?= htmlspecialchars($banco['nm_banco']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Forma de Pagamento:</label>
                            <select name="formaPagamento" class="form-control bg-black text-white border-secondary" required>
                                <option value="Pix">Pix</option>
                                <option value="Dinheiro">Dinheiro</option>
                                <option value="Transferência">Transferência</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Data:</label>
                            <input type="date" name="dataMovimentacao" class="form-control bg-black text-white border-secondary" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm fw-bold px-4" style="background-color: #8fe028; color: #0d0e12;">Salvar Entrada</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL DE SAÍDA -->
    <div class="modal fade" id="modalSaque" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: #14161d; color: #fff; border: 1px solid #222630; border-radius: 12px;">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title">Adicionar Saída (Gasto)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="salvarTransacao.php" method="POST">
                    <input type="hidden" name="tipoMovimentacao" value="saque">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-white">Descrição / Nome do Gasto:</label>
                            <input type="text" name="descricaoMovimentacao" class="form-control bg-black text-white border-secondary" placeholder="Ex: Mercado, Conta de Luz..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Valor (R$):</label>
                            <input type="number" step="0.01" name="valorMovimentacao" class="form-control bg-black text-white border-secondary" placeholder="0.00" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Banco / Conta:</label>
                            <select name="idBanco" class="form-control bg-black text-white border-secondary" required>
                                <option value="">Selecione o banco...</option>
                                <?php foreach ($bancosUsuario as $banco): ?>
                                    <option value="<?= $banco['cd_banco']; ?>"><?= htmlspecialchars($banco['nm_banco']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Categoria do Gasto:</label>
                            <select name="idCategoria" class="form-control bg-black text-white border-secondary" required>
                                <option value="">Selecione a categoria...</option>
                                <?php foreach ($categoriasGasto as $cat): ?>
                                    <option value="<?= $cat['cd_categoria']; ?>"><?= htmlspecialchars($cat['nm_categoria']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Forma de Pagamento:</label>
                            <select name="formaPagamento" class="form-control bg-black text-white border-secondary" required>
                                <option value="Pix">Pix</option>
                                <option value="Dinheiro">Dinheiro</option>
                                <option value="Cartão de Crédito">Cartão de Crédito</option>
                                <option value="Cartão de Débito">Cartão de Débito</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Data:</label>
                            <input type="date" name="dataMovimentacao" class="form-control bg-black text-white border-secondary" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm fw-bold px-4" style="background-color: #e53e3e; color: #fff;">Salvar Saída</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>