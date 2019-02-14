<template>
    <div class="container max-w-lg mx-auto">
        <div :class='"bg-" + getColor() + " flex text-white p-4 sm:mt-8 mt-0 font-semibold shadow border-t-4 sm:rounded-t rounded-none border-" + getColor() + "-dark"'>
            <div class="flex-1 my-auto">Todo-list app</div>
            <a href="/" :class='"justify-end text-white no-underline text-sm bg-" + getColor() + "-dark px-3 py-2 rounded cursor-pointer"'>Alle taken</a>
            <a href="/logout" :class='"justify-end text-white no-underline text-sm bg-" + getColor() + "-dark px-3 py-2 rounded cursor-pointer ml-3"'>Uitloggen</a>
        </div>
        <div class="leading-normal sm:rounded-b rounded-none flex shadow sm:flex-row flex-col-reverse">
            <div class="w-full p-6 sm:rounded-b rounded-none text-sm bg-white pt-4 overflow-y-auto" style="max-height: 390px; min-height: 390px">
                <div class="font-semibold text-grey-darkest">Jouw gegevens</div>
                <input type="text" :value="user.name" class="mt-2 w-1/2 block border py-2 px-2 rounded cursor-not-allowed" disabled>
                <input type="email" :value="user.email" class="mt-2 w-1/2 block border py-2 px-2 rounded cursor-not-allowed" disabled>

                <div class="mt-4 font-semibold text-grey-darkest">Jouw rol</div>
                <input type="email" :value="user.role.name" class="mt-2 w-1/2 block border py-2 px-2 rounded cursor-not-allowed" disabled>

                <div class="mt-4 font-semibold text-grey-darkest">Profile aangemaakt op</div>
                <input type="email" :value="getCreatedAtDate(this.user.created_at)" class="mt-2 w-1/2 block border py-2 px-2 rounded cursor-not-allowed" disabled>

                <div v-if="user.role.name === 'Administrator'" class="mt-8 font-semibold text-grey-darkest">Alle gebruikers</div>
                <table v-if="user.role.name === 'Administrator'" class="leading-normal mt-2 w-full text-grey-darker">
                    <tr>
                        <th class="text-left">#</th>
                        <th class="text-left">Naam</th>
                        <th class="text-left">E-mail</th>
                        <th class="text-left">Rol</th>
                        <th class="text-left">Aantal taken</th>
                        <th class="text-left">Aanmaakdatum</th>
                    </tr>
                    <tr class="text-black" v-for="user in users" :key="user.id">
                        <td class="font-semibold">{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.role.name }}</td>
                        <td>{{ user.tasks.length }}</td>
                        <td>{{ getCreatedAtDate(user.created_at) }}</td>
                    </tr>
                </table>
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
            },
            users: null
        }
    },

    mounted () {
        this.color = this.$cookies.isKey('color') ? this.$cookies.get('color') : 'red'

        axios.get('/user').then(response => {
            if (response.status !== 200) return

            this.user = response.data.user

            if (this.user.role.name === 'Administrator') {
                axios.get('/users').then(response => {
                    if (response.status !== 200) return

                    this.users = response.data.users
                })
            }
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

        getCreatedAtDate(date) {
            const d = new Date(date)

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

            return d.getDay() + " " + month[d.getMonth()] + " " + d.getFullYear() + " - " + d.getHours() + ":" + d.getMinutes()
        }
    }
}
</script>
