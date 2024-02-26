<template>
    <div class="popup" @keyup.esc="close" tabindex="1" ref="popupContainer" v-if="isOpen">
        <div class="popup__container">
            <div class="popup__content" id="form-scrollable-content">
                <div class="popup__head mb-5">
                    <h2 class="popup__header">Pytanie / odpowiedź</h2>
                    <button type="button" class="btn-close" aria-label="Close"
                            @click="$emit('hideEventPopup') && close"></button>
                </div>

                <div class="d-flex flex-column">


                    <form-row name="question_text" label="Klient" :error="errors?.question_text">
                        <input
                                class="form-control"
                                name="question_text"
                                v-model="treeItem.question_text"/>

                    </form-row>

                    <div v-if="treeItem.id">
                        <button type="button" class="btn btn-success form-control"
                                @click="$emit('updateEvent', treeItem)">
                            Aktualizuj wpis
                        </button>
                        <button type="button" class="btn btn-danger form-control mt-2 text-center"
                                @click="$emit('deleteEvent', treeItem)">
                            Usuń wpis
                        </button>
                    </div>
                    <div v-else>
                        <button type="button" class="btn btn-success form-control text-center"
                                @click="$emit('createEvent', treeItem)">
                            Stwórz wpis
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
    event = {...props.selectedTreeItem}
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
    background-color: #fff;
    height: 90%;
    width: 520px;
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
    background: #fff;
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

  &__close {
    cursor: pointer;
  }
}
</style>
