<script setup>
import { reactive, computed } from 'vue';
import { GeneralService } from '@services/GeneralService';
import { isRequired, validatePostalCode } from '@utils/rules';
import { useDebounceFn } from '@vueuse/shared';
import NoDataText from '@components/NoDataText.vue';

const props = defineProps({
  state: {
    type: [Object, null],
    required: false,
  },
  municipality: {
    type: [Object, null],
    required: false,
  },
  colony: {
    type: [String, null],
    required: false,
  },
  street: {
    type: [String, null],
    required: false,
  },
  exteriorNumber: {
    type: [String, null],
    required: false,
  },
  internalNumber: {
    type: [String, null],
    required: false,
  },
  zipCode: {
    type: [String, null],
    required: false,
  },
  notRequired: {
    type: Array,
    required: false,
    default: [],
  },
  readonly: {
    type: Array,
    required: false,
    default: [],
  },
  disabled: {
    type: Array,
    required: false,
    default: [],
  },
  customProps: {
    type: Object,
    required: false,
    default: {
      state: {},
      municipality: {},
      colony: {},
      street: {},
      exteriorNumber: {},
      internalNumber: {},
      zipCode: {},
    },
  },
  customRules: {
    type: Object,
    required: false,
    default: {
      state: [],
      municipality: [],
      colony: [],
      street: [],
      exteriorNumber: [],
      internalNumber: [],
      zipCode: [],
    },
  },
  md: {
    type: [String, Number, Boolean],
    required: false,
    default: 3,
  },
  title: {
    type: [String, Boolean],
    required: false,
    default: false,
  },
});

const emit = defineEmits([
  'update:state',
  'update:municipality',
  'update:colony',
  'update:street',
  'update:exteriorNumber',
  'update:internalNumber',
  'update:zipCode',
  'error',
]);

const searchState = useDebounceFn(getStates, 400);
const searchMunicipality = useDebounceFn(getMunicipalities, 400);
const list = reactive({
  state: [],
  municipality: [],
});
const isloading = reactive({
  state: false,
  municipality: false,
});
const typed = reactive({
  state: '',
  municipality: '',
});

const modelState = computed({
  get: () => props.state,
  set: (value) => {
    if (renderInput('municipality')) {
      emit('update:municipality', null);
    }
    emit('update:state', value);
  }
});

const modelMunicipality = computed({
  get: () => props.municipality,
  set: (value) => {
    emit('update:municipality', value);
  }
});

const modelColony = computed({
  get: () => props.colony,
  set: (value) => {
    emit('update:colony', value);
  }
});

const modelStreet = computed({
  get: () => props.street,
  set: (value) => {
    emit('update:street', value);
  }
});

const modelExteriorNumber = computed({
  get: () => props.exteriorNumber,
  set: (value) => {
    emit('update:exteriorNumber', value);
  }
});

const modelInternalNumber = computed({
  get: () => props.internalNumber,
  set: (value) => {
    emit('update:internalNumber', value);
  }
});

const modelZipCode = computed({
  get: () => props.zipCode,
  set: (value) => {
    emit('update:zipCode', value);
  }
});

const stateSetting = computed(() => {
  return setInputProps('state');
});

const municipalitySetting = computed(() => {
  const municipalityProps = setInputProps('municipality');
  Object.assign(municipalityProps, {
    disabled: disabledMunicipality.value,
  });
  return municipalityProps;
});

const colonySetting = computed(() => {
  return setInputProps('colony');
});

const streetSetting = computed(() => {
  return setInputProps('street');
});

const exteriorNumberSetting = computed(() => {
  return setInputProps('exteriorNumber');
});

const internalNumberSetting = computed(() => {
  return setInputProps('internalNumber');
});

const zipCodeSetting = computed(() => {
  const zipCodeProps = setInputProps('zipCode');
  zipCodeProps.rules.push(validatePostalCode);
  return zipCodeProps;
});

const disabledMunicipality = computed(() => {
  return !modelState.value;
});

const titleRendered = computed(() => {
  return {
    show: !!props.title,
    text: typeof props.title === 'string' ? props.title : 'Domicilio',
  };
})

function setInputProps(inputName) {
  const inputProps = {
    rules: [],
  };
  if (props?.customProps?.[inputName]) {
    Object.assign(inputProps, props.customProps[inputName]);
  }

  if (isInputRequiredAndEnabled(inputName)) {
    inputProps.rules.push(isRequired);
  }

  if (props?.customRules?.[inputName]) {
    inputProps.rules.push(...props.customRules[inputName]);
  }

  if (isInputReadonly(inputName)) {
    Object.assign(inputProps, {
      variant: 'plain',
      'menu-icon': '',
      readonly: true,
    });
  }

  if (isInputDisabled(inputName)) {
    Object.assign(inputProps, {
      disabled: true,
    });
  }
  return inputProps;
}

function renderInput(inputName) {
  return props?.[inputName] !== undefined ?? false;
}

function isInputRequired(inputName) {
  return !(props.notRequired.includes(inputName) || props.notRequired.includes('all'));
}

function isInputRequiredAndEnabled(inputName) {
  return isInputRequired(inputName) && !(isInputReadonly(inputName) || isInputDisabled(inputName));
}

function isInputReadonly(inputName) {
  return props.readonly.includes(inputName) || props.readonly.includes('all');
}

function isInputDisabled(inputName) {
  return props.disabled.includes(inputName);
}

function addAsterisk(inputName) {
  return isInputRequiredAndEnabled(inputName) ? '*' : '';
}

async function getStates(hint) {
  try {
    if (hint === modelState.value?.name) {
      return;
    }
    typed.state = hint;
    list.state = [];
    if (!hint) {
      return;
    }
    isloading.state = true;
    const params = {
      search: {
        selects: [
          { field: 'id' },
          { field: 'name' },
        ],
        filters: [
          {
            field: 'name',
            operator: 'like',
            value: `${hint}%`
          },
        ],
      },
    };
    const response = await GeneralService.fetchStates(params);
    list.state = response.data;
  } catch (error) {
    console.error(error);
    emit('error', 'Ha ocurrido un error al obtener los estados');
  } finally {
    isloading.state = false;
  }
}

async function getMunicipalities(hint) {
  try {
    if (hint === modelMunicipality.value?.name) {
      return;
    }
    typed.municipality = hint;
    list.municipality = [];
    if ((!hint) || !modelState?.value?.id) {
      return;
    }
    isloading.municipality = true;
    const params = {
      search: {
        selects: [
          { field: 'id' },
          { field: 'name' },
        ],
        filters: [
          {
            field: 'name',
            operator: 'like',
            value: `${hint}%`,
            type: 'and',
          },
          {
            field: 'state_id',
            operator: '=',
            value: modelState.value?.id ?? 0,
          },
        ],
      },
    };
    const response = await GeneralService.fetchMunicipalities(params);
    list.municipality = response.data;
  } catch (error) {
    console.error(error);
    emit('error', 'Ha ocurrido un error al obtener los municipios');
  } finally {
    isloading.municipality = false;
  }
}

</script>

<template>
  <h4 v-if="titleRendered.show" class="mt-4 mb-2">{{ titleRendered.text }}</h4>
  <VRow>
    <VCol cols="12" :md="md" v-if="renderInput('state')">
      <VAutocomplete :label="`Estado ${addAsterisk('state')}`" v-model="modelState" :items="list.state" item-title="name"
        return-object :loading="isloading.state" autocomplete="off" v-bind="stateSetting" @update:search="searchState">
        <template #no-data>
          <NoDataText item-name="estados" :typed="typed.state" :is-getting-data="isloading.state" makes-requests />
        </template>
      </VAutocomplete>
    </VCol>

    <VCol cols="12" :md="md" v-if="renderInput('municipality')">
      <VAutocomplete :label="`Localidad o Municipio ${addAsterisk('municipality')}`" v-model="modelMunicipality"
        :items="list.municipality" item-title="name" return-object :loading="isloading.municipality" autocomplete="off"
        v-bind="municipalitySetting" @update:search="searchMunicipality">
        <template #no-data>
          <NoDataText item-name="municipios" :typed="typed.municipality" :is-getting-data="isloading.municipality"
            makes-requests />
        </template>
      </VAutocomplete>
    </VCol>

    <VCol cols="12" :md="md" v-if="renderInput('colony')">
      <VTextField :label="`Colonia ${addAsterisk('colony')}`" v-model="modelColony" v-bind="colonySetting" />
    </VCol>

    <VCol cols="12" :md="md" v-if="renderInput('street')">
      <VTextField :label="`Calle ${addAsterisk('street')}`" v-model="modelStreet" v-bind="streetSetting" />
    </VCol>

    <VCol cols="12" :md="md" v-if="renderInput('exteriorNumber')">
      <VTextField :label="`Número exterior ${addAsterisk('exteriorNumber')}`" v-model="modelExteriorNumber"
        v-bind="exteriorNumberSetting" />
    </VCol>

    <VCol cols="12" :md="md" v-if="renderInput('internalNumber')">
      <VTextField :label="`Número interior ${addAsterisk('internalNumber')}`" v-model="modelInternalNumber"
        v-bind="internalNumberSetting" />
    </VCol>

    <VCol cols="12" :md="md" v-if="renderInput('zipCode')">
      <VTextField :label="`Código postal ${addAsterisk('zipCode')}`" v-model="modelZipCode" v-bind="zipCodeSetting" />
    </VCol>
  </VRow>
</template>
