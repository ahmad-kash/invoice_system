export default class ProductsHandler {

    constructor() {
        this.selectListSection = document.querySelector('#section_id');
        this.selectListProduct = document.querySelector('#product_id');
        this.products = [];
        this.selectedSectionId = null;
    }

    async execute() {
        let newSelectedSectionId = this.selectListSection.selectedOptions[0].value;
        //if selected option has changed get new data
        if (newSelectedSectionId && newSelectedSectionId != this.selectedSectionId) {
            this.selectedSectionId = newSelectedSectionId;
            await this.getProducts(this.selectedSectionId);
            this.putProductInProductList();
        }

    }
    async getProducts(selectedSectionId) {
        const response = await fetch(`/sections/${selectedSectionId}/products`);
        this.products = await response.json();
    }

    putProductInProductList() {
        this.removeAllProductsFromSelectList();

        if (this.products.length == 0) {
            this.addOptionToSelectList({ innerText: 'لا يوجد منتجات متاحة', selected: true, disabled: true });
            return;
        }


        this.addOptionToSelectList({ innerText: 'حدد المنتج المطلوب', selected: true, disabled: true });

        this.products.forEach(element => {
            this.addOptionToSelectList({ value: element.id, innerText: element.name });
        });
    }
    addOptionToSelectList({ value = '', innerText = '', selected = false, disabled = false }) {
        var option = document.createElement('option');
        if (selected)
            option.selected = selected;
        if (disabled)
            option.disabled = disabled;
        if (value)
            option.value = value;
        if (innerText)
            option.innerText = innerText;
        this.selectListProduct.appendChild(option);
    }
    removeAllProductsFromSelectList() {
        while (this.selectListProduct.firstChild) {
            this.selectListProduct.removeChild(this.selectListProduct.firstChild);
        }
    }
}
