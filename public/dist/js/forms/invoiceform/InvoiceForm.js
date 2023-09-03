import Form from "../Form.js";
import InputsWatchersList from "../watcher/InputWatcherList.js";
import ProductsHandler from "./Handlers/ProductHandler.js";
import InvoiceCalculationHandler from "./Handlers/InvoiceCalculationHandler.js";
import InvoiceMaskHandler from "./Handlers/InvoiceMaskHandler.js";


class InvoiceFormWatchersList extends InputsWatchersList {
    //we override getIsEmptyResolver to change the Resolver in the case of VAT_rate input
    getIsEmptyResolver(input) {
        if (input.name == "VAT_rate")
            return (value) => value.slice(1) == ''
        return (value) => value == '';
    }
}

export default class InvoiceForm extends Form {

    constructor(selector) {
        super(selector, new InvoiceFormWatchersList());
        this.productsHandler = new ProductsHandler();
        this.invoiceCalculationHandler = new InvoiceCalculationHandler();
        this.maskHandler = new InvoiceMaskHandler();
        this.handelProducts()
    }
    inputChanged(inputWatcher) {
        this.handelCalculations();
        this.handelProducts();
        this.handleInputsMask(inputWatcher);
    }
    beforeSubmit() {
        let VATRate = document.querySelector("#VAT_rate")
        VATRate.value = VATRate.value.slice(1);
    }
    handelCalculations() {
        this.invoiceCalculationHandler.execute();
    }
    handelProducts() {
        this.productsHandler.execute();
    }
    handleInputsMask(inputWatcher) {
        this.maskHandler.execute(inputWatcher);
    }
}
