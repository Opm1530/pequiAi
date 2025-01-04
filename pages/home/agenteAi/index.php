<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Agente AI</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
    
</head>
<body>
    <div class="popup-background" id="popup">
        <div class="popup">
            <div class="firstStep">
                <div id="haveApi">
                    <h1>API CADASTRADA E CONECTADA</h1>
                    <button class="nextStep">Avançar</button>
                </div>
                <div id="dontHaveApi">
                    <input type="text" placeholder="API OPEN AI">
                    <button>Salvar</button>
                </div>
                <div id="loading">
                    <img src="../../../source/img/loading-gif.gif" width="130px">
                    <span>Verificando API...</span>
                </div>
            </div>
            <div class="secondStep" style="display: none;">
                <input type="text" placeholder="Nome do Agente">
                <select>
                    <option>MODELO CHATPT</option>
                </select>
                <button class="nextStep">Avançar</button>
                <button class="prevStep">Voltar</button>
            </div>
            <div class="thirdStep" style="display: none;">
                <textarea placeholder="Instruções do Agente"></textarea>
                <button>Criar</button>
                <button class="prevStep">Voltar</button>
            </div>
            <button class="btn btn-danger" id="closePopupBtn">Fechar Pop-Up</button>
        </div>
    </div>
    
    <div class="container">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
            <span class="fs-4">Pequi Ai Sistema</span>
        </a>

        <ul class="nav nav-pills">
            <li class="nav-item"><a href="../index.php" class="nav-link " aria-current="page">Home</a></li>
            <li class="nav-item"><a href="../instancias/index.php" class="nav-link ">Instâncias</a></li>
            <li class="nav-item"><a href="../privateMessage/index.php" class="nav-link active">Agente AI</a></li>
            <li class="nav-item"><a href="../../../services/auth/logout.php"class="nav-link">Sair</a></li>
        </ul>
        </header>
    </div>
    <div class="container text-center">
        <div class="row">
            <div class="col">
                <h1>Agente AI</h1>
            </div>
            <div class="col">
            
            </div>
            <div class="col">
                <button type="button" class="btn btn-primary" id="openPopupBtn">Novo Agente</button>
            </div>
        </div>
    </div>
    <button id="checkAPIButton">CHECK API</button>
    <div class="container">
        
        </table>
    </div>
    <script src='./js/script.js'></script>
    
</body>
</html>