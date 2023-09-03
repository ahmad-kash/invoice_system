import InputsType from "../inputsTypes/InputsType.js";

export default class InputWatcher {
    constructor(input, callback, valueIsEmptyResolver) {
        this.callback = callback;
        this.valueIsEmptyResolver = valueIsEmptyResolver ?? ((value) => value == '');
        this.input = input;
        this.inputName = this.input.name;
        this.init();
        this.isEmpty = this.valueIsEmptyResolver(this.input.value);
    }
    init() {
        this.input.addEventListener(InputsType.mapToListenerName(this.input.tagName), (e) => this.inputHasChange());
    }
    inputHasChange() {
        this.isEmpty = this.valueIsEmptyResolver(this.input.value);
        this.callback(this);
    }
}
