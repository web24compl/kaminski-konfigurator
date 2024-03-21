<template>
    <div class="popup" @keyup.esc="$emit('hideItemTreePopup')" tabindex="1" v-if="isOpen">
        <div class="popup__container bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="popup__content">
                <span @click="$emit('hideItemTreePopup')" class="close">&times;</span>
                <div class="popup__head mb-5">
                    <h2 class="popup__header">Pytanie / odpowiedź</h2>
                </div>

                <div v-if="treeItem.parent_question?.question_text">
                    Odpowiedź na pytanie:<br/>
                    <u>{{ treeItem.parent_question.question_text }}</u>
                </div>

                <div class="d-flex flex-column">

                    <form-row v-if="!treeItem.is_first_item || treeItem.parent_question?.question_text"
                              name="answer_text" label="Treść odpowiedzi"
                              :error="errors?.answer_text">
                        <p>
                            <textarea
                                    class="textarea"
                                    name="answer_text"
                                    v-model="treeItem.answer_text"/>
                        </p>
                    </form-row>
                    <p v-if="!treeItem.is_first_item" class="mb-2">
                        <input type="checkbox" id="show_input_question_text" name="input_question_text" class="mr-2"
                               v-model="treeItem.show_input_question_text">
                        <label for="show_input_question_text">Dodaj pytanie wynikające z odpowiedzi</label>
                    </p>

                    <form-row
                            v-if="treeItem.show_input_question_text || treeItem.is_first_item"
                            name="question_text" label="Treść pytania" :error="errors?.question_text">
                        <p>
                            <textarea
                                    class="textarea"
                                    name="question_text"
                                    v-model="treeItem.question_text"/>
                        </p>
                    </form-row>

                    <p class="mb-2">
                        <input type="checkbox" id="multiple_answers" name="multiple_answers" class="mr-2"
                               v-model="treeItem.multiple_answers">
                        <label for="multiple_answers">Pytanie z wieloma odpowiedziami</label>
                    </p>

                    <div v-if="treeItem.id">
                        <button
                                class="mr-2 shrink-0 h-9 font-bold px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 inline-flex items-center shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600"
                                @click="$emit('updateTreeItem', treeItem)">
                            Aktualizuj
                        </button>
                        <button
                                class="mr-2 shrink-0 h-9 font-bold px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 inline-flex items-center shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600"
                                @click="$emit('deleteTreeItem', treeItem)">
                            Usuń
                        </button>
                    </div>
                    <div v-else>
                        <button
                                class="mr-2 shrink-0 h-9 font-bold px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 inline-flex items-center shadow rounded focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring bg-primary-500 hover:bg-primary-400 active:bg-primary-600"
                                @click="$emit('createTreeItem', treeItem)">
                            Utwórz nowy element
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import FormRow from "./FormRow.vue";

import {ref, watch} from "vue";

const props = defineProps(['isOpen', 'selectedTreeItem', 'errors']);
const treeItem = ref(props.selectedTreeItem);

watch(() => props.selectedTreeItem, () => {
    treeItem.value = props.selectedTreeItem;
    treeItem.value.multiple_answers = !!treeItem.value.multiple_answers;
}, {deep: true});

</script>


<style lang="scss" scoped>

$sideMargin: 20px;

.popup {
  display: flex;
  align-items: flex-start;
  position: fixed;
  top: 0;
  right: 0;
  z-index: 100;
  height: 100%;
  width: 100%;
  overflow-y: auto;
  background-color: rgba(0, 0, 0, 0.6);

  &__container {
    height: 500px;
    width: 620px;
    margin: auto;
    box-shadow: -10px 0px 20px 20px rgba(0, 0, 0, 0.1);
  }

  &__content {
    width: 100%;
    height: calc(100% - 63px);
    position: relative;
    overflow-y: auto;
    padding: 0 $sideMargin;
  }

  &__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 0 negative($sideMargin) 15px;
    position: sticky;
    top: 0;
    z-index: 50;
    padding: 15px 0;
    border-bottom: 1px solid rgb(var(--color-slate-200));
  }

  &__header {
    font-size: 20px;
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    flex-direction: column;
    line-height: 1.1;
  }

  .close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    margin-top: 15px;
  }

  .textarea {
    width: 100%;
    height: 70px;
    padding: 5px;
    border: 2px solid #666;
  }
}
</style>
