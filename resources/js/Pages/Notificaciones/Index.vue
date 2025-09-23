<template>
  <LayoutMain>
    <SectionTitleLineWithButton :title="titulo" main />

    <NotificationBar v-if="$page.props.flash.success" color="success" :icon="mdiInformation" :outline="false">
      {{ $page.props.flash.success }}
    </NotificationBar>

    <NotificationBar v-if="$page.props.flash.error" color="danger" :icon="mdiInformation" :outline="false">
      {{ $page.props.flash.error }}
    </NotificationBar>

    <h2 class="text-2xl font-semibold mb-4">Notificaciones</h2>

    <CardBox>
      <div class="grid grid-cols-3 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-3">
        <div v-for="notification in $page.props.notifications" :key="notification.id"
          class="p-4 max-w-full bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
          <p class="text-xl">{{ notification.data.materia }}</p>
          <p> {{ notification.data.profesor }}</p>
          
          <p class="text-xl"> {{ notification.data.message2}}</p>
          <p> {{ notification.data.materia2 }}</p>
          <p> {{ notification.data.alumno }}</p>

          <p> Fecha: {{ formatDate(notification.created_at) }}</p>
          <p> {{ notification.data.message }}</p>

          <BaseButtons class="justify-between mt-4">
            <BaseButton @click="markAsRead(notification.id)" small :icon="mdiRead" label="Marcar como leída" color="info"/>
         <!--    <BaseButton @click="deleteNotification(notification.id)" small :icon="mdiTrashCan" label="Eliminar" color="danger"/> -->
          </BaseButtons>
        </div>
      </div>
    </CardBox>
    
    <!-- <button @click="markAsRead(notification.id)">Marcar como leída</button>
    <button @click="deleteNotification(notification.id)">Eliminar</button> -->
    <!-- <ul>
        <li v-for="notification in $page.props.notifications" :key="notification.id" class="border">
          <p>{{ notification.data.message }}</p>
          <p>{{ notification.data.materia }}</p>
          <p>{{ notification.data.profesor }}</p>
          <p>{{ formatDate(notification.created_at) }}</p>
          <button @click="markAsRead(notification.id)">Marcar como leída</button>
          <button @click="deleteNotification(notification.id)">Eliminar</button>
        </li>        
      </ul> -->

  </LayoutMain>
</template>

<script>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import Swal from 'sweetalert2';
import LayoutMain from '@/Layouts/LayoutMain.vue';
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue';
import CardBox from '@/Components/CardBox.vue';
import CardBoxComponentEmpty from '@/Components/CardBoxComponentEmpty.vue';
import NotificationBar from '@/Components/NotificationBar.vue';
import { mdiInformation, mdiRead, mdiTrashCan } from '@mdi/js';
import BaseButtons from "@/components/BaseButtons.vue";
import BaseButton from "@/components/BaseButton.vue";

export default {
  props: {
    titulo: { type: String, required: true }
  },
  components: {
    LayoutMain,
    SectionTitleLineWithButton,
    CardBox,
    CardBoxComponentEmpty,
    NotificationBar,
    BaseButton,
    BaseButtons
  },
  setup() {
    const notifications = ref([]);

    const loadNotifications = () => {
      axios.get('/notificaciones')
        .then(response => {
          console.log(response); // Agregar 
          notifications.value = response.data.notifications;
        })
        .catch(error => {
          console.error('Error al cargar las notificaciones:', error);
          Swal.fire('¡Error!', 'No se pudieron cargar las notificaciones.', 'error');
        });
    };

    const markAsRead = (id) => {
      Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción marcará la notificación como leída.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, marcar como leída',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          axios.put(`/notificaciones/${id}/marcar-como-leida`)
            .then(response => {
            //  router.visit('/notificaciones');

              loadNotifications();
              Swal.fire('¡Notificación marcada como leída!', '', 'success');
            
            })
            .catch(error => {
              console.error('Error al marcar la notificación como leída:', error);
              Swal.fire('¡Error!', 'No se pudo marcar la notificación como leída.', 'error');
            });
        }
      });
    };

    const deleteNotification = (id) => {
      Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará la notificación.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          axios.delete(`/notificaciones/${id}`)
            .then(response => {
              loadNotifications();
              Swal.fire('¡Notificación eliminada!', '', 'success');
            })
            .catch(error => {
              console.error('Error al eliminar la notificación:', error);
              Swal.fire('¡Error!', 'No se pudo eliminar la notificación.', 'error');
            });
        }
      });
    };

    const formatDate = (date) => {
      return new Date(date).toLocaleString();
    };

    onMounted(() => {
      loadNotifications();

    });

    return {
      notifications,
      markAsRead,
      deleteNotification,
      formatDate,
      mdiRead,
      mdiTrashCan
    };
  }
};
</script>

<style scoped>
/* Estilos opcionales para el componente */
</style>
