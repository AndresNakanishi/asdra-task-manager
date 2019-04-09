<div class="row mt-5">
    <div class="col-lg-12">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="<?php echo $this->Url->build('/', true) ?>init-dashboard">
                <img class="asdra-logo" src="<?php echo $this->Url->build('/', true) ?>img/logo.png" alt="ASDRA Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="d-flex justify-content-end collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav d-flex align-items-center">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo $this->Url->build('/', true) ?>init-dashboard">Inicio</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="configDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Configuración
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="configDropdown">
                            <a class="dropdown-item" href="<?php echo $this->Url->build('/', true) ?>init-in-charge">Personas a Cargo</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Reportes</a>
                    </li>
                    <li class="nav-item mr-5">
                        <a class="nav-link" href="<?php echo $this->Url->build('/', true) ?>logout">Salir</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <img class="user-image rounded-circle" src="<?= $authUser->photo ?>" alt="<?= $authUser->user ?>">
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
