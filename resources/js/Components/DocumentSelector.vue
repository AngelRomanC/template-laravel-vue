<template>
  <div class="document-selector">
    <!-- Documentos Técnicos -->
    <div class="document-section">
      <h3 class="section-title">Documentos Técnicos</h3>
      <div class="document-list">
        <div 
          v-for="empresa in empresasConDocumentosTecnicos" 
          :key="'tec-emp-'+empresa.id"
          class="empresa-group"
        >
          <h4 class="empresa-name">{{ empresa.nombre }}</h4>
          <div 
            v-for="doc in empresa.documentos" 
            :key="'tec-'+empresa.id+'-'+doc.tipo"
            class="document-group"
          >
            <h5 class="document-type">{{ doc.tipo }}</h5>
            <div class="document-items">
              <label 
                v-for="archivo in doc.archivos" 
                :key="'tecnico-'+archivo.id"
                class="document-item"
              >
                <input
                  type="checkbox"
                  :value="archivo.id"
                  v-model="selectedTecnicos"
                  class="document-checkbox"
                >
                <span class="document-name">{{ archivo.nombre }}</span>
                <button 
                  @click.stop="openModal(archivo.url)"
                  class="document-preview"
                  type="button"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Documentos Legales -->
    <div class="document-section">
      <h3 class="section-title">Documentos Legales</h3>
      <div class="document-list">
        <div 
          v-for="empresa in empresasConDocumentosLegales" 
          :key="'leg-emp-'+empresa.id"
          class="empresa-group"
        >
          <h4 class="empresa-name">{{ empresa.nombre }}</h4>
          <div 
            v-for="doc in empresa.documentos" 
            :key="'leg-'+empresa.id+'-'+doc.tipo"
            class="document-group"
          >
            <h5 class="document-type">{{ doc.tipo }}</h5>
            <div class="document-items">
              <label 
                v-for="archivo in doc.archivos" 
                :key="'legal-'+archivo.id"
                class="document-item"
              >
                <input
                  type="checkbox"
                  :value="archivo.id"
                  v-model="selectedLegales"
                  class="document-checkbox"
                >
                <span class="document-name">{{ archivo.nombre }}</span>
                <button 
                  @click.stop="openModal(archivo.url)"
                  class="document-preview"
                  type="button"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para visualización de documentos -->
    <div v-if="showModal" class="document-modal">
      <div class="modal-overlay" @click="closeModal"></div>
      <div class="modal-content">
        <div class="modal-header">
          <h3>Visualizador de Documento</h3>
          <button @click="closeModal" class="modal-close">
            &times;
          </button>
        </div>
        <div class="modal-body">
          <iframe 
            v-if="currentDocument"
            :src="getDocumentUrl(currentDocument)"
            frameborder="0"
            class="document-iframe"
          ></iframe>
          <div v-else class="document-error">
            No se puede visualizar el documento
          </div>
        </div>
        <div class="modal-footer">
          <a 
            :href="getDocumentUrl(currentDocument)"
            download
            class="download-button"
          >
            Descargar Documento
          </a>
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
  modelValueTecnicos: {
    type: Array,
    default: () => []
  },
  modelValueLegales: {
    type: Array,
    default: () => []
  },
  baseUrl: {
    type: String,
    default: '/storage/'
  }
});

const emit = defineEmits([
  'update:modelValueTecnicos',
  'update:modelValueLegales'
]);

// Datos reactivos
const archivosTecnicos = ref([]);
const archivosLegales = ref([]);
const showModal = ref(false);
const currentDocument = ref(null);

// Watcher para controlar cambios en las empresas seleccionadas
watch(() => props.modelValueEmpresas, async (nuevasEmpresas, oldEmpresas) => {
  // Si no hay empresas seleccionadas, limpiar todo
  if (!nuevasEmpresas || nuevasEmpresas.length === 0) {
    archivosTecnicos.value = [];
    archivosLegales.value = [];
    emit('update:modelValueTecnicos', []);
    emit('update:modelValueLegales', []);
    return;
  }

  // Obtener empresas que se eliminaron
  const empresasRemovidas = oldEmpresas?.filter(e => !nuevasEmpresas.includes(e)) || [];
  
  // Si solo estamos agregando empresas (no quitando), no limpiar selecciones
  if (empresasRemovidas.length === 0) {
    // Solo cargar documentos de las nuevas empresas (las que no estaban antes)
    const nuevasEmpresasACargar = oldEmpresas 
      ? nuevasEmpresas.filter(e => !oldEmpresas.includes(e))
      : nuevasEmpresas;
    
    for (const empresaId of nuevasEmpresasACargar) {
      await cargarDocumentosEmpresa(empresaId);
    }
    return;
  }

  // Si estamos quitando empresas:  
  // 1. Primero cargar todos los documentos de las empresas actuales
  archivosTecnicos.value = [];
  archivosLegales.value = [];
  
  for (const empresaId of nuevasEmpresas) {
    await cargarDocumentosEmpresa(empresaId);
  }

  // 2. Filtrar las selecciones para mantener solo las de empresas que siguen seleccionadas
  if (props.modelValueTecnicos.length > 0) {
    const archivosTecnicosEmpresasActuales = archivosTecnicos.value.map(a => a.id);
    emit('update:modelValueTecnicos', props.modelValueTecnicos.filter(
      id => archivosTecnicosEmpresasActuales.includes(id)
    ));
  }

  if (props.modelValueLegales.length > 0) {
    const archivosLegalesEmpresasActuales = archivosLegales.value.map(a => a.id);
    emit('update:modelValueLegales', props.modelValueLegales.filter(
      id => archivosLegalesEmpresasActuales.includes(id)
    ));
  }
}, { immediate: true });

// Función para cargar documentos de una empresa
async function cargarDocumentosEmpresa(empresaId) {
  const empresa = props.empresas.find(e => e.id === empresaId);
  const response = await axios.get(`/empresa/${empresaId}/documentos`);
  const documentos = response.data.documentos_tecnicos;
  const documentosLegales = response.data.documentos_legales;

  const nuevosTecnicos = documentos.flatMap(doc =>
    doc.archivos.map(archivo => ({
      id: archivo.id,
      nombre: archivo.nombre_original,
      url: archivo.ruta_archivo,
      documento: doc.tipo_de_documento.nombre_documento,
      empresa_id: empresaId,
      empresa_nombre: empresa?.name || empresa?.nombre || 'Empresa desconocida'
    }))
  );

  const nuevosLegales = documentosLegales.flatMap(doc =>
    doc.archivos.map(archivo => ({
      id: archivo.id,
      nombre: archivo.nombre_original,
      url: archivo.ruta_archivo,
      documento: doc.tipo_de_documento.nombre_documento,
      empresa_id: empresaId,
      empresa_nombre: empresa?.name || empresa?.nombre || 'Empresa desconocida'
    }))
  );

  archivosTecnicos.value.push(...nuevosTecnicos);
  archivosLegales.value.push(...nuevosLegales);
}

// Agrupar documentos técnicos por empresa y luego por tipo
const empresasConDocumentosTecnicos = computed(() => {
  const empresasMap = {};
  
  archivosTecnicos.value.forEach(archivo => {
    if (!empresasMap[archivo.empresa_id]) {
      empresasMap[archivo.empresa_id] = {
        id: archivo.empresa_id,
        nombre: archivo.empresa_nombre,
        documentos: {}
      };
    }
    
    if (!empresasMap[archivo.empresa_id].documentos[archivo.documento]) {
      empresasMap[archivo.empresa_id].documentos[archivo.documento] = [];
    }
    
    empresasMap[archivo.empresa_id].documentos[archivo.documento].push(archivo);
  });
  
  return Object.values(empresasMap).map(empresa => ({
    ...empresa,
    documentos: Object.keys(empresa.documentos).map(tipo => ({
      tipo,
      archivos: empresa.documentos[tipo]
    }))
  }));
});

// Agrupar documentos legales por empresa y luego por tipo
const empresasConDocumentosLegales = computed(() => {
  const empresasMap = {};
  
  archivosLegales.value.forEach(archivo => {
    if (!empresasMap[archivo.empresa_id]) {
      empresasMap[archivo.empresa_id] = {
        id: archivo.empresa_id,
        nombre: archivo.empresa_nombre,
        documentos: {}
      };
    }
    
    if (!empresasMap[archivo.empresa_id].documentos[archivo.documento]) {
      empresasMap[archivo.empresa_id].documentos[archivo.documento] = [];
    }
    
    empresasMap[archivo.empresa_id].documentos[archivo.documento].push(archivo);
  });
  
  return Object.values(empresasMap).map(empresa => ({
    ...empresa,
    documentos: Object.keys(empresa.documentos).map(tipo => ({
      tipo,
      archivos: empresa.documentos[tipo]
    }))
  }));
});

// Modelos para los checkboxes
const selectedTecnicos = computed({
  get: () => props.modelValueTecnicos,
  set: (value) => emit('update:modelValueTecnicos', value)
});

const selectedLegales = computed({
  get: () => props.modelValueLegales,
  set: (value) => emit('update:modelValueLegales', value)
});

// Métodos para el modal
const openModal = (documentUrl) => {
  currentDocument.value = documentUrl;
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  currentDocument.value = null;
};

const getDocumentUrl = (path) => {
  return props.baseUrl + path;
};
</script>

<style scoped>
.document-selector {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
  margin-top: 1rem;
}

.empresa-group {
  margin-bottom: 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  padding: 0.75rem;
  background-color: #f7fafc;
}

.empresa-name {
  font-size: 0.9375rem;
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 0.5rem;
  padding-bottom: 0.25rem;
  border-bottom: 1px solid #e2e8f0;
}

.document-group {
  margin-bottom: 1rem;
  margin-left: 0.25rem;
}

.document-type {
  font-size: 0.8125rem;
  font-weight: 500;
  color: #4a5568;
  margin-bottom: 0.5rem;
}

.document-items {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.document-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem;
  border-radius: 0.375rem;
  transition: background-color 0.2s;
  flex-wrap: wrap;
}

.document-item:hover {
  background-color: #e5e7eb;
}

.document-checkbox {
  width: 1rem;
  height: 1rem;
  border-color: #d1d5db;
}

.document-name {
  flex: 1;
  min-width: 120px;
  font-size: 0.8125rem;
  color: #374151;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.document-preview {
  color: #3b82f6;
  display: flex;
  align-items: center;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.25rem;
}

.document-preview:hover {
  color: #2563eb;
}

.document-modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
  position: relative;
  background-color: white;
  border-radius: 0.5rem;
  width: 95%;
  max-width: 900px;
  max-height: 85vh;
  display: flex;
  flex-direction: column;
  z-index: 10;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  margin: 0.5rem;
}

.modal-header {
  padding: 0.75rem;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.25rem;
  cursor: pointer;
  color: #6b7280;
}

.modal-body {
  flex: 1;
  padding: 0.75rem;
  overflow: auto;
}

.document-iframe {
  width: 100%;
  height: 60vh;
  min-height: 300px;
}

.document-error {
  padding: 1.5rem;
  text-align: center;
  color: #ef4444;
  font-size: 0.875rem;
}

.modal-footer {
  padding: 0.75rem;
  border-top: 1px solid #e5e7eb;
  display: flex;
  justify-content: flex-end;
}

.download-button {
  background-color: #3b82f6;
  color: white;
  padding: 0.5rem 0.75rem;
  border-radius: 0.375rem;
  text-decoration: none;
  font-weight: 500;
  font-size: 0.875rem;
}

@media (min-width: 640px) {
  .document-name {
    font-size: 0.875rem;
  }
  
  .modal-header {
    padding: 1rem;
  }
  
  .modal-body {
    padding: 1rem;
  }
  
  .modal-footer {
    padding: 1rem;
  }
}

@media (min-width: 768px) {
  .document-selector {
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-top: 1.5rem;
  }
  
  .empresa-group {
    margin-bottom: 1.5rem;
    padding: 1rem;
  }
  
  .empresa-name {
    font-size: 1rem;
    margin-bottom: 0.75rem;
  }
  
  .document-type {
    font-size: 0.875rem;
  }
  
  .document-preview {
    padding: 0;
  }
  
  .modal-content {
    width: 80%;
    max-height: 90vh;
    margin: 0;
  }
  
  .document-iframe {
    height: 65vh;
    min-height: 400px;
  }
  
  .download-button {
    padding: 0.5rem 1rem;
    font-size: 1rem;
  }
  
  .section-title {
    font-size: 1.125rem;
    margin-bottom: 1rem;
  }
}
</style>