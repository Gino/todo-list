<template>
    <div class="container max-w-lg mx-auto">
        <div :class='"bg-" + getColor() + " flex text-white p-4 sm:mt-8 mt-0 font-semibold shadow border-t-4 sm:rounded-t rounded-none border-" + getColor() + "-dark"'>
            <div class="flex-1 my-auto">Todo-list app</div>
            <a href="/" :class='"justify-end text-white no-underline text-sm bg-" + getColor() + "-dark px-3 py-2 rounded cursor-pointer"'>Alle taken</a>
            <a href="/logout" :class='"justify-end text-white no-underline text-sm bg-" + getColor() + "-dark px-3 py-2 rounded cursor-pointer ml-3"'>Uitloggen</a>
        </div>
        <div class="leading-normal sm:rounded-b rounded-none flex shadow sm:flex-row flex-col-reverse">
            <div class="w-full p-6 sm:rounded-b rounded-none text-sm bg-white pt-8 overflow-y-auto" style="max-height: 390px; min-height: 390px">
                <div class="font-semibold text-grey-darkest text-base mb-6">Maak een nieuwe lijst aan</div>

                <div class="mt-6 font-semibold text-grey-darkest">Wat is de naam van de lijst?</div>
                <input @keyup="errors.list = null" v-model="fields.list" class="mt-2 w-1/2 block border py-2 px-2 rounded" type="text" name="list">
                <div :class='"text-" + getColor() + " font-semibold mt-2 mb-1 text-xs"' v-if="getError('list')" v-text="getError('list')"></div>

                <button @click="submit" :disabled="disabled" :class='"bg-" + getColor() + " text-white px-4 py-2 rounded font-semibold mt-6 mr-2 hover:bg-" + getColor() + "-dark"' type="button">Maak lijst aan</button>
            </div>
        </div>
        <div class="flex justify-end sm:mr-0 mr-2">
            <div @click="setColor('red')" class="bg-red p-2 rounded-full mt-4 ml-2 border-white shadow border cursor-pointer"></div>
            <div @click="setColor('green')" class="bg-green p-2 rounded-full mt-4 ml-2 border-white shadow border cursor-pointer"></div>
            <div @click="setColor('blue')" class="bg-blue p-2 rounded-full mt-4 ml-2 border-white shadow border cursor-pointer"></div>
        </div>
    </div>
</template>
<script>
export default {
    data () {
        return {
            color: 'red',

            disabled: false,

            fields: {
                list: null,
            },

            errors: {
                list: null,
            }
        }
    },

    mounted () {
        this.color = this.$cookies.isKey('color') ? this.$cookies.get('color') : 'red'
    },

    methods: {
        setColor (color) {
            this.color = color

            this.$cookies.set('color', color)
        },

        getColor () {
            return this.color
        },

        getError (field) {
            return this.errors[field]
        },

        submit () {
            this.errors.list = null

            if (this.fields.list === null  || this.fields.list == "") {
                this.errors.list ='Vul alstublieft een naam in voor de lijst.'
            }

            if (this.errors.list === null) {
                this.disabled = true

                axios.post('/lists/create', {
                    list: this.fields.list
                }).then((res) => {
                    if (res.status !== 200) return
                    window.location.href = '/'
                });
            }
        }
    }
}
</script>
