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
            <input type="text"
                   class="form-control"
                   id="clientID"
                   name="clientID"
                   placeholder="<?=$this->getTrans('clientID') ?>"
                   value="<?=($this->escape($this->get('settings')->getClientID()) != '') ? $this->escape($this->get('settings')->getClientID()) : $this->escape($this->originalInput('clientID')) ?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-2 control-label">
            <?=$this->getTrans('settingsPaypalAdvanced') ?>:
        </div>
        <div class="col-lg-4">
            <div class="flipswitch">
                <input type="radio" class="flipswitch-input" id="paypalAdvanced-on" name="paypalAdvanced" value="1" <?=($this->get('paypalAdvanced') == '1') ? 'checked="checked"' : '' ?> />
                <label for="paypalAdvanced-on" class="flipswitch-label flipswitch-label-on"><?=$this->getTrans('on') ?></label>
                <input type="radio" class="flipswitch-input" id="paypalAdvanced-off" name="paypalAdvanced" value="0" <?=($this->get('paypalAdvanced') != '1') ? 'checked="checked"' : '' ?> />
                <label for="paypalAdvanced-off" class="flipswitch-label flipswitch-label-off"><?=$this->getTrans('off') ?></label>
                <span class="flipswitch-selection"></span>
            </div>
        </div>
    </div>
    <?=$this->getSaveBar('saveButton') ?>
</form>
