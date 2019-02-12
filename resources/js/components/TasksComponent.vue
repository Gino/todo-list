<template>
    <div class="container max-w-lg mx-auto">
        <div :class='"bg-" + getColor() + " flex text-white p-4 sm:mt-8 mt-0 font-semibold shadow border-t-4 sm:rounded-t rounded-none border-" + getColor() + "-dark"'>
            <div class="flex-1 my-auto">Todo-list app</div>
            <a href="/profile" :class='"justify-end text-white no-underline text-sm bg-" + getColor() + "-dark px-3 py-2 rounded cursor-pointer"'>Mijn profiel</a>
            <a href="/logout" :class='"justify-end text-white no-underline text-sm bg-" + getColor() + "-dark px-3 py-2 rounded cursor-pointer ml-3"'>Uitloggen</a>
        </div>
        <div class="leading-normal sm:rounded-b rounded-none flex shadow sm:flex-row flex-col-reverse">
            <div class="sm:w-1/4 w-full sm:rounded-b rounded-none bg-grey-lightest p-4">
                <div class="font-semibold flex text-grey-darkest relative text-base mb-4 mt-4">
                    Lijsten

                    <div :class='"bg-grey-light mt-1 hover:bg-grey h-4 w-4 rounded-full cursor-pointer p-2 flex flex-1 justify-end pin-r my-auto absolute text-center"'>
                        <a class="no-underline" href="/list/create"><div style="bottom: 0;top: -29%;left: 0;right: 0" class="my-auto font-bold text-white absolute">+</div></a>
                    </div>
                </div>
                <div v-if="!allTasks" @click="showAllTasks" class="font-semibold bg-grey-lighter text-grey-darkest cursor-pointer mb-4 text-sm rounded p-2">
                    Alle taken
                </div>
                <div v-for="list in listsData" :ref='"list-" + list.id' :key="list.id" class="list flex relative text-sm mb-2">
                    <span @click="getSpecificTasks(list)" :class='"text-" + getColor() + " cursor-pointer hover:underline"'>{{ list.name }}</span>
                    <div class="ml-1 text-xs my-auto text-grey-darker" v-if="list.user_id !== user.id">(Not yours)</div>
                    <div :class='"bg-red-light hover:bg-red delete h-4 w-4 rounded-full cursor-pointer p-2 flex flex-1 justify-end pin-r my-auto absolute text-center"'>
                        <div @click="deleteList(list)" style="bottom: 0;top: -25%;left: 0;right: 0" class="my-auto font-bold text-white absolute">-</div>
                    </div>
                </div>
            </div>
            <div class="sm:w-3/4 w-full p-6 sm:rounded-b rounded-none text-sm bg-white pt-8 overflow-y-auto" style="max-height: 390px; min-height: 390px">
                <div v-if="tasks.length > 0" class="list font-semibold relative flex text-grey-darkest text-base mb-6">
                    <div class="outline-none" id="allTasks" @blur="saveList" :ref="'listName-' + currentList.id">Alle taken</div>
                    <div @click="editList" v-if="!allTasks" class="change h-4 w-4 bg-grey-light rounded-full relative my-auto ml-2 cursor-pointer">
                        <svg class="text-white absolute fill-current" style="top: 17%; left: 19%" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="10" height="10" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><g><path  d="M422.953,176.019c0.549-0.48,1.09-0.975,1.612-1.498l21.772-21.772c12.883-12.883,12.883-33.771,0-46.654l-40.434-40.434c-12.883-12.883-33.771-12.883-46.653,0l-21.772,21.772c-0.523,0.523-1.018,1.064-1.498,1.613L422.953,176.019z"/><polygon points="114.317,397.684 157.317,440.684 106.658,448.342 56,456 63.658,405.341 71.316,354.683 	"/><polygon points="349.143,125.535 118.982,355.694 106.541,343.253 336.701,113.094 324.26,100.653 81.659,343.253 168.747,430.341 411.348,187.74 	"/></g></svg>
                    </div>

                    <div :class='"bg-grey-light mt-1 hover:bg-grey h-4 w-4 rounded-full cursor-pointer p-2 flex flex-1 justify-end pin-r my-auto absolute text-center"'>
                        <a class="no-underline" :href='"/task/create/" + ((currentList.id !== undefined) ? currentList.id : "")'><div style="bottom: 0;top: -29%;left: 0;right: 0" class="my-auto font-bold text-white absolute">+</div></a>
                    </div>
                </div>
                <div v-if="!tasks.length > 0" :class='"text-center mt-3 text-" + getColor() + "-dark"'>
                    Er zijn geen taken beschikbaar.

                    <a class="no-underline" href="/task/create"><div :class='"bg-" + getColor() + " text-white rounded p-2 shadow-inner cursor-pointer hover:bg-" + getColor() + "-dark font-semibold w-2/5 mx-auto mt-4"'>
                        Maak je eerste taak aan
                    </div></a>
                </div>
                <div v-for="task in getTasks()" :ref='"task-" + task.id' :key="task.id" class="task relative flex border-b pb-3 mb-6">
                    <div @click="markTask(task)" :class='"relative border border-grey-light my-auto rounded-full mr-4 text-center text-grey-dark cursor-pointer hover:border-" + getColor() + "-dark hover:text-" + getColor()' style="min-width: 1.25rem; min-height: 1.25rem">
                        <svg v-if="isCompleted(task)" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 1px; left: 23%; top: 27%" class="absolute fill-current" height="10px" width="10px" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg>
                    </div>
                    <div class="w-4/5">
                        <div id="task" @blur="saveTask(task)" :ref='task.id' class="w-full inline outline-none cursor-pointer" contenteditable tabindex="-1">{{ task.body }}</div>
                        <span :class='"text-" + getColor()' v-if="task.user_id !== user.id && task.user">(from {{ task.user.name }})</span>
                    </div>

                    <!-- List changer -->
                    <!-- <div class="text-xs my-auto bg-grey-lighter font-semibold text-grey-dark px-1 -ml-8 rounded">
                        {{ getListFromTask(task) }}
                    </div> -->

                    <!-- Delete button -->
                    <div :class='"bg-red-light hover:bg-red delete h-4 w-4 rounded-full cursor-pointer p-2 flex flex-1 justify-end pin-r my-auto absolute text-center"'>
                        <div @click="deleteTask(task)" style="bottom: 0;top: -25%;left: 0;right: 0" class="my-auto font-bold text-white absolute">-</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end sm:mr-0 mr-2">
            <div @click="setColor('red')" class="bg-red p-2 rounded-full mt-4 ml-2 border-white shadow border cursor-pointer" />
            <div @click="setColor('green')" class="bg-green p-2 rounded-full mt-4 ml-2 border-white shadow border cursor-pointer" />
            <div @click="setColor('blue')" class="bg-blue p-2 rounded-full mt-4 ml-2 border-white shadow border cursor-pointer" />
        </div>
    </div>
</template>

<script>
    export default {
        props: ['tasks', 'lists'],

        data () {
            return {
                color: 'red',
                currentList: '',
                completedTasks: [],
                specificTasks: [],
                allTasks: true,
                tasksData: this.tasks,
                listsData: this.lists,
                user: {
                    id: null
                }
            }
        },

        mounted () {
            this.color = this.$cookies.isKey('color') ? this.$cookies.get('color') : 'red'

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

                this.$cookies.set('color', color)
            },

            getColor () {
                return this.color
            },

            getTasks () {
                if (this.allTasks) {
                    return this.tasksData
                } else {
                    return this.specificTasks
                }
            },

            editList () {
                let element = this.$refs['listName-' + this.currentList.id]
                element.contentEditable = true
                this.focus(element);
            },

            saveList() {
                const value = this.$refs['listName-' + this.currentList.id].textContent

                if (value === null || value == '' || value === this.currentList.name) return

                axios.post('/lists/change/' + this.currentList.id, {
                    list: value
                }).then(res => {
                    this.listsData = res.data

                    let element = this.$refs['listName-' + this.currentList.id]
                    element.contentEditable = false
                });
            },

            getListFromTask(task)
            {
                return this.lists.filter(list => {
                    return list.id === task.list_id
                })[0].name
            },

            saveTask(task)
            {
                const value = this.$refs[task.id][0].textContent

                if (value === null || value == '' || value === task.body) return
                axios.post('/tasks/change/' + task.id, {
                    task: value
                }).then(res => {
                    this.tasksData = res.data
                });
            },

            showAllTasks () {
                this.allTasks = true
                document.getElementById('allTasks').textContent = 'Alle taken'
            },

            deleteTask(task) {
                axios.get('/tasks/delete/' + task.id).then(res => {
                    if (res.status !== 200) return

                    this.$refs["task-" + task.id][0].remove()
                })
            },

            deleteList(list) {
                axios.get('/lists/delete/' + list.id).then(res => {
                    if (res.status !== 200) return

                    this.$refs["list-" + list.id][0].remove()
                })
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
                    this.currentList = list
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
                return this.completedTasks.includes(task.id)
            },

            focus (element) {
                element.focus()
                var range = document.createRange();
                var sel = window.getSelection();
                range.setStart(element, 1);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        }
    }
</script>
