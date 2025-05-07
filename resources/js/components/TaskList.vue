<template>
    <div>
      <h2 class="mb-4 text-center">Gestión de Tareas</h2>

      <!-- Formulario para crear una nueva tarea -->
      <div class="card mb-4">
        <div class="card-header">Nueva Tarea</div>
        <div class="card-body">
          <form @submit.prevent="crearTarea">
            <div class="form-group">
              <label>Título</label>
              <input v-model="nuevaTarea.titulo" class="form-control" required />
            </div>

            <div class="form-group">
              <label>Descripción</label>
              <textarea v-model="nuevaTarea.descripcion" class="form-control" required></textarea>
            </div>

            <div class="form-group">
              <label>Prioridad</label>
              <select v-model="nuevaTarea.prioridad" class="form-control" required>
                <option disabled value="">Seleccione una prioridad</option>
                <option value="baja">Baja</option>
                <option value="media">Media</option>
                <option value="alta">Alta</option>
              </select>
            </div>

            <div class="form-group">
              <label>Fecha de Vencimiento</label>
              <input type="date" v-model="nuevaTarea.fecha_vencimiento" class="form-control" required />
            </div>

            <button class="btn btn-primary mt-2">Crear Tarea</button>
          </form>
        </div>
      </div>

      <!-- Filtro -->
      <div class="mb-3">
        <label>Filtrar por prioridad:</label>
        <select v-model="filtroPrioridad" @change="filtrarTareas" class="form-control w-50">
          <option value="">Todas</option>
          <option value="baja">Baja</option>
          <option value="media">Media</option>
          <option value="alta">Alta</option>
        </select>
      </div>

      <!-- Lista de Tareas -->
      <div v-if="tareas.length">
        <div v-for="tarea in tareas" :key="tarea.id" class="card mb-3">
          <div class="card-header d-flex justify-content-between align-items-center">
            <strong>{{ tarea.titulo }}</strong>
            <span :class="estadoClase(tarea.estado)">
              {{ tarea.estado === 'completada' ? '✅ Completada' : '⏳ Pendiente' }}
            </span>
          </div>
          <div class="card-body">
            <p><strong>Descripción:</strong> {{ tarea.descripcion }}</p>
            <p><strong>Prioridad:</strong> {{ tarea.prioridad }}</p>
            <p><strong>Fecha de Vencimiento:</strong> {{ tarea.fecha_vencimiento }}</p>

            <div class="mb-2">
              <button class="btn btn-sm btn-success mr-2" @click="cambiarEstado(tarea.id)">Cambiar Estado</button>
              <button class="btn btn-sm btn-danger" @click="eliminarTarea(tarea.id)">Eliminar</button>
            </div>

            <!-- Comentarios -->
            <div>
              <h6>Comentarios</h6>
              <ul class="list-group mb-2">
                <li v-for="comentario in tarea.comentarios" :key="comentario.id" class="list-group-item">
                  {{ comentario.comentario }}
                </li>
              </ul>
              <div class="input-group">
                <input
                  v-model="comentariosPorTarea[tarea.id]"
                  placeholder="Escribe un comentario..."
                  class="form-control"
                />
                <div class="input-group-append">
                  <button class="btn btn-outline-primary" @click="agregarComentario(tarea.id)">Agregar</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else>
        <p>No hay tareas para mostrar.</p>
      </div>
    </div>
  </template>

  <script>
  export default {
    data() {
      return {
        tareas: [],
        filtroPrioridad: '',
        nuevaTarea: {
          titulo: '',
          descripcion: '',
          prioridad: '',
          fecha_vencimiento: '',
        },
        comentariosPorTarea: {},
      };
    },
    mounted() {
      this.obtenerTareas();
    },
    methods: {
      async obtenerTareas() {
        const response = await fetch(`/api/tareas?prioridad=${this.filtroPrioridad}`);
        if (response.ok) {
          this.tareas = await response.json();
        }
      },
      async crearTarea() {
        const response = await fetch('/api/tareas', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.nuevaTarea),
        });
        if (response.ok) {
          this.nuevaTarea = { titulo: '', descripcion: '', prioridad: '', fecha_vencimiento: '' };
          this.obtenerTareas();
        }
      },
      async cambiarEstado(id) {
        const response = await fetch(`/api/tareas/${id}/estado`, { method: 'PATCH' });
        if (response.ok) this.obtenerTareas();
      },
      async eliminarTarea(id) {
        await fetch(`/api/tareas/${id}`, { method: 'DELETE' });
        this.tareas = this.tareas.filter(t => t.id !== id);
      },
      async agregarComentario(tareaId) {
        const texto = this.comentariosPorTarea[tareaId];
        if (!texto) return;
        const response = await fetch(`/api/tareas/${tareaId}/comentarios`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ comentario: texto }),
        });
        if (response.ok) {
          this.comentariosPorTarea[tareaId] = '';
          this.obtenerTareas();
        }
      },
      filtrarTareas() {
        this.obtenerTareas();
      },
      estadoClase(estado) {
        return estado === 'completada' ? 'text-success' : 'text-warning';
      },
    },
  };
  </script>

  <style scoped>
  .card-header span {
    font-size: 0.9rem;
  }
  </style>
