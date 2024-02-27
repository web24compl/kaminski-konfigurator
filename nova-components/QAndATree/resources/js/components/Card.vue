<template>
    <Card>
        <div class="px-3 py-3">
            <h1 class="text-center text-3xl text-gray-500 font-light">Q And A Tree</h1>
            <Tree
                    :children="tree"
                    @showCreateForm="showCreateForm"
                    @showEditForm="showEditForm"
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

const tree = ref(null);
const treeItems = ref(null);
let showTreeItemPopup = reactive({value: false});
let errors = reactive({value: false});
let selectedTreeItem = reactive({});


const fetchTree = async () => {
    await axios.get(`/nova-vendor/q-and-a-tree/tree`)
        .then((response) => {
            tree.value = response.data.multilevel_tree;
            treeItems.value = response.data.tree_items;
        });
}

const createTreeItem = async (item) => {

    let formData = new FormData();
    formData.append('question_text', item.question_text);
    formData.append('answer_text', item.answer_text);
    formData.append('parent_question_id', item.parent_question.id);
    await axios.post(`/nova-vendor/q-and-a-tree/tree/`, formData)
        .then((response) => {
            console.log(response)
            showTreeItemPopup.value = false;
        }).catch((response) => {
            if (response.response.status === 422) {
                errors.value = response.response.data.errors
            }
        });

    await fetchTree();
}

const updateTreeItem = async (item) => {
    let formData = new FormData();
    formData.append('question_text', item.question_text);
    formData.append('answer_text', item.answer_text);
    await axios.post(`/nova-vendor/q-and-a-tree/tree/${item.id}`, formData)
        .then((response) => {
            console.log(response)
            showTreeItemPopup.value = false;
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
        .then((response) => {
            showTreeItemPopup.value = false;
            console.log(response)
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

const showEditForm = (item) => {
    selectedTreeItem = item
    showTreeItemPopup.value = true;
}

const hideItemTreePopup = () => {
    showTreeItemPopup.value = false;
}

onMounted(async () => {
    await fetchTree();
});

</script>
<style>
li {
    padding-left: 30px;
}
</style>
