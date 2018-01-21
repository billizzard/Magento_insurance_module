function InsuranceShipping(info) {

    var data, infoNode, costNode, insuranceCheckbox;

    var init = function(info) {
        data = info;
        infoNode = jQuery('#insure-info');
        insuranceCheckbox = jQuery('#s_insurance');
        costNode = jQuery('#s_insurance_cost');
        addEvents();
    }

    var addEvents = function() {
        if (data.length) {
            var nodeShippingStep = jQuery('#checkout-step-shipping_method');

            nodeShippingStep.on('change', '.sp-methods input[name=shipping_method]' , function() {
                hideInfo();
                var value = jQuery(this).val();
                showInfo(findItem(value));
            });

            nodeShippingStep.on('DOMSubtreeModified', '#checkout-shipping-method-load', function() {
                var checkedItem = jQuery('.sp-methods input[name=shipping_method]:checked');
                if (checkedItem && checkedItem.length) {
                    var value = checkedItem.val();
                    showInfo(findItem(value));
                }
            })
        }
    }

    var findItem = function(value) {
        var result = false;
        data.forEach(function(item) {
            if (item.isEnabled && ~value.indexOf(item.method)) {
                result =  item;
            }
        })
        return result;
    }

    var hideInfo = function() {
        infoNode.css('display', 'none');
        insuranceCheckbox.prop('checked', false);
    }

    var showInfo = function(item) {
        if (item) {
            infoNode.css('display', 'block');
            costNode.html(item.message)
        }
    }

    init(info);
}





