<template>
    <div>
        <div v-if="view === 0" class="app__view">
                <label for="email" class="flex-column">
                    Email
                    <input type="email" v-model="email" class="form__input-text">
                    <span style="color:red;">{{ error }}</span>
                </label>
                <label for="consent">
                    <input type="checkbox" v-model="consent" id="consent">
                    Wyrażam zgodę na przetwarzanie moich danych osobowych.
                </label>
                <button :class="`button button--large ${email && consent ? '' : 'button--disabled'}`" :disabled="!email || !consent" @click="nextView">Dalej</button>
        </div>
        <div v-else-if="view === 1">
            <div>
                <p class="current__index">Pytanie {{ usedQuestions.length + 1 }}</p>
            </div>
            <Question :question="questions[currentQuestionIndex]" @nextQuestion="nextQuestion"/>
        </div>
        <div v-else-if="view === 2">
            {{response}}
        <!-- Prezentacja produktu -->
        </div>
        <div v-else>
            <h2>Wystąpił błąd</h2>
        </div>
    </div>
</template>

<script setup>
    import {ref} from "vue";
    import Question from "./Question.vue";
    import axios from "axios";

    let view = ref(0);
    let email = ref('');
    let consent = ref(false);
    let finished = ref(false);
    let error = ref('');

    const questions = [
        {
            id: 1,
            question: 'question 1',
            answers: [
                {
                    answer: 'answer 1.1',
                    childQuestionId: 22,
                },
                {
                    answer: 'answer 1.2',
                    childQuestionId: 22,
                },
                {
                    answer: 'answer 1.3',
                    childQuestionId: 33,
                },
            ]
        },
        {
            id: 22,
            question: 'question 2',
            answers: [
                {
                    answer: 'answer 2.1',
                    childQuestionId: 33,
                },
                {
                    answer: 'answer 2.2',
                    childQuestionId: 33,
                },
                {
                    answer: 'answer 2.3',
                    childQuestionId: 44,
                },
            ]
        },
        {
            id: 33,
            question: 'question 3',
            answers: [
                {
                    answer: 'answer 3.1',
                },
                {
                    answer: 'answer 3.2',
                },
                {
                    answer: 'answer 3.3',
                },
            ]
        },
        {
            id: 44,
            question: 'question 4',
            answers: [
                {
                    answer: 'answer 4.1',
                },
                {
                    answer: 'answer 4.2',
                },
                {
                    answer: 'answer 4.3',
                },
            ]
        },
    ]

    let currentQuestionIndex = ref(0);
    let usedQuestions = [];
    let answers = [];


    let response = ref({});

    const nextView = () => {
        const mailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        console.log('nextView', email.value.match('/^[a-zA-Z0-9.!#$%&\'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\\' +
            '.[a-zA-Z0-9-]+)*$/'));
        email.value.match(mailRegex) !== null ? view.value += 1 : error.value = 'Niepoprawny adres email';
    }

    const nextQuestion = (questionId, answer) => {
        const nextIndex = questions.findIndex(question => question.id === questionId);

        usedQuestions.push(currentQuestionIndex.value)
        answers.push(answer);

        if (nextIndex !== -1) {
            currentQuestionIndex.value = nextIndex;
        }
        else {
            finished.value = true;

            let questionsContent = usedQuestions.map((questionIndex) => questions[questionIndex].question);

            axios.post(`${window.location.origin}/ai`, {
                answers: answers,
                questions: questionsContent,
                email: email.value,
                csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            })
                .then(res => {
                response.value = res.data;
                console.log(response.value)
            })
                .catch(err => {
                console.log(err.response.data)
            });

            view.value += 1;
        }

    };

</script>

<style scoped>
    .current__index {
        font-size: 16px;
        font-weight: 600;
        font-family: Bebas Neue, sans-serif;
    }
</style>
