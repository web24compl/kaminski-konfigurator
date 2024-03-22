<template xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <h2>{{ question.question_text }}</h2>
        <div class="question">
            <div v-for="answer in question.answers" class="form__group">
                <input
                    class="radio"
                    :type="question.multiple_answers ? 'checkbox' : 'radio'"
                    :id="answer.id"
                    :value="answer.answer_text"
                    :checked="selectedAnswers.length > 0 && selectedAnswers.includes(answer.answer_text)"
                    :name="question.question_text"
                    @change="check($event, answer.id, answer.answer_text)"
                >
                <label :for="answer.id">{{ answer.answer_text }}</label>
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
import {ref, defineEmits, defineProps, reactive, onMounted} from "vue";

    const props = defineProps(['question']);
    const emit = defineEmits(['nextQuestion'])

    let checked = ref(false);
    let selectedAnswerQuestionId = ref(null);
    let selectedAnswers = reactive([]);
console.log('start', selectedAnswers, selectedAnswerQuestionId.value)

    const check = (e, answer_id = -1, text) => {
        checked.value = true;

        if (props.question.multiple_answers) {
            if (e.target.checked) {
                selectedAnswers = reactive([...selectedAnswers, text])
                selectedAnswerQuestionId.value = props.question.answers[0].id;
            } else {
                const index = selectedAnswers.indexOf(text);
                if (index !== -1) {
                    selectedAnswers.splice(index, 1);
                    checked.value = false;
                }
            }
        } else {
            selectedAnswers = reactive([text]);
            selectedAnswerQuestionId.value = answer_id;
        }

        if (selectedAnswers.length === 0) {
            selectedAnswerQuestionId.value = null;
        }
    }

    const reset = () => {
        emit('nextQuestion', selectedAnswerQuestionId, selectedAnswers.join(', '))

        props.question.answers.forEach(answer => {
            answer.checked = false;
        });

        checked.value = false;
        selectedAnswerQuestionId.value = null;
        selectedAnswers = [];
    }

    onMounted(() => {
        props.question.answers.forEach(answer => {
            answer.checked = false;
        });
    })
</script>

<style scoped>
    h2 {
        width: 100%;
    }
</style>
