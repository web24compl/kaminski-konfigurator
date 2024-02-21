<template>
    <Card class="">
        <div class="px-3 py-3">
            <h1 class="text-center text-3xl text-gray-500 font-light">Q And A Tree</h1>

            <Tree :children="tree"></Tree>


        </div>
    </Card>
</template>

<script setup>
import Tree from "./Tree.vue";

const tree = [
    {
        question_text: "W jakim kraju mieszkasz?",
        children: [
            {
                answer_text: "W Polsce",
                question_text: "Czy mówisz po polsku?",
                children: [
                    {
                        answer_text: "Tak",
                    },
                    {
                        answer_text: "Nie",
                    }
                ]
            },
            {
                answer_text: "W Holandii",
            }
        ]
    }
];

</script>
<style>
li {
    padding-left: 30px;
}
</style>
