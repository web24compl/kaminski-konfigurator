<template>
    <div>
        <div v-if="failedCaptcha">
            <h2>Spróbuj ponownie za jakiś czas.</h2>
        </div>
        <div v-else-if="view === 0" class="app__view">
            <label for="email" class="flex-column">
                Email
                <input type="email" v-model="email" class="form__input-text">
            </label>
            <label for="phone" class="flex-column">
                Telefon
                <input type="tel" v-model="phone" class="form__input-text">
            </label>
            <label for="consent">
                <input type="checkbox" v-model="consent" id="consent">
                Wyrażam zgodę na przetwarzanie moich danych osobowych.
            </label>
            <span style="color:red;" v-html="error"></span>
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
    import {onBeforeUnmount, onMounted, ref, watch} from "vue";
    import Question from "./Question.vue";
    import axios from "axios";
    import {v4 as uuid } from 'uuid';

    const start = true;

    let view = ref(+localStorage.getItem('view') || 0);
    let email = ref(localStorage.getItem('email') || '');
    let phone = ref(localStorage.getItem('phone') || '');
    let consent = ref(localStorage.getItem('consent') || false);
    let finished = ref(false);
    let error = ref('');
    let failedCaptcha = ref(false);
    let userUUID = ref(localStorage.getItem('uuid') || uuid());

    const questions = window.questions;

    let currentQuestionIndex = ref(+localStorage.getItem('currentQuestionIndex') || 0);
    let usedQuestions = JSON.parse(localStorage.getItem('usedQuestions')) || [];
    let answers = JSON.parse(localStorage.getItem('answers')) || [];


    let response = ref({});

    const nextView = (e) => {
        e.target.disabled = true;
        grecaptcha.enterprise.ready(async () => {
            const key = document.querySelector('meta[name="recaptcha-key"]').getAttribute('content')
            const token = await grecaptcha.enterprise.execute(key, {action: 'NEXT_VIEW'});
            axios.post(`/recaptcha`, {
                token: token,
                csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            })
                .then(res => {
                    if (res.data.success) {
                        const mailRegex = /^(?!.*\.{2})[a-zA-Z0-9]{1}[a-zA-Z0-9._\-+]+[a-zA-Z0-9]{1}@[a-zA-Z0-9]{1}[a-zA-Z0-9.-]*[a-zA-Z0-9]{1}\.[a-zA-Z]{2,6}$/;
                        const phoneRegex = /^[+]*[0-9]*[\s.0-9]*$/;

                        if (email.value.match(mailRegex) !== null && phone.value.match(phoneRegex) !== null) {
                            view.value += 1;
                            error.value = '';
                        } else {
                            error.value = '';
                            error.value += email.value.match(mailRegex) === null ? 'Niepoprawny adres email<br/>' : '';
                            error.value += phone.value.match(phoneRegex) === null ? 'Niepoprawny numer telefonu<br/>' : '';
                            e.target.disabled = false;
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

        usedQuestions.push(currentQuestionIndex.value);
        answers.push(answer);

        if (questions[nextIndex].question_text) {
            currentQuestionIndex.value = nextIndex;
        }
        else {
            finished.value = true;

            let questionsContent = usedQuestions.map((questionIndex) => questions[questionIndex].question_text);

            axios.post(`${window.location.origin}/ai`, {
                answers: answers,
                questions: questionsContent,
                email: email.value,
                phone: phone.value,
                uuid: userUUID.value,
                csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            })
                .then(res => {
                response.value = res.data;
                error.value = false;
                clearLocalStorage();
            })
                .catch(err => {
                response.value = {
                    data: "Wystąpił błąd spróbuj ponownie"
                }
                error.value = true;
            });

            view.value += 1;
        }

    };

    const sendRequest = (e) => {
        e.preventDefault();
        e.returnValue = '';

        if(view.value === 1 && start) {
            setAllLocalStorageItems();

            let questionsContent = usedQuestions.map((questionIndex) => questions[questionIndex].question_text);

            axios.post('/interrupted', {
                email: email.value,
                phone: phone.value,
                answers: answers,
                questions: questionsContent,
                csrf_token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }).then(response => {
                console.log(response.data);
            }).catch(error => {
                console.error(error);
            });
        }
    }

    const setAllLocalStorageItems = () => {
        localStorage.setItem('email', email.value);
        localStorage.setItem('phone', phone.value);
        localStorage.setItem('consent', consent.value);
        localStorage.setItem('usedQuestions', JSON.stringify(usedQuestions));
        localStorage.setItem('answers', JSON.stringify(answers));
        localStorage.setItem('view', view.value);
        localStorage.setItem('currentQuestionIndex', currentQuestionIndex.value);
        localStorage.setItem('uuid', userUUID.value);
    }

    const clearLocalStorage = () => {
        localStorage.removeItem('email');
        localStorage.removeItem('phone');
        localStorage.removeItem('consent');
        localStorage.removeItem('usedQuestions');
        localStorage.removeItem('answers');
        localStorage.removeItem('view');
        localStorage.removeItem('currentQuestionIndex');
        localStorage.removeItem('uuid');
    }

    let unwatch = watch(view, (newValue) => {
        if (newValue === 1) {
            window.addEventListener('beforeunload', sendRequest);
        } else {
            window.removeEventListener('beforeunload', sendRequest);
        }
    }, { immediate: true });

    onBeforeUnmount(() => {
        unwatch();
    });


</script>

<style scoped>
    .current__index {
        font-size: 16px;
        font-weight: 600;
        font-family: Bebas Neue, sans-serif;
    }
</style>
