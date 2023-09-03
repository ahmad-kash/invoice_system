import InputsType from "./inputsTypes/InputsType.js";

export default class Form {
    constructor(selector, watcherListObject) {
        if (!selector)
            selector = 'form';
        if (!watcherListObject)
            console.error("watcherListObject is required");

        this.form = document.querySelector(selector);
        this.watcherList = watcherListObject;
        this.submitButton = null;
        this.inputs = null;
        this.init();
    }
    init() {
        this.getInputs()
            .getSubmitButton()
            .setSubmitWatcher()
            .setInputsWatcherList()
            .handleSubmitButtonState();
    }
    getInputs() {
        this.inputs = this.form.querySelectorAll(InputsType.getAll.join(','));
        return this;
    }
    getSubmitButton() {
        this.submitButton = this.form.querySelector('button[type=submit]');
        return this;
    }
    setInputsWatcherList() {
        this.watcherList.build(this.inputs, (args) => {
            this.handleSubmitButtonState()
            this.inputChanged(args)
        });
        return this;
    }
    setSubmitWatcher() {
        this.submitButton.addEventListener('click', (e) => { this.beforeSubmit(e) });
        return this;
    }
    inputChanged(inputWatcher) {

    }
    handleSubmitButtonState() {
        if (!this.watcherList.AllRequiredInputsAreNotEmpty)
            this.submitButton.disabled = true;
        else
            this.submitButton.disabled = false;
    }
}

