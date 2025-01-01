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
    <title>Envio no Privado</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
    
</head>
<body>
    
    <div class="container">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
            <span class="fs-4">Pequi Ai Sistema</span>
        </a>

        <ul class="nav nav-pills">
            <li class="nav-item"><a href="../index.php" class="nav-link " aria-current="page">Home</a></li>
            <li class="nav-item"><a href="../instancias/index.php" class="nav-link ">Instâncias</a></li>
            <li class="nav-item"><a href="../privateMessage/index.php" class="nav-link active">Envio no privado</a></li>
            <li class="nav-item"><a href="../../../services/auth/logout.php"class="nav-link">Sair</a></li>
        </ul>
        </header>
    </div>
    <div class="container text-center">
        <div class="row">
            <div class="col">
                <h1>Envio no privado</h1>
            </div>
            <div class="col">
            
            </div>
            <div class="col">
                <button type="button" class="btn btn-primary" id="openPopupBtn">Novo Envio</button>
            </div>
        </div>
    </div>
    <div class="container">
        
        </table>
    </div>
    <script src='./js/script.js'></script>
</body>
</html>