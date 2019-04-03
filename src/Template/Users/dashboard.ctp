<?php $title = 'Dashboard';
$this->assign('title', $title);?>

<!-- Usuarios con tareas atrasadas -->
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body d-flex align-items-end flex-column">
                <h6 class="actualDate"><b><?= date('d/m/Y'); ?></b></h6>
                <div class="updateContainerHeader d-flex justify-content-between align-items-center mt-3">
                    <h5>Con tareas atrasadas a la <?= date('H:i A'); ?></h5>
                    <a class="updateContainer-iconSize" href="#"><i class="fas fa-sync-alt"></i></a>
                </div>
                <div class="updateContainerData">
                    <div class="updateContainerDataItem">
                        <div class="updateContainerDataItemUserData">
                            <div class="updateContainerDataItemUserDataImg">
                                <img class="user-image rounded-circle" src="https://ui-avatars.com/api/?size=128&font-size=0.33&background=CCC&color=000&name=Pedro+Martinez" alt="User">
                            </div>
                            <div class="updateContainerDataItemUserDataContainer">
                                <div class="updateContainerData-header">
                                    <p class="updateContainerData-username">Pedro Martinez</p>
                                    <p class="updateContainerData-business">(Empresa: UxorIT)</p>
                                </div>
                                <p class="updateContainerData-lastTask">No tiene tarea registradas hoy</p>
                            </div>
                        </div>
                        <div class="updateContainerDataItemActions">
                            <a class="updateContainer-iconSize" href="#"><i class="fas fa-bars"></i></a>
                            <a class="updateContainer-iconSize" href="#"><i class="fas fa-phone"></i></a>
                        </div>
                    </div>
                    <div class="updateContainerDataItem">
                        <div class="updateContainerDataItemUserData">
                            <div class="updateContainerDataItemUserDataImg">
                                <img class="user-image rounded-circle" src="https://ui-avatars.com/api/?size=128&font-size=0.33&background=CCC&color=000&name=Marina+Molinari" alt="User">
                            </div>
                            <div class="updateContainerDataItemUserDataContainer">
                                <div class="updateContainerData-header">
                                    <p class="updateContainerData-username">Marina Molinari</p>
                                    <p class="updateContainerData-business">(Empresa: UxorIT)</p>
                                </div>
                                <p class="updateContainerData-lastTask">Última tarea a la 07:23 hs - 2 tareas pendientes</p>
                            </div>
                        </div>
                        <div class="updateContainerDataItemActions">
                            <a class="updateContainer-iconSize" href="#"><i class="fas fa-bars"></i></a>
                            <a class="updateContainer-iconSize" href="#"><i class="fas fa-phone"></i></a>
                        </div>
                    </div>
                    <div class="updateContainerDataItem">
                        <div class="updateContainerDataItemUserData">
                            <div class="updateContainerDataItemUserDataImg">
                                <img class="user-image rounded-circle" src="https://ui-avatars.com/api/?size=128&font-size=0.33&background=CCC&color=000&name=Roberto+Rodriguez" alt="User">
                            </div>
                            <div class="updateContainerDataItemUserDataContainer">
                                <div class="updateContainerData-header">
                                    <p class="updateContainerData-username">Roberto Rodriguez</p>
                                    <p class="updateContainerData-business">(Empresa: NexusCOM)</p>
                                </div>
                                <p class="updateContainerData-lastTask">Última tarea a la 07:45 hs - 1 tarea pendiente</p>
                            </div>
                        </div>
                        <div class="updateContainerDataItemActions">
                            <a class="updateContainer-iconSize" href="#"><i class="fas fa-bars"></i></a>
                            <a class="updateContainer-iconSize" href="#"><i class="fas fa-phone"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Termina Usuarios con Tareas Atrasadas -->


<!-- Búsqueda de Users -->
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="usersContainer">
            <div class="card">
                <div class="card-body">
                    <div class="usersContainerHeader">
                        <h3 class="usersContainerHeaderTitle">Con tareas al día</h3>
                        <input type="search" class="form-control usersContainerSearch" id="search-input" placeholder="Buscar..." autocomplete="off" style="position: relative; vertical-align: top;">
                    </div>
                    <div class="usersContainerItems">
                        <div class="userCard d-flex flex-column">
                            <img class="usersContainerItemsImg rounded-circle" src="https://ui-avatars.com/api/?size=256&font-size=0.33&background=CCC&color=000&name=Yolanda+Ortiz" alt="User">
                            <p class="usersContainerItemsName">
                                Carlitos Ameguino
                            </p>
                        </div>
                        <div class="userCard d-flex flex-column">
                            <img class="usersContainerItemsImg rounded-circle" src="https://ui-avatars.com/api/?size=256&font-size=0.33&background=CCC&color=000&name=Yolanda+Ortiz" alt="User">
                            <p class="usersContainerItemsName">
                                Carlitos Ameguino
                            </p>
                        </div>
                        <div class="userCard d-flex flex-column">
                            <img class="usersContainerItemsImg rounded-circle" src="https://ui-avatars.com/api/?size=256&font-size=0.33&background=CCC&color=000&name=Yolanda+Ortiz" alt="User">
                            <p class="usersContainerItemsName">
                                Carlitos Ameguino
                            </p>
                        </div>
                        <div class="userCard d-flex flex-column">
                            <img class="usersContainerItemsImg rounded-circle" src="https://ui-avatars.com/api/?size=256&font-size=0.33&background=CCC&color=000&name=Yolanda+Ortiz" alt="User">
                            <p class="usersContainerItemsName">
                                Carlitos Ameguino
                            </p>
                        </div>
                        <div class="userCard d-flex flex-column">
                            <img class="usersContainerItemsImg rounded-circle" src="https://ui-avatars.com/api/?size=256&font-size=0.33&background=CCC&color=000&name=Yolanda+Ortiz" alt="User">
                            <p class="usersContainerItemsName">
                                Carlitos Ameguino
                            </p>
                        </div>
                        <div class="userCard d-flex flex-column">
                            <img class="usersContainerItemsImg rounded-circle" src="https://ui-avatars.com/api/?size=256&font-size=0.33&background=CCC&color=000&name=Yolanda+Ortiz" alt="User">
                            <p class="usersContainerItemsName">
                                Carlitos Ameguino
                            </p>
                        </div>
                        <div class="userCard d-flex flex-column">
                            <img class="usersContainerItemsImg rounded-circle" src="https://ui-avatars.com/api/?size=256&font-size=0.33&background=CCC&color=000&name=Yolanda+Ortiz" alt="User">
                            <p class="usersContainerItemsName">
                                Carlitos Ameguino
                            </p>
                        </div>
                        <div class="userCard d-flex flex-column">
                            <img class="usersContainerItemsImg rounded-circle" src="https://ui-avatars.com/api/?size=256&font-size=0.33&background=CCC&color=000&name=Yolanda+Ortiz" alt="User">
                            <p class="usersContainerItemsName">
                                Carlitos Ameguino
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Termina Búsqueda de Users -->
