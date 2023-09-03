
export default class Mask {
    static maskNames = ['percentage', 'float'];
    static maskFunction = {
        percentage:
            (input) => {
                if (input.value)
                    input.value = input.value.replace(/[^0-9%]/g, '').replace(/(?<=%.*)%/, '').replace(/^[^%]/g, '')
            },
        float: (input) => {
            if (input.value)
                input.value = input.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        }
    };
    static execute(input, maskName) {
        if (!this.maskNames.includes(maskName))
            console.error(`can't apply mask ${maskName} it does not exists`)
        this.maskFunction[maskName](input);
    }

}
