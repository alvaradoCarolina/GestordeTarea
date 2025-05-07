<template>
    <div>
      <h3 class="mb-4">📋 Mis Tareas</h3>

      <!-- Filtro por prioridad -->
      <div class="form-group">
        <label for="filtro">Filtrar por prioridad:</label>
        <select v-model="filtroPrioridad" @change="filtrarTareas" class="form-control w-25">
          <option value="">Todas</option>
          <option value="baja">Baja</option>
          <option value="media">Media</option>
          <option value="alta">Alta</option>
        </select>
      </div>

      <!-- Tabla de tareas -->
      <table class="table table-bordered mt-3">
        <thead class="thead-light">
          <tr>
            <th>Descripción</th>
            <th>Prioridad</th>
            <th>Fecha Vencimiento</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in tareasFiltradas" :key="task.id">
            <td>{{ task.descripcion }}</td>
            <td>
              <span :class="{
                'badge badge-danger': task.prioridad === 'alta',
                'badge badge-warning': task.prioridad === 'media',
                'badge badge-success': task.prioridad === 'baja',
              }">
                {{ task.prioridad }}
              </span>
            </td>
            <td>{{ task.fecha_vencimiento }}</td>
            <td>
              <span :class="{'text-success': task.estado === 'completada', 'text-secondary': task.estado === 'pendiente'}">
                {{ task.estado }}
              </span>
            </td>
            <td>
              <button class="btn btn-sm"
                :class="task.estado === 'pendiente' ? 'btn-success' : 'btn-secondary'"
                @click="toggleEstado(task)">
                {{ task.estado === 'pendiente' ? 'Marcar como Completada' : 'Marcar como Pendiente' }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>

  <script>
  export default {
    name: 'TaskList',
    data() {
      return {
        tareas: [],
        filtroPrioridad: '',
      };
    },
    computed: {
      tareasFiltradas() {
        if (!this.filtroPrioridad) return this.tareas;
        return this.tareas.filter(t => t.prioridad === this.filtroPrioridad);
      }
    },
    created() {
      this.obtenerTareas();
    },
    methods: {
      obtenerTareas() {
        this.axios.get('/api/tareas')
          .then(response => {
            this.tareas = response.data;
          });
      },
      filtrarTareas() {
        // ya está controlado por el computed
      },
      toggleEstado(tarea) {
        const nuevoEstado = tarea.estado === 'pendiente' ? 'completada' : 'pendiente';
        this.axios.put(`/api/tareas/${tarea.id}`, { estado: nuevoEstado })
          .then(() => {
            tarea.estado = nuevoEstado;
          });
      },
    }
  };
  </script>
