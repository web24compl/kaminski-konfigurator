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
li {
    padding-left: 20px;
}
</style>
