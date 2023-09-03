export default class InputsType {
    static inputsTypes = {
        INPUT: 'input',
        SELECT: 'change',
        TEXTAREA: 'input'
    };
    static mapToListenerName(inputType) {
        return this.inputsTypes[inputType];
    }
    static get getAll() {
        return Object.keys(this.inputsTypes);
    }
    static get getListenersNames() {
        return Object.values(this.inputsTypes);
    }

}
