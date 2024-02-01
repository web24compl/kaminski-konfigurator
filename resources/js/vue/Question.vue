<template xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <h2>{{ question.question }}</h2>
        <div class="question">
            <div v-for="answer in question.answers" class="form__group">
                <input
                    class="radio"
                    type="radio"
                    :id="answer.answer"
                    :value="answer.answer"
                    v-model="selectedAnswer"
                    :name="question.question"
                    @change="check($event, answer.childQuestionId)"
                >
                <label :for="answer.answer">{{ answer.answer }}</label>
            </div>
            <button :disabled="!checked"
                    :class="`button button--large ${checked ? '' : 'button--disabled'}`"
                    @click="reset"
            >
                Dalej
            </button>
        </div>
    </div>
</template>

<script setup>
    import { ref, defineEmits, defineProps } from "vue";

    defineProps(['question']);
    const emit = defineEmits(['nextQuestion'])

    let checked = ref(false);
    let selectedAnswerQuestionId = ref(null);
    let selectedAnswer = '';

    // const check = (e, answer, childQuestionId = -1) => {
    const check = (e, childQuestionId = -1) => {
        checked.value = true;
        selectedAnswerQuestionId = childQuestionId;
    }

    const reset = () => {
        emit('nextQuestion', selectedAnswerQuestionId, selectedAnswer)

        checked.value = false;
        selectedAnswerQuestionId = null;
        selectedAnswer = null;

    }

</script>

<style scoped>
    h2 {
        width: 100%;
    }
</style>
