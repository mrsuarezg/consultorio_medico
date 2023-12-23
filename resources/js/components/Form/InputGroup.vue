<script setup>
const props = defineProps({
  inputs: {
    type: Array,
    required: true,
  }
});

const emits = defineEmits([
  'action',
]);

/**
 * @description Emits the action to the parent component when executed on an input
 * @param {string} componentName Name of the component that performs the action. Must be defined in the parent component
 * @param {string} actionName Name of the action performed Ex. (click, update, input)
 * @param value New component value
**/
const sendAction = (componentName, actionName, value) => {
  emits('action', {
    'component': componentName,
    'actionName': actionName,
    'value': value,
  });
};

const emitClick = (input) => {
  if (input.component.name !== 'VBtn') {
    return;
  }
  sendAction(input.name, 'click', true);
};
</script>

<template>
  <VCol v-for="input in inputs" v-bind="input.colProps" :class="input.colClass">
    <component
      :is="input.component"
      :class="input.inputClass"
      :label="input.label"
      v-model="input.value"
      :items="input.options"
      v-bind="input.inputProps"
      @click.prevent="emitClick(input)">
        <span v-if="input.component.name === 'VBtn'">{{ input.label }}</span>
    </component>
  </VCol>
</template>
