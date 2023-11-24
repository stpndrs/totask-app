<script setup>
import {inject, ref} from "vue";
import router from "@/router";

const endpoint = inject('endpoint')
const name = ref('')
const email = ref('')
const password = ref('')

async function register() {
    try {
        const response = await fetch(endpoint + '/api/auth/register', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: email.value,
                password: password.value,
                name: name.value
            })
        })

        const res = await response.json()

        if (!response.ok) {
            alert(res.message)
        } else {
            localStorage.setItem('token', res.token)
            router.push({name: 'home'})
        }
    } catch (e) {
        console.log(e)
    }
}
</script>

<template>
    <div class="container">
        <div class="login">
            <div class="card">
                <div class="card-header">
                    <h1>Register</h1>
                    <p>ToTask App</p>
                </div>
                <div class="card-body">
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
                        <input class="form-control" placeholder="Your Password" type="password" v-model="password">
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-fill" @click="register">Register</button>
                    <RouterLink :to="{name: 'login'}">Login</RouterLink>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.card-header {
    display: block;
    margin-bottom: 20px;
    text-align: center;
}

.login {
    width: 100%;
    height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.card {
    width: 20%;
    background-color: #282828;
    color: white;
}

a {
    text-decoration: none;
    color: white;
}

.btn-fill {
    background-color: white;
    color: #1a202c;
}
</style>