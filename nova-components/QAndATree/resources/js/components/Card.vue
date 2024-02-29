<template>
    <Card>
        <div class="px-3 py-3">
            <h1 class="text-center text-3xl text-gray-500 font-light">Drzewo Pytań i Odpowiedzi</h1>

            <div v-if="!tree || tree.length === 0">
                <button
                        class="shrink-0 h-9 font-bold px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 inline-flex items-center shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600"
                        @click="addFirstItem">Dodaj pierwsze pytanie
                </button>
            </div>

            <Tree
                    :children="tree"
                    @showCreateForm="showCreateForm"
                    @showEditForm="showEditForm"
                    @deleteTreeItem="deleteTreeItem"
            ></Tree>
        </div>

        <tree-item-popup
                :is-open="showTreeItemPopup.value"
                :errors="errors.value"
                :selected-tree-item="selectedTreeItem"
                @hideItemTreePopup="hideItemTreePopup"
                @deleteTreeItem="deleteTreeItem"
                @updateTreeItem="updateTreeItem"
                @createTreeItem="createTreeItem"
        />

    </Card>
</template>

<script setup>
import Tree from "./Tree.vue";
import TreeItemPopup from "./TreeItemPopup.vue";

import axios from 'axios';
import {onMounted, reactive, ref} from 'vue';

let tree = ref(null);
let showTreeItemPopup = reactive({value: false});
let errors = reactive({value: {}});
let selectedTreeItem = reactive({});


const fetchTree = async () => {
    await axios.get(`/nova-vendor/q-and-a-tree/tree`)
        .then((response) => {
            tree.value = response.data.tree;
        });
}

const createTreeItem = async (item) => {
    let formData = new FormData();
    formData.append('question_text', item.question_text ?? '');
    formData.append('answer_text', item.answer_text ?? '');
    item.parent_question?.id && formData.append('parent_question_id', item.parent_question.id);

    await axios.post(`/nova-vendor/q-and-a-tree/tree/`, formData)
        .then((response) => {
            showTreeItemPopup.value = false;
            errors.value = {};
        }).catch((response) => {
            if (response.response.status === 422) {
                errors.value = response.response.data.errors
            }
        });

    await fetchTree();
}

const updateTreeItem = async (item) => {
    let formData = new FormData();
    formData.append('question_text', item.question_text ?? '');
    formData.append('answer_text', item.answer_text ?? '');
    formData.append('id', item.id);
    item.parent_question_id && formData.append('parent_question_id', item.parent_question_id);

    await axios.post(`/nova-vendor/q-and-a-tree/tree/${item.id}`, formData)
        .then((response) => {
            showTreeItemPopup.value = false;
            errors.value = {};
        }).catch((response) => {
            if (response.response.status === 422) {
                errors.value = response.response.data.errors
            }
        });

    await fetchTree();
}

const deleteTreeItem = async (item) => {
    const confirmResult = confirm("Czy na pewno chcesz usunąć ten element? Jeżeli posiada on powiązane elementy to one również zostaną usunięte");
    if (confirmResult !== true) {
        return;
    }

    await axios.delete(`/nova-vendor/q-and-a-tree/tree/${item.id}`)
        .then(() => {
            showTreeItemPopup.value = false;
            errors.value = {};
        });

    await fetchTree();
}

const showCreateForm = (item) => {
    selectedTreeItem = {
        question_text: '',
        answer_text: '',
        parent_question: item,
    }

    showTreeItemPopup.value = true;
}

const addFirstItem = () => {
    selectedTreeItem = {
        is_first_item: true,
        question_text: '',
        answer_text: '',
        parent_question: null,
    }

    showTreeItemPopup.value = true;
}

const showEditForm = (item) => {
    selectedTreeItem = item
    showTreeItemPopup.value = true;
}

const hideItemTreePopup = () => {
    showTreeItemPopup.value = false;
    errors.value = {};
}

onMounted(async () => {
    await fetchTree();
});

</script>
<style scoped>
li {
    padding-left: 30px;
}
</style>
