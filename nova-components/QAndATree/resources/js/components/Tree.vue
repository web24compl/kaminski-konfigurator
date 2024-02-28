<template>
    <ul>
        <li v-for="item in children">
            <div v-if="item.answer_text"><i>Odpowiedź:</i> <b>{{ item.answer_text }}</b></div>
            <div v-if="item.question_text"><i>Pytanie:</i> <b>{{ item.question_text }}</b></div>

            <button
                    class="mr-2 shrink-0 h-6 px-1 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 inline-flex items-center shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600"
                    @click="showCreateForm(item)"
                    :class="`${item.question_text ?? 'disabled'}`">
                Dodaj odpowiedź
            </button>
            <button
                    class="mr-2 shrink-0 h-6 px-1 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 inline-flex items-center shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600"
                    @click="showEditForm(item)">
                Edytuj
            </button>
            <button
                    class="mr-2 shrink-0 h-6 px-1 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 inline-flex items-center shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600"
                    @click="deleteTreeItem(item)">
                Usuń
            </button>

            <Tree
                    v-if="item.children"
                    :children="item.children"
                    @showEditForm="showEditForm"
                    @showCreateForm="showCreateForm"
                    @deleteTreeItem="deleteTreeItem"
            ></Tree>
        </li>
    </ul>
</template>


<script setup>
import Tree from "./Tree.vue";

const props = defineProps(['children'])
const emit = defineEmits(['showCreateForm', 'showEditForm', 'deleteTreeItem'])

const showCreateForm = (item) => {
    if (!item.question_text) {
        alert('Nie można dodać odpowiedzi ponieważ element nadrzędny nie zawiera pytania');

        return;
    }

    emit('showCreateForm', item)
}

const showEditForm = (item) => {
    emit('showEditForm', item)
}

const deleteTreeItem = (item) => {
    emit('deleteTreeItem', item)
}

</script>

<style>
* {
    margin: 0;
    padding: 0;
    list-style: none;
}

ul li {
    margin-left: 15px;
    position: relative;
    padding-left: 5px;
}

ul li::before {
    content: " ";
    position: absolute;
    width: 1px;
    background-color: #eee;
    top: 5px;
    bottom: -12px;
    left: -10px;
}

body > ul > li:first-child::before {
    top: 12px;
}

ul li:not(:first-child):last-child::before {
    display: none;
}

ul li:only-child::before {
    display: list-item;
    content: " ";
    position: absolute;
    width: 1px;
    background-color: #eee;
    top: 5px;
    bottom: 7px;
    height: 7px;
    left: -10px;
}

ul li::after {
    content: " ";
    position: absolute;
    left: -10px;
    width: 10px;
    height: 1px;
    background-color: #eee;
    top: 12px;
}

.disabled {
    cursor: not-allowed;
}

</style>
