import { createApp } from 'vue'
import CheckboxSearchList from '~/vue/CheckboxSearchList.vue'

// App main
const main = async () => {
    const checkboxFields = document.querySelectorAll('.csl-component')
    const checkboxFieldsToMount = new Object()

    checkboxFields.forEach( (checkboxField) => {

        let field = checkboxField.id.replace('-', '')

        checkboxFieldsToMount[field] = {
            'id': '#' + checkboxField.id,
            'app': createApp({ ...CheckboxSearchList })
        }

    })

    const root = Object.entries(checkboxFieldsToMount).map(entry => {
        let field = entry[1]
        return field.app.mount(field.id)
    })

    return root;
}

// Execute async function
main().then(() => {
    console.log()
})
