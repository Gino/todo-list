<template>
    <div class="container max-w-lg mx-auto">
        <div :class='"bg-red flex text-white p-4 sm:mt-8 mt-0 font-semibold shadow border-t-4 sm:rounded-t rounded-none border-red-dark"'>
            <div class="flex-1 my-auto">Todo-list app</div>
        </div>
        <div class="leading-normal sm:rounded-b rounded-none shadow">
            <div class="w-full w-1/2 p-6 text-sm bg-white sm:rounded-b rounded-none pt-8 overflow-y-auto" style="max-height: 390px; min-height: 390px">

                <div v-if="register === false" class="sm:w-1/2 w-full mx-auto mt-12 mb-4">
                    <div class="font-semibold mb-4 text-grey-darkest text-base">
                        Login
                    </div>
                    <div v-if="status" class="bg-green text-white rounded p-3 font-semibold text-xs mt-2 mb-4" v-text="status"></div>
                    <div class="mb-4 text-red text-sm font-semibold" v-if="authenticated === false">
                        Het e-mailadres of wachtwoord is ongeldig.
                    </div>
                    <form @keyup.enter="submit">
                        <input @keyup="errors.email = null" v-model="fields.email" class="mt-2 w-full block border py-2 px-2 rounded" type="email" name="email" placeholder="E-mailadres">
                        <div :class='"text-red font-semibold mt-2 mb-1 text-xs"' v-if="getError('email')" v-text="getError('email')"></div>

                        <input @keyup="errors.password = null" v-model="fields.password" class="mt-2 w-full block border py-2 px-2 rounded" type="password" name="password" placeholder="Wachtwoord">
                        <div :class='"text-red font-semibold mt-2 mb-1 text-xs"' v-if="getError('password')" v-text="getError('password')"></div>

                        <button @click="submit" :disabled="disabled" :class='"bg-red text-white px-4 py-2 rounded font-semibold mt-4 mr-2 hover:bg-red-dark"' type="button">Login</button>
                        <button @click="register = true" class="bg-grey-lighter text-grey-darker px-4 py-2 rounded font-semibold mt-4 hover:bg-grey-light" type="button">Registreren</button>
                    </form>
                </div>

                <div v-if="register === true" class="sm:w-1/2 w-full mx-auto mt-8 mb-4">
                    <div class="font-semibold mb-4 text-grey-darkest text-base">
                        Registreer
                    </div>
                    <form @keyup.enter="submit">
                        <input @keyup="errors.name = null" v-model="fields.name" class="mt-2 w-full block border py-2 px-2 rounded" type="text" name="name" placeholder="Uw volledige naam">
                        <div :class='"text-red font-semibold mt-2 mb-1 text-xs"' v-if="getError('name')" v-text="getError('name')"></div>

                        <input @keyup="errors.email = null" v-model="fields.email" class="mt-2 w-full block border py-2 px-2 rounded" type="email" name="email" placeholder="E-mailadres">
                        <div :class='"text-red font-semibold mt-2 mb-1 text-xs"' v-if="getError('email')" v-text="getError('email')"></div>

                        <input @keyup="errors.password = null" v-model="fields.password" class="mt-2 w-full block border py-2 px-2 rounded" type="password" name="password" placeholder="Wachtwoord">
                        <div :class='"text-red font-semibold mt-2 mb-1 text-xs"' v-if="getError('password')" v-text="getError('password')"></div>

                        <input @keyup="errors.password_confirmation = null" v-model="fields.password_confirmation" class="mt-2 w-full block border py-2 px-2 rounded" type="password" name="confirm_password" placeholder="Herhaal wachtwoord">
                        <div :class='"text-red font-semibold mt-2 mb-1 text-xs"' v-if="getError('password_confirmation')" v-text="getError('password_confirmation')"></div>

                        <button @click="submitRegistration" :disabled="disabled" :class='"bg-red text-white px-4 py-2 rounded font-semibold mt-4 mr-2 hover:bg-red-dark"' type="button">Registreer</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {

    data () {
        return {
            color: 'red',
            register: false,
            authenticated: null,
            disabled: false,
            status: null,

            fields: {
                name: null,
                email: null,
                password: null,
                password_confirmation: null
            },

            errors: {
                name: null,
                email: null,
                password: null,
                password_confirmation: null
            }
        }
    },

    methods: {
        getError (field) {
            return this.errors[field]
        },

        submitRegistration () {
            this.errors.name = null
            this.errors.email = null
            this.errors.password = null
            this.errors.password_confirmation = null

            if (this.fields.name === null  || this.fields.name == "") {
                this.errors.name ='Uw e-mailadres is verplicht.'
            }

            if (this.fields.email === null  || this.fields.email == "") {
                this.errors.email ='Uw e-mailadres is verplicht.'
            }

            if (this.fields.password === null || this.fields.password == "") {
                this.errors.password = 'Uw wachtwoord is verplicht.'
            }

            if (this.fields.password_confirmation === null || this.fields.password_confirmation == "") {
                this.errors.password_confirmation = 'Herhaal uw wachtwoord alstublieft.'
            }

            if (this.fields.password !== this.fields.password_confirmation) {
                this.errors.password_confirmation = 'Uw wachtwoord komt niet overheen.'
            }

            if (this.errors.name === null && this.errors.email === null && this.errors.password === null && this.errors.password_confirmation === null) {
                this.disabled = true
                axios.post('/register', {
                    'name': this.fields.name,
                    'email': this.fields.email,
                    'password': this.fields.password,
                    'password_confirmation': this.fields.password_confirmation
                }).then(response => {
                    this.register = false
                    this.status = "Je account is aangemaakt, je kan hier inloggen."
                    this.disabled = false
                    this.fields.email = null
                    this.fields.password = null
                })
            }
        },

        submit () {
            this.errors.email = null
            this.errors.password = null

            if (this.fields.email === null  || this.fields.email == "") {
                this.authenticated = null
                this.errors.email ='Uw e-mailadres is verplicht.'
            }

            if (this.fields.password === null || this.fields.password == "") {
                this.authenticated = null
                this.errors.password = 'Uw wachtwoord is verplicht.'
            }

            if (this.errors.email === null && this.errors.password === null) {
                this.disabled = true
                axios.post('/login', {
                    'email': this.fields.email,
                    'password': this.fields.password
                }).then(response => {
                    if (!response.data.authenticated) {
                        this.authenticated = false
                        this.disabled = false
                    } else {
                        this.authenticated = true
                        window.location.href = '/'
                    }
                })
            }
        }
    }
}
</script>
