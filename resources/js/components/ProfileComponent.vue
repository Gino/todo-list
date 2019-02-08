<template>
    <div class="container max-w-lg mx-auto">
        <div :class='"bg-" + getColor() + " flex text-white p-4 sm:mt-8 mt-0 font-semibold shadow border-t-4 sm:rounded-t rounded-none border-" + getColor() + "-dark"'>
            <div class="flex-1 my-auto">Todo-list app</div>
            <a href="/" :class='"justify-end text-white no-underline text-sm bg-" + getColor() + "-dark px-3 py-2 rounded cursor-pointer"'>Alle taken</a>
            <a href="/logout" :class='"justify-end text-white no-underline text-sm bg-" + getColor() + "-dark px-3 py-2 rounded cursor-pointer ml-3"'>Uitloggen</a>
        </div>
        <div class="leading-normal sm:rounded-b rounded-none flex shadow sm:flex-row flex-col-reverse">
            <div class="w-full p-6 sm:rounded-b rounded-none text-sm bg-white pt-8 overflow-y-auto" style="max-height: 390px; min-height: 390px">
                <div class="mt-2 font-semibold text-grey-darkest">Jouw gegevens</div>
                <input type="text" :value="user.name" class="mt-2 w-1/2 block border py-2 px-2 rounded cursor-not-allowed" disabled>
                <input type="email" :value="user.email" class="mt-2 w-1/2 block border py-2 px-2 rounded cursor-not-allowed" disabled>

                <div class="mt-4 font-semibold text-grey-darkest">Jouw rol</div>
                <input type="email" :value="user.role.name" class="mt-2 w-1/2 block border py-2 px-2 rounded cursor-not-allowed" disabled>

                <div class="mt-4 font-semibold text-grey-darkest">Profile aangemaakt op</div>
                <input type="email" :value="getCreatedAtDate()" class="mt-2 w-1/2 block border py-2 px-2 rounded cursor-not-allowed" disabled>
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
            user: {
                name: null,
                email: null,
                role: {
                    name: null
                }
            }
        }
    },

    mounted () {
        this.color = this.$cookies.isKey('color') ? this.$cookies.get('color') : 'red'

        axios.get('/user').then(response => {
            if (response.status !== 200) return

            this.user = response.data.user
        })
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

        getCreatedAtDate() {
            const date = new Date(this.user.created_at)

            var month = [];
            month[0] = "Januari";
            month[1] = "Februari";
            month[2] = "Maart";
            month[3] = "April";
            month[4] = "Mei";
            month[5] = "Juni";
            month[6] = "Juli";
            month[7] = "Augustus";
            month[8] = "September";
            month[9] = "Oktober";
            month[10] = "November";
            month[11] = "December";

            return date.getDay() + " " + month[date.getMonth()] + " " + date.getFullYear() + " - " + date.getHours() + ":" + date.getMinutes()
        }
    }
}
</script>
