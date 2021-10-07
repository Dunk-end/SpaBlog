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
            <el-form style="margin-top: 150px; background: #fff; padding: 24px; border-radius: 4px; box-shadow: 0 3px 10px 0 #22222255;">
                <h3 style="margin-bottom: 12px;">Регистрация</h3>
                <valid_errors v-if="valid_errors" :errors="valid_errors"></valid_errors>
                <el-form-item>
                    <el-input type="text" v-model="form_data.name" name="name" autofocus class="form-control" placeholder="Ваше имя, например: Иван" />
                </el-form-item>
                <el-form-item>
                    <el-input type="text" v-model="form_data.login" name="login" class="form-control" placeholder="Ваш логин, например: user" />
                </el-form-item>
                <el-form-item>
                    <el-input type="password" v-model="form_data.password" name="password" class="form-control" placeholder="Введите пароль" show-password/>
                </el-form-item>
                <el-form-item>
                    <el-input type="password" v-model="form_data.password_confirm" name="password_confirm" class="form-control" placeholder="Повторите пароль" show-password/>
                </el-form-item>
                <el-button :loading="loadState" @click.prevent='send' v-if="form_data.name.length < 3" disabled style="outline: none">Заполните все поля</el-button>
                <el-button :loading="loadState" @click.prevent='send' v-else-if="form_data.login.length < 3" disabled style="outline: none">Заполните все поля</el-button>
                <el-button :loading="loadState" @click.prevent='send' v-else-if="form_data.password.length < 8" disabled style="outline: none">Заполните все поля</el-button>
                <el-button :loading="loadState" @click.prevent='send' v-else-if="form_data.password_confirm.length < 8" disabled style="outline: none">Заполните все поля</el-button>
                <el-button :loading="loadState" @click.prevent='send' v-else style="outline: none">Зарегистрироваться</el-button>
                <router-link class="log-reg" to="/login">Есть аккаунта? - Авторизируйтесь!</router-link>
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
            loadState: false,
            valid_errors: '',
            form_data: {
                name: '',
                login: '',
                password: '',
                password_confirm: '',
                role_user: ''
            },
            roles_user: []
        }
    },
    mounted() {
        this.getRoleUser();
    },
    methods:
        {
            getRoleUser()
            {
                axios
                    .get('/getUserRole')
                    .then(response => {
                        this.roles_user = response.data;
                    })
                    .catch(error => {
                        console.log(error)
                    });
            },
            send()
            {
                let login = this.form_data.login;
                if (login === 'admin') {
                    this.form_data.role_user = this.roles_user[1].id
                } else if (login === 'writer') {
                    this.form_data.role_user = this.roles_user[2].id
                } else if (login === 'admember') {
                    this.form_data.role_user = this.roles_user[3].id
                } else {
                    this.form_data.role_user = this.roles_user[0].id
                }
                this.loadState = true
                axios
                    .post('/register', this.form_data)
                    .then(response => {
                        this.$notify({
                            title: 'Успех!',
                            message: response.data.status,
                            type: 'success',
                            offset: 50
                        });
                        setTimeout(() => {
                            this.loadState = false;
                            this.redirect();
                        }, 600);
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.valid_errors = error.response.data.errors;
                            this.$notify.error({
                                title: 'Ошибка!',
                                message: 'Проверьте правильность заполнения полей!',
                                offset: 50
                            });
                            setTimeout(() => {
                                this.loadState = false;
                            }, 600);
                        }

                    })
            },
            redirect()
            {
                this.$router.push({ path: '/login' })
            }
        }
}
</script>
