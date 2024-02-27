<template>
    <div class="popup" @keyup.esc="$emit('hideItemTreePopup')" tabindex="1" ref="popupContainer" v-if="isOpen">
        <div class="popup__container bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="popup__content" id="form-scrollable-content">
                <span @click="$emit('hideItemTreePopup')" class="close">&times;</span>
                <div class="popup__head mb-5">
                    <h2 class="popup__header">Pytanie / odpowiedź</h2>
                </div>

                <div v-if="treeItem.parent_question?.question_text">
                    Odpowiedź na pytanie:<br/>
                    <u>{{ treeItem.parent_question.question_text }}</u>
                </div>

                <div class="d-flex flex-column">

                    <form-row name="answer_text" label="Treść odpowiedzi" :error="errors?.answer_text">
                        <textarea
                                class="form-control"
                                name="answer_text"
                                v-model="treeItem.answer_text"/>
                    </form-row>

                    <form-row name="question_text" label="Treść następnego pytania" :error="errors?.question_text">
                        <textarea
                                class="form-control"
                                name="question_text"
                                v-model="treeItem.question_text"/>
                    </form-row>

                    <div v-if="treeItem.id">
                        <button type="button" class="btn btn-success form-control"
                                @click="$emit('updateTreeItem', treeItem)">
                            Aktualizuj
                        </button>
                        <br>
                        <button type="button" class="btn btn-danger form-control mt-2 text-center"
                                @click="$emit('deleteTreeItem', treeItem)">
                            Usuń
                        </button>
                    </div>
                    <div v-else>
                        <br>
                        <button type="button" class="btn btn-success form-control text-center"
                                @click="$emit('createTreeItem', treeItem)">
                            Stwórz nowy
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
let treeItem = ref({...props.selectedTreeItem});

watch(() => props.selectedTreeItem, () => {
    treeItem = {...props.selectedTreeItem}
})
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
    height: 400px;
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
}
</style>
