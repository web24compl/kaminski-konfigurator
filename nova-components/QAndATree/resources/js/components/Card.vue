<template>
    <Card>
        <div class="px-3 py-3">
            <h1 class="text-center text-3xl text-gray-500 font-light">Q And A Tree</h1>
            <Tree :children="tree"></Tree>
        </div>

        <tree-item-popup
                :is-open="showTreeItemPopup.value"
                :errors="errors.value"
                :selected-tree-item="selectedTreeItem"
        />

    </Card>
</template>

<script setup>
import Tree from "./Tree.vue";
import TreeItemPopup from "./TreeItemPopup.vue";

import axios from 'axios';
import {onMounted, reactive, ref} from 'vue';

const tree = ref(null);
let showTreeItemPopup = reactive({value: false});
let errors = reactive({value: false});
let selectedTreeItem = reactive({});


const fetchTree = async () => {
    await axios.get(`/nova-vendor/q-and-a-tree/tree`)
        .then((response) => {
            tree.value = response.data;
        });
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
