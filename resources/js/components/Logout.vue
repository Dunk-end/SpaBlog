<template>
<!--    <p v-if="token"></p>-->
    <header>
        <el-container class="el-container">
            <el-header class="header">
                <router-link :underline="false" to="/">SpaBlog</router-link>
                <el-menu class="el__header">
                    <router-link :underline="false" class="nav__link" to="/">Главная</router-link>
                    <router-link class="nav__link" to="/login">Войти</router-link>
                </el-menu>
            </el-header>
        </el-container>
    </header>
</template>

<script>
export default {
    mounted() {
      this.send()
    },
    methods:
    {
        send()
        {
            axios
                .post('/logout')
                .then(response => {
                    this.$notify({
                            title: 'Успех!',
                            message: response.data.status,
                            type: 'success',
                            offset: 50
                        });
                        localStorage.clear();
                        this.redirect();
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.valid_errors = error.response.data.errors;
                        this.$notify.error({
                            title: 'Ошибка!',
                            message: 'Выйти не удалось!',
                            offset: 50
                        });
                    }
                })
        },
        redirect()
        {
            this.$router.push({ path: '/' })
        }
    }
}
</script>
