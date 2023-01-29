<?php
$itemsMapper = $this->get('itemsMapper');
?>

<h1>
    <?=$this->getTrans('menuCostumerArea') ?>
</h1>

<?php if (!empty($this->get('order'))) : ?>
    <?php
    $order = $this->get('order');
    $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $this->escape($order->getDatetime()));
    $orderTime = date_format($myDateTime, ' H:i ');
    $orderDate = date_format($myDateTime, 'd.m.Y ');
    $invoiceNr = date_format($myDateTime, 'ymd').'-'.$order->getId();
    ?>

    <h4><?=$this->getTrans('CostumerAreaInfoBuyer') ?></h4>
    <div class="table-responsive">
        <table class="table">
            <colgroup>
                <col class="col-lg-1">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th><?=$this->getTrans('name') ?></th>
                <td><?=$this->escape($order->getInvoiceAddress()->getPrename()) ?> <?=$this->escape($order->getInvoiceAddress()->getLastname()) ?></td>
            </tr>
            <tr>
                <th><?=$this->getTrans('deliveryAddress') ?></th>
                <td><?=$this->escape($order->getInvoiceAddress()->getStreet()) ?>, <?=$this->escape($order->getInvoiceAddress()->getPostcode()) ?> <?=$this->escape($order->getInvoiceAddress()->getCity()) ?>, <?=$this->escape($order->getInvoiceAddress()->getCountry()) ?></td>
            </tr>
            <tr>
                <th><?=$this->getTrans('invoiceAddress') ?></th>
                <td><?=$this->escape($order->getDeliveryAddress()->getStreet()) ?>, <?=$this->escape($order->getDeliveryAddress()->getPostcode()) ?> <?=$this->escape($order->getDeliveryAddress()->getCity()) ?>, <?=$this->escape($order->getDeliveryAddress()->getCountry()) ?></td>
            </tr>
            <tr>
                <th><?=$this->getTrans('emailAdress') ?></th>
                <td><a href="mailto:<?=$this->escape($order->getEmail()) ?>"><?=$this->escape($order->getEmail()) ?></a></td>
            </tr>
            <tr>
                <th><?=$this->getTrans('date') ?></th>
                <td><?=$orderDate . $this->getTrans('dateTimeAt') . $orderTime .$this->getTrans('dateTimeoClock') ?></td>
            </tr>
            <tr>
                <th><?=$this->getTrans('invoice') ?></th>
                <td><?=$this->getTrans('invoice') ?> - <?=utf8_decode($this->getTrans('numberShort')) ?> <?=$invoiceNr ?></td>
            </tr>
            <tr>
                <th><?=$this->getTrans('status') ?></th>
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
            </tr>
            </tbody>
        </table>
    </div>

    <h4><?=$this->getTrans('orderedItems') ?></h4>
    <div class="cart">
        <table>
            <thead>
            <tr>
                <th scope="col" width="10%"><?=$this->getTrans('productImage') ?><br />&nbsp;</th>
                <th scope="col" width="25%"><?=$this->getTrans('productName') ?><br /><small><?=$this->getTrans('itemNumber') ?></small></th>
                <th scope="col" width="15%"><?=$this->getTrans('singlePrice') ?><br /><small><?=$this->getTrans('withoutTax') ?></small></th>
                <th scope="col" width="10%"><?=$this->getTrans('taxShort') ?><br />&nbsp;</th>
                <th scope="col" width="15%"><?=$this->getTrans('singlePrice') ?><br /><small><?=$this->getTrans('withTax') ?></small></th>
                <th scope="col" width="10%" class="text-center"><?=$this->getTrans('entries') ?><br />&nbsp;</th>
                <th scope="col" width="15%" class="text-right"><?=$this->getTrans('total') ?><br /><small><?=$this->getTrans('withTax') ?></small></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $subtotal_price = 0;
            foreach ($_SESSION['shopping_cart'] as $product) {
                $itemId = $product['id'];
                $itemImg = $itemsMapper->getShopItemById($itemId)->getImage();
                $itemName = $itemsMapper->getShopItemById($itemId)->getName();
                $itemNumber = $itemsMapper->getShopItemById($itemId)->getItemnumber();
                $itemPrice = $itemsMapper->getShopItemById($itemId)->getPrice();
                $itemTax = $itemsMapper->getShopItemById($itemId)->getTax();
                $itemPriceWithoutTax = round(($itemPrice / (100 + $itemTax)) * 100, 2);
                $arrayShippingCosts[] = $itemsMapper->getShopItemById($itemId)->getShippingCosts();
                $arrayTaxes[] = $itemTax;
                $arrayPrices[] = $itemPrice * $product['quantity'];
                $arrayPricesWithoutTax[] = $itemPriceWithoutTax * $product['quantity'];
                $shopImgPath = '/application/modules/shop/static/img/';
                if ($itemImg AND file_exists(ROOT_PATH.'/'.$itemImg)) {
                    $img = BASE_URL.'/'.$itemImg;
                } else {
                    $img = BASE_URL.$shopImgPath.'noimg.jpg';
                }
                ?>
                <tr>
                    <td data-label="<?=$this->getTrans('productImage') ?>">
                        <img src="<?=$img ?>" alt="<?=$this->escape($itemName) ?>"/>
                    </td>
                    <td data-label="<?=$this->getTrans('productName') ?>">
                        <b><?=$this->escape($itemName); ?></b><br /><small><?=$this->escape($itemNumber); ?></small>
                    </td>
                    <td data-label="<?=$this->getTrans('singlePrice') ?> (<?=$this->getTrans('withoutTax') ?>)">
                        <?=number_format($itemPriceWithoutTax, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?>
                    </td>
                    <td data-label="<?=$this->getTrans('taxShort') ?>"><?=$itemTax ?> %</td>
                    <td data-label="<?=$this->getTrans('singlePrice') ?> (<?=$this->getTrans('withTax') ?>)">
                        <?=number_format($itemPrice, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?>
                    </td>
                    <td data-label="<?=$this->getTrans('entries') ?>" class="text-center">
                        <b><?=$product['quantity'] ?></b>
                    </td>
                    <td data-label="<?=$this->getTrans('total') ?> (<?=$this->getTrans('withTax') ?>)" class="text-right">
                        <b><?=number_format($itemPrice * $product['quantity'], 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?></b>
                    </td>
                </tr>
                <?php
                $subtotal_price += round($itemPrice * $product['quantity'], 2);
            }
            ?>
            </tbody>
        </table>
        <table class="sum">
            <tr>
                <th>
                    <?=$this->getTrans('deliveryCosts') ?>
                </th>
                <td data-label="<?=$this->getTrans('deliveryCosts') ?>" class="text-right">
                    <?php $shipping_costs = max($arrayShippingCosts); ?>
                    <?=number_format($shipping_costs, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?>
                </td>
            </tr>
            <tr>
                <th>
                    <?=$this->getTrans('subtotal') ?> (<?=$this->getTrans('withTax') ?>)
                </th>
                <td data-label="<?=$this->getTrans('subtotal') ?> (<?=$this->getTrans('withTax') ?>)" class="text-right">
                    <?php $total_price = array_sum($arrayPrices) + $shipping_costs; ?>
                    <?=number_format($total_price, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?>
                </td>
            </tr>
            <tr>
                <th>
                    <?=$this->getTrans('subtotal') ?> (<?=$this->getTrans('withoutTax') ?>)
                </th>
                <td data-label="<?=$this->getTrans('subtotal') ?> (<?=$this->getTrans('withTax') ?>)" class="text-right">
                    <?php $sumPricewithoutTax = array_sum($arrayPricesWithoutTax) + round(($shipping_costs / (100 + max($arrayTaxes))) * 100, 2); ?>
                    <?=number_format($sumPricewithoutTax, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?>
                </td>
            </tr>
            <tr>
                <th>
                    <?=$this->getTrans('tax') ?>
                </th>
                <td data-label="<?=$this->getTrans('tax') ?>" class="text-right">
                    <?php $differenzTax = round($total_price - $sumPricewithoutTax, 2); ?>
                    <?=number_format($differenzTax, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?>
                </td>
            </tr>
            <tr>
                <th>
                    <b><?=$this->getTrans('totalPrice') ?></b>
                </th>
                <td data-label="<?=$this->getTrans('totalPrice') ?>" class="text-right">
                    <b><?=number_format($total_price, 2, '.', '') ?> <?=$this->escape($this->get('currency')) ?></b>
                </td>
            </tr>
        </table>
    </div>
<?php else : ?>
<p><?=$this->getTrans('costumerAreaOrderNotFound') ?></p>
<?php endif; ?>
