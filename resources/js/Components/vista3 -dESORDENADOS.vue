<template>
  <div class="document-selector">
    <!-- Sección de documentos -->
    <div class="document-section">
      <h3 class="section-title">{{ title }}</h3>
      <div class="document-types-container">
        <div 
          v-for="doc in groupedDocumentTypes" 
          :key="`${type}-${doc.tipo}`"
          class="document-type-card"
        >
          <label class="document-type-label">
            <input
              type="checkbox"
              :value="doc.tipo"
              v-model="selectedDocumentTypes"
              @change="handleDocumentTypeSelection(doc)"
              class="document-type-checkbox"
            >
            <div class="document-type-content">
              <span class="document-type-name">{{ doc.tipo }}</span>
              <span class="document-meta">
                <span class="document-count">{{ doc.totalArchivos }} archivo(s)</span>
                <span class="document-empresas">{{ doc.empresas.length }} empresa(s)</span>
              </span>
            </div>
          </label>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
  empresas: {
    type: Array,
    required: true,
    default: () => []
  },
  modelValueEmpresas: {
    type: Array,
    required: true,
    default: () => []
  },
  modelValue: {
    type: Array,
    default: () => []
  },
  type: {
    type: String,
    required: true,
    validator: (value) => ['tecnico', 'legal'].includes(value)
  },
  baseUrl: {
    type: String,
    default: '/storage/'
  }
});

const emit = defineEmits(['update:modelValue']);

// Datos reactivos
const documentos = ref([]);
const selectedDocumentTypes = ref([]);
const documentTypeMap = ref({});

// Título dinámico
const title = computed(() => 
  props.type === 'tecnico' ? 'Documentos Técnicos' : 'Documentos Legales'
);

// Watcher para controlar cambios en las empresas seleccionadas
watch(() => props.modelValueEmpresas, async (nuevasEmpresas, oldEmpresas) => {
  if (!nuevasEmpresas || nuevasEmpresas.length === 0) {
    documentos.value = [];
    emit('update:modelValue', []);
    documentTypeMap.value = {};
    selectedDocumentTypes.value = [];
    return;
  }

  const empresasRemovidas = oldEmpresas?.filter(e => !nuevasEmpresas.includes(e)) || [];
  
  if (empresasRemovidas.length === 0) {
    const nuevasEmpresasACargar = oldEmpresas 
      ? nuevasEmpresas.filter(e => !oldEmpresas.includes(e))
      : nuevasEmpresas;
    
    for (const empresaId of nuevasEmpresasACargar) {
      await cargarDocumentosEmpresa(empresaId);
    }
    return;
  }

  documentos.value = [];
  documentTypeMap.value = {};
  selectedDocumentTypes.value = [];
  
  for (const empresaId of nuevasEmpresas) {
    await cargarDocumentosEmpresa(empresaId);
  }

  if (props.modelValue.length > 0) {
    const documentosEmpresasActuales = documentos.value.map(a => a.id);
    emit('update:modelValue', props.modelValue.filter(
      id => documentosEmpresasActuales.includes(id)
    ));
  }
}, { immediate: true });

// Función para cargar documentos de una empresa
async function cargarDocumentosEmpresa(empresaId) {
  const empresa = props.empresas.find(e => e.id === empresaId);
  const response = await axios.get(`/empresa/${empresaId}/documentos`);
  const docs = props.type === 'tecnico' 
    ? response.data.documentos_tecnicos 
    : response.data.documentos_legales;

  const nuevosDocumentos = docs.flatMap(doc =>
    doc.archivos.map(archivo => ({
      id: archivo.id,
      nombre: archivo.nombre_original,
      url: archivo.ruta_archivo,
      documento: doc.tipo_de_documento.nombre_documento,
      empresa_id: empresaId,
      empresa_nombre: empresa?.name || empresa?.nombre || 'Empresa desconocida'
    }))
  );

  documentos.value.push(...nuevosDocumentos);

  // Actualizar el mapeo de tipos de documento
  docs.forEach(doc => {
    const tipo = doc.tipo_de_documento.nombre_documento;
    const archivosIds = doc.archivos.map(a => a.id);
    
    if (!documentTypeMap.value[tipo]) {
      documentTypeMap.value[tipo] = {
        archivos: new Set(),
        empresas: new Set()
      };
    }
    
    archivosIds.forEach(id => documentTypeMap.value[tipo].archivos.add(id));
    documentTypeMap.value[tipo].empresas.add(empresaId);
    // Forzar actualización de selectedDocumentTypes después de cargar documentos
    updateSelectedDocumentTypesFromModelValue();

  });
}

// Agrupar documentos por tipo (sin duplicados)
const groupedDocumentTypes = computed(() => {
  const tiposUnicos = [];
  
  for (const [tipo, data] of Object.entries(documentTypeMap.value)) {
    tiposUnicos.push({
      tipo,
      archivos: Array.from(data.archivos),
      empresas: Array.from(data.empresas),
      totalArchivos: data.archivos.size
    });
  }
  
  return tiposUnicos.sort((a, b) => a.tipo.localeCompare(b.tipo));
});

// Manejar selección de tipos de documento
function handleDocumentTypeSelection(doc) {
  const allSelectedArchivos = new Set(props.modelValue);
  
  if (selectedDocumentTypes.value.includes(doc.tipo)) {
    // Agregar todos los archivos de este tipo
    doc.archivos.forEach(archivo => allSelectedArchivos.add(archivo));
  } else {
    // Remover todos los archivos de este tipo
    doc.archivos.forEach(archivo => allSelectedArchivos.delete(archivo));
  }
  
  emit('update:modelValue', Array.from(allSelectedArchivos));
}

// Watcher para sincronizar selectedDocumentTypes cuando cambia modelValue
watch(() => props.modelValue, (newValue) => {
  const tiposSeleccionados = new Set();
  const archivosSeleccionados = new Set(newValue);
  
  groupedDocumentTypes.value.forEach(doc => {
    const todosArchivosSeleccionados = doc.archivos.every(archivo => 
      archivosSeleccionados.has(archivo)
    );
    
    if (todosArchivosSeleccionados && doc.archivos.length > 0) {
      tiposSeleccionados.add(doc.tipo);
    }
  });
  
  selectedDocumentTypes.value = Array.from(tiposSeleccionados);
}, { immediate: true });

//Funcion para la carga de edit.vue
function updateSelectedDocumentTypesFromModelValue() {
  const tiposSeleccionados = new Set();
  const archivosSeleccionados = new Set(props.modelValue);
  
  groupedDocumentTypes.value.forEach(doc => {
    const todosArchivosSeleccionados = doc.archivos.every(archivo =>
      archivosSeleccionados.has(archivo)
    );
    
    if (todosArchivosSeleccionados && doc.archivos.length > 0) {
      tiposSeleccionados.add(doc.tipo);
    }
  });

  selectedDocumentTypes.value = Array.from(tiposSeleccionados);
}

</script>

<style scoped>
.document-selector {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
  margin-top: 1rem;
}

.section-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 1.5rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #e2e8f0;
}

.document-types-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1rem;
}

.document-type-card {
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  background-color: white;
  transition: all 0.2s;
}

.document-type-card:hover {
  border-color: #cbd5e0;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.document-type-label {
  display: flex;
  align-items: center;
  padding: 1rem;
  cursor: pointer;
  height: 100%;
}

.document-type-checkbox {
  width: 1.25rem;
  height: 1.25rem;
  margin-right: 1rem;
  cursor: pointer;
}

.document-type-content {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

.document-type-name {
  font-size: 0.9375rem;
  font-weight: 500;
  color: #2d3748;
  margin-bottom: 0.25rem;
}

.document-meta {
  display: flex;
  gap: 0.75rem;
  font-size: 0.8125rem;
  color: #718096;
}

.document-count::before {
  content: "📄 ";
}

.document-empresas::before {
  content: "🏢 ";
}

@media (min-width: 768px) {
  .document-types-container {
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.25rem;
  }
  
  .section-title {
    font-size: 1.25rem;
  }
  
  .document-type-name {
    font-size: 1rem;
  }
}
</style>