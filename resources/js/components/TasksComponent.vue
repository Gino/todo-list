<template>
    <div class="container max-w-lg mx-auto">
        <div :class='"bg-" + getColor() + " text-white p-4 mt-8 font-semibold shadow border-t-4 rounded-t border-" + getColor() + "-dark"'>
            Todo-list app
        </div>
        <div class="leading-normal rounded-b flex shadow">
            <div class="w-1/4 bg-grey-lightest p-4">
                <div class="font-semibold text-grey-darkest text-base mb-4">Lijsten</div>
                <div v-for="list in lists" :key="list.id" class="text-sm mb-2 rounded"><a :class='"text-" + getColor() + " no-underline"' href="#">{{ list.name }}</a></div>
            </div>
            <div class="w-3/4 p-6 text-sm bg-white">
                <div v-for="task in tasks" :key="task.id" class="flex border-b pb-3 my-6">
                    <div class="border border-grey my-auto rounded-full mr-4 w-5 h-5 text-center text-grey-darker">
                        <svg xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 1px" class="fill-current" height="10px" width="10px" viewBox="0 0 24 24"><path d="M20.285 2l-11.285 11.567-5.286-5.011-3.714 3.716 9 8.728 15-15.285z"/></svg>
                    </div>
                    {{ task.body }}
                </div>
            </div>
        </div>
        <div class="flex justify-end">
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
                color: 'red'
            }
        },

        methods: {
            setColor (color) {
                this.color = color;
            },

            getColor () {
                return this.color;
            }
        }
    }
</script>
<style scoped>

</style>
