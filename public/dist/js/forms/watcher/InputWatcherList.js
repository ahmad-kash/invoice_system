import InputWatcher from "./InputWatcher.js"

export default class InputsWatchersList {
    constructor() {
        this.watchers = [];
        this.self = null;
    }
    build(inputs, callback) {
        this.getRequiredInputs(inputs).forEach((input) => {
            this.watchers.push(new InputWatcher(input, (args) => { callback(args) }, this.getIsEmptyResolver(input)));
        });
        return this;
    }
    getIsEmptyResolver(input) {
        return ((value) => value == '');
    }
    getRequiredInputs(inputs) {
        // inputs is a node list so we must change it to an array to become iterable
        return [...inputs].filter((input) => input.required);
    }
    get AllRequiredInputsAreNotEmpty() {
        return this.watchers.reduce(
            (isAllInputsEmpty, watcher) => isAllInputsEmpty && !watcher.isEmpty, true);
    }
}
