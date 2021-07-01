<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div id="todo-container" class="p-2">
          <section id="todo-header" class="mt-3">
            <h3 class="text-center">Todo Tasks</h3>
          </section>
          <section id="todo-errors">
            <div
              v-if="createTodoForm.errors.length > 0"
              class="alert alert-warning alert-dismissible fade show"
              role="alert"
            >
              <span v-for="(error, index) in createTodoForm.errors" :key="index"
                ><ul>
                  <li>{{ error }}</li>
                </ul>
              </span>
              <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div
              v-if="createTodoForm.isCreated"
              class="alert alert-success alert-dismissible fade show"
              role="alert"
            >
              <span>Todo task created successfully</span>
              <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div
              v-if="editTodoForm.isUpdated"
              class="alert alert-success alert-dismissible fade show"
              role="alert"
            >
              <span>Todo task updated successfully</span>
              <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          </section>
          <section id="add-todo-form" class="my-2">
            <form>
              <div class="d-flex justify-content-between align-items-center">
                <input
                  v-model="createTodoForm.name"
                  minlength="3"
                  maxlength="50"
                  placeholder="Enter title"
                  type="text"
                  class="form-control mr-3"
                />
                <textarea
                  id="description"
                  minlength="5"
                  placeholder="Enter description"
                  type="text"
                  v-model="createTodoForm.description"
                  class="form-control mr-3"
                ></textarea>
                <button
                  v-if="createTodoForm.isSubmitting"
                  class="btn btn-primary"
                  type="button"
                  disabled
                >
                  <span
                    class="spinner-grow spinner-grow-sm"
                    role="status"
                    aria-hidden="true"
                  ></span>
                  <span class="sr-only">Loading...</span>
                </button>
                <button v-else @click.prevent="addTodo" class="btn btn-primary">
                  Add
                </button>
              </div>
            </form>
          </section>
          <section id="todo-actions"></section>
          <section id="todo-list">
            <ul class="list-group">
              <div v-if="todos.isLoading" class="text-center">
                <div class="spinner-border" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </div>
              <li
                v-if="!todos.isLoading && todos.data.length > 0"
                v-for="todo in todos.data"
                :key="todo.id"
                class="list-group-item"
              >
                <div class="d-flex justify-content-between align-items-center">
                  {{ todo.name }}
                  <span
                    class="d-flex justify-content-between align-items-center"
                  >
                    <a
                      class="text-success mr-2"
                      href="#"
                      @click.prevent="showTodo(todo)"
                    >
                      <i class="fa fa-eye"> </i> View
                    </a>
                    <a
                      class="text-success mr-2"
                      href="#"
                      @click.prevent="showEditTodoForm(todo)"
                    >
                      <i class="fa fa-edit"> </i> Edit
                    </a>
                    <a
                      class="text-danger"
                      href="#"
                      @click.prevent="destroy(todo)"
                    >
                      <i class="fa fa-trash-o"></i> Delete
                    </a>
                  </span>
                </div>            
              </li>
              <li
                v-if="!todos.isLoading && todos.data.length === 0"
                class="
                  list-group-item list-group-item-action list-group-item-warning
                "
              >
                No todo task found.
              </li>
            </ul>
          </section>
        </div>
      </div>
    </div>
    <!-- Modals -->
    <!-- show todo task -->
    <div
      class="modal fade"
      id="showTodoItemModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="showTodoItemModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="showTodoItemModalLabel">Todo View</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <section id="show-todo">
              <form>
                <div class="container center">
                  <h2>Title: {{ editTodoForm.name }}</h2>
                  <p>Details: {{ editTodoForm.description }}</p>
                  <small>created: {{ editTodoForm.created_at }}</small>
                </div>
              </form>
            </section>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- edit todo task -->
    <div
      class="modal fade"
      id="editTodoItemModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="editTodoItemModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editTodoItemModalLabel">Update Todo</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <section id="edit-todo-errors" class="mb-3">
              <div
                v-if="editTodoForm.errors.length > 0"
                class="alert alert-warning alert-dismissible fade show"
                role="alert"
              >
                <span
                  v-for="(error, index) in editTodoForm.errors"
                  :key="index"
                  >{{ error }}</span
                >
                <button
                  type="button"
                  class="close"
                  data-dismiss="alert"
                  aria-label="Close"
                >
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </section>
            <section id="edit-todo">
              <form>
                <div class="container">
                  <input
                    v-model="editTodoForm.name"
                    v-on:keyup.enter="updateTodo"
                    minlength="5"
                    maxlength="50"
                    placeholder="Enter task name"
                    type="text"
                    class="form-control"
                  />
                  <br />
                  <div class="control">
                    <textarea
                      v-model="editTodoForm.description"
                      placeholder="Enter description"
                    ></textarea>
                  </div>
                  <button
                    v-if="editTodoForm.isSubmitting"
                    class="btn btn-primary"
                    type="button"
                    disabled
                  >
                    <span
                      class="spinner-grow spinner-grow-sm"
                      role="status"
                      aria-hidden="true"
                    ></span>
                    <span class="sr-only">Loading...</span>
                  </button>
                  <button
                    v-else
                    @click.prevent="updateTodo"
                    class="btn btn-success"
                  >
                    Update
                  </button>
                </div>
              </form>
            </section>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TodoList",
  data() {
    return {
      todos: {
        isLoading: false,
        data: [],
      },
      createTodoForm: {
        isSubmitting: false,
        isCreated: false,
        name: undefined,
        description: undefined,
        errors: [],
      },
      editTodoForm: {
        isSubmitting: false,
        isUpdated: false,
        id: undefined,
        name: undefined,
        description: undefined,
        created_at: undefined,
        errors: [],
      },
      error: undefined,
    };
  },
  mounted() {
    this.loadTodos();
  },
  methods: {
    showEditTodoForm(todo) {
      this.editTodoForm.name = todo.name;
      this.editTodoForm.description = todo.description;
      this.editTodoForm.id = todo.id;
      $("#editTodoItemModal").modal("show");
    },
    showTodo(todo) {
      this.editTodoForm.name = todo.name;
      this.editTodoForm.description = todo.description;
      this.editTodoForm.created_at = todo.created_at;
      this.editTodoForm.uuid = todo.uuid;
      $("#showTodoItemModal").modal("show");
    },
    /**
     * Loads todos
     */
    loadTodos() {
      // update loader to loading
      this.todos.isLoading = true;

      axios
        .get("/api/todos")
        .then((response) => {
          console.log(response.data);
          this.todos.data = response.data;
        })
        .catch((error) => {
          //TODO: update with your logic on how to handle this error.
          console.log(error.message);
          this.error = "Unable to load todos. View log for more information";
        })
        .finally(() => {
          // disable loader
          this.todos.isLoading = false;
        });
    },

    /**
     * Create a TodoItem.
     * Uses payload in the this.createTodoForm param
     */
    addTodo() {
      // update loader to loading
      this.createTodoForm.isSubmitting = true;
      // Use can use this as the payload.
      // const todoData = { name: this.createTodoForm.name };
      axios
        .post("/api/todos", this.createTodoForm)
        .then((response) => {
          this.createTodoForm.errors = [];
          this.createTodoForm.name = undefined;
          this.createTodoForm.description = undefined;
          this.createTodoForm.isCreated = true;
          this.loadTodos();
        })
        .catch((error) => {
          /**
           * Check for form validation error. Laravel return http code 422
           * _ lodash is already loaded by laravel. check resources/js/bootstrap.js
           */
          if (error.response && error.response.status === 422) {
            this.createTodoForm.errors = _.flatten(
              _.toArray(error.response.data.errors)
            );
          } else {
            //TODO: update with your logic on how to handle this error.
            console.log(error.message);
            this.error = "Unable to add todo. View log for more information";
          }
        })
        .finally(() => {
          // disable loader
          this.createTodoForm.isSubmitting = false;
        });
    },
    /**
     * Updates a TodoItem
     *
     */
    updateTodo() {
      // update loader to loading
      this.editTodoForm.isSubmitting = true;
      // Use can use this as the payload.
      // const todoData = { name: this.createTodoForm.name };
      axios
        .put(`/api/todos/${this.editTodoForm.id}`, this.editTodoForm)
        .then((response) => {
          $("#editTodoItemModal").modal("hide");
          this.editTodoForm.name = undefined;
          this.editTodoForm.description = undefined;
          this.editTodoForm.uuid = undefined;
          this.editTodoForm.isUpdated = true;
          this.loadTodos();
        })
        .catch((error) => {
          /**
           * Check for form validation error. Laravel return http code 422
           * _ lodash is already loaded by laravel. check resources/js/bootstrap.js
           */
          if (error.response && error.response.status === 422) {
            this.editTodoForm.errors = _.flatten(
              _.toArray(error.response.data.errors)
            );
          } else {
            //TODO: update with your logic on how to handle this error.
            console.log(error.message);
            this.error = "Unable to update todo. View log for more information";
          }
        })
        .finally(() => {
          // disable loader
          this.editTodoForm.isSubmitting = false;
        });
    },
    /**
     * Deletes a TodoItem
     * @param todoItem
     */
    destroy(todoItem) {
      // Use can use this as the payload.
      // const todoData = { name: this.createTodoForm.name };
      axios
        .delete(`/api/todos/${todoItem.id}`)
        .then((response) => {
          this.loadTodos();
        })
        .catch((error) => {
          //TODO: update with your logic on how to handle this error.
          console.log(error.message);
          this.error = "Unable to delete todo. View log for more information";
        });
    },
  },
};
</script>

<style scoped>
textarea {
  width: 100%;
}
</style>
