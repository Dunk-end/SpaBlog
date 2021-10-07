<template>
    <div id="app">
        <Header />
            <router-view />
        <Footer />
    </div>
</template>

<script>

export default {
    comments: {
        //
    },
    data()
    {
        return {
            token: ''
        }
    },
    mounted() {
        this.token = localStorage.getItem('token')
    },
    // async created() {
    //     const response = await axios.get('user', {
    //         headers: {
    //             Authorization: localStorage.getItem('token')
    //         }
    //     });
    //
    //     if (response.config.headers.Authorization) {
    //         this.token = response.config.headers.Authorization
    //     }
    // }
}
</script>
