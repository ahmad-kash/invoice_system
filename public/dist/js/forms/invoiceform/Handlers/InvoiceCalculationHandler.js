export default class InvoiceCalculationHandler {

    execute() {
        let commissionAmount = this.getInputValueAsFloat("commission_amount");
        let discount = this.getInputValueAsFloat("discount");
        let VATRate = this.getInputValueAsFloat("VAT_rate", (value) => {
            return value.slice(1);
        });

        const { VATValue, total } = this.calculateVATValueAndTotal(commissionAmount, discount, VATRate);

        this.setInputValue("#VAT_value", VATValue);
        this.setInputValue('#total', total);
    }
    getInputValueAsFloat(id, IsEmptyResolver = (value) => value) {
        let value = this.getInputValue(`#${id}`);

        value = IsEmptyResolver(value);
        if (!value)
            return parseFloat(0);

        return parseFloat(value);
    }

    calculateVATValueAndTotal(commissionAmount, discount, VATRate) {

        let commissionAmountAfterDiscount = commissionAmount - discount;

        let VATValue = commissionAmountAfterDiscount * VATRate / 100;

        let total = parseFloat(VATValue + commissionAmountAfterDiscount);

        VATValue = parseFloat(VATValue).toFixed(2);

        total = parseFloat(total).toFixed(2);
        return {
            total,
            VATValue
        };

    }
    setInputValue(selector, value) {
        if (!selector || !value)
            console.error('selector and value is required to set input value');
        let input = document.querySelector(selector);
        input.value = value;
    }
    getInputValue(selector) {
        if (!selector)
            console.error('selector and value is required to set input value');
        return document.querySelector(selector).value;
    }
}
