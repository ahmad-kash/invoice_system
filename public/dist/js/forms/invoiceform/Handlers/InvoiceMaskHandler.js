import Mask from "../../mask/Mask.js";

export default class InvoiceMaskHandler {
    // name:maskName
    static mapInputsNameMaskedName = {
        collection_amount: 'float',
        commission_amount: 'float',
        discount: 'float',
        VAT_value: 'float',
        total: 'float',
        VAT_rate: 'percentage',
    }
    execute(inputWatcher) {
        if (inputWatcher && Object.keys(InvoiceMaskHandler.mapInputsNameMaskedName).includes(inputWatcher.inputName))
            Mask.execute(inputWatcher.input, InvoiceMaskHandler.mapInputsNameMaskedName[inputWatcher.inputName]);
    }
}
