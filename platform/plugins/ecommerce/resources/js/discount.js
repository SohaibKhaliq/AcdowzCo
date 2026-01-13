import DiscountComponent from './components/DiscountComponent.vue'

if (typeof vueApp !== 'undefined') {
    vueApp.booting((vue) => {
        vue.component('discount-component', DiscountComponent)
    })
}
