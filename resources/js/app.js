require('./bootstrap');

import Vue from "vue";
import VueRouter from "vue-router";
import router from "./routes/router";
import App from "./components/App";
import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/ru-RU';
import Error from "./components/path/valid_errors";

Vue.component('valid_errors', Error);

Vue.use(ElementUI, { locale, size: 'small' });

Vue.prototype.$loading = ElementUI.Loading.service;
Vue.prototype.$msgbox = ElementUI.MessageBox;
Vue.prototype.$alert = ElementUI.MessageBox.alert;
Vue.prototype.$confirm = ElementUI.MessageBox.confirm;
Vue.prototype.$prompt = ElementUI.MessageBox.prompt;
Vue.prototype.$notify = ElementUI.Notification;
Vue.prototype.$message = ElementUI.Message;

Vue.use(VueRouter);

const app = new Vue({
    el: '#app',
    history: true,
    hashbang: false,
    render: h => h(App),
    router
});
