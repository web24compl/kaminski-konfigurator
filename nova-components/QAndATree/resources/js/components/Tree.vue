<template>
    <ul>
        <li v-for="child in children">
            <div v-if="child.answer_text"><i>Odpowiedź:</i> <b>{{ child.answer_text }}</b></div>
            <div v-if="child.question_text"><i>Pytanie:</i> <b>{{ child.question_text }}</b></div>

            <span>(edit)</span>
            <span>(add new)</span>

            <Tree v-if="child.children" :children="child.children"></Tree>
        </li>
    </ul>
</template>

<script setup>
import Tree from "./Tree.vue";

const props = defineProps(['children'])
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
</style>
