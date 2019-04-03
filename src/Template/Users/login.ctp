<?php $title = 'Login';
$this->assign('title', $title);?>
<?= $this->Form->create(null, ['class' => 'form-signin']); ?>
    <img class="mb-4" src="img/logo.png" alt="ASDRA">

    <label for="inputUser" class="sr-only">Usuario</label>

    <input type="text" id="inputUser" name="user" class="form-control" placeholder="Usuario" required autofocus>

    <label for="inputPassword" class="sr-only">Contraseña</label>

    <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Contraseña" required>

    <div class="checkbox mb-3">
        <label>
            <input type="checkbox" value="remember-me"> Recuerdame
        </label>
    </div>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
    <p class="mt-5 mb-3 text-muted">&copy; ASDRA. Todos los derechos reservados.</p>
<?= $this->Form->end(); ?>
