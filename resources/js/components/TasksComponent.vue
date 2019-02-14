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
                        <a class="no-underline" href="/list/create"><div style="bottom: 0;top: -29%;left: 0;right: 0" class="my-auto select-none font-bold text-white absolute">+</div></a>
                    </div>
                </div>
                <div v-if="!allTasks" @click="showAllTasks" class="font-semibold bg-grey-lighter text-grey-darkest cursor-pointer mb-4 text-sm rounded p-2">
                    Alle taken
                </div>

                <div v-for="list in listsData" :ref='"list-" + list.id' :key="list.id" class="list flex relative text-sm mb-2">
                    <span @click="getSpecificTasks(list)" :class='"text-" + getColor() + " cursor-pointer hover:underline"'>{{ list.name }}</span>
                    <div class="ml-1 text-xs my-auto text-grey-darker" v-if="list.user_id !== user.id && user.role.name === 'Administrator'">(Not yours)</div>
                    <div :class='"bg-red-light hover:bg-red delete h-4 w-4 rounded-full cursor-pointer p-2 flex flex-1 justify-end pin-r my-auto absolute text-center"'>
                        <div @click="deleteList(list)" style="bottom: 0;top: -25%;left: 0;right: 0" class="my-auto select-none font-bold text-white absolute">-</div>
                    </div>
                </div>
            </div>
            <div class="sm:w-3/4 w-full p-6 sm:rounded-b rounded-none text-sm bg-white pt-8 overflow-y-auto" style="max-height: 390px; min-height: 390px">
                <div :class='(tasksData.length > 0 ? "flex" : "hidden") + " list font-semibold relative text-grey-darkest text-base mb-6"'>
                    <div class="outline-none" id="allTasks" @blur="saveList" :ref="'listName-' + currentList.id">Alle taken</div>
                    <div @click="editList" v-if="!allTasks" class="change h-4 w-4 bg-grey-light rounded-full relative my-auto ml-2 cursor-pointer">
                        <svg class="text-white absolute fill-current" style="top: 17%; left: 19%" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="10" height="10" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><g><path  d="M422.953,176.019c0.549-0.48,1.09-0.975,1.612-1.498l21.772-21.772c12.883-12.883,12.883-33.771,0-46.654l-40.434-40.434c-12.883-12.883-33.771-12.883-46.653,0l-21.772,21.772c-0.523,0.523-1.018,1.064-1.498,1.613L422.953,176.019z"/><polygon points="114.317,397.684 157.317,440.684 106.658,448.342 56,456 63.658,405.341 71.316,354.683 	"/><polygon points="349.143,125.535 118.982,355.694 106.541,343.253 336.701,113.094 324.26,100.653 81.659,343.253 168.747,430.341 411.348,187.74 	"/></g></svg>
                    </div>

                    <div :class='"bg-grey-light mt-1 hover:bg-grey h-4 w-4 rounded-full cursor-pointer p-2 flex flex-1 justify-end pin-r my-auto absolute text-center"'>
                        <a class="no-underline" :href='"/task/create/" + ((currentList.id !== undefined) ? currentList.id : "")'><div style="bottom: 0;top: -29%;left: 0;right: 0" class="my-auto select-none font-bold text-white absolute">+</div></a>
                    </div>

                    <!-- Sort -->
                    <div class="text-grey select-none cursor-pointer ml-2" @click="sortTasks">
                        <div v-if="sort === false || sort === null" class="w-3 h-3">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="fill-current"><path d="M4.702 116.686l79.984-80.002c6.248-6.247 16.383-6.245 22.627 0l79.981 80.002c10.07 10.07 2.899 27.314-11.314 27.314H128v320c0 8.837-7.163 16-16 16H80c-8.837 0-16-7.163-16-16V144H16.016c-14.241 0-21.363-17.264-11.314-27.314zM240 96h256c8.837 0 16-7.163 16-16V48c0-8.837-7.163-16-16-16H240c-8.837 0-16 7.163-16 16v32c0 8.837 7.163 16 16 16zm-16 112v-32c0-8.837 7.163-16 16-16h192c8.837 0 16 7.163 16 16v32c0 8.837-7.163 16-16 16H240c-8.837 0-16-7.163-16-16zm0 256v-32c0-8.837 7.163-16 16-16h64c8.837 0 16 7.163 16 16v32c0 8.837-7.163 16-16 16h-64c-8.837 0-16-7.163-16-16zm0-128v-32c0-8.837 7.163-16 16-16h128c8.837 0 16 7.163 16 16v32c0 8.837-7.163 16-16 16H240c-8.837 0-16-7.163-16-16z"></path></svg>
                        </div>
                        <div v-if="sort === true" class="w-3 h-3">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="fill-current"><path d="M187.298 395.314l-79.984 80.002c-6.248 6.247-16.383 6.245-22.627 0L4.705 395.314C-5.365 385.244 1.807 368 16.019 368H64V48c0-8.837 7.163-16 16-16h32c8.837 0 16 7.163 16 16v320h47.984c14.241 0 21.363 17.264 11.314 27.314zM240 96h256c8.837 0 16-7.163 16-16V48c0-8.837-7.163-16-16-16H240c-8.837 0-16 7.163-16 16v32c0 8.837 7.163 16 16 16zm-16 112v-32c0-8.837 7.163-16 16-16h192c8.837 0 16 7.163 16 16v32c0 8.837-7.163 16-16 16H240c-8.837 0-16-7.163-16-16zm0 256v-32c0-8.837 7.163-16 16-16h64c8.837 0 16 7.163 16 16v32c0 8.837-7.163 16-16 16h-64c-8.837 0-16-7.163-16-16zm0-128v-32c0-8.837 7.163-16 16-16h128c8.837 0 16 7.163 16 16v32c0 8.837-7.163 16-16 16H240c-8.837 0-16-7.163-16-16z"></path></svg>
                        </div>
                    </div>

                    <!-- Filter -->
                     <div class="flex my-auto items-center justify-end flex-1 mr-8">
                        <select @change="filterMethod" v-model="filter" class="text-sm">
                            <option disabled selected>Sorteer op status</option>
                            <option value="all">Alle taken</option>
                            <option value="allCompleted">Alle afgeronde taken</option>
                            <option value="allIncompleted">Alle niet-afgeronde taken</option>
                            <option v-if="user.role.name === 'Administrator'" disabled>Administrator options</option>
                            <option v-if="user.role.name === 'Administrator'" value="ownTasks">Alleen eigen taken</option>
                            <option v-if="user.role.name === 'Administrator'" value="otherUserTasks">Alleen taken van anderen</option>
                        </select>
                     </div>
                </div>
                <div v-if="!tasksData.length > 0" :class='"text-center mt-3 text-" + getColor() + "-dark"'>
                    <div>Er zijn geen taken beschikbaar.</div>

                        <a class="no-underline" :href='"/task/create/" + ((currentList.id !== undefined) ? currentList.id : "")'>
                            <button :class='"bg-" + getColor() + " text-white rounded p-2 shadow-inner cursor-pointer hover:bg-" + getColor() + "-dark font-semibold w-2/5 mx-auto mt-4"'>
                                Maak je eerste taak aan
                            </button>
                        </a>
                </div>
                <div v-for="task in getTasks" :ref='"task-" + task.id' :key="task.id" class="task relative flex border-b pb-3 mb-6">
                    <div @click="markTask(task)" :class='"relative border border-grey-light my-auto rounded-full mr-4 text-center text-grey-dark cursor-pointer hover:border-" + getColor() + "-dark hover:text-" + getColor()' style="min-width: 1.25rem; min-height: 1.25rem">
                        <svg v-if="isCompleted(task)" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 1px; left: 23%; top: 27%" class="absolute fill-current" height="10px" width="10px" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg>
                    </div>
                    <div class="w-4/5">
                        <div id="task" @blur="saveTask(task)" :ref='task.id' class="w-full inline outline-none cursor-pointer" contenteditable tabindex="-1">{{ task.body }}</div>
                        <span :class='"text-" + getColor()' v-if="task.user_id !== user.id && task.user && user.role.name === 'Administrator'">(from {{ task.user.name }})</span>
                    </div>

                    <!-- Delete button -->
                    <div :class='"bg-red-light hover:bg-red delete h-4 w-4 rounded-full cursor-pointer p-2 flex flex-1 justify-end pin-r my-auto absolute text-center"'>
                        <div @click="deleteTask(task)" style="bottom: 0;top: -25%;left: 0;right: 0" class="my-auto select-none font-bold text-white absolute">-</div>
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
                filter: 'all',
                sort: null,
                tasksData: this.tasks,
                listsData: this.lists,
                user: {
                    id: null,
                    role: {
                        name: null
                    }
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

                response.data.user.role.name === 'Administrator' ? this.filter = 'ownTasks' : this.filter = 'all'
            })
        },

        computed: {
            getTasks () {
                if (this.filter === 'all') {
                    if (this.allTasks) {
                        if (this.sort === true) {
                            return this.tasksData.sort((taskA, taskB) => {
                                return taskB.completed - taskA.completed
                            })
                        } else if (this.sort === false) {
                            return this.tasksData.sort((taskA, taskB) => {
                                return taskA.completed - taskB.completed
                            })
                        }

                        return this.tasksData
                    } else {
                        if (this.sort === true) {
                            return this.specificTasks.sort((taskA, taskB) => {
                                return taskB.completed - taskA.completed
                            })
                        } else if (this.sort === false) {
                            return this.specificTasks.sort((taskA, taskB) => {
                                return taskA.completed - taskB.completed
                            })
                        }

                        return this.specificTasks
                    }
                } else if (this.filter === 'allIncompleted') {
                    if (this.allTasks) document.getElementById('allTasks').textContent = 'Alle niet-afgeronde taken'
                    this.sort = ''
                    return (this.allTasks) ? this.tasksData.filter(task => {
                        return task.completed === 0
                    }) : this.specificTasks.filter(task => {
                        return task.completed === 0
                    })
                } else if (this.filter === 'allCompleted') {
                    if (this.allTasks) document.getElementById('allTasks').textContent = 'Alle afgeronde taken'
                    this.sort = ''
                    return (this.allTasks) ? this.tasksData.filter(task => {
                        return task.completed === 1
                    }) : this.specificTasks.filter(task => {
                        return task.completed === 1
                    })
                } else if (this.filter === 'ownTasks') {
                    if (this.allTasks) document.getElementById('allTasks').textContent = 'Alle eigen taken'
                    if (this.allTasks) {
                        if (this.sort === true) {
                            const tasks = this.tasksData.filter(task => {
                                return task.user_id === this.user.id
                            })
                            return tasks.sort((taskA, taskB) => {
                                return taskB.completed - taskA.completed
                            })
                        } else if (this.sort === false) {
                            const tasks = this.tasksData.filter(task => {
                                return task.user_id === this.user.id
                            })
                            return tasks.sort((taskA, taskB) => {
                                return taskA.completed - taskB.completed
                            })
                        }

                        return this.tasksData.filter(task => {
                            return task.user_id === this.user.id
                        })
                    } else {
                        if (this.sort === true) {
                            const tasks = this.specificTasks.filter(task => {
                                return task.user_id === this.user.id
                            })

                            return tasks.sort((taskA, taskB) => {
                                return taskB.completed - taskA.completed
                            })
                        } else if (this.sort === false) {
                            const tasks = this.specificTasks.filter(task => {
                                return task.user_id === this.user.id
                            })

                            return tasks.sort((taskA, taskB) => {
                                return taskA.completed - taskB.completed
                            })
                        }

                        return this.specificTasks.filter(task => {
                            return task.user_id === this.user.id
                        })
                    }
                } else if (this.filter === 'otherUserTasks') {
                    if (this.allTasks) document.getElementById('allTasks').textContent = 'Alle taken van anderen'
                    if (this.allTasks) {
                        if (this.sort === true) {
                            const tasks = this.tasksData.filter(task => {
                                return task.user_id !== this.user.id
                            })
                            return tasks.sort((taskA, taskB) => {
                                return taskB.completed - taskA.completed
                            })
                        } else if (this.sort === false) {
                            const tasks = this.tasksData.filter(task => {
                                return task.user_id !== this.user.id
                            })
                            return tasks.sort((taskA, taskB) => {
                                return taskA.completed - taskB.completed
                            })
                        }

                        return this.tasksData.filter(task => {
                            return task.user_id !== this.user.id
                        })
                    } else {
                        if (this.sort === true) {
                            const tasks = this.specificTasks.filter(task => {
                                return task.user_id !== this.user.id
                            })

                            return tasks.sort((taskA, taskB) => {
                                return taskB.completed - taskA.completed
                            })
                        } else if (this.sort === false) {
                            const tasks = this.specificTasks.filter(task => {
                                return task.user_id !== this.user.id
                            })

                            return tasks.sort((taskA, taskB) => {
                                return taskA.completed - taskB.completed
                            })
                        }

                        return this.specificTasks.filter(task => {
                            return task.user_id !== this.user.id
                        })
                    }
                }
            }
        },

        methods: {
            setColor (color) {
                this.color = color

                this.$cookies.set('color', color)
            },

            getColor () {
                return this.color
            },

            sortTasks () {
                this.sort = !this.sort
            },

            filterMethod () {
                if ((this.filter === 'all' || this.filter === 'ownTasks' || this.filter === 'otherUserTasks') && this.sort === '') {
                    this.sort = null
                }
            },

            editList () {
                let element = this.$refs['listName-' + this.currentList.id]
                element.contentEditable = true
                this.focus(element);
            },

            saveList() {
                const value = this.$refs['listName-' + this.currentList.id].textContent

                const list = this.listsData.find(list => {
                    return list.id === this.currentList.id
                })

                if (value === null || value == '' || value === list.name) return

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
                const taskArray = this.tasksData.find(taskA => {
                    return taskA.id === task.id
                })

                this.tasksData.splice(taskArray, 1)

                axios.get('/tasks/delete/' + task.id).then(res => {
                    if (res.status !== 200) return
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
                    if (!this.isCompleted(task)) {
                        this.completedTasks.push(task.id)

                        this.tasksData.find(taskA => {
                            if (taskA.id === task.id) {
                                taskA.completed = 1
                            }
                        })
                    } else {
                        this.completedTasks.splice(this.completedTasks.indexOf(task.id), 1)

                        this.tasksData.find(taskA => {
                            if (taskA.id === task.id) {
                                taskA.completed = 0
                            }
                        })
                    }

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
