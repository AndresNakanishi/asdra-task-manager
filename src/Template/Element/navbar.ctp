<div class="row mt-5">
    <div class="col-lg-12">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="<?= $this->Url->build('/', true) ?>init-dashboard">
                <img class="asdra-logo" src="<?= $this->Url->build('/', true) ?>img/logo.png" alt="ASDRA Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavDropdown">
             <ul class="navbar-nav d-flex align-items-center ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= $this->Url->build('/', true) ?>init-dashboard">Inicio</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="configDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Configuración
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="configDropdown">
                            <a class="dropdown-item" href="<?= $this->Url->build('/', true) ?>init-in-charge">Personas a Cargo</a>
                            <a class="dropdown-item" href="<?= $this->Url->build('/', true) ?>users/init-tutors">Tutores</a>
                            <a class="dropdown-item" href="<?= $this->Url->build('/', true) ?>companies/init-comp">Compañias</a>
                            <a class="dropdown-item" href="<?= $this->Url->build('/', true) ?>groups">Grupo de Tareas</a>
                        </div>
                    </li>
                    <li class="nav-item mr-5">
                        <a class="nav-link" href="<?= $this->Url->build('/', true) ?>logout">Salir</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $this->Url->build('/', true) ?>myprofile/<?= $authUser->user_id ?>">
                            <img class="user-image rounded-circle" src="<?= $authUser->photo ?>" alt="<?= $authUser->user ?>">
                        </a>
                    </li>
                </ul>
          </div>
        </nav>
    </div>
</div>