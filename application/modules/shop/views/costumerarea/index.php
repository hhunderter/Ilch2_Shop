<h1>
    <?=$this->getTrans('menuCostumerArea') ?>
</h1>

<?php if (!empty($this->get('orders'))) : ?>
    <div class="table-responsive">
        <table id="sortTable" class="table table-hover table-striped">
            <colgroup>
                <col class="icon_width">
                <col>
                <col>
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
            <tr>
                <th></th>
                <th class="sort"><?=$this->getTrans('status') ?></th>
                <th class="sort"><?=$this->getTrans('orderDate') ?></th>
                <th class="sort"><?=$this->getTrans('invoice').' '.$this->getTrans('numberShort') ?></th>
                <th class="sort"><?=$this->getTrans('name') ?></th>
                <th class="sort"><?=$this->getTrans('deliveryAddress') ?></th>
                <th class="sort"><?=$this->getTrans('invoiceAddress') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($this->get('orders') as $order): ?>
                <tr class="filter">
                    <td><a href="<?=$this->getUrl(['action' => 'show', 'id' => $order->getId()]) ?>" title="<?=$this->getTrans('showOrderDetails') ?>"><i class="fa-regular fa-folder-open"></i></a></td>
                    <td>
                        <?php if ($order->getStatus() == 0) : ?>
                            <div class="btn btn-sm alert-danger"><i class="fa-solid fa-plus-square" aria-hidden="true"></i>&nbsp;<b><?=$this->getTrans('newBIG') ?></b></div>
                        <?php elseif ($order->getStatus() == 1) : ?>
                            <div class="btn btn-sm alert-warning"><i class="fa-solid fa-pencil-square" aria-hidden="true"></i>&nbsp;<b><?= $this->getTrans('processingBIG') ?></b></div>
                        <?php elseif ($order->getStatus() == 2) : ?>
                            <div class="btn btn-sm alert-info"><i class="fa-solid fa-exclamation-triangle" aria-hidden="true"></i>&nbsp;<b><?= $this->getTrans('canceledBIG') ?></b></div>
                        <?php else : ?>
                            <div class="btn btn-sm alert-success"><i class="fa-solid fa-check-square" aria-hidden="true"></i>&nbsp;<b><?= $this->getTrans('completedBIG') ?></b></div>
                        <?php endif; ?>
                    </td>
                    <?php
                    $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $this->escape($order->getDatetime()));
                    ?>
                    <td>
                        <?=date_format($myDateTime, 'd.m.Y | H:i ') . $this->getTrans('dateTimeoClock') ?>
                    </td>
                    <?php
                    $orderDate = date_format($myDateTime, 'd.m.Y');
                    $invoiceNr = date_format($myDateTime, 'ymd').'-'.$order->getId();
                    ?>
                    <td>
                        <?=$invoiceNr ?>
                    </td>
                    <td>
                        <?=$this->escape($order->getInvoiceAddress()->getPrename()) ?> <?=$this->escape($order->getInvoiceAddress()->getLastname()) ?>
                    </td>
                    <td>
                        <?=$this->escape($order->getInvoiceAddress()->getStreet()) ?>,
                        <?=$this->escape($order->getInvoiceAddress()->getPostcode()) ?> <?=$this->escape($order->getInvoiceAddress()->getCity()) ?>,
                        <?=$this->escape($order->getInvoiceAddress()->getCountry()) ?>
                    </td>
                    <td>
                        <?=$this->escape($order->getDeliveryAddress()->getStreet()) ?>,
                        <?=$this->escape($order->getDeliveryAddress()->getPostcode()) ?> <?=$this->escape($order->getDeliveryAddress()->getCity()) ?>,
                        <?=$this->escape($order->getDeliveryAddress()->getCountry()) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
<p><?=$this->getTrans('costumerAreaNoPreviousOrder') ?></p>
<?php endif; ?>
