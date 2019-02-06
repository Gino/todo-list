<template>
    <div class="container max-w-lg mx-auto">
        <div class="bg-red text-white p-4 mt-8 font-semibold shadow border-t-4 rounded-t border-red-dark">
            Todo-list app
        </div>
        <div class="leading-normal rounded-b flex shadow">
            <div class="w-1/4 bg-grey-lightest p-4">
                <div class="font-semibold text-grey-darkest text-sm mb-4">Lijsten</div>
                <div v-for="list in lists" :key="list.id" class="text-sm"><a class="text-red no-underline" href="#">{{ list.name }}</a></div>
            </div>
            <div class="w-3/4 p-4 text-sm bg-white">
                <div v-for="task in tasks" :key="task.id" class="border-b pb-2 mb-6">{{ task.body }}</div>
            </div>
        </div>
        <div class="flex justify-end">
            <div class="bg-red p-2 rounded-full mt-4 ml-2 border-white shadow border cursor-pointer"></div>
            <div class="bg-green p-2 rounded-full mt-4 ml-2 border-white shadow border cursor-pointer"></div>
            <div class="bg-blue p-2 rounded-full mt-4 ml-2 border-white shadow border cursor-pointer"></div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['tasks', 'lists'],

        mounted() {
            console.log('Component mounted')
        }
    }
</script>
