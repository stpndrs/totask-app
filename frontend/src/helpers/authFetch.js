import router from "@/router";

export default async function authFetch(url, method, data) {
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify(data)
        })

        const res = await response.json()

        if (response.ok) {
            return res
        } else if (res.message == 'Unauthenticated.') {
            router.push({name: 'login'})
        } else {
            alert(res.message)
        }
    }
    catch (e) {
        console.log(e)
    }
}