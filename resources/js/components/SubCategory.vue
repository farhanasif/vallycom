<template>
  <div class="container mt-3">
    <div class="row" v-if="$gate.isAdminOrAuthor()">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">All Sub Categories</h3>

            <div class="card-tools">
              <button class="btn btn-success" @click="newModal">
                Add New SubCategory
                <i class="fas fa-list-alt fa-fw"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Department Name</th>
                  <th>Category Name</th>
                  <th>SubCategory Name</th>
                  <th>Image</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="subcategory in subcategories.data" :key="subcategory.subcategory_id">
                  <td>{{subcategory.subcategory_id}}</td>
                  <td>{{subcategory.department_name}}</td>
                  <td>{{subcategory.category_name}}</td>
                  <td>{{subcategory.subcategory_name}}</td>
                  <td>
                    <img style="width:40px; height:40px" :src="subcategory.photo" alt="image" />
                  </td>
                  <td>{{subcategory.created_at | myDate}}</td>
                  <td>
                    <a href="#" @click="editModal(subcategory)">
                      <i class="fas fa-edit blue"></i>
                    </a>
                    |
                    <a
                      href="#"
                      @click="deleteSubcategory(subcategory.subcategory_id)"
                    >
                      <i class="fas fa-trash red"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <pagination :data="subcategories" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
      </div>
    </div>
    <div v-if="!$gate.isAdminOrAuthor()">
      <not-found></not-found>
    </div>
    <!-- Modal -->
    <div
      class="modal fade"
      id="subcategoryModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="subcategoryModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <form @submit.prevent="editmode ? updateSubcategory() : createSubcategory()">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New SubCategory</h5>
              <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update SubCategory Info</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Select Department Name</label>
                <select
                  name="department_id"
                  v-model="form.department_id"
                  id="department_id"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('text') }"
                  @click="loadGetCategory(form.department_id)"
                >
                  <option value>-- select Department --</option>
                  <option
                    v-for="department in departments"
                    :key="department.id"
                    v-bind:value="department.id"
                    v-text="department.department_name"
                  ></option>
                </select>
                <has-error :form="form" field="department_id"></has-error>
              </div>

              <div class="form-group">
                <label>Select Category Name</label>
                <select
                  name="category_id"
                  v-model="form.category_id"
                  id="category_id"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('text') }"
                >
                  <option value>-- select category --</option>
                  <option
                    v-for="category in categories"
                    :key="category.id"
                    v-bind:value="category.id"
                    v-text="category.category_name"
                  ></option>
                </select>
                <has-error :form="form" field="category_id"></has-error>
              </div>

              <div class="form-group">
                <label>SubCategory Name</label>
                <input
                  v-model="form.subcategory_name"
                  type="text"
                  name="subcategory_name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('subcategory_name') }"
                />
                <has-error :form="form" field="subcategoryName"></has-error>
              </div>
              <div class="form-group">
                <label>Photo</label>
                <input type="file" name="photo" class="form-control" @change="photoUp" />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
              <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { Form, HasError, AlertError } from "vform";
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
import Swal from "sweetalert2";
export default {
  data() {
    return {
      editmode: false,
      subcategories: {},
      categories: {},
      departments: {},
      form: new Form({
        subcategory_id: "",
        department_id: "",
        category_id: "",
        subcategory_name: "",
        photo: ""
      })
    };
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/subcategory?page=" + page).then(response => {
        this.subcategories = response.data;
      });
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#subcategoryModal").modal("show");
    },
    editModal(subcategory) {
      this.editmode = true;
      this.form.reset();
      $("#subcategoryModal").modal("show");
      this.form.fill(subcategory);
    },
    loadSubcategory() {
      //load usrs
      if (this.$gate.isAdminOrAuthor()) {
        axios.get("api/subcategory").then(({ data }) => {
          this.subcategories = data;
        });
      }
    },
    loadGetDepartment() {
      //load usrs
      if (this.$gate.isAdminOrAuthor()) {
        axios.get("api/getDepartment").then(({ data }) => {
          this.departments = data;
        });
      }
    },
    loadGetCategory(id) {
      //load usrs
      if (this.$gate.isAdminOrAuthor()) {
        console.log(14);
        axios.get("api/getCategory/" + id).then(({ data }) => {
          this.categories = data;
        });
      }
    },
    createSubcategory() {
      this.form
        .post("api/subcategory")
        .then(() => {
          Fire.$emit("AfterCreate");
          $("#subcategoryModal").modal("hide");
          this.$swal({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            icon: "success",
            title: "Subcategory",
            text: "created successfully!"
          });
          this.$Progress.finish();
        })
        .catch(() => {});
    },
    updateSubcategory() {
      this.$Progress.start();
      this.form
        .put("api/subcategory/" + this.form.subcategory_id)
        .then(() => {
          // success
          $("#subcategoryModal").modal("hide");
          swal.fire("Updated!", "Information has been updated.", "success");
          this.$Progress.finish();
          Fire.$emit("AfterCreate");
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    deleteSubcategory(id) {
      swal
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        })
        .then(result => {
          // Send request to the server
          if (result.value) {
            this.form
              .delete("api/subcategory/" + id)
              .then(() => {
                swal.fire({
                  icon: "success",
                  title: "Deleted",
                  text: "Your file has been deleted."
                });
                Fire.$emit("AfterCreate");
                console.log("complete");
              })
              .catch(() => {
                swal.fire("Failed!", "There was something wronge.", "warning");
              });
          }
        });
    },
    getDepartment() {
      axios.get("api/getDepartment").then(response => {
        this.departments = response.data.departments;
      });
    },
    getCategory(id) {
      axios.get("api/getCategory/" + id).then(response => {
        this.categories = response.data.categories;
      });
    },
    photoUp(e) {
      var filereader = new FileReader();
      filereader.readAsDataURL(e.target.files[0]);
      filereader.onload = e => {
        this.form.photo = e.target.result;
      };
    }
  },
  mounted() {
    console.log("Component mounted.");
  },
  created() {
    Fire.$on("searching", () => {
      let query = this.$parent.search;
      axios
        .get("api/findsubcategory?q=" + query)
        .then(data => {
          this.categories = data.data;
        })
        .catch(() => {});
    });
    this.loadSubcategory();
    this.loadGetDepartment();
    // this.loadGetCategory();
    //setInterval(() => this.loadsubcategory(), 15000);
    Fire.$on("AfterCreate", () => {
      this.loadSubcategory();
      this.loadGetDepartment();
      //   this.loadGetCategory();
    });
  }
};
</script>
