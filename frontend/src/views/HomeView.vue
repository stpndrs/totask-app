<script setup>
import { inject, onMounted, ref } from "vue";
import authFetch from "@/helpers/authFetch";

const endpoint = inject('endpoint')
const data = ref([])
const detail = ref([])
const user = ref([])
const isOpenDetail = ref(false)
const isOpenAdd = ref(false)
const isOpenProfile = ref(false)

const title = ref('')
const description = ref('')
const deadline = ref('')
const status = ref('')

const name = ref('')
const email = ref('')
const password = ref('')
const profile_image = ref('')

onMounted(function () {
    getTask()
    getUser()
})

async function getTask() {
    data.value = await authFetch(endpoint + '/api/task')
}

async function getUser() {
    user.value = await authFetch(endpoint + '/api/user')
    name.value = user.value.name
    email.value = user.value.email
}

async function updateUser() {
    const formData = new FormData();
    // formData.append('name', name.value);
    // formData.append('email', email.value);
    // formData.append('password', password.value);
    formData.append('profile_image', profile_image.value);
    console.log(formData);
    try {
        const response = await fetch(endpoint + '/api/user', {
            method: 'POST',
            headers: {
                // 'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: formData
        });

        if (response.ok) {
            const responseData = await response.json();
            if (responseData.message === 'Update profile success') {
                isOpenProfile.value = false;
                getUser();
                // name.value = '';
                // email.value = '';
                // password.value = '';
                profile_image.value = '';
            } else {
                // Handle other responses or errors here
                console.error('Update failed:', responseData.message);
            }
        } else {
            // Handle non-200 HTTP status here
            console.error('HTTP Error:', response.status);
        }
    } catch (error) {
        console.error('An error occurred:', error);
    }
}

// async function updateUser() {

//     const response = await authFetch(endpoint + '/api/user', 'PUT', {
//            name: name.value,
//            email: email.value,
//            password: password.value,
//            profile_image: profile_image.value,
//        })

//     if (response.message === 'Update profile success') {
//         isOpenProfile.value = false
//         getUser()
//         name.value = ''
//         email.value = ''
//         password.value = ''
//         profile_image.value = ''
//     }
// }

const handleChangeFile = (event) => {
    profile_image.value = event.target.files[0];
};

async function getTaskById(id) {
    const response = await authFetch(endpoint + '/api/task/' + id)
    detail.value = response
    title.value = response.data.title
    description.value = response.data.description
    deadline.value = response.data.deadline
    status.value = response.data.status
    isOpenDetail.value = true
}

async function addTask() {
    const response = await authFetch(endpoint + '/api/task', 'POST', {
        title: title.value,
        description: description.value,
        deadline: deadline.value,
        status: status.value,
    })

    if (response.message === 'Create task success') {
        isOpenAdd.value = false
        getTask()
        title.value = ''
        description.value = ''
        deadline.value = ''
        status.value = ''
    }
}

async function updateTask(id) {
    const response = await authFetch(endpoint + '/api/task/' + id, 'PUT', {
        title: title.value,
        description: description.value,
        deadline: deadline.value,
        status: status.value,
    })

    if (response.message === 'Update task success') {
        isOpenDetail.value = false
        getTask()
        title.value = ''
        description.value = ''
        deadline.value = ''
        status.value = ''
    }
}

function dateFormat(data) {
    const date = new Date(data)
    const options = { year: 'numeric', month: 'long', day: 'numeric' }
    return date.toLocaleDateString('en-EN', options)
}
</script>


<template>
    <div class="container">
        <header>
            <h1 class="header-title">ToTask App</h1>
            <nav>
                <ul>
                    <li><button class="btn btn-fill" @click="isOpenAdd = true">Add New Task</button></li>
                </ul>
            </nav>
            <button class="btn btn-profile">
                Hi, {{ user.name }}
                <br>
                <img src="../../public/avatar.png" alt="avatar" @click="isOpenProfile = true">
            </button>
        </header>
        <div class="modal-overlay" id="modal-detail" :class="isOpenDetail ? 'open' : ''">
            <div class="modal">
                <div class="modal-header">
                    <h2 class="modal-title">{{ detail?.data?.title }}</h2>
                    <div class="modal-close" @click="isOpenDetail = false">X</div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input class="form-control" placeholder="Task Title" type="text" v-model="title">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" placeholder="Task Description" v-model="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="deadline">Deadline</label>
                        <input class="form-control" placeholder="Task Deadline" type="date" v-model="deadline">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" v-model="status">
                            <option value="">Select your task status</option>
                            <option value="1">Postponed</option>
                            <option value="2">In Process</option>
                            <option value="3">Done</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-fill" @click="updateTask(detail?.data?.id)">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-overlay" id="modal-add" :class="isOpenAdd ? 'open' : ''">
            <div class="modal">
                <div class="modal-header">
                    <h2 class="modal-title">Add new task to do</h2>
                    <div class="modal-close" @click="isOpenAdd = false">X</div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input class="form-control" placeholder="Task Title" type="text" v-model="title">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" placeholder="Task Description" v-model="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="deadline">Deadline</label>
                        <input class="form-control" placeholder="Task Deadline" type="date" v-model="deadline">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" v-model="status">
                            <option value="">Select your task status</option>
                            <option value="1">Postponed</option>
                            <option value="2">In Process</option>
                            <option value="3">Done</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-fill" @click="addTask">Save</button>
                </div>
            </div>
        </div>
        <div class="modal-overlay" id="modal-profile" :class="isOpenProfile ? 'open' : ''">
            <div class="modal">
                <div class="modal-header">
                    <h2 class="modal-title">Edit Your Profile</h2>
                    <div class="modal-close" @click="isOpenProfile = false">X</div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input class="form-control" placeholder="Your Name" type="text" v-model="name">
                    </div>
                    <div class="form-group">
                        <label for="email">E-Mail</label>
                        <input class="form-control" placeholder="Your Email" type="email" v-model="email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-control" placeholder="Type new password if you want changes" type="password"
                            v-model="password">
                    </div>
                    <div class="form-group">
                        <label for="profile">Profile Image</label>
                        <input class="form-control" type="file" @change="handleChangeFile">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-fill" @click="updateUser">Save</button>
                </div>
            </div>
        </div>
        <div class="totask-container">
            <div class="grid-container">
                <div class="grid-item task-item" v-for="item in data?.task">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title task-title">{{ item.title }}</h2>
                            <!--                          <div class="btn">-->
                            <!--                              <i class="bi bi-three-dots-vertical"></i>-->
                            <!--                          </div>-->
                            <button class="btn btn-fill" @click="getTaskById(item.id)">Detail</button>
                            <!--                          <div class="menu">-->
                            <!--                              <ul>-->
                            <!--                                  <li>-->
                            <!--                                  </li>-->
                            <!--                                  <li>-->
                            <!--                                      <button class="btn">Mark as done</button>-->
                            <!--                                  </li>-->
                            <!--                              </ul>-->
                            <!--                          </div>-->
                        </div>
                        <div class="card-body">
                            <p class="task-deadline">
                                Deadline: <b>{{ dateFormat(item.deadline) }}</b>
                            </p>
                            <p class="task-description">
                                {{ item.description }}
                            </p>
                            <div class="task-status">
                                {{ item.status == 1 ? 'Posponed' : item.status == 2 ? 'In Process' : 'Done' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
</script>