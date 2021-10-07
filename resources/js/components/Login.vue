<template>
    <section>
        <header>
            <el-container class="el-container">
                <el-header class="header">
                    <router-link :underline="false" to="/">SpaBlog</router-link>
                    <el-menu class="el__header">
                        <router-link :underline="false" class="nav__link" to="/">Главная</router-link>
                        <router-link v-if="token" class="nav__link" to="/logout">Выйти</router-link>
                        <router-link v-else class="nav__link" to="/login">Войти</router-link>
                    </el-menu>
                </el-header>
            </el-container>
        </header>
        <div class="form__container">
            <el-form>
                <h3 style="margin-bottom: 12px;">Вход</h3>
                <valid_errors v-if="valid_errors" :errors="valid_errors"></valid_errors>
                <el-form-item>
                    <el-input type="text" v-model="form_data.login" name="login" autofocus class="form-control" placeholder="Ваш логин, например: user" />
                </el-form-item>
                <el-form-item>
                    <el-input type="password" v-model="form_data.password" name="password" class="form-control" placeholder="Введите пароль" show-password/>
                </el-form-item>
                <el-button :loading="loadStatus" @click.prevent='send' style="outline: none" v-if="form_data.password.length < 8" disabled>Заполните все поля</el-button>
                <el-button :loading="loadStatus" @click.prevent='send' style="outline: none" v-else-if="form_data.login.length < 3" disabled>Заполните все поля</el-button>
                <el-button :loading="loadStatus" @click.prevent='send' v-else style="outline: none">Войти</el-button>
                <router-link class="log-reg" to="/register">Нет аккаунта? - Зарегистрируйтесь!</router-link>
            </el-form>
        </div>
    </section>
</template>

<script>
export default {
    data()
    {
        return {
            token: '',
            loadStatus: false,
            valid_errors: '',
            form_data: {
                login: '',
                password: '',
            }
        }
    },
    methods:
        {
            send()
            {
                this.loadStatus = true
                axios
                    .post('/login', this.form_data)
                    .then(response => {
                        if (response.data.status === '422') {
                            this.$notify.error({
                                title: 'Ошибка!',
                                message: 'Пароль или логин не совпадают!',
                                offset: 50
                            });
                            setTimeout(() => {
                                this.loadStatus = false;
                            }, 600);
                        } else {
                            this.$notify({
                                title: 'Успех!',
                                message: response.data.status,
                                type: 'success',
                                offset: 50
                            });
                            localStorage.setItem('token', response.data.token);
                            localStorage.setItem('user_id', response.data.id);
                            localStorage.setItem('role', response.data.role);
                            localStorage.setItem('name', response.data.name);
                            localStorage.setItem('login', response.data.login);
                            setTimeout(() => {
                                this.loadStatus = false;
                                this.redirect();
                            }, 600);
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.valid_errors = error.response.data.errors;
                            this.$notify.error({
                                title: 'Ошибка!',
                                message: 'Пароль или логин не совпадают!',
                                offset: 50
                            });
                            setTimeout(() => {
                                this.loadStatus = false;
                            }, 600);
                        }
                    })
            },
            redirect()
            {
                this.$router.push({ path: '/' })
            }
        },
    validation: {

    }
}
</script>
