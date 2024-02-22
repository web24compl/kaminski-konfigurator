<template>
    <div>
        <div v-if="failedCaptcha">
            <h2>Spróbuj ponownie za jakiś czas.</h2>
        </div>
        <div v-else-if="view === 0" class="app__view">
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
        <div v-else-if="view === 2 && !error">
            <div v-if="Object.keys(response).length === 0">
                <h2>Trwa wyszukiwanie odpowiedniego produktu</h2>
            </div>
            <div v-else class="product">
                <h2>
                    Sugerowany produkt:
                </h2>
                <div class="product__image">
                    <img :src="response.image" alt="product">
                </div>
                <div class="product__info">
                    <h2>{{ response.name }}</h2>
                    <h3>{{ response.price }} PLN</h3>
                    <p v-html="response.descriptionhtml"></p>
                    <a :href="response.permalink" class="button button--large" target="_blank">Zobacz produkt</a>
                </div>
            </div>
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
    let failedCaptcha = ref(false);

    const questions = window.questions;

    let currentQuestionIndex = ref(0);
    let usedQuestions = [];
    let answers = [];


    let response = ref({});

    const nextView = (e) => {
        e.target.disabled = true;
        grecaptcha.enterprise.ready(async () => {
            const token = await grecaptcha.enterprise.execute('6LdEpHIpAAAAAAPzYkxy4y1RsZYFdzxFvUX3iMnt', {action: 'NEXT_VIEW'});
            axios.post(`/recaptcha`, {
                token: token,
                csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            })
                .then(res => {
                    if (res.data.success) {
                        const mailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
                        if (email.value.match(mailRegex) !== null) {
                            view.value += 1;
                            error.value = '';
                        } else {
                            error.value = 'Niepoprawny adres email';
                        }
                    }
                })
                .catch(err => {
                    failedCaptcha.value = true;
                });
        });
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
                response.value = {
                    data: "Wystąpił błąd spróbuj ponownie"
                }
                error.value = true
                console.log(err.response.data)
            });

            view.value += 1;
        }

    };
    function onClick(e) {
        e.preventDefault();
    }

</script>

<style scoped>
    .current__index {
        font-size: 16px;
        font-weight: 600;
        font-family: Bebas Neue, sans-serif;
    }
</style>
