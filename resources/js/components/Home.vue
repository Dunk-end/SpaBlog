<template>
    <main>
        <header>
            <el-container class="el-container">
                <el-header class="header">
                    <router-link :underline="false" to="/">SpaBlog</router-link>
                    <el-menu class="el__header">
                        <router-link v-if="user.role_user === 'admin'" to="/">Админка</router-link>
                        <router-link :underline="false" class="nav__link" to="/">Главная</router-link>
                        <router-link v-if="token" class="nav__link" to="/logout">Выйти</router-link>
                        <router-link v-else class="nav__link" to="/login">Войти</router-link>
                    </el-menu>
                </el-header>
            </el-container>
        </header>
        <section class="home_posts">
            <!--        Здесь фильтр-->
            <div class="filter_posts">
                <div v-if="user.role_user !== 'user' && token !== ''" class="buttons">
                    <el-tooltip class="item" effect="light" content="Добавить зпись" placement="top">
                        <el-button @click="dialogVisible=true" type="primary" class="el-icon-plus" circle style="margin: 0 -5px 0 0"></el-button>
                    </el-tooltip>
                </div>
                <el-tooltip class="item" effect="light" content="Сначала показаны новые" placement="top">
                    <el-button class="el-icon-sort" circle></el-button>
                </el-tooltip>
                <el-tooltip class="item" effect="light" content="Перезагрузить" placement="top">
                    <el-button class="el-icon-refresh" @click="getPosts()" circle></el-button>
                </el-tooltip>
                <el-input
                    style="width: 240px"
                    placeholder="Поиск..."
                    auto-complete="on"
                    v-model="word_search">
                    <i slot="prefix" class="el-input__icon el-icon-search"></i>
                </el-input>
                <el-select v-model="options_category.value" filterable multiple collapse-tags clearable placeholder="Все категории" style="width: 240px" auto-complete="on">
                    <el-option
                        v-for="item in options_category"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value">
                    </el-option>
                </el-select>
                <el-select v-model="options_author.value" filterable multiple collapse-tags clearable placeholder="Все авторы" style="width: 240px" auto-complete="on">
                    <el-option
                        v-for="item in options_author"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value">
                    </el-option>
                </el-select>
            </div>

            <!--Здесь формочка для редактирования записи-->
            <el-dialog
                title="Изменение записи"
                :visible="dialogVisibleRedaction"
                width="40%"
                :before-close="handleClose">
            <span>
                <el-form class="no_border" enctype="multipart/form-data">

                    <valid_errors v-if="valid_errors" :errors="valid_errors"></valid_errors>
                    <el-form-item>
                        <el-input type="text" v-model="post.name" name="name" class="form-control" maxlength="35" show-word-limit placeholder="Название"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-input v-model="post.desc" type="textarea" style="border: none; padding: 0" name="desc" class="form-control" maxlength="255" show-word-limit placeholder="Описание" />
                    </el-form-item>
                    <el-form-item>
                        <el-upload
                            v-model="uploadFile"
                            ref="upload"
                            name="file"
                            drag
                            action="/api/upload-file"
                            list-type="fileList"
                            :headers="{ 'X-CSRF-TOKEN': csrf }"
                            :limit="1"
                            :accept="'image/.jpeg,.jpg,.png'"
                            :auto-upload="false"
                            :on-remove="handleRemove"
                            :before-upload="upload_file"
                            :on-success="success_file"
                            disabled>
                            <i class="el-icon-upload"></i>
                            <div class="el-upload__text">Перетащите файл сюда или <em>нажмите, чтобы загрузить</em></div>
                            <div class="el-upload__tip" slot="tip">На данный момент редактирование фото недоступно</div>
                            <div class="el-upload__tip" slot="tip">файлы jpg/png размером менее 500kb</div>
                        </el-upload>
                    </el-form-item>
                    <div style="display: flex; justify-content: flex-end">
                        <el-button type="primary" :loading="load_state" @click="send()">Изменить</el-button>
                        <el-button @click="dialogVisibleRedaction=false">Отменить</el-button>
                    </div>
                </el-form>
            </span>
            </el-dialog>


            <!--Здаесь формочка для создания записи-->
            <el-dialog
                title="Новая запись"
                :visible.sync="dialogVisible"
                width="40%"
                :before-close="handleClose">
                <span>
                    <el-form class="no_border" enctype="multipart/form-data">

                        <valid_errors v-if="valid_errors" :errors="valid_errors"></valid_errors>
                        <el-form-item>
                            <el-input type="text" v-model="form_data.name" name="name" class="form-control" maxlength="35" show-word-limit placeholder="Название" />
                        </el-form-item>
                        <el-form-item>
                            <el-input v-model="form_data.desc" type="textarea" style="border: none; padding: 0" name="desc" class="form-control" maxlength="255" show-word-limit placeholder="Описание" />
                        </el-form-item>
                            <el-select v-model="form_data.categories" filterable multiple collapse-tags clearable placeholder="Выберите категории" style="width: 50%; margin-bottom: 16px" auto-complete="on">
                                <el-option
                                    v-for="category in categories"
                                    :key="category.name"
                                    :label="category.name"
                                    :value="category.id">
                                </el-option>
                            </el-select>
                        <el-form-item>
                            <el-upload
                                v-model="uploadFile"
                                ref="upload"
                                name="file"
                                drag
                                action="/api/upload-file"
                                list-type="fileList"
                                :headers="{ 'X-CSRF-TOKEN': csrf }"
                                :limit="1"
                                :accept="'image/.jpeg,.jpg,.png'"
                                :auto-upload="false"
                                :on-remove="handleRemove"
                                :before-upload="upload_file"
                                :on-success="success_file">
                                <i class="el-icon-upload"></i>
                                <div class="el-upload__text">Перетащите файл сюда или <em>нажмите, чтобы загрузить</em></div>
                                <div class="el-upload__tip" slot="tip">файлы jpg/png размером менее 500kb</div>
                            </el-upload>
                        </el-form-item>
                        <div style="display: flex; justify-content: flex-end">
                            <el-button type="primary" :loading="load_state" @click.prevent="send(); getPosts()">Добавить</el-button>
                            <el-button @click="dialogVisible=false">Отменить</el-button>
                        </div>
                    </el-form>
                </span>
            </el-dialog>


            <el-alert
                v-if="errored"
                type="info"
                center
                :closable="false">
                Произошла какая-то ошибка, попробуйте позже:(
            </el-alert>

            <div
                class="loading__icons"
                v-loading="loading"
                element-loading-text="Загрузка..."
                style="width: 100%; height: 50%; background: #000000ff">
            </div>

            <!--        Здесь пост-->
            <div v-if="loading===false" v-for="post in posts" :key="post.id" class="post">
                <div class="post_id" style="display: none">{{ post.id }}</div>
                <el-row :gutter="24">
                    <el-col :xl="20" style="padding: 0">
                        <h2 class="name__post">{{ post.name }}</h2>
                    </el-col>
                    <el-col v-if="user.role_user !== 'user' && user.role_user !== 'writer'|| user.user_id === post.user_id && user.role_user !== 'user'" :xl="3" :offset="1">
                        <el-tooltip class="item" effect="light" content="Редактировать зпись" placement="top">
                            <el-button class="el-icon-edit" @click="dialogVisibleRedaction=true; editPost(post)" style="margin: 0 0 0 11px;" circle></el-button>
                        </el-tooltip>
                        <el-tooltip class="item" effect="light" content="Удалить зпись" placement="top">
                            <el-popconfirm title="Вы уверены, что хотите удалить это?" @confirm="deletePost(post.id);" style="padding: 0; margin: 0">
                                <el-button slot="reference" class="el-icon-delete" style="margin: 0" circle></el-button>
                            </el-popconfirm>
                        </el-tooltip>
                    </el-col>
                </el-row>
                <el-row :gutter="24">
                    <el-col :xl="10" style="padding: 0">
                        <el-image
                            :src=post.img
                            style="border-radius: 4px;
                            display:flex;
                            box-shadow: 0 3px 10px 0 #22222255;
                            border: none;
                            max-height: 300px;
                            width: 100%;
                            :fit=cover">
                        </el-image>
                    </el-col>
                    <el-col
                        :xl="13"
                        :offset="1">
                        <p class="mini__dest" style="max-width: 100%">{{ post.desc }}</p>
                        <ul>
                            <li v-for="category in categories">
                                {{ category.name }}
                            </li>
                        </ul>
                        <div class="post_info-text" style="">
                            <p class="text__info" v-if="user.user_id === post.user_id">{{ user.login }}</p>
                            <p class="text__info" v-else>Другой пользователь</p>
                            <p class="text__info" v-if="(post.created_at < post.updated_at)"><i class="el-icon-edit"></i>Изменено: {{ post.updated_at }}</p>
                            <p class="text__info" v-else>Добавлено: {{ post.created_at }}</p>
                        </div>
                    </el-col>
                </el-row>
            </div>
<!--            <ul>-->
<!--                <li v-for="(game, index) in filteredGames" :key="index">{{ game }}</li>-->
<!--            </ul>-->
        </section>
    </main>
</template>

<script>
export default {
    props: ['csrf'],
    data()
    {
        return {
            token: '',
            posts: [],
            post: {
                id: '',
                name: '',
                file: '',
                desc: '',
                created_at: '',
                updated_at: '',
                user_id: ''
            },
            categories: [],
            category: {
                id: '',
                name: ''
            },
            pagination: {},
            edit: false,
            loading: false,
            errored: false,
            valid_errors: '',
            // категории
            options_category: [{
                value: 'Option1',
                label: 'Категория 1'
            }, {
                value: 'Option2',
                label: 'Категория 2'
            }, {
                value: 'Option3',
                label: 'Категория 3'
            }],
            options_author: [{
                value: 'Option1',
                label: 'Автор 1'
            }, {
                value: 'Option2',
                label: 'Автор 2'
            }, {
                value: 'Option3',
                label: 'Автор 3'
            }],
            form_data: {
                name: '',
                desc: '',
                file: '',
                categories: '',
                user_id: ''
            },
            month: {
                0: 'января',
                1: 'февраля',
                2: 'марта',
                3: 'апреля',
                4: 'мая',
                5: 'июня',
                6: 'июля',
                7: 'августа',
                8: 'сентября',
                9: 'октября',
                10: 'ноября',
                11: 'декабря',
            },
            uploadFile: '',
            fileName: '',
            load_state: false,
            word_search: '',
            dialogVisible: false,
            dialogVisibleRedaction: false,
            user: {
                role_user: '',
                user_id: '',
                name: '',
                login: ''
            }
            // games: ['qwe', 'ewq', 'asd', 'dsa']
        }
    },
    async created() {
        const response = await axios.get('user', {
            headers: {
                Authorization: localStorage.getItem('token'),
            }
        });

        if (response.config.headers.Authorization) {
            this.token = response.config.headers.Authorization;
        }
    },
    // computed: {
    //     filteredGames() {
    //         return this.games.filter(game => {
    //             return game.indexOf(this.word_search) !== -1;
    //         })
    //     }
    // },
    mounted() {
        this.getPosts();
        this.showCategory();
        this.form_data.user_id = localStorage.getItem('user_id');
        this.user.user_id = localStorage.getItem('user_id');
        this.user.role_user = localStorage.getItem('role');
        this.user.name = localStorage.getItem('name');
        this.user.login = localStorage.getItem('login');
        // axios
        //     .get('/getPostCat')
        //     .then(response =>{
        //         console.log(response.data);
        //     })
        //     .catch(error => {
        //         console.log(error);
        //     })
    },
    methods: {
        showCategory() {
            axios
                .get('/showCategory')
                .then(response =>{
                    this.categories = response.data;
                })
                .catch(error => {
                    console.log(error);
                })
        },
        getPosts() {
            let dontLoading = document.querySelector('.loading__icons');
            dontLoading.classList.remove('not__loading');
            this.loading = true;
            axios
                .get('/api/posts')
                .then(response => {
                    this.posts = response.data.data;
                    if (this.posts.length >= 0) {
                        for (let i = 0; i < this.posts.length; i++) {

                            let updatedPostDate = this.posts[i].updated_at;
                            let updatedDate = new Date(updatedPostDate);
                            let updatedHours = updatedDate.getHours();
                            let updatedMinutes = updatedDate.getMinutes();
                            let updatedMonth = this.month[updatedDate.getMonth()];
                            let updatedDay = updatedDate.getDate();

                            if (updatedHours < 10) {
                                updatedHours = '0' + updatedHours;
                            }
                            if (updatedMinutes < 10) {
                                updatedMinutes = '0' + updatedMinutes;
                            }

                            this.posts[i].updated_at = updatedDay + ' ' + updatedMonth + ' в ' + updatedHours + ':' + updatedMinutes;


                            let createdPostDate = this.posts[i].created_at;
                            let createdDate = new Date(createdPostDate);
                            let createdHours = createdDate.getHours();
                            let createdMinutes = createdDate.getMinutes();
                            let createdMonth = this.month[updatedDate.getMonth()];
                            let createdDay = createdDate.getDate();

                            if (createdHours < 10) {
                                createdHours = '0' + createdHours;
                            }
                            if (createdMinutes < 10) {
                                createdMinutes = '0' + createdMinutes;
                            }

                            this.posts[i].created_at = createdDay + ' ' + createdMonth + ' в ' + createdHours + ':' + createdMinutes;
                        }
                    }
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true;
                })
                .finally(() => setTimeout(() => {
                    this.loading = false
                    dontLoading.classList.add('not__loading');
                }, 500));
        },
        handleClose(done) {
            this.$confirm('Вы уверены, что хотите закрыть окно?')
                .then(_ => {
                    done();
                    this.dialogVisibleRedaction = false;
                })
                .catch(_ => {});
        },
        send() {
            this.$refs.upload.submit();
            this.load_state = true;
            if (this.edit === false) {
                axios
                    .post('/api/posts', this.form_data)
                    .then(response => {
                        if (response.data.status === '422') {
                            this.$notify.error({
                                title: 'Ошибка!',
                                message: 'Error!',
                                offset: 50
                            });
                            setTimeout(() => {
                                this.load_state = false;
                            }, 600);
                        } else {
                            this.$notify({
                                title: 'Успех!',
                                message: response.data.status,
                                type: 'success',
                                offset: 50
                            });
                            setTimeout(() => {
                                this.load_state = false;
                                this.dialogVisible = false;
                            }, 600);
                        }
                        this.load_state = false;
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.valid_errors = error.response.data.errors;
                            this.$notify.error({
                                title: 'Ошибка!',
                                message: 'Проверьте праильность заполнения полей!',
                                offset: 50
                            });
                            setTimeout(() => {
                                this.load_state = false;
                            }, 600);
                        }
                    })
                .finally(() => {
                    this.form_data.name = '';
                    this.form_data.desc = '';
                    this.form_data.file = '';
                    this.form_data.categories = '';
                })
                setTimeout(() => {
                    this.load_state = false;
                    this.getPosts();
                }, 600);
            } else {
                axios
                    .put(`/api/posts/${this.post.id}`, this.post)
                    .then(response => {
                        if (response.data.status === '422') {
                            this.valid_errors = error.response.data.errors;
                            this.$notify.error({
                                title: 'Ошибка!',
                                message: 'Error!',
                                offset: 50
                            });
                            setTimeout(() => {
                                this.load_state = false;
                            }, 600);
                        } else {
                            this.$notify({
                                title: 'Успех!',
                                message: response.data.status,
                                type: 'success',
                                offset: 50
                            });
                            setTimeout(() => {
                                this.load_state = false;
                                this.dialogVisibleRedaction = false;
                            }, 600);
                        }
                        this.load_state = false;
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.valid_errors = error.response.data.errors;
                            this.$notify.error({
                                title: 'Ошибка!',
                                message: 'Проверьте праильность заполнения полей!',
                                offset: 50
                            });
                            setTimeout(() => {
                                this.load_state = false;
                            }, 600);
                        }
                    })
                setTimeout(() => {
                    this.load_state = false;
                    this.edit = false;
                    this.getPosts();
                }, 600);
            }
        },
        handleRemove(file) {
            this.imageUrl = '';
        },
        success_file(res, file) {
            this.form_data.file = file.name;
        },
        upload_file(file) {
            this.form_data.file = file.name;
        },
        deletePost(id) {
            axios
                .delete(`/api/posts/${id}`)
                .then(response => {
                    if (response.data.status === '422') {
                        this.$notify.error({
                            title: 'Ошибка!',
                            message: 'Error!',
                            offset: 50
                        });
                    } else {
                        this.$notify({
                            title: 'Успех!',
                            message: response.data.status,
                            type: 'success',
                            offset: 50
                        });
                    }
                })
                .catch(error => {
                    this.valid_errors = error.response.data.errors;
                    this.$notify.error({
                        title: 'Ошибка!',
                        message: 'Удалить запись не удалось!',
                        offset: 50
                    });
                });
            //
            this.getPosts();
            this.getPosts();
        },
        editPost(post) {
            this.edit = true
            this.post.id = post.id
            this.post.name = post.name
            this.post.desc = post.desc
            this.post.file = post.file
            this.post.updated_at = post.updated_at
            this.post.created_at = post.created_at
            this.getPosts();
        }
    }
}
</script>
