<template>
  <div class="transfer-list">
    <div class="list-container">
      <h3>Estados disponibles</h3>
      <select 
        multiple 
        v-model="selectedAvailable" 
        class="list-box"
      >
        <option 
          v-for="state in availableStates" 
          :key="state" 
          :value="state"
        >
          {{ state.name }}
        </option>
      </select>
    </div>

    <div class="transfer-buttons">
      <button @click="transferToSelected" :disabled="!selectedAvailable.length">→</button>
      <button @click="transferToAvailable" :disabled="!selectedChosen.length">←</button>
    </div>

    <div class="list-container">
      <h3>Estados seleccionados</h3>
      <select 
        multiple 
        v-model="selectedChosen" 
        class="list-box"
      >
        <option 
          v-for="state in chosenStates" 
          :key="state" 
          :value="state"
        >
          {{ state.name }}
        </option>
      </select>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    estados: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  data() {
    return {
      availableStates: [...this.estados],
      chosenStates: [],
      selectedAvailable: [],
      selectedChosen: []
    }
  },
  methods: {
    transferToSelected() {
      this.chosenStates.push(...this.selectedAvailable);
      this.availableStates = this.availableStates.filter(
        state => !this.selectedAvailable.includes(state)
      );
      this.selectedAvailable = [];
    },
    transferToAvailable() {
      this.availableStates.push(...this.selectedChosen);
      this.chosenStates = this.chosenStates.filter(
        state => !this.selectedChosen.includes(state)
      );
      this.selectedChosen = [];
    }
  },
  watch: {
    estados(newVal) {
      this.availableStates = [...newVal];
      this.chosenStates = [];
    }
  }
}
</script>

<style>
.transfer-list {
  display: flex;
  align-items: center;
  gap: 20px;
}

.list-container {
  display: flex;
  flex-direction: column;
}

.list-box {
  width: 200px;
  height: 300px;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.transfer-buttons {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.transfer-buttons button {
  padding: 8px 12px;
  cursor: pointer;
}
</style>