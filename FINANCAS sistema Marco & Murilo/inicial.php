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

$partesNome = explode(' ', trim($nomeUsuarioLogado));
$iniciaisUsuario = mb_strtoupper(substr($partesNome[0], 0, 1));
if (count($partesNome) > 1) {
    $iniciaisUsuario .= mb_strtoupper(substr(end($partesNome), 0, 1));
}

try {
    $stmtDep = $pdo->prepare("SELECT SUM(ds_valor_deposito) as total_depositos FROM tb_depositos WHERE id_usuario = ?");
    $stmtDep->execute([$idUsuarioLogado]);
    $resDep = $stmtDep->fetch();
    $totalDepositos = $resDep['total_depositos'] ?? 0;

    $stmtSaq = $pdo->prepare("SELECT SUM(ds_valor_saque) as total_saques FROM tb_saques WHERE id_usuario = ?");
    $stmtSaq->execute([$idUsuarioLogado]);
    $resSaq = $stmtSaq->fetch();
    $totalSaques = $resSaq['total_saques'] ?? 0;

    $stmtBancos = $pdo->prepare("SELECT cd_banco, nm_banco FROM tb_bancos WHERE id_usuario = ?");
    $stmtBancos->execute([$idUsuarioLogado]);
    $bancosUsuario = $stmtBancos->fetchAll();

    $stmtCat = $pdo->query("SELECT cd_categoria, nm_categoria FROM tb_categoria_contas ORDER BY nm_categoria ASC");
    $categoriasGasto = $stmtCat->fetchAll();

    $saldoAtual = $totalDepositos - $totalSaques;

    $metricas = [
        ['titulo' => 'Saldo Total', 'icone' => '₼', 'valor' => 'R$ ' . number_format($saldoAtual, 2, ',', '.'), 'variacao' => '', 'nota' => 'atualizado agora'],
        ['titulo' => 'Total Entradas', 'icone' => '↗', 'valor' => 'R$ ' . number_format($totalDepositos, 2, ',', '.'), 'variacao' => '', 'nota' => 'depositos'],
        ['titulo' => 'Total Gastos', 'icone' => '↙', 'valor' => 'R$ ' . number_format($totalSaques, 2, ',', '.'), 'variacao' => '', 'nota' => 'saques'],
    ];

    $stmtCli = $pdo->prepare("SELECT * FROM tb_depositos WHERE id_usuario = ? ORDER BY cd_deposito DESC LIMIT 5");
    $stmtCli->execute([$idUsuarioLogado]);
    $ultimosDepositos = $stmtCli->fetchAll();

} catch (PDOException $e) {
    $metricas = [];
    $ultimosDepositos = [];
    $bancosUsuario = [];
    $categoriasGasto = [];
}

$notificacoes = [
    ['texto' => 'Conexão com o Supabase estabelecida com sucesso.', 'tempo' => 'Agora'],
    ['texto' => 'Bem-vindo ao Gerenci Money!', 'tempo' => 'Hoje']
];

$atividades = [
    ['iniciais' => $iniciaisUsuario, 'texto' => 'Login realizado no sistema', 'tempo' => 'Recentemente']
];
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visão geral | GERENCI MONEY</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilo.css">

    <style>
        .dropdown-item-text {
            color: greenyellow !important;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const tipoSelect = document.querySelector('select[name="tipoMovimentacao"]');
            const blocoCategoria = document.getElementById('blocoCategoria');
            const selectCategoria = document.getElementById('selectCategoria');

            function alternarCategoria() {
                if (tipoSelect.value == 'saque') {
                    blocoCategoria.style.display = 'block';
                    selectCategoria.setAttribute('required', 'required');
                } else {
                    blocoCategoria.style.display = 'none';
                    selectCategoria.removeAttribute('required');
                    selectCategoria.value = '';
                }
            }

            alternarCategoria();

            tipoSelect.addEventListener('change', alternarCategoria);
        });
    </script>
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
                    <a class="menu-link active" href="#"><span>▦</span> Visão geral</a>
                    <a class="menu-link" href="transacoes.php"><span>◫</span> Transações</a>
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
                    <div class="breadcrumbs"><span>▱</span><span>☆</span><span class="muted">/</span><span
                            class="muted">Painel</span><span class="muted">/</span><strong>Visão geral</strong></div>

                    <div class="top-actions d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-sm fw-bold px-3 py-2" data-bs-toggle="modal"
                            data-bs-target="#modalTransacao"
                            style="background-color: #8fe028; color: #0d0e12; border: none; border-radius: 8px;">
                            + Nova Transação
                        </button>

                        <div class="dropdown">
                            <div class="avatar user-avatar dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false" style="cursor: pointer; overflow: hidden; padding: 0;">
                                <?php if (!empty($_SESSION['usuario_foto']) && file_exists($_SESSION['usuario_foto'])): ?>
                                    <img src="<?= htmlspecialchars($_SESSION['usuario_foto']) ?>" alt="Perfil"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <?= htmlspecialchars($iniciaisUsuario) ?>
                                <?php endif; ?>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow"
                                style="background-color: #14161d; border: 1px solid #222630;">
                                <li>
                                    <h6 class="dropdown-header text-light"><?= htmlspecialchars($nomeUsuarioLogado) ?>
                                    </h6>
                                </li>
                                <li><small class="dropdown-item-text text-truncate"
                                        style="max-width: 200px;"><?= htmlspecialchars($emailUsuarioLogado) ?></small>
                                </li>
                                <li>
                                    <hr class="dropdown-divider border-secondary">
                                </li>
                                <li><a class="dropdown-item text-light" href="configuracoes.php">⚙ Minhas
                                        Configurações</a></li>
                                <li><a class="dropdown-item text-danger" href="logout.php">Sair da Conta</a></li>
                            </ul>
                        </div>
                    </div>
                </header>

                <div class="heading-row">
                    <div>
                        <p class="eyebrow">SEU DINHEIRO EM UM SÓ LUGAR</p>
                        <h1>Olá, <?= htmlspecialchars($nomeUsuarioLogado) ?>!</h1>
                    </div>
                </div>

                <section class="metric-grid">
                    <?php foreach ($metricas as $item): ?>
                        <article class="metric-card">
                            <div class="metric-top"><span><?= htmlspecialchars($item['titulo']) ?></span><span
                                    class="metric-icon"><?= htmlspecialchars($item['icone']) ?></span></div>
                            <div class="metric-value"><?= htmlspecialchars($item['valor']) ?></div>
                            <div class="metric-note"><strong><?= htmlspecialchars($item['variacao']) ?></strong>
                                <?= htmlspecialchars($item['nota']) ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </section>

                <section class="content-grid">
                    <div class="center-column">
                        <article class="panel sales-panel">
                            <div class="panel-heading">
                                <div>
                                    <h2>Resumo Financeiro</h2>
                                    <p>Balanço geral de entradas e saídas</p>
                                </div><button class="dots" aria-label="Mais opções">⋮</button>
                            </div>
                            <div class="sales-content">
                                <div class="donut-wrap">
                                    <div class="donut">
                                        <div class="donut-center"><strong>R$</strong><span>Saldo</span></div>
                                    </div>
                                </div>
                                <div class="sales-details">
                                    <div class="sales-total"><span class="money-icon">$</span>
                                        <div><small>Saldo Disponível</small><strong>R$
                                                <?= number_format($saldoAtual, 2, ',', '.') ?></strong></div>
                                    </div>
                                    <div class="legend-grid">
                                        <div><i class="dot dot-green"></i><span>Entradas</span><strong>R$
                                                <?= number_format($totalDepositos, 2, ',', '.') ?></strong></div>
                                        <div><i class="dot dot-lime"></i><span>Saídas</span><strong>R$
                                                <?= number_format($totalSaques, 2, ',', '.') ?></strong></div>
                                    </div>
                                </div>
                            </div>
                        </article>

                        <div class="mini-grid">
                            <article class="panel mini-card">
                                <div class="mini-icon green-icon">↗</div><small>Total Entradas</small><strong>R$
                                    <?= number_format($totalDepositos, 2, ',', '.') ?></strong><span
                                    class="positive">Supabase Ativo</span>
                            </article>
                            <article class="panel mini-card">
                                <div class="mini-icon lime-icon">↙</div><small>Total Saídas</small><strong>R$
                                    <?= number_format($totalSaques, 2, ',', '.') ?></strong><span
                                    class="positive">Supabase Ativo</span>
                            </article>
                        </div>

                        <article class="panel customers-panel">
                            <div class="panel-heading">
                                <div>
                                    <h2>Últimos Depósitos</h2>
                                    <p>Suas movimentações recentes registradas</p>
                                </div><button class="dots" aria-label="Mais opções">⋮</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table customer-table">
                                    <thead>
                                        <tr>
                                            <th>BANCO / ORIGEM</th>
                                            <th>DATA</th>
                                            <th>VALOR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($ultimosDepositos)): ?>
                                            <?php foreach ($ultimosDepositos + [] as $dep): ?>
                                                <tr>
                                                    <td>
                                                        <div class="customer">
                                                            <div>
                                                                <strong><?= htmlspecialchars($dep['ds_banco'] ?? 'Depósito') ?></strong><small>ID:
                                                                    <?= htmlspecialchars($dep['cd_deposito']) ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?= htmlspecialchars($dep['dt_deposito'] ?? '-') ?></td>
                                                    <td class="table-total text-success">+ R$
                                                        <?= number_format($dep['ds_valor_deposito'] ?? 0, 2, ',', '.') ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">Nenhum depósito
                                                    registrado ainda.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </article>
                    </div>

                    <article class="panel side-panel">
                        <div class="panel-heading">
                            <h2>Atividades</h2><button class="dots" aria-label="Mais opções">⋮</button>
                        </div>
                        <div class="activity-list">
                            <?php foreach ($atividades as $a): ?>
                                <div class="feed-item">
                                    <div class="avatar activity-avatar"><?= htmlspecialchars($a['iniciais']) ?></div>
                                    <div>
                                        <strong><?= htmlspecialchars($a['texto']) ?></strong><small><?= htmlspecialchars($a['tempo']) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </article>

                    </aside>
                </section>
                <footer class="footer">© <?= date('Y') ?> GERENCI MONEY. Todos os direitos reservados.</footer>
            </section>
        </main>
    </div>

    <div class="modal fade" id="modalTransacao" tabindex="-1" aria-labelledby="modalTransacaoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="background-color: #14161d; color: #fff; border: 1px solid #222630; border-radius: 12px;">

                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title" id="modalTransacaoLabel">Adicionar Movimentação</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form action="salvarTransacao.php" method="POST">
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label text-white">Tipo de Movimentação:</label>
                            <select name="tipoMovimentacao" class="form-control bg-black text-white border-secondary"
                                required>
                                <option value="deposito">Entrada (Depósito / Receita)</option>
                                <option value="saque">Saída (Saque / Gasto)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Valor (R$):</label>
                            <input type="number" step="0.01" name="valorMovimentacao"
                                class="form-control bg-black text-white border-secondary" placeholder="0.00" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Descrição / Banco / Nome:</label>
                            <input type="text" name="descricaoMovimentacao"
                                class="form-control bg-black text-white border-secondary"
                                placeholder="Ex: Salário, Nubank, Mercado..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Banco / Conta:</label>
                            <select name="idBanco" class="form-control bg-black text-white border-secondary" required>
                                <option value="">Selecione o banco...</option>
                                <?php if (!empty($bancosUsuario)): ?>
                                    <?php foreach ($bancosUsuario as $banco): ?>
                                        <option value="<?= $banco['cd_banco']; ?>"><?= htmlspecialchars($banco['nm_banco']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>Nenhum banco cadastrado. Cadastre um banco primeiro!</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Data:</label>
                            <input type="date" name="dataMovimentacao"
                                class="form-control bg-black text-white border-secondary" value="<?= date('Y-m-d'); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Forma de Pagamento:</label>
                            <select name="formaPagamento" class="form-control bg-black text-white border-secondary"
                                required>
                                <option value="Pix">Pix</option>
                                <option value="Dinheiro">Dinheiro</option>
                                <option value="Cartão de Crédito">Cartão de Crédito</option>
                                <option value="Cartão de Débito">Cartão de Débito</option>
                                <option value="Transferência">Transferência</option>
                            </select>
                        </div>

                        <div class="mb-3" id="blocoCategoria" style="display: none;">
                            <label class="form-label text-white">Categoria do Gasto / Conta:</label>
                            <select name="idCategoria" id="selectCategoria"
                                class="form-control bg-black text-white border-secondary">
                                <option value="">Selecione a categoria...</option>
                                <?php if (!empty($categoriasGasto)): ?>
                                    <?php foreach ($categoriasGasto as $cat): ?>
                                        <option value="<?= $cat['cd_categoria']; ?>">
                                            <?= htmlspecialchars($cat['nm_categoria']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>Nenhuma categoria encontrada.</option>
                                <?php endif; ?>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer border-top border-secondary">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm fw-bold px-4"
                            style="background-color: #8fe028; color: #0d0e12;">Salvar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>