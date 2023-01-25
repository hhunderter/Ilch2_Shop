<link href="<?=$this->getModuleUrl('static/css/shop_admin.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('menuCostumers') ?></h1>
<div class="alert alert-danger"><?=$this->getTrans('warningDeletionOfCostumer') ?></div>

<div class="table-responsive">
    <table class="table table-hover table-striped">
        <colgroup>
            <col class="icon_width">
            <col class="icon_width">
            <col class="icon_width">
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th><?=$this->getCheckAllCheckbox('check_costumers') ?></th>
            <th></th>
            <th></th>
            <th><?=$this->getTrans('costumerId') ?></th>
            <th><?=$this->getTrans('emailAdress') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->get('costumers') as $costumer): ?>
            <tr id="<?=$this->escape($costumer->getId()) ?>">
                <td><?=$this->getDeleteCheckbox('check_costumers', $costumer->getId()) ?></td>
                <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $costumer->getId()]) ?></td>
                <td><a href="<?=$this->getUrl(['action' => 'show', 'id' => $costumer->getId()]) ?>" title="<?=$this->getTrans('showCostumerDetails') ?>"><i class="far fa-folder-open"></i></a></td>
                <td><?=$this->escape($costumer->getId()) ?></td>
                <td><?=$this->escape($costumer->getEmail()) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
