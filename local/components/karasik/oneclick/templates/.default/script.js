BX.ready(function () {
  var oPopup = new BX.PopupWindow('one-click-order', window.body, {
    autoHide: true,
    offsetTop: 1,
    offsetLeft: 0,
    lightShadow: true,
    closeIcon: true,
    closeByEsc: true,
    overlay: {
      backgroundColor: 'black',
      opacity: '80',
    },
  });
  oPopup.setContent(BX('one-click-popup'));
  BX.bindDelegate(
    BX('one-click-container'),
    'click',
    {
      tagName: 'button',
    },
    BX.proxy(function (e) {
      if (!e) e = window.event;
      oPopup.show();
      return BX.PreventDefault(e);
    }, oPopup)
  );

  BX.addCustomEvent('onCatalogStoreProductChange', function (changeID) {
    //событие изменения ID выбранного торгового предложения
    const productId = document.querySelector('.product-id');
    productId.value = changeID;
  });
});

function addOrder(arParams) {
  const form = document.querySelector('#one-click-form');
  const quantity = document.querySelector('.product-item-amount-field') ? document.querySelector('.product-item-amount-field').value : null;
  const formData = new FormData(form);
  formData.set('quantity', quantity);
  BX.ajax
    .runComponentAction('karasik:oneclick', 'ajaxRequest', {
      mode: 'class',
      method: 'POST',
      signedParameters: arParams,
      data: formData,
    })
    .then(
      (response) => {
        if (response.status === 'success') {
          form.reset();
          const btn = document.getElementById('one-click-btn');
          btn.classList.add('ui-btn-disabled');
          btn.disabled = true;
          const feedback = document.querySelector('.one-click-feedback');
          feedback.classList.remove('one-click-feedback_hidden');
          console.log(response);
        }
      },
      (response) => {
        if (response.status === 'error') {
          console.log(response.errors[0].message);
        }
      }
    );
}
