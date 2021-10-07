<template>
    <div>
        <el-alert
            v-if="valid_errors"
            style="margin-bottom: 24px"
            type="error"
            show-icon>
            <ul>
                <li v-for="(value, Register) in valid_errors" :key="Register">
                    {{ value }}
                </li>
            </ul>
        </el-alert>
    </div>
</template>

<script>
export default {
    props: {
        errors: Object
    },
    computed: {
        valid_errors() {
            let errors = Object.values(this.errors);
            errors = errors.flat();

            return errors;
        }
    }
}
</script>
