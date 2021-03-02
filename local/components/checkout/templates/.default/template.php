<?
    /* @var $arResult */
    //var_dump($arResult);
?>

<div class="checkout">
    <div class="row">
        <? if (isset($arResult['ORDER_STATUS'])): ?>
        <div class="col-md-4 offset-4">
            <div class="alert alert-success" role="alert">
                <h5 class="alert-heading">Заказ успешно оформлен!</h5>
                <p>
                    <small>Мы уже начали собирать Ваш заказ.<br>Скоро он будет передан для транспортировки.</small>
                </p>
            </div>
        </div>
        <? else: ?>
            <div class="col-7 offset-1">
                <div class="checkout-form-box">
                    <div class="card">
                        <div class="card-header">
                            <div class="h4 text-center">Оформление заказа</div>
                        </div>
                        <div class="card-body">
                            <form class="form" method="POST" action="/checkout/">
                                <div class="form-group row">
                                    <label for="name" class="col-4">ФИО*</label>
                                    <div class="col-8">
                                        <input type="text" name="name" id="name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="phone" class="col-4">Телефон*</label>
                                    <div class="col-8">
                                        <input type="text" name="phone" id="phone" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-4">E-mail</label>
                                    <div class="col-8">
                                        <input type="email" name="email" id="email" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="address" class="col-4">Адрес</label>
                                    <div class="col-8">
                                        <input type="text" name="address" id="address" class="form-control">
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="h6 text-muted mb-3">Выберите службу доставки</div>
                                <? foreach ($arResult['DELIVERY'] as $key => $service): ?>
                                    <div class="form-group">
                                        <label class="row align-items-center">
                                        <span class="col-1">
                                            <input type="radio" name="delivery"
                                                   value="<?= $service['ID'] ?>"
                                                   <?= empty($key) ? 'checked' : '' ?>
                                            >
                                        </span>
                                            <span class="col-3">
                                            <img src="<?= $service['LOGO'] ?>" alt="<?= $service['LOGO'] ?>" class="delivery-logo">
                                        </span>
                                            <span class="col-4">Доставка <?= $service['DEADLINE'] ?></span>
                                            <span class="col-4">
                                            <span class="btn btn-warning"><?= $service['COST'] ?> ₽</span>
                                        </span>
                                        </label>
                                    </div>
                                <? endforeach ?>
                                <hr class="mb-4">
                                <div class="h6 text-muted">Оплата</div>
                                <div class="row align-items-center">
                                    <? foreach ($arResult['PAYMENTS'] as $key => $service): ?>
                                        <div class="form-group col-4">
                                            <label class="align-items-center row">
                                        <span class="col-1">
                                            <input type="radio" name="payment"
                                                   value="<?= $service['URL'] ?>"
                                                   <?= empty($key) ? 'checked' : '' ?>
                                            >
                                        </span>
                                                <span class="col-3">
                                            <img src="<?= $service['LOGO'] ?>" alt="<?= $service['LOGO'] ?>"
                                                 class="payment-logo<?= empty($key) ? ' payment-logo_ukassa' : '' ?>"
                                            >
                                        </span>
                                            </label>
                                        </div>
                                    <? endforeach ?>
                                </div>
                                <input type="hidden" name="sum" value="<?= $arResult['CART']['sum'] ?>">
                                <input type="hidden" name="qty" value="<?= $arResult['CART']['qty'] ?>">
                                <div class="form-group">
                                    <input type="submit" value="Подтвердить" class="btn btn-success mt-3">
                                    <a href="/checkout" class="btn btn-dark mt-3">Отменить</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        <div class="h5 text-center">Корзина</div>
                    </div>
                    <div class="card-body">
                        <? if (empty($arResult['CART']) || empty($arResult['CART']['sum'])): ?>
                            <p>Корзина пуста</p>
                        <? else: ?>
                            <p>Всего товаров: <?= $arResult['CART']['qty'] ?></p>
                            <p>Сумма: <?= $arResult['CART']['sum'] ?></p>
                        <? endif ?>
                    </div>
                </div>
            </div>
        <? endif ?>
    </div>
</div>