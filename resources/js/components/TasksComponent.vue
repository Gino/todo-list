<template>
    <div class="container max-w-lg mx-auto">
        <div :class='"bg-" + getColor() + " flex text-white p-4 sm:mt-8 mt-0 font-semibold shadow border-t-4 sm:rounded-t rounded-none border-" + getColor() + "-dark"'>
            <div class="flex-1 my-auto">Todo-list app</div>
            <div :class='"justify-end text-sm bg-" + getColor() + "-dark px-3 py-2 rounded cursor-pointer"'>+ Taak toevoegen</div>
        </div>
        <div class="leading-normal sm:rounded-b rounded-none flex shadow sm:flex-row flex-col">
            <div class="sm:w-1/4 w-full bg-grey-lightest p-4">
                <div class="font-semibold text-grey-darkest text-base mb-4">Lijsten</div>
                <div v-for="list in lists" :key="list.id" class="text-sm mb-2 rounded"><a :class='"text-" + getColor() + " no-underline"' href="#">{{ list.name }}</a></div>
            </div>
            <div class="sm:w-3/4 w-full p-6 text-sm bg-white pt-8 overflow-y-scroll" style="max-height: 390px">
                <div v-for="task in tasks" :key="task.id" class="flex border-b pb-3 mb-6">
                    <div @click="markTask(task)" :class='"border border-grey-light my-auto rounded-full mr-4 w-5 h-5 text-center text-grey-dark cursor-pointer hover:border-" + getColor() + "-dark hover:text-" + getColor()'>
                        <svg v-if="isCompleted(task)" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 1px" class="fill-current" height="10px" width="10px" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg>
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
                completedTasks: []
            }
        },

        mounted () {
            this.tasks.filter(task => {
                return task.completed === 1
            }).forEach(task => {
                this.completedTasks.push(task.id)
            })
        },

        methods: {
            setColor (color) {
                this.color = color
            },

            getColor () {
                return this.color
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
