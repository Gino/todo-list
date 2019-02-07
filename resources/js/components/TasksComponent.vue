<template>
    <div class="container max-w-lg mx-auto">
        <div :class='"bg-" + getColor() + " flex text-white p-4 sm:mt-8 mt-0 font-semibold shadow border-t-4 sm:rounded-t rounded-none border-" + getColor() + "-dark"'>
            <div class="flex-1 my-auto">Todo-list app</div>
            <a href="/task/create" :class='"justify-end text-white no-underline text-sm bg-" + getColor() + "-dark px-3 py-2 rounded cursor-pointer"'>+ Taak toevoegen</a>
            <a href="/logout" :class='"justify-end text-white no-underline text-sm bg-" + getColor() + "-dark px-3 py-2 rounded cursor-pointer ml-3"'>Uitloggen</a>
        </div>
        <div class="leading-normal sm:rounded-b rounded-none flex shadow sm:flex-row flex-col-reverse">
            <div class="sm:w-1/4 w-full sm:rounded-b rounded-none bg-grey-lightest p-4">
                <div class="font-semibold text-grey-darkest text-base mb-4 mt-4">Lijsten</div>
                <div v-if="!allTasks" @click="showAllTasks" class="font-semibold bg-grey-lighter text-grey-darkest cursor-pointer mb-4 text-sm rounded p-2">
                    Alle taken
                </div>
                <div v-for="list in lists" :key="list.id" class="text-sm mb-2"><span @click="getSpecificTasks(list)" :class='"text-" + getColor() + " cursor-pointer hover:underline"'>{{ list.name }}</span></div>
            </div>
            <div class="sm:w-3/4 w-full p-6 sm:rounded-b rounded-none text-sm bg-white pt-8 overflow-y-auto" style="max-height: 390px; min-height: 390px">
                <div id="allTasks" v-if="tasks.length > 0" class="font-semibold text-grey-darkest text-base mb-6">Alle taken</div>
                <div v-if="!tasks.length > 0" :class='"text-center mt-3 text-" + getColor() + "-dark"'>Er zijn geen taken beschikbaar.</div>
                <div v-for="task in getTasks()" :key="task.id" class="flex border-b pb-3 mb-6">
                    <div @click="markTask(task)" :class='"relative border border-grey-light my-auto rounded-full mr-4 text-center text-grey-dark cursor-pointer hover:border-" + getColor() + "-dark hover:text-" + getColor()' style="min-width: 1.25rem; min-height: 1.25rem">
                        <svg v-if="isCompleted(task)" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 1px; left: 23%; top: 27%" class="absolute fill-current" height="10px" width="10px" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg>
                    </div>
                    {{ task.body }}
                </div>
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
        props: ['tasks', 'lists'],

        data () {
            return {
                color: 'red',
                completedTasks: [],
                specificTasks: [],
                allTasks: true,
                user: null
            }
        },

        mounted () {
            this.tasks.filter(task => {
                return task.completed === 1
            }).forEach(task => {
                this.completedTasks.push(task.id)
            })

            axios.get('/user').then(response => {
                if (response.status !== 200) return

                this.user = response.data.user
            })
        },

        methods: {
            setColor (color) {
                this.color = color
            },

            getColor () {
                return this.color
            },

            getTasks () {
                if (this.allTasks) {
                    return this.tasks
                } else {
                    return this.specificTasks
                }
            },

            showAllTasks () {
                this.allTasks = true
                document.getElementById('allTasks').textContent = 'Alle taken'
            },

            getSpecificTasks (list) {
                this.specificTasks = []
                this.allTasks = false

                axios.get('/tasks/list/' + list.id).then(res => {
                    if (res.status !== 200) {
                        this.allTasks = true
                        return
                    }

                    const tasks = res.data

                    tasks.forEach(task => {
                        this.specificTasks.push(task)
                    })

                    document.getElementById('allTasks').textContent = list.name
                })
            },

            markTask (task) {
                axios.post('/tasks/' + task.id + '/check', {
                    'task': task
                }).then(res => {
                    (!this.isCompleted(task)) ? this.completedTasks.push(task.id) : this.completedTasks.splice(this.completedTasks.indexOf(task.id), 1)
                }).catch(err => {
                    console.error(err)
                })
            },

            isCompleted (task) {
                return this.completedTasks.includes(task.id);
            }
        }
    }
</script>
