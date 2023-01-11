<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuSettings') ?></h1>

<form class="form-horizontal" method="POST" action="">
    <?=$this->getTokenField() ?>
    <ul class="nav nav-tabs">
        <li>
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'index']) ?>">
                <i class="fas fa-store"></i> <?=$this->getTrans('menuSettingShop') ?>
            </a>
        </li>
        <li>
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'bank']) ?>">
                <i class="fas fa-university"></i> <?=$this->getTrans('menuSettingBank') ?>
            </a>
        </li>
        <li>
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'default']) ?>">
                <i class="fas fa-tools"></i> <?=$this->getTrans('menuSettingDefault') ?>
            </a>
        </li>
        <li>
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'agb']) ?>">
                <i class="fas fa-gavel"></i> <?=$this->getTrans('menuSettingAGB') ?>
            </a>
        </li>
        <li class="active">
            <a href="<?=$this->getUrl(['controller' => 'settings', 'action' => 'payment']) ?>">
                <i class="fas fa-money-bill"></i> <b><?=$this->getTrans('menuSettingPayment') ?></b>
            </a>
        </li>
    </ul>
    <br />
    <div class="form-group <?=$this->validation()->hasError('clientID') ? 'has-error' : '' ?>">
        <label for="clientID" class="col-lg-2 control-label">
            <?=$this->getTrans('clientID') ?>:
        </label>
        <div class="col-lg-3">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="fas fa-info" data-toggle="event-popover" title="<?=$this->getTrans('popoverInfo') ?>" data-content="<?=$this->getTrans('clientIDInfo') ?>"></span>
                </span>
                <input type="text"
                       class="form-control"
                       id="clientID"
                       name="clientID"
                       placeholder="<?=$this->getTrans('clientID') ?>"
                       value="<?=($this->escape($this->get('settings')->getClientID()) != '') ? $this->escape($this->get('settings')->getClientID()) : $this->escape($this->originalInput('clientID')) ?>"
                       required />
            </div>
        </div>
    </div>
    <?=$this->getSaveBar('saveButton') ?>
</form>

<script>
    $(function () {
        $('[data-toggle="event-popover"]').popover({
            container: 'body',
            trigger: 'hover',
            placement: 'top',
        });
    });
</script>
