<div class="modal fade" id="js-one-click-modal">
    <div class="modal-dialog pre-order" role="document">
        <div class="modal-content pre-order__content">
            <div class="modal-header">
                <h5 class="modal-title pre-order__title">Введите данные для предзаказа</h5>
                <button type="button" class="close pre-order__close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="uform">
                <div class="modal-body pre-order__body">
                    <div class="form-group pre-order__form-group clearfix">
                        <label class="col-sm-3 control-label pre-order__control-label" for="input-uname">Ваше имя:</label>
                        <div class="col-sm-7">
                            <input type="text" name="uname" value="" placeholder="Имя" id="input-uname" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group pre-order__form-group clearfix">
                        <label class="col-sm-3 control-label pre-order__control-label" for="input-uphone">Телефон:</label>
                        <div class="col-sm-7">
                            <input type="text" name="uphone" value="" placeholder="Телефон" id="input-uphone" class="form-control" />
                        </div>
                    </div>
                    <input type="text" name="uproduct_id" value="" hidden>
                    <input type="text" name="uprice" value="" hidden>
                    <input type="text" name="uquantity" value="" hidden>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="js-submit-oneclick" class="btn btn-secondary pre-order__btn" >Отправить</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>


    // Flabers
    function preorder(product_id,price,quantity=1) {
        $('input[name="uproduct_id"]').val(product_id);
        $('input[name="uprice"]').val(price);
        $('input[name="uquantity"]').val(quantity);
        $('#input-uphone').mask("+38(999) 999-9999").val('');
        $('input[name="uname"]').val('');
        $('#js-one-click-modal').modal('show');
    }

    $('#uform').submit(function () {
        return false;
    });

    $('#uform').validate({
        rules: {
            uname: {
                required: true
            },
            uphone: {
                required: true
            }
        },
        submitHandler: function(form) {
            var udata=$('#uform').serialize();
            $('.modal-footer').remove();
            $('.pre-order__body').empty().append('<img src="image/preloader4.gif" style="display: block;margin: 0 auto;">');

            $.ajax({
                url: 'index.php?route=extended/oneclick/oneclick_order',
                type: 'post',
                data: udata,
                dataType: 'json',
                success: function(json) {

                    dataLayer.push({
                        'eventCategory':'preorder',
                        'eventAction':'send',
                        'event':'event-to-ga'
                    });

                    $('.pre-order__title').text('Спасибо, Ваш заказ успешно оформлен!');
                    $('.pre-order__body').empty().append('<p>Номер Вашего заказа '+json.order_id+'. Ожидайте звонка для подтверждения заказа.</p>');
                    $('.modal-footer').remove();

                    $('#js-one-click-modal').modal('show');
                }
            });
        }
    });



</script>