<script setup>
import {inject, ref} from "vue";
import router from "@/router";

const endpoint = inject('endpoint')
const email = ref('')
const password = ref('')

async function login() {
    try {
        const response = await fetch(endpoint + '/api/auth/login', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: email.value,
                password: password.value,
            })
        })

        const res = await response.json()

        if (!response.ok) {
            alert(res.message)
        } else {
            localStorage.setItem('token', res.token)
            router.push({name: 'home'})
        }
    }
    catch (e) {
        console.log(e)
    }
}
</script>

<template>
    <div class="container">
        <div class="login">
            <div class="card">
                <div class="card-header">
                    <h1>Login</h1>
                    <p>ToTask App</p>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" placeholder="Your Email" type="email" v-model="email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-control" placeholder="Your Password" type="password" v-model="password">
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-fill" @click="login">Login</button>
                    <RouterLink :to="{name: 'register'}">Register</RouterLink>
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