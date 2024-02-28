<template xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <h2>{{ question.question_text }}</h2>
        <div class="question">
            <div v-for="answer in question.answers" class="form__group">
                <input
                    class="radio"
                    type="radio"
                    :id="answer.id"
                    :value="answer.id"
                    v-model="selectedAnswer"
                    :name="question.question_text"
                    @change="check($event, answer.parent_question_id)"
                >
                <label :for="answer.answer_text">{{ answer.answer_text }}</label>
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

    const check = (e, question_id = -1) => {
        checked.value = true;
        selectedAnswerQuestionId = question_id;
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
